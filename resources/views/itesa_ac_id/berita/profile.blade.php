@extends('indexberita')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="profile-section">
        <div class="container">
            <div class="profile-wrapper">
                <!-- Profil User -->
                <div class="profile-card">
                    <div class="profile-header"></div>
                    <div class="profile-body">
                        <div class="profile-photo-wrapper" title="Klik untuk mengubah foto profil">
                            <img src="{{ $user->profile_photo ? asset('storage/profile_photos/'.$user->profile_photo) : asset('lib/default_media/default.jpg') }}"
                                 class="profile-photo-profile"
                                 data-original-src="{{ $user->profile_photo ? asset('storage/profile_photos/'.$user->profile_photo) : asset('lib/default_media/default.jpg') }}"
                                 alt="{{ $user->username }}'s profile photo">

                            @if(Auth::id() === $user->id)
                                <input type="file"
                                       id="profile_photo_input"
                                       accept="image/*"
                                       class="hidden">
                                <div class="photo-overlay">
                                    <i class="fas fa-camera"></i>
                                    <span>Ubah Foto</span>
                                </div>
                            @endif
                        </div>

                        <h4 class="profile-name">{{ $user->username }}</h4>
                        <p class="profile-email">{{ $user->email }}</p>

                        @if ($user->bio)
                            <div class="profile-bio">
                                {{ $user->bio }}
                            </div>
                        @endif

                        @if (Auth::id() === $user->id)
                            <button class="btn-edit-profile" id="editProfileBtn">
                                <i class="fas fa-edit"></i> Edit Profil
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Profil -->
    <div id="editProfileModal" class="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('user.update') }}" method="POST" class="profile-edit-form">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-user-edit"></i> Edit Profil
                        </h5>
                        <button type="button" class="modal-close" onclick="closeModal('editProfileModal')">×</button>
                    </div>

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="username" class="form-label">
                                <i class="fas fa-user"></i> Username
                            </label>
                            <input type="text" id="username" name="username" value="{{ $user->username }}" required>
                        </div>

                        <div class="form-group">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope"></i> Email
                            </label>
                            <input type="email" id="email" name="email" value="{{ $user->email }}" required>
                        </div>

                        <div class="form-group">
                            <label for="bio" class="form-label">
                                <i class="fas fa-info-circle"></i> Bio
                            </label>
                            <textarea id="bio" name="bio" rows="3">{{ $user->bio }}</textarea>
                        </div>

                        <div class="form-divider"></div>

                        <h6 class="form-subtitle">Ubah Password</h6>

                        <div class="form-group">
                            <label for="current_password" class="form-label">
                                <i class="fas fa-lock"></i> Password Saat Ini
                            </label>
                            <div class="password-input-group">
                                <input type="password" id="current_password" name="current_password">
                                <button type="button" class="btn-toggle-password">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="new_password" class="form-label">
                                <i class="fas fa-key"></i> Password Baru
                            </label>
                            <div class="password-input-group">
                                <input type="password" id="new_password" name="new_password">
                                <button type="button" class="btn-toggle-password">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="new_password_confirmation" class="form-label">
                                <i class="fas fa-check-circle"></i> Konfirmasi Password Baru
                            </label>
                            <div class="password-input-group">
                                <input type="password" id="new_password_confirmation" name="new_password_confirmation">
                                <button type="button" class="btn-toggle-password">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn-secondary" onclick="closeModal('editProfileModal')">
                            <i class="fas fa-times"></i> Batal
                        </button>
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-save"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
<!-- Load JavaScript di akhir content -->
@section('scripts')
    <style>
        .error {
            border-color: #dc3545;
        }

        .error-message {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 25px;
            border-radius: 4px;
            color: white;
            z-index: 1000;
            animation: slideIn 0.3s ease-in-out;
        }

        .notification.success {
            background-color: #28a745;
        }

        .notification.error {
            background-color: #dc3545;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .profile-photo-wrapper {
            position: relative;
            width: 150px;
            height: 150px;
            margin: -75px auto 20px;
            cursor: pointer;
            border-radius: 50%;
            overflow: hidden;
        }

        .profile-photo-profile {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: filter 0.3s ease;
        }

        .photo-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: white;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .profile-photo-wrapper:hover .photo-overlay {
            opacity: 1;
        }

        .profile-photo-wrapper:hover .profile-photo-profile {
            filter: brightness(0.8);
        }

        .photo-overlay i {
            font-size: 24px;
            margin-bottom: 8px;
        }

        .photo-overlay span {
            font-size: 14px;
        }

        .hidden {
            display: none;
        }

        /* Loading state */
        .profile-photo-wrapper.loading .photo-overlay {
            opacity: 1;
            background: rgba(255, 255, 255, 0.8);
            color: #4a90e2;
        }

        .profile-photo-wrapper.loading .photo-overlay i {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
    <script>
        window.appConfig = {
            csrfToken: '{{ csrf_token() }}',
            updateUrl: '{{ route("user.update") }}',
            uploadPhotoUrl: '{{ route("user.photo.upload") }}'
        };
    </script>
    <script src="{{ asset('lib/js/profile.js') }}"></script>
@endsection
