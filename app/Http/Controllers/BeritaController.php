<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\categori;
use App\Models\Agenda;
use App\Models\Announcement;
use App\Models\Achievement;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Database\Eloquent\ModelNotFoundException;
/**
 * Controller untuk mengelola semua fungsionalitas terkait berita/artikel
 *
 * Class ini menangani operasi-operasi seperti:
 * - Menampilkan daftar artikel
 * - Menampilkan detail artikel
 * - Pencarian artikel
 * - Filter artikel berdasarkan kategori
 * - Menampilkan arsip artikel
 * - Mengelola berita fakultas
 *
 * @package App\Http\Controllers
 * @author Natanael Ben Iriyanto / Natzsixn - RPL 2
 */
class BeritaController extends Controller
{
    /**
     * Menampilkan halaman utama daftar artikel
     *
     * @return \Illuminate\View\View
     * @throws \Exception
     */
    public function index()
    {
        try {
            // Inisialisasi variabel dengan collection kosong sebagai default
            $articles = collect();
            $topArticle = null;
            $trendingArticles = collect();
            $popularArticles = collect();
            $categories = collect();
            $archives = collect();
            $agendas = collect();

            // Ambil artikel teratas berdasarkan views dalam 2 minggu terakhir
            $twoWeeksAgo = now()->subWeeks(2);

            // Pastikan query dijalankan dengan proper error handling
            try {
                $agendas = Agenda::all() ?? collect();

                // Artikel paling populer (top 1)
                $topArticle = Article::with(['author', 'category'])
                    ->where('status', 'published')
                    ->where('published_at', '>=', $twoWeeksAgo)
                    ->orderBy('views', 'desc')
                    ->first();

                // 2 artikel trending berikutnya
                if ($topArticle) {
                    $trendingArticles =
                        Article::with(['author', 'category'])
                            ->where('status', 'published')
                            ->where('published_at', '>=', $twoWeeksAgo)
                            ->where('id', '!=', $topArticle->id)
                            ->orderBy('views', 'desc')
                            ->take(2)
                            ->get() ?? collect();
                }

                // Ambil artikel yang sudah dipublish
                $articles = Article::with(['author', 'category'])
                    ->where('status', 'published')
                    ->latest('published_at')
                    ->paginate(10);

                // Ambil artikel populer dalam 30 hari terakhir
                $popularArticles =
                    Article::with(['author', 'category'])
                        ->where('status', 'published')
                        ->where('published_at', '>=', now()->subDays(30))
                        ->orderBy('views', 'desc')
                        ->take(5)
                        ->get() ?? collect();

                // Ambil semua kategori
                $categories =
                    categori::withCount([
                        'articles' => function ($query) {
                            $query->where('status', 'published');
                        },
                    ])->get() ?? collect();

                // Ambil arsip artikel
                $archives =
                    Article::selectRaw(
                        'YEAR(published_at) as year, MONTH(published_at) as month, COUNT(*) as total'
                    )
                        ->where('status', 'published')
                        ->groupBy('year', 'month')
                        ->orderByDesc('year')
                        ->orderByDesc('month')
                        ->get() ?? collect();
            } catch (\Exception $e) {
                \Log::error('Error in fetching data: ' . $e->getMessage());
            }

            return view(
                'itesa_ac_id.berita.index',
                compact(
                    'topArticle',
                    'trendingArticles',
                    'agendas',
                    'articles',
                    'popularArticles',
                    'categories',
                    'archives'
                )
            );
        } catch (\Exception $e) {
            \Log::error('Error in BeritaController@index: ' . $e->getMessage());
            return abort(500, 'Terjadi kesalahan saat memuat berita');
        }
    }

    public function agendaShow()
    {
        try {
            // Ubah query untuk mengambil semua agenda tanpa filter status dulu
            $agendas = Agenda::orderBy('start_date', 'desc')->get();

            // Tambahkan debug logging
            \Log::info('Debug Agenda:', [
                'count' => $agendas->count(),
                'data' => $agendas->toArray(),
            ]);

            // Ambil artikel populer untuk sidebar
            $popularArticles = Article::with(['author', 'category'])
                ->where('status', 'published')
                ->orderBy('views', 'desc')
                ->take(5)
                ->get();

            // Ambil kategori untuk sidebar
            $categories = categori::withCount([
                'articles' => function ($query) {
                    $query->where('status', 'published');
                },
            ])->get();

            return view(
                'itesa_ac_id.berita.show_agenda',
                compact('agendas', 'popularArticles', 'categories')
            );
        } catch (\Exception $e) {
            \Log::error('Error in agendaShow: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return back()->with(
                'error',
                'Terjadi kesalahan saat memuat agenda'
            );
        }
    }

