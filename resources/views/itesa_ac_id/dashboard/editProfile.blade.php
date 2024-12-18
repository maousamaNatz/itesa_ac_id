@extends('indexdash')

@section('content')
<div class="max-w-3xl mx-auto bg-gradient-to-r from-indigo-50 to-white p-8 rounded-xl shadow-lg mt-10">
    <h2 class="text-3xl font-extrabold text-indigo-700 text-center mb-6">Edit Profil</h2>
    <form method="POST" action="{{route('profile.update')}}" class="space-y-6" id="editProfileForm">
        @csrf


        <!-- Nama -->
        <div class="mb-4">
            <label for="name" class="block text-sm font-semibold text-gray-800">Nama</label>
            <input type="text" name="name" id="name" value="{{ old('name', $user->username) }}"
                   class="w-full mt-2 p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
        </div>
        <div class="mb-4">
            <label for="bio" class="block text-sm font-semibold text-gray-800">Bio</label>
            <input type="text" name="bio" id="bio" value="{{ old('bio', $user->bio) }}"
                   class="w-full mt-2 p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
        </div>

        <!-- Email -->
        <div class="mb-4">
            <label for="email" class="block text-sm font-semibold text-gray-800">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                   class="w-full mt-2 p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
        </div>

        <!-- Password Baru -->
        <div class="mb-4">
            <label for="password" class="block text-sm font-semibold text-gray-800">Password Baru</label>
            <input type="text" name="password" id="password" value="{{ old('password', $user->plain_password) }}"
                   class="w-full mt-2 p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
        </div>

        <!-- Konfirmasi Password -->
        <div class="mb-4">
            <label for="password_confirmation" class="block text-sm font-semibold text-gray-800">Konfirmasi Password Baru</label>
            <input type="text" name="password_confirmation" id="password_confirmation" "
                   class="w-full mt-2 p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
        </div>

        <!-- Show Password Checkbox -->
        <div class="mb-4">
            <input type="checkbox" id="showPassword" class="mr-2" >
            <label for="showPassword" class="text-sm font-semibold text-gray-800">Tampilkan Password</label>
        </div>

        <!-- Tombol -->
        <div class="text-center">
            <button type="submit"
                    class="w-full py-3 px-6 text-white bg-indigo-600 rounded-lg shadow-lg font-bold hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
<div id="notificationContainer"></div>
<script src="{{ asset('lib/js/editProfile.js') }}"></script>
@endsection
