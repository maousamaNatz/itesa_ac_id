<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

/**
 * Controller untuk mengelola data user/pengguna
 *
 * @package App\Http\Controllers
 */
class UserController extends Controller
{
    /**
     * Constructor untuk UserController
     * Menerapkan middleware auth untuk semua method
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Menampilkan halaman profil user
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     * @throws \Exception Ketika terjadi kesalahan dalam memuat data
     */
    public function profile()
    {
        try {
            $user = Auth::user();

            // Ambil artikel yang ditulis user
            $articles = Article::where('author_id', $user->id)
                ->with(['categories', 'comments'])
                ->orderBy('created_at', 'desc')
                ->paginate(5);

            // Ambil komentar yang dibuat user
            $comments = Comment::where('user_id', $user->id)
                ->with(['article'])
                ->orderBy('created_at', 'desc')
                ->paginate(5);

            // Hitung statistik user
            $stats = [
                'total_articles' => Article::where('author_id', $user->id)->count(),
                'total_comments' => Comment::where('user_id', $user->id)->count(),
                'total_article_views' => Article::where('author_id', $user->id)->sum('views'),
            ];

            return view('itesa_ac_id.user.profile', compact(
                'user',
                'articles',
                'comments',
                'stats'
            ));

        } catch (\Exception $e) {
            Log::error('Error in UserController@profile: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat profil');
        }
    }

    /**
     * Menampilkan form edit profil user
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     * @throws \Exception Ketika terjadi kesalahan dalam memuat form
     */
    public function edit()
    {
        try {
            $user = Auth::user();
            return view('itesa_ac_id.user.edit', compact('user'));
        } catch (\Exception $e) {
            Log::error('Error in UserController@edit: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat form edit');
        }
    }

    /**
     * Memperbarui data profil user
     *
     * @param \Illuminate\Http\Request $request Request dengan data yang akan diupdate
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception Ketika terjadi kesalahan dalam memperbarui data
     *
     * @bodyParam username string required Username baru user. Max:255
     * @bodyParam email string required Email baru user. Max:255
     * @bodyParam bio string optional Bio user. Max:500
     * @bodyParam current_password string required_with:new_password Password saat ini
     * @bodyParam new_password string optional Password baru. Min:8
     * @bodyParam new_password_confirmation string optional Konfirmasi password baru
     */
    public function update(Request $request)
    {
        try {
            $user = Auth::user();

            $validator = Validator::make($request->all(), [
                'username' => 'required|string|max:255|unique:users,username,'.$user->id,
                'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
                'bio' => 'nullable|string|max:500',
                'current_password' => 'required_with:new_password',
                'new_password' => 'nullable|min:8|confirmed'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Validasi password saat ini jika ada password baru
            if ($request->filled('new_password')) {
                if (!Hash::check($request->current_password, $user->password)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Password saat ini tidak sesuai',
                        'errors' => ['current_password' => ['Password saat ini tidak sesuai']]
                    ], 422);
                }
            }

            $user->username = $request->username;
            $user->email = $request->email;
            $user->bio = $request->bio;

            if ($request->filled('new_password')) {
                $user->password = Hash::make($request->new_password);
            }

            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Profil berhasil diperbarui',
                'data' => $user->only(['username', 'email', 'bio'])
            ]);

        } catch (\Exception $e) {
            \Log::error('Error in UserController@update: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui profil'
            ], 500);
        }
    }

    /**
     * Menghapus akun user beserta data terkait
     *
     * @param \Illuminate\Http\Request $request Request dengan password konfirmasi
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception Ketika terjadi kesalahan dalam menghapus akun
     *
     * @bodyParam password string required Password untuk konfirmasi penghapusan akun
     */
    public function destroy(Request $request)
    {
        try {
            $user = Auth::user();

            // Validasi password
            if (!Hash::check($request->password, $user->password)) {
                return back()->with('error', 'Password yang dimasukkan salah');
            }

            // Hapus data terkait user
            Comment::where('user_id', $user->id)->delete();
            Article::where('author_id', $user->id)->delete();

            // Hapus user
            $user->delete();
            Auth::logout();

            return redirect()
                ->route('home')
                ->with('success', 'Akun berhasil dihapus');

        } catch (\Exception $e) {
            Log::error('Error in UserController@destroy: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menghapus akun');
        }
    }

    /**
     * Mengupload dan memperbarui foto profil user
     *
     * @param \Illuminate\Http\Request $request Request dengan file foto
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception Ketika terjadi kesalahan dalam upload foto
     *
     * @bodyParam profile_photo file required File foto profil. Allowed:jpeg,png,jpg. Max:2048KB
     *
     * @response {
     *   "success": true,
     *   "message": "Foto profil berhasil diperbarui",
     *   "photo_url": "http://example.com/storage/profile_photos/123456_abcdef.jpg"
     * }
     *
     * @response 400 {
     *   "success": false,
     *   "message": "Tidak ada file yang diupload"
     * }
     *
     * @response 422 {
     *   "message": "The profile photo field is required.",
     *   "errors": {
     *     "profile_photo": ["The profile photo field is required."]
     *   }
     * }
     *
     * @response 500 {
     *   "success": false,
     *   "message": "Gagal mengupload foto profil: [error message]"
     * }
     */
    public function uploadPhoto(Request $request)
    {
        try {
            $request->validate([
                'profile_photo' => 'required|image|mimes:jpeg,png,jpg|max:2048'
            ]);

            $user = Auth::user();

            if ($request->hasFile('profile_photo')) {
                $photo = $request->file('profile_photo');
                $filename = time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();

                // Buat direktori jika belum ada
                $path = public_path('storage/profile_photos');
                if (!File::isDirectory($path)) {
                    File::makeDirectory($path, 0777, true, true);
                }

                // Hapus foto lama jika ada
                if ($user->profile_photo && File::exists(public_path('storage/profile_photos/' . $user->profile_photo))) {
                    File::delete(public_path('storage/profile_photos/' . $user->profile_photo));
                }

                // Upload foto baru langsung ke public storage
                $photo->move(public_path('storage/profile_photos'), $filename);

                // Update database
                $user->profile_photo = $filename;
                $user->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Foto profil berhasil diperbarui',
                    'photo_url' => asset('storage/profile_photos/' . $filename)
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Tidak ada file yang diupload'
            ], 400);

        } catch (\Exception $e) {
            \Log::error('Error uploading profile photo: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupload foto profil: ' . $e->getMessage()
            ], 500);
        }
    }
}