    /**
     * Menampilkan detail artikel berdasarkan slug
     *
     * @param string $slug Slug artikel yang akan ditampilkan
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function show($slug)
    {
        try {
            // Cari artikel berdasarkan slug
            $article = Article::with(['author', 'categories'])
                ->where('slug', $slug)
                ->where('status', 'published')
                ->firstOrFail();

            $comments = $article
                ->comments()
                ->with(['user', 'replies.user'])
                ->whereNull('parent_id') // Hanya komentar utama
                ->orderBy('created_at', 'desc')
                ->get();
            $comments = $comments ?? collect();
            // Increment view count
            $article->increment('views');

            // Ambil semua kategori dengan jumlah artikel
            $allCategories = categori::withCount([
                'articles' => function ($query) {
                    $query->where('status', 'published');
                },
            ])->get();

            // Artikel terkait (dari kategori yang sama)
            $relatedArticles = Article::with(['author', 'categories'])
                ->where('status', 'published')
                ->where('id', '!=', $article->id)
                ->whereHas('categories', function ($query) use ($article) {
                    $query->whereIn(
                        'categories.id',
                        $article->categories->pluck('id')
                    );
                })
                ->latest('published_at')
                ->take(5)
                ->get();

            // Artikel terbaru
            $latestArticles = Article::with(['author', 'categories'])
                ->where('status', 'published')
                ->where('id', '!=', $article->id)
                ->latest('published_at')
                ->take(5)
                ->get();

            // Artikel yang direkomendasikan (bisa berdasarkan kategori yang sama)
            $recommendedArticles = Article::with(['author', 'categories'])
                ->where('status', 'published')
                ->where('id', '!=', $article->id)
                ->whereHas('categories', function ($query) use ($article) {
                    $query->whereIn(
                        'categories.id',
                        $article->categories->pluck('id')
                    );
                })
                ->inRandomOrder()
                ->take(5)
                ->get();

            // Artikel populer minggu ini
            $weeklyTopArticles = Article::with(['author', 'categories'])
                ->where('status', 'published')
                ->where('published_at', '>=', now()->subWeek())
                ->orderByDesc('views')
                ->take(5)
                ->get();

            // Kategori populer dengan artikel terpopulernya
            $popularCategories = categori::withCount([
                'articles' => function ($query) {
                    $query->where('status', 'published');
                },
            ])
                ->having('articles_count', '>', 0)
                ->orderByDesc('articles_count')
                ->take(5)
                ->get()
                ->each(function ($category) {
                    $category->popular_articles = Article::where(
                        'status',
                        'published'
                    )
                        ->whereHas('categories', function ($query) use (
                            $category
                        ) {
                            $query->where('categories.id', $category->id);
                        })
                        ->orderByDesc('views')
                        ->take(3)
                        ->get();
                });
            return view(
                'itesa_ac_id.berita.show',
                compact(
                    'article',
                    'allCategories',
                    'relatedArticles',
                    'latestArticles',
                    'recommendedArticles',
                    'weeklyTopArticles',
                    'popularCategories',
                    'comments'
                )
            );
        } catch (\Exception $e) {
            \Log::error('Error in show article: ' . $e->getMessage());
            return redirect()
                ->route('berita.index')
                ->with('error', 'Terjadi kesalahan saat memuat artikel');
        }
    }

    /**
     * Melakukan pencarian artikel berdasarkan kata kunci
     *
     * @param \Illuminate\Http\Request $request Request yang berisi parameter pencarian
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function search(Request $request)
    {
        /*
         *
         * Melakukan pencarian artikel berdasarkan kata kunci
         *
         */
        try {
            // Ambil kata kunci dari request
            $query = $request->input('q');

            // Ambil semua kategori
            $categories = Categori::all();

            // Ambil arsip artikel berdasarkan tahun dan bulan
            $archives = Article::where('status', 'published')
                ->whereNotNull('published_at')
                ->selectRaw(
                    'YEAR(published_at) as year, MONTH(published_at) as month, COUNT(*) as total'
                )
                ->groupBy('year', 'month')
                ->orderByDesc('year')
                ->orderByDesc('month')
                ->get()
                ->map(function ($item) {
                    $date = Carbon::createFromDate(
                        $item->year,
                        $item->month,
                        1
                    );
                    return [
                        'year' => $item->year,
                        'month' => $date->format('F'),
                        'month_number' => $item->month,
                        'total' => $item->total,
                    ];
                });

            // Ambil artikel populer dalam 30 hari terakhir
            $popularArticles = Article::with(['author', 'category'])
                ->where('status', 'published')
                ->where('published_at', '>=', now()->subDays(30))
                ->orderBy('views', 'desc')
                ->take(5)
                ->get();

            // Query pencarian artikel
            $articles = Article::where('status', 'published')
                ->whereNotNull('published_at')
                ->where(function ($q) use ($query) {
                    $q
                        ->where('title', 'like', "%{$query}%")
                        ->orWhere('content', 'like', "%{$query}%");
                })
                ->orderByDesc('published_at')
                ->paginate(10);

            return view('itesa_ac_id.berita.menu', [
                'articles' => $articles,
                'categories' => $categories,
                'archives' => $archives,
                'query' => $query,
                'popularArticles' => $popularArticles,
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in search: ' . $e->getMessage());
            return redirect()
                ->route('berita.index')
                ->with('error', 'Terjadi kesalahan saat melakukan pencarian');
        }
    }

    /**
     * Menampilkan artikel berdasarkan kategori
     *
     * @param string|null $slug Slug kategori yang akan difilter
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function category($slug = null)
    {
        /*
         *
         * Menampilkan artikel berdasarkan kategori
         *
         */
        try {
            if (!$slug) {
                return redirect()->route('berita.index');
            }

            // \Log::info('Searching for category:', ['slug' => $slug]);

            $category = categori::where('slug', $slug)->firstOrFail();

            // \Log::info('Category found:', [
            //     'id' => $category->id,
            //     'name' => $category->name,
            // ]);

            // Query artikel
            $articlesQuery = Article::with(['author', 'categories'])
                ->whereHas('categories', function ($query) use ($category) {
                    $query->where('categories.id', $category->id);
                })
                ->where('status', 'published')
                ->whereNotNull('published_at')
                ->latest('published_at');

            $articles = $articlesQuery->paginate(10);

            // Ambil artikel populer dalam 30 hari terakhir
            $popularArticles = Article::with(['author', 'category'])
                ->where('status', 'published')
                ->where('published_at', '>=', now()->subDays(30))
                ->orderBy('views', 'desc')
                ->take(5)
                ->get();

            // Ambil semua kategori
            $categories = categori::withCount([
                'articles' => function ($query) {
                    $query
                        ->where('status', 'published')
                        ->whereNotNull('published_at');
                },
            ])
                ->orderBy('name')
                ->get();

            // Ambil arsip artikel berdasarkan tahun dan bulan
            $archives = Article::where('status', 'published')
                ->whereNotNull('published_at')
                ->selectRaw(
                    'YEAR(published_at) as year, MONTH(published_at) as month, COUNT(*) as total'
                )
                ->groupBy('year', 'month')
                ->orderByDesc('year')
                ->orderByDesc('month')
                ->get()
                ->map(function ($item) {
                    $date = \Carbon\Carbon::createFromDate(
                        $item->year,
                        $item->month,
                        1
                    );
                    return [
                        'year' => $item->year,
                        'month' => $date->format('F'),
                        'month_number' => $item->month,
                        'total' => $item->total,
                    ];
                });

            return view('itesa_ac_id.berita.menu', [
                'articles' => $articles,
                'category' => $category,
                'categories' => $categories,
                'archives' => $archives,
                'currentCategory' => $category,
                'popularArticles' => $popularArticles,
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in category view: ' . $e->getMessage());
            if ($e instanceof ModelNotFoundException) {
                return abort(404, 'Kategori tidak ditemukan');
            }
            return abort(500, 'Terjadi kesalahan saat memuat kategori');
        }
    }

    /**
     * Menampilkan artikel berdasarkan arsip (tahun dan bulan)
     *
     * @param int $year Tahun arsip
     * @param int $month Bulan arsip
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function archive($year, $month)
    {
        try {
            $articles = Article::with(['author', 'category'])
                ->where('status', 'published')
                ->whereYear('published_at', $year)
                ->whereMonth('published_at', $month)
                ->latest('published_at')
                ->paginate(10);

            // Ambil artikel populer dalam 30 hari terakhir
            $popularArticles = Article::with(['author', 'category'])
                ->where('status', 'published')
                ->where('published_at', '>=', now()->subDays(30))
                ->orderBy('views', 'desc')
                ->take(5)
                ->get();

            $categories = categori::withCount([
                'articles' => function ($query) {
                    $query->where('status', 'published');
                },
            ])->get();

            $archives = Article::selectRaw(
                'YEAR(published_at) as year,
                                         MONTH(published_at) as month,
                                         COUNT(*) as total'
            )
                ->where('status', 'published')
                ->groupBy('year', 'month')
                ->orderByDesc('year')
                ->orderByDesc('month')
                ->get();

            $monthName = Carbon::createFromDate(
                $year,
                $month,
                1
            )->translatedFormat('F');

            return view('itesa_ac_id.berita.menu', [
                'articles' => $articles,
                'categories' => $categories,
                'archives' => $archives,
                'archive' => [
                    'year' => $year,
                    'month' => $month,
                    'month_name' => $monthName,
                ],
                'popularArticles' => $popularArticles,
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in archive view: ' . $e->getMessage());
            return redirect()
                ->route('berita.index')
                ->with('error', 'Arsip tidak ditemukan');
        }
    }

    /**
     * Method helper untuk mengambil berita berdasarkan fakultas
     *
     * @param string $fakultas Slug fakultas yang akan diambil beritanya
     * @return \Illuminate\View\View
     * @throws \Exception
     */
    private function getFakultasBerita($fakultas)
    {
        try {
            // Ambil berita terbaru sesuai fakultas
            $articles = Article::with(['author', 'category'])
                ->where('status', 'published')
                ->whereHas('category', function ($query) use ($fakultas) {
                    $query->where('slug', $fakultas);
                })
                ->latest('published_at')
                ->take(3)
                ->get();

            // Ambil berita populer sesuai fakultas
            $popularArticles = Article::with(['author', 'category'])
                ->where('status', 'published')
                ->whereHas('category', function ($query) use ($fakultas) {
                    $query->where('slug', $fakultas);
                })
                ->orderBy('views', 'desc')
                ->take(5)
                ->get();

            return view(
                'indexfk_sains_aktuaria',
                compact('articles', 'popularArticles')
            );
        } catch (\Exception $e) {
            \Log::error(
                'Error in BeritaController@getFakultasBerita: ' .
                    $e->getMessage()
            );
            return view('indexfk_sains_aktuaria', [
                'articles' => collect(),
                'popularArticles' => collect(),
            ])->with('error', 'Terjadi kesalahan saat memuat berita');
        }
    }

    /**
     * Menampilkan berita khusus Fakultas Sains Aktuaria
     *
     * @return \Illuminate\View\View
     */
    public function sainsAktuaria()
    {
        return $this->getFakultasBerita('sains-aktuaria');
    }

    /**
     * Menampilkan berita khusus Fakultas Manajemen Retail
     *
     * @return \Illuminate\View\View
     */
    public function manajemenRetail()
    {
        return $this->getFakultasBerita('manajemen-retail');
    }

    /**
     * Menampilkan berita khusus Program Studi RPL
     *
     * @return \Illuminate\View\View
     */
    public function rpl()
    {
        return $this->getFakultasBerita('rpl');
    }

    /**
     * Menampilkan berita khusus Program Studi Statistika
     *
     * @return \Illuminate\View\View
     */
    public function statistika()
    {
        return $this->getFakultasBerita('statistika');
    }

    /**
     * Menampilkan profil user beserta artikel dan komentar mereka
     *
     * @param string|null $username Username dari user yang akan ditampilkan
     * @return \Illuminate\View\View
     */
    public function userProfile($username = null)
    {
        try {
            // Jika username tidak ada, gunakan user yang sedang login
            if (!$username) {
                $user = Auth::user();
            } else {
                // Jika melihat profil user lain
                $user = User::where('username', $username)->firstOrFail();
            }

            // Ambil artikel yang ditulis user
            $articles = Article::with(['category', 'comments'])
                ->where('author_id', $user->id)
                ->where('status', 'published')
                ->latest('published_at')
                ->paginate(5, ['*'], 'articles');

            // Ambil komentar yang dibuat user
            $comments = Comment::with(['article', 'user'])
                ->where('user_id', $user->id)
                ->latest()
                ->paginate(5, ['*'], 'comments');

            // Statistik user
            $stats = [
                'total_articles' => Article::where('author_id', $user->id)
                                         ->where('status', 'published')
                                         ->count(),
                'total_comments' => Comment::where('user_id', $user->id)->count(),
                'total_views' => Article::where('author_id', $user->id)
                                       ->where('status', 'published')
                                       ->sum('views'),
            ];

            return view('itesa_ac_id.berita.profile', compact(
                'user',
                'articles',
                'comments',
                'stats'
            ));

        } catch (ModelNotFoundException $e) {
            return redirect()
                ->route('berita.index')
                ->with('error', 'User tidak ditemukan');
        } catch (\Exception $e) {
            \Log::error('Error in user profile: ' . $e->getMessage());
            return redirect()
                ->route('berita.index')
                ->with('error', 'Terjadi kesalahan saat memuat profil');
        }
    }
}
