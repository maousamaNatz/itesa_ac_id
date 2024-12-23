document.addEventListener('DOMContentLoaded', function() {
    // Fungsi untuk membuka modal
    window.openModal = function(modalId) {
        document.getElementById(modalId).style.display = 'block';
    };

    // Fungsi untuk menutup modal
    window.closeModal = function(modalId) {
        document.getElementById(modalId).style.display = 'none';
    };

    // Event listener untuk tombol edit profile
    document.getElementById('editProfileBtn').addEventListener('click', function() {
        openModal('editProfileModal');
    });

    // Toggle password visibility
    const toggleButtons = document.querySelectorAll('.btn-toggle-password');
    toggleButtons.forEach(button => {
        button.addEventListener('click', function() {
            const input = this.parentElement.querySelector('input');
            const icon = this.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });

    // Handle form submission
    const form = document.querySelector('.profile-edit-form');
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        // Reset error messages
        clearErrors();

        fetch(window.appConfig.updateUrl, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': window.appConfig.csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData,
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update UI dengan data baru
                document.querySelector('.profile-name').textContent = formData.get('username');
                document.querySelector('.profile-email').textContent = formData.get('email');
                if (document.querySelector('.profile-bio')) {
                    document.querySelector('.profile-bio').textContent = formData.get('bio');
                }

                // Tutup modal
                closeModal('editProfileModal');

                // Tampilkan notifikasi sukses
                showNotification('success', 'Profil berhasil diperbarui');

                // Refresh halaman setelah update berhasil
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                showNotification('error', data.message || 'Terjadi kesalahan saat memperbarui profil');

                // Tampilkan error validasi jika ada
                if (data.errors) {
                    displayErrors(data.errors);
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('error', 'Terjadi kesalahan saat memperbarui profil');
        });
    });

    // Fungsi untuk menampilkan error validasi
    function displayErrors(errors) {
        Object.keys(errors).forEach(field => {
            const input = document.querySelector(`[name="${field}"]`);
            if (input) {
                input.classList.add('error');
                const errorDiv = document.createElement('div');
                errorDiv.className = 'error-message';
                errorDiv.textContent = errors[field][0];
                input.parentNode.appendChild(errorDiv);
            }
        });
    }

    // Fungsi untuk membersihkan error messages
    function clearErrors() {
        document.querySelectorAll('.error-message').forEach(el => el.remove());
        document.querySelectorAll('.error').forEach(el => el.classList.remove('error'));
    }

    // Fungsi untuk menampilkan notifikasi
    function showNotification(type, message) {
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.textContent = message;
        document.body.appendChild(notification);

        setTimeout(() => {
            notification.remove();
        }, 3000);
    }

    // Tambahkan handler untuk upload foto
    const profilePhotoInput = document.getElementById('profile_photo_input');
    const profilePhotoWrapper = document.querySelector('.profile-photo-wrapper');
    const profilePhotoPreview = document.querySelector('.profile-photo-profile');

    // Tambahkan event click ke wrapper foto
    profilePhotoWrapper?.addEventListener('click', () => {
        profilePhotoInput.click();
    });

    // Tambahkan hover effect
    profilePhotoWrapper?.addEventListener('mouseenter', () => {
        profilePhotoWrapper.classList.add('hover');
    });

    profilePhotoWrapper?.addEventListener('mouseleave', () => {
        profilePhotoWrapper.classList.remove('hover');
    });

    // Handler untuk perubahan file
    profilePhotoInput?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Validasi ukuran file (max 2MB)
            if (file.size > 2 * 1024 * 1024) {
                showNotification('error', 'Ukuran file maksimal 2MB');
                return;
            }

            // Validasi tipe file
            if (!file.type.match('image.*')) {
                showNotification('error', 'File harus berupa gambar');
                return;
            }

            // Tambahkan loading state
            profilePhotoWrapper.classList.add('loading');

            // Preview foto
            const reader = new FileReader();
            reader.onload = function(e) {
                profilePhotoPreview.src = e.target.result;
            }
            reader.readAsDataURL(file);

            // Upload foto
            uploadProfilePhoto(file);
        }
    });

    // Fungsi untuk upload foto
    function uploadProfilePhoto(file) {
        const formData = new FormData();
        formData.append('profile_photo', file);
        formData.append('_token', window.appConfig.csrfToken);

        fetch(window.appConfig.uploadPhotoUrl, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': window.appConfig.csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('success', 'Foto profil berhasil diperbarui');
                profilePhotoPreview.src = data.photo_url;
                profilePhotoPreview.dataset.originalSrc = data.photo_url;
            } else {
                showNotification('error', data.message || 'Gagal mengupload foto');
                profilePhotoPreview.src = profilePhotoPreview.dataset.originalSrc;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('error', 'Gagal mengupload foto');
            profilePhotoPreview.src = profilePhotoPreview.dataset.originalSrc;
        })
        .finally(() => {
            // Hapus loading state
            profilePhotoWrapper.classList.remove('loading');
        });
    }
});
