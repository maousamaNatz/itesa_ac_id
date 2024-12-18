@extends('indexdash')

@section('content')
<div class="mx-auto bg-white rounded-lg shadow-lg overflow-hidden">
    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 h-48"></div>
    <form action="{{ route('profile.upload') }}" method="post" class="relative -mt-16 flex justify-center">
        <input type="file" id="profileImage" name="profile_image" accept="image/*" class="hidden">
        <img id="profilePreview" class="w-32 h-32 rounded-full border-4 border-white shadow-md cursor-pointer" src="{{ asset('storage/profile_photos/' . Auth::user()->profile_photo) ?? asset('./lib/default_media/default.jpg') }}" alt="User Profile Picture">
    </form>
    <div class="text-center mt-6">
        <h2 class="text-2xl font-semibold text-gray-800">
            <input type="text" id="name" value="{{ Auth::user()->username }}" class="bg-transparent border-none text-center">
        </h2>
        {{-- <p class="text-gray-600">{{ '@' . Auth::user()->username }}</p> --}}
        <p class="text-gray-500 mt-2">
            <textarea id="bio" class="bg-transparent border-none text-center">{{ Auth::user()->bio ?? 'Web Developer & Designer | Coffee Lover' }}</textarea>
        </p>
    </div>

    <div class="flex justify-around mt-6">
        <div class="text-center">
            <h3 class="text-lg font-bold text-gray-800">{{ $articles->count() }}</h3>
            <p class="text-gray-600">Posts</p>
        </div>
        <div class="text-center">
            <h3 class="text-lg font-bold text-gray-800">{{ $totalViews ?? 0  }}</h3>
            <p class="text-gray-600">read article</p>
        </div>
        <div class="text-center">
            <h3 class="text-lg font-bold text-gray-800">280</h3>
            <p class="text-gray-600">Following</p>
        </div>
    </div>

    <div class="px-6 py-4">
        <h3 class="text-lg font-medium text-gray-800">About Me</h3>
        <p class="text-gray-600 mt-2">{{ Auth::user()->bio ?? 'I am a passionate developer with 5+ years of experience creating stunning web applications. I love working with modern tools and sharing my knowledge with the community.' }}</p>
    </div>

    <div class="flex justify-center gap-4 py-4 border-t border-gray-200">
        <a href="{{ route('profile.edit') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md shadow hover:bg-indigo-700">Edit Profile</a>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const saveButton = document.getElementById('saveProfile');
    const nameInput = document.getElementById('name');
    const bioInput = document.getElementById('bio');
    const profileImageInput = document.getElementById('profileImage');
    const profilePreview = document.getElementById('profilePreview');

    saveButton.addEventListener('click', function () {
        const formData = new FormData();
        formData.append('name', nameInput.value);
        formData.append('bio', bioInput.value);

        fetch('/dash/profile/update', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccess('Profile updated successfully!');
            } else {
                showError('Failed to update profile.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('An error occurred while updating the profile.');
        });
    });

    profilePreview.addEventListener('click', function() {
        profileImageInput.click();
    });

    profileImageInput.addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                profilePreview.src = e.target.result;
            };
            reader.readAsDataURL(file);

            // Upload file setelah dipilih
            const formData = new FormData();
            formData.append('profile_image', file);

            fetch('/dash/profile/upload', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccess('Foto profil berhasil diperbarui');
                } else {
                    showError('Gagal memperbarui foto profil');
                }
            })
            .catch(error => console.error('Error:', error));
        } else {
            console.warn('Tidak ada file yang dipilih');
        }
    });
});
</script>
@endsection
