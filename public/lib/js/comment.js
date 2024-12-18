class CommentSystem {
    constructor() {
        this.initializeEventListeners();
        this.csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        this.articleId = document.querySelector('input[name="article_id"]').value;
        this.loadExistingComments();
        this.updateTotalCommentCount();

        // Perbaikan definisi user photo dengan path yang benar
        this.user = {
            photo: document.querySelector('meta[name="user-photo"]')?.content || '/lib/default_media/default.jpg'
        };

        // Tambahkan base URL untuk storage
        this.storageUrl = '/storage/profile_photos/';

        // Setup AJAX CSRF
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': this.csrfToken
            }
        });
    }

    loadExistingComments() {
        $.ajax({
            url: `/comments/get-article-comments/${this.articleId}`,
            type: 'GET',
            success: (response) => {
                if (response.success) {
                    $('#comments-container').empty();
                    response.comments.forEach(comment => {
                        const commentHtml = this.createCommentHTML(comment);
                        $('#comments-container').append(commentHtml);

                        if (comment.replies && comment.replies.length > 0) {
                            comment.replies.forEach(reply => {
                                const replyHtml = this.createReplyHTML(reply);
                                $(`#replies-${comment.id}`).append(replyHtml);
                            });
                        }
                    });
                    this.updateTotalCommentCount();
                }
            },
            error: (xhr) => {
                console.error('Error loading comments:', xhr);
            }
        });
    }

    initializeEventListeners() {
        // Handle submit komentar utama
        $(document).on('submit', '#comment-form', (e) => {
            e.preventDefault();
            this.handleCommentSubmit(e);
        });

        // Handle submit balasan
        $(document).on('submit', '.reply-form', (e) => {
            e.preventDefault();
            this.handleReplySubmit(e);
        });

        // Toggle form balasan
        $(document).on('click', '.btn-reply', (e) => {
            const commentId = $(e.target).closest('.btn-reply').data('comment-id');
            this.toggleReplyForm(commentId);
        });

        // Handle hapus komentar/balasan
        $(document).on('click', '.btn-delete', (e) => {
            e.preventDefault();
            const button = $(e.currentTarget);
            const commentId = button.data('comment-id');
            this.handleDelete(commentId);
        });

        // Handle batal balas
        $(document).on('click', '.btn-cancel', (e) => {
            $(e.target).closest('.reply-form-container').slideUp();
        });
    }

    handleCommentSubmit(e) {
        const form = $(e.target);
        const content = form.find('textarea[name="content"]').val().trim();

        if (!content) {
            this.showNotification('Komentar tidak boleh kosong', 'error');
            return;
        }

        $.ajax({
            url: '/comments',
            type: 'POST',
            data: {
                article_id: this.articleId,
                content: content,
                _token: this.csrfToken
            },
            success: (response) => {
                if (response.success) {
                    const newComment = this.createCommentHTML({
                        id: response.comment.id,
                        content: response.comment.content,
                        user: response.comment.user,
                        created_at: 'Baru saja',
                        article_id: this.articleId,
                        can_delete: true
                    });

                    $('#comments-container').prepend(newComment);
                    form[0].reset();
                    this.showNotification('Komentar berhasil ditambahkan', 'success');
                    this.updateTotalCommentCount();
                }
            },
            error: (xhr) => {
                console.error('Error:', xhr);
                this.showNotification(xhr.responseJSON?.message || 'Gagal menambahkan komentar', 'error');
            }
        });
    }

    handleReplySubmit(e) {
        const form = $(e.target);
        const content = form.find('textarea[name="content"]').val().trim();
        const parentId = form.find('input[name="parent_id"]').val();

        if (!content) {
            this.showNotification('Balasan tidak boleh kosong', 'error');
            return;
        }

        $.ajax({
            url: '/comments/reply',
            type: 'POST',
            data: {
                article_id: this.articleId,
                parent_id: parentId,
                content: content,
                _token: this.csrfToken
            },
            success: (response) => {
                if (response.success) {
                    const newReply = this.createReplyHTML({
                        id: response.reply.id,
                        content: response.reply.content,
                        user: response.reply.user,
                        created_at: 'Baru saja',
                        can_delete: true
                    });

                    $(`#comment-${parentId} .replies-container`).append(newReply);
                    form[0].reset();
                    form.closest('.reply-form-container').slideUp();
                    this.showNotification('Balasan berhasil ditambahkan', 'success');
                    this.updateTotalCommentCount();
                }
            },
            error: (xhr) => {
                console.error('Error:', xhr);
                this.showNotification(xhr.responseJSON?.message || 'Gagal menambahkan balasan', 'error');
            }
        });
    }

    handleDelete(commentId) {
        if (confirm('Apakah Anda yakin ingin menghapus komentar ini?')) {
            $.ajax({
                url: `/comments/${commentId}`,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': this.csrfToken
                },
                success: (response) => {
                    if (response.success) {
                        // Hapus elemen komentar dari DOM
                        const commentElement = $(`#comment-${commentId}`);
                        const replyElement = $(`#reply-${commentId}`);

                        if (commentElement.length) {
                            commentElement.fadeOut(300, function() {
                                $(this).remove();
                            });
                        }

                        if (replyElement.length) {
                            replyElement.fadeOut(300, function() {
                                $(this).remove();
                            });
                        }

                        this.updateTotalCommentCount();
                        this.showNotification('Komentar berhasil dihapus', 'success');
                    }
                },
                error: (xhr) => {
                    console.error('Error:', xhr);
                    if (xhr.status === 403) {
                        this.showNotification('Anda tidak memiliki izin untuk menghapus komentar ini', 'error');
                    } else {
                        this.showNotification('Gagal menghapus komentar', 'error');
                    }
                }
            });
        }
    }

    startAutoRefresh() {
        setInterval(() => {
            this.loadNewComments();
        }, 30000); // Refresh setiap 30 detik
    }

    loadNewComments() {
        const lastCommentId = $('.comment-thread').first().data('id') || 0;

        $.ajax({
            url: `/comments/load-new/${this.articleId}/${lastCommentId}`,
            type: 'GET',
            success: (response) => {
                if (response.comments?.length) {
                    response.comments.forEach(comment => {
                        if (!$(`#comment-${comment.id}`).length) {
                            const newComment = this.createCommentHTML(comment);
                            $('#comments-container').prepend(newComment);
                            this.updateTotalCommentCount();
                        }
                    });
                }
            },
            error: (xhr) => {
                console.error('Error loading new comments:', xhr);
            }
        });
    }

    toggleReplyForm(commentId) {
        const replyForm = $(`#reply-form-${commentId}`);
        $('.reply-form-container').not(replyForm).slideUp();
        replyForm.slideToggle(() => {
            if (replyForm.is(':visible')) {
                replyForm.find('textarea').focus();
            }
        });
    }

    updateTotalCommentCount() {
        $.ajax({
            url: `/comments/count/${this.articleId}`,
            type: 'GET',
            success: (response) => {
                if (response.success) {
                    const totalComments = response.total;
                    $('.comments-title').text(`Komentar (${totalComments})`);
                }
            },
            error: (xhr) => {
                console.error('Error getting comment count:', xhr);
            }
        });
    }

    showNotification(message, type) {
        const notification = $(`
            <div class="notification ${type}">
                ${this.escapeHtml(message)}
                <button class="close-notification">&times;</button>
            </div>
        `).appendTo('body');

        notification.animate({ right: '20px' }, 300);

        const timeout = setTimeout(() => {
            notification.animate({ right: '-100%' }, 300, function() {
                $(this).remove();
            });
        }, 3000);

        notification.find('.close-notification').on('click', function() {
            clearTimeout(timeout);
            $(this).parent().animate({ right: '-100%' }, 300, function() {
                $(this).remove();
            });
        });
    }

    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    createCommentHTML(comment) {
        const canDelete = comment.user_id === parseInt($('meta[name="user-id"]').attr('content'));
        const userPhoto = comment.user.profile_photo
            ? `/storage/profile_photos/${comment.user.profile_photo}`
            : '/lib/default_media/default.jpg';

        return `
            <div class="comment-thread" id="comment-${comment.id}" data-id="${comment.id}">
                <div class="comment-main">
                    <img src="${userPhoto}"
                         class="comment-avatar" alt="${comment.user.name}">
                    <div class="comment-content">
                        <div class="comment-header">
                            <span class="username">${this.escapeHtml(comment.user.name)}</span>
                            <span class="comment-time">${comment.created_at}</span>
                        </div>
                        <p class="comment-text">${this.escapeHtml(comment.content)}</p>
                        <div class="comment-actions">
                            <button class="btn-reply" data-comment-id="${comment.id}">
                                <i class="fas fa-reply"></i> Balas
                            </button>
                            ${canDelete ? `
                                <button class="btn-delete" data-comment-id="${comment.id}">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            ` : ''}
                        </div>
                    </div>
                </div>
                <div class="reply-form-container" id="reply-form-${comment.id}" style="display: none;">
                    <form class="reply-form">
                        <input type="hidden" name="parent_id" value="${comment.id}">
                        <input type="hidden" name="article_id" value="${this.articleId}">
                        <div class="form-group">
                            <textarea name="content" class="reply-textarea"
                                      placeholder="Tulis balasan..." required></textarea>
                            <div class="form-actions">
                                <button type="button" class="btn-cancel">Batal</button>
                                <button type="submit" class="btn-submit">Kirim</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="replies-container" id="replies-${comment.id}"></div>
            </div>
        `;
    }

    createReplyHTML(reply) {
        const canDelete = reply.user_id === parseInt($('meta[name="user-id"]').attr('content'));
        const userPhoto = reply.user.profile_photo
            ? `/storage/profile_photos/${reply.user.profile_photo}`
            : '/lib/default_media/default.jpg';

        return `
            <div class="reply-item" id="reply-${reply.id}">
                <img src="${userPhoto}"
                     class="reply-avatar" alt="${reply.user.name}">
                <div class="reply-content">
                    <div class="reply-header">
                        <span class="username">${this.escapeHtml(reply.user.name)}</span>
                        <span class="reply-time">${reply.created_at}</span>
                    </div>
                    <p class="reply-text">${this.escapeHtml(reply.content)}</p>
                    ${canDelete ? `
                        <button class="btn-delete" data-comment-id="${reply.id}">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    ` : ''}
                </div>
            </div>
        `;
    }
}

// Initialize when document is ready
$(document).ready(() => {
    new CommentSystem();
});
