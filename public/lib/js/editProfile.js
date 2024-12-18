document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('editProfileForm');
    const showPasswordCheckbox = document.getElementById('showPassword');
    const passwordInput = document.getElementById('password');
    const passwordConfirmationInput = document.getElementById('password_confirmation');

    form.addEventListener('submit', function (event) {
        event.preventDefault();

        const formData = new FormData(form);

        fetch('/dash/profile/update', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.text())
        .then(data => {
            console.log('Raw Response:', data);
            if (data.startsWith('<!DOCTYPE html>')) {
                console.error('Received HTML instead of JSON. Check the server response.');
                showError('Terjadi kesalahan saat memperbarui profil.');
                return;
            }
            try {
                const jsonData = JSON.parse(data);
                if (jsonData.success) {
                    showSuccess('Profil berhasil diperbarui!');
                } else {
                    showError('Gagal memperbarui profil.');
                }
            } catch (error) {
                console.error('Error parsing JSON:', error);
                showError('Terjadi kesalahan saat memperbarui profil.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('Terjadi kesalahan saat memperbarui profil.');
        });
    });

    showPasswordCheckbox.addEventListener('change', function () {
        const type = this.checked ? 'text' : 'password';
        passwordInput.type = type;
        passwordConfirmationInput.type = type;
    });
});
