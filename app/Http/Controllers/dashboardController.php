<?php
/*
|--------------------------------------------------------------------------
| Controller Dashboard
|--------------------------------------------------------------------------
| berikut adalah controller dashboard yang digunakan untuk mengelola dashboard secara
| keseluruhan dari portal berita ITESA tidak untuk web itesa secara keseluruhan dan
| tidak untuk web lainnya
*/

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\User;
use App\Models\Comment;
use App\Models\categori;
use App\Models\Agenda;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\View;
use App\Traits\MediaHandlerTrait;
use App\Traits\NotificationHandlerTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class DashboardController extends Controller
{
    use MediaHandlerTrait, NotificationHandlerTrait;

    public function index()
    {
        $user = auth()->user();
        $totalArticles = Article::count();

        // Cek apakah kolom views ada
        try {
            $totalViews = Article::sum('views');
        } catch (\Exception $e) {
            $totalViews = 0;
        }

        $totalComments = Comment::count() ?? 0;
        $totalUsers = User::count() ?? 0;
        $articles = Article::with(['category', 'author'])
            ->latest()
            ->paginate(10);
        $latestComments = Comment::with(['user', 'article'])
            ->latest()
            ->take(5)
            ->get();

        return view(
            'itesa_ac_id.dashboard.beranda',
            compact(
                'user',
                'articles',
                'totalArticles',
                'totalViews',
                'totalComments',
                'totalUsers',
                'latestComments'
            )
        );
    }

    public function article()
    {
        $articles = Article::with(['category', 'author'])
            ->latest()
            ->paginate(15);
        $categories = categori::all();

        return view(
            'itesa_ac_id.dashboard.article',
            compact('articles', 'categories')
        );
    }
    public function createarticle()
    {
        $categories = categori::orderBy('name')->get();
        return view(
            'itesa_ac_id.dashboard.createarticle',
            compact('categories')
        );
    }
    public function storeArticle(Request $request)
    {
        try {
            // Tambahkan logging untuk memeriksa request
            \Log::info('Article request data:', $request->all());

            $validated = $request->validate([
                'title' => 'required|max:255',
                'content' => 'required',
                'thumbnail' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                'meta_title' => 'nullable|max:255',
                'meta_description' => 'nullable',
                'meta_keyword' => 'nullable|string|max:255',
                'category_ids' => 'required|array',
                'category_ids.*' => 'exists:categories,id',
                'status' => 'required|in:draft,published',
                'tags' => 'required|string'
            ]);

            // Log data yang sudah divalidasi
            \Log::info('Validated data:', $validated);

            DB::beginTransaction();

            // Handle thumbnail upload
            $thumbnailPath = null;
            if ($request->hasFile('thumbnail')) {
                \Log::info('Upload attempt:', [
                    'original_name' => $request->file('thumbnail')->getClientOriginalName(),
                    'mime_type' => $request->file('thumbnail')->getMimeType(),
                    'size' => $request->file('thumbnail')->getSize()
                ]);

                $thumbnailPath = $this->handleMediaUpload(
                    $request->file('thumbnail'),
                    'thumbnail'
                );

                // Verifikasi file tersimpan
                if (!File::exists(public_path('storage/' . $thumbnailPath))) {
                    throw new \Exception('File gagal disimpan');
                }
            }

            // Buat artikel baru dengan nilai default untuk meta fields
            $article = Article::create([
                'title' => $request->title,
                'slug' => Str::slug($request->title),
                'content' => $request->content,
                'thumbnail' => $thumbnailPath,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'meta_keyword' => $request->meta_keyword,
                'author_id' => auth()->id(),
                'category_id' => $request->category_ids[0],
                'status' => $request->status,
                'published_at' => $request->status === 'published' ? now() : null,
                'views' => 0
            ]);

            // Handle tags
            if ($request->has('tags')) {
                // Split tags string menjadi array (misalnya "tag1, tag2, tag3" menjadi ['tag1', 'tag2', 'tag3'])
                $tagNames = explode(',', $validated['tags']);
                $tagIds = [];

                foreach ($tagNames as $tagName) {
                    $tagName = trim($tagName);
                    $tag = Tag::firstOrCreate([
                        'name' => $tagName,
                        'slug' => Str::slug($tagName)
                    ]);
                    $tagIds[] = $tag->id;
                }

                // Attach tags ke artikel
                $article->tags()->sync($tagIds);
            }

            // Attach categories
            $article->categories()->attach($request->category_ids);

            DB::commit();

            return redirect()
                ->route('admin.article.index')
                ->with('notification', [
                    'type' => 'success',
                    'title' => 'Berhasil!',
                    'message' => 'Artikel berhasil ditambahkan dengan thumbnail.'
                ]);

        } catch (\Exception $e) {
            DB::rollBack();

            // Hapus file jika ada error menggunakan trait
            if (isset($thumbnailPath)) {
                $this->deleteMedia($thumbnailPath);
            }

            \Log::error('Error in storeArticle:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()
                ->withInput()
                ->with('notification', [
                    'type' => 'error',
                    'title' => 'Error!',
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ]);
        }
    }

    public function category()
    {
        $categories = categori::withCount('articles')
            ->latest()
            ->paginate(10);

        return view('itesa_ac_id.dashboard.category', compact('categories'));
    }

    public function createcategory()
    {
        $categories = Category::all();
        return view(
            'itesa_ac_id.dashboard.createcategory',
            compact('categories')
        );
    }
    public function editcategory(categori $category)
    {
        $categories = Category::all();
        return view(
        'itesa_ac_id.dashboard.editcategory',
            compact('category')
        );
    }
    /**
     * Show all comments
     */
    public function comment()
    {
        $comments = Comment::with(['user', 'article'])
            ->latest()
            ->paginate(15);

        return view('itesa_ac_id.dashboard.comment', compact('comments'));
    }
    /**
     * Upload image for content
     */
    public function uploadImage(Request $request)
    {
        if ($request->hasFile('file')) {
            try {
                $path = $this->handleMediaUpload(
                    $request->file('file'),
                    'content_image'
                );
                return response()->json([
                    'location' => asset('storage/' . $path),
                ]);
            } catch (\Exception $e) {
                return response()->json(
                    [
                        'error' => 'Upload failed: ' . $e->getMessage(),
                    ],
                    500
                );
            }
        }

        return response()->json(
            [
                'error' => 'No file uploaded',
            ],
            400
        );
    }


    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        try {
            // Ambil artikel tanpa memandang status
            $article = Article::with(['author', 'category', 'categories'])
                            ->where('slug', $slug)
                            ->firstOrFail();

            // Jika artikel published, redirect ke halaman publik
            if ($article->status === 'published') {
                return redirect()->route('berita.show', ['slug' => $slug]);
            }

            // Jika draft, tampilkan preview
            return view('itesa_ac_id.dashboard.preview-article', compact('article'));

        } catch (\Exception $e) {
            \Log::error('Error di DashboardController@show: ' . $e->getMessage());
            return redirect()
                ->route('admin.article')
                ->with('error', 'Artikel tidak ditemukan.');
        }
    }
    public function createAgenda()
    {
        $agenda = Agenda::all();
        return view('itesa_ac_id.dashboard.createAgenda', compact('agenda'));
    }
    public function agenda()
    {
        try {
            $agendas = Agenda::orderBy('start_date', 'desc')->paginate(10);
            return view('itesa_ac_id.dashboard.agenda', compact('agendas'));
        } catch (\Exception $e) {
            \Log::error('Error in agenda: ' . $e->getMessage());
            return back()->with('notification', [
                'type' => 'error',
                'title' => 'Error!',
                'message' => 'Terjadi kesalahan saat memuat data agenda'
            ]);
        }
    }
    public function storeAgenda(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'location' => 'required|string|max:255',
                'status' => 'required|in:active,inactive'
            ]);

            // Tambahkan created_by dari user yang sedang login
            $validated['created_by'] = auth()->id();

            DB::beginTransaction();

            $agenda = Agenda::create($validated);

            DB::commit();

            return redirect()
                ->route('admin.agenda.index')
                ->with('notification', [
                    'type' => 'success',
                    'title' => 'Berhasil!',
                    'message' => 'Agenda berhasil ditambahkan'
                ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error in storeAgenda: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('notification', [
                    'type' => 'error',
                    'title' => 'Error!',
                    'message' => 'Terjadi kesalahan saat menyimpan agenda'
                ]);
        }
    }
    public function editAgenda(Agenda $agenda)
    {
        try {
            return view('itesa_ac_id.dashboard.editagenda', compact('agenda'));
        } catch (\Exception $e) {
            \Log::error('Error in editAgenda: ' . $e->getMessage());
            return redirect()
                ->route('admin.agenda')
                ->with('notification', [
                    'type' => 'error',
                    'title' => 'Error!',
                    'message' => 'Terjadi kesalahan saat memuat data agenda'
                ]);
        }
    }
    public function updateAgenda(Request $request, Agenda $agenda)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'location' => 'required|string|max:255',
                'status' => 'required|in:active,inactive'
            ]);

            DB::beginTransaction();

            // Update data agenda tanpa updated_by
            $agenda->update($validated);

            DB::commit();

            return redirect()
                ->route('admin.agenda.index')
                ->with('notification', [
                    'type' => 'success',
                    'title' => 'Berhasil!',
                    'message' => 'Agenda berhasil diperbarui'
                ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error in updateAgenda: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('notification', [
                    'type' => 'error',
                    'title' => 'Error!',
                    'message' => 'Terjadi kesalahan saat memperbarui agenda'
                ]);
        }
    }
    public function destroyAgenda(Agenda $agenda)
    {
        try {
            $agenda->delete();

            return redirect()
                ->route('admin.agenda')
                ->with('notification', [
                    'type' => 'success',
                    'title' => 'Berhasil!',
                    'message' => 'Agenda berhasil dihapus'
                ]);

        } catch (\Exception $e) {
            \Log::error('Error in destroyAgenda: ' . $e->getMessage());
            return back()->with('notification', [
                'type' => 'error',
                'title' => 'Error!',
                'message' => 'Terjadi kesalahan saat menghapus agenda'
            ]);
        }
    }
    public function destroyarticle($article)
    {
        try {
            $article = Article::findOrFail($article);
            // Hapus thumbnail jika ada
            if ($article->thumbnail && Storage::disk('public')->exists($article->thumbnail)) {
                Storage::disk('public')->delete($article->thumbnail);
            }

            // Hapus relasi dengan kategori
            $article->categories()->detach();

            // Hapus artikel
            $article->delete();

            return redirect()
                ->route('admin.article.index')
                ->with('notification', [
                    'type' => 'success',
                    'title' => 'Berhasil!',
                    'message' => 'Artikel berhasil dihapus'
                ]);

        } catch (\Exception $e) {
            \Log::error('Error saat menghapus artikel: ' . $e->getMessage());

            return back()->with('notification', [
                'type' => 'error',
                'title' => 'Error!',
                'message' => 'Gagal menghapus artikel: ' . $e->getMessage()
            ]);
        }
    }

    public function deletecategory(categori $category)
    {
        try {
            // Cek apakah kategori memiliki artikel
            if ($category->articles()->count() > 0) {
                return back()->with('error', 'Kategori tidak dapat dihapus karena masih memiliki artikel terkait.');
            }

            $category->delete();
            return redirect()
                ->route('admin.category')
                ->with('success', 'Kategori berhasil dihapus');
        } catch (\Exception $e) {
            \Log::error('Error in deletecategory: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menghapus kategori');
        }
    }

    public function storecategory(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|max:255',
                'description' => 'nullable',
                'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
            ]);

            // Generate unique slug
            $baseSlug = Str::slug($validated['name']);
            $slug = $baseSlug;
            $counter = 1;

            // Check if slug exists and generate unique one
            while (categori::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }

            $category = new categori();
            $category->name = $validated['name'];
            $category->slug = $slug;
            $category->description = $validated['description'] ?? null;

            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('categories', 'public');
                $category->image = $imagePath;
            }

            $category->save();

            return redirect()
                ->route('admin.category')
                ->with('success', 'Kategori berhasil ditambahkan');
        } catch (\Exception $e) {
            \Log::error('Error in storecategory: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function editarticle($id)
    {
        try {
            // Cari artikel berdasarkan ID
            $article = Article::findOrFail($id);

            // Ambil semua kategori
            $categories = categori::orderBy('name')->get();

            // Tampilkan view dengan data artikel dan kategori
            return view('itesa_ac_id.dashboard.editarticle', compact('article', 'categories'));

        } catch (\Exception $e) {
            \Log::error('Error in editarticle: ' . $e->getMessage());

            return redirect()
                ->route('admin.article.index')
                ->with('notification', [
                    'type' => 'error',
                    'title' => 'Error!',
                    'message' => 'Artikel tidak ditemukan atau terjadi kesalahan'
                ]);
        }
    }

    public function updatearticle(Request $request, $id)
    {
        try {
            // Cari artikel berdasarkan ID
            $article = Article::findOrFail($id);

            // Validasi request
            $validated = $request->validate([
                'title' => 'required|max:255',
                'content' => 'required',
                'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'meta_title' => 'nullable|max:255',
                'meta_description' => 'nullable',
                'category_ids' => 'required|array',
                'category_ids.*' => 'exists:categories,id',
                'status' => 'required|in:draft,published'
            ]);

            DB::beginTransaction();

            // Update artikel yang sudah ada
            $article->title = $validated['title'];
            $article->content = $validated['content'];
            $article->meta_title = $validated['meta_title'] ?? $validated['title'];
            $article->meta_description = $validated['meta_description'] ?? Str::limit(strip_tags($validated['content']), 160);
            $article->category_id = $validated['category_ids'][0];
            $article->status = $validated['status'];
            $article->author_id = auth()->id();

            // Update slug jika judul berubah
            if ($article->isDirty('title')) {
                $slug = Str::slug($validated['title']);
                $baseSlug = $slug;
                $counter = 1;

                while (Article::where('slug', $slug)->where('id', '!=', $article->id)->exists()) {
                    $slug = $baseSlug . '-' . $counter;
                    $counter++;
                }
                $article->slug = $slug;
            }

            // Update thumbnail jika ada
            if ($request->hasFile('thumbnail')) {
                // Hapus thumbnail lama jika ada
                if ($article->thumbnail) {
                    Storage::disk('public')->delete($article->thumbnail);
                }
                $thumbnailPath = $request->file('thumbnail')->store('articles/thumbnails', 'public');
                $article->thumbnail = $thumbnailPath;
            }

            // Update published_at jika status berubah menjadi published
            if ($validated['status'] === 'published' && !$article->published_at) {
                $article->published_at = now();
            }

            $article->save();

            // Update categories
            $article->categories()->sync($validated['category_ids']);

            DB::commit();

            return redirect()
                ->route('admin.article.index')
                ->with('notification', [
                    'type' => 'success',
                    'title' => 'Berhasil!',
                    'message' => 'Artikel berhasil diperbarui.'
                ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error in updatearticle: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('notification', [
                    'type' => 'error',
                    'title' => 'Error!',
                    'message' => 'Terjadi kesalahan saat memperbarui artikel: ' . $e->getMessage()
                ]);
        }
    }
}
