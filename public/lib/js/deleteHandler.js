function initializeDeleteHandlers(config = null) {
    // Cek notifikasi dari session
    if (typeof serverNotification !== 'undefined') {
        switch(serverNotification.type) {
            case 'success':
                showSuccess(serverNotification.message, serverNotification.title);
                break;
            case 'error':
                showError(serverNotification.message, serverNotification.title);
                break;
            case 'warning':
                showWarning(serverNotification.message, serverNotification.title);
                break;
        }
    }

    const deleteButtons = document.querySelectorAll('.delete-btn');

    deleteButtons.forEach(button => {
        button.addEventListener('click', async function(e) {
            e.preventDefault();

            const form = this.closest('.delete-form');
            const isArticle = this.querySelector('.fa-trash') && !this.textContent.includes('Hapus');

            // Tentukan konfigurasi berdasarkan tipe
            let deleteText, confirmText;
            if (config) {
                if (isArticle) {
                    deleteText = config.article.text;
                    confirmText = config.article.confirmButtonText;
                } else {
                    deleteText = config.comment.text;
                    confirmText = config.comment.confirmButtonText;
                }
            } else {
                // Default fallback jika tidak ada config
                deleteText = 'Item yang dihapus tidak dapat dikembalikan!';
                confirmText = 'Ya, hapus!';
            }

            try {
                const result = await confirmDelete({
                    title: 'Apakah Anda yakin?',
                    text: deleteText,
                    confirmButtonText: confirmText,
                    icon: 'warning'
                });

                if (result.isConfirmed) {
                    // Tampilkan loading state
                    Swal.fire({
                        title: 'Sedang memproses...',
                        text: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        allowEnterKey: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    form.submit();
                }
            } catch (error) {
                showError('Terjadi kesalahan saat menghapus data');
            }
        });
    });
}
