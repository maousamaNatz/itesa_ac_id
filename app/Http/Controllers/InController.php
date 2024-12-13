<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Agenda;
use App\Models\categori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class InController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // Inisialisasi variabel
            $latestArticles = collect();
            $upcomingAgendas = collect();
            $popularArticles = collect();
            $categories = collect();
            $articlesByCategory = collect();

            // Debug untuk melihat query
            \DB::enableQueryLog();

            // Mengambil artikel terbaru yang sudah dipublish
            if (Schema::hasTable('articles')) {
                $latestArticles = Article::query()
                    ->with(['author', 'categories'])
                    ->where('status', 'published')
                    ->whereHas('categories', function($query) {
                        $query->where('categories.id', 1);
                    })
                    ->orderBy('created_at', 'desc')
                    ->take(4)
                    ->get();

                // Log untuk debugging
                \Log::info('Latest Articles Query:', [
                    'query' => \DB::getQueryLog(),
                    'count' => $latestArticles->count(),
                    'articles' => $latestArticles
                ]);
            }

            // Mengambil agenda mendatang
            if (Schema::hasTable('agendas')) {
                $upcomingAgendas = Agenda::where('start_date', '>=', now())
                    ->orderBy('start_date', 'asc')
                    ->take(3)
                    ->get();
            }

            // Mengambil artikel populer
            if (Schema::hasTable('articles')) {
                $popularArticles = Article::query()
                    ->with(['author', 'categories'])
                    ->where('status', 'published')
                    ->orderBy('views', 'desc')
                    ->take(5)
                    ->get();
            }

            // Mengambil kategori dengan jumlah artikel
            if (Schema::hasTable('categories')) {
                $categories = categori::withCount(['articles' => function($query) {
                    $query->where('status', 'published');
                }])->get();
            }

            // Mengambil artikel berdasarkan kategori
            if (Schema::hasTable('categories')) {
                foreach($categories as $category) {
                    $articlesByCategory[$category->slug] = Article::query()
                        ->with(['author', 'categories'])
                        ->where('status', 'published')
                        ->whereHas('categories', function($query) use ($category) {
                            $query->where('categories.id', $category->id);
                        })
                        ->latest('created_at')
                        ->take(3)
                        ->get();
                }
            }

            return view('itesa_ac_id.index', compact(
                'upcomingAgendas',
                'latestArticles',
                'popularArticles',
                'categories',
                'articlesByCategory'
            ));

        } catch (\Exception $e) {
            \Log::error('Error di InController@index: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());

            return view('itesa_ac_id.index', [
                'upcomingAgendas' => collect(),
                'latestArticles' => collect(),
                'popularArticles' => collect(),
                'categories' => collect(),
                'articlesByCategory' => collect()
            ])->with('error', 'Terjadi kesalahan saat memuat data');
        }
    }

    public function management_retail()
    {
        try {
            // Cari kategori dengan slug 'management-retail'
            $category = categori::where('slug', 'management-retail')->firstOrFail();

            $articles = Article::with(['author', 'category'])
                ->whereHas('categories', function($query) use ($category) {
                    $query->where('categories.id', $category->id);
                })
                ->where('status', 'published')
                ->latest('published_at')
                ->paginate(10);

            $popularArticles = Article::with(['author', 'category'])
                ->whereHas('categories', function($query) use ($category) {
                    $query->where('categories.id', $category->id);
                })
                ->where('status', 'published')
                ->orderBy('views', 'desc')
                ->take(5)
                ->get();

            $categories = categori::withCount(['articles' => function($query) {
                $query->where('status', 'published');
            }])->get();

            return view('indexfk_management_retail', compact(
                'articles',
                'category',
                'popularArticles',
                'categories'
            ));

        } catch (ModelNotFoundException $e) {
            \Log::error('Kategori management-retail tidak ditemukan');
            abort(404, 'Halaman tidak ditemukan');
        }
    }

    public function teknologi_informasi()
    {
        try {
            $category = categori::where('slug', 'teknologi-informasi')->firstOrFail();

            $articles = Article::with(['author', 'category'])
                ->where('status', 'published')
                ->where('categori_id', $category->id)
                ->latest('published_at')
                ->paginate(6);

            return view('indexrpl', compact('articles', 'category'));

        } catch (\Exception $e) {
            \Log::error('Error di InController@teknologi_informasi: ' . $e->getMessage());
            return abort(404, 'Halaman tidak ditemukan');
        }
    }

    public function sains_aktuaria()
    {
        try {
            $category = categori::where('slug', 'sains-aktuaria')->firstOrFail();

            $articles = Article::with(['author', 'category'])
                ->where('status', 'published')
                ->where('category_id', $category->id)
                ->latest('published_at')
                ->paginate(6);

            // Ambil data tambahan untuk sidebar
            $popularArticles = Article::with(['author', 'category'])
                ->where('status', 'published')
                ->where('category_id', $category->id)
                ->orderBy('views', 'desc')
                ->take(5)
                ->get();

            $categories = categori::withCount(['articles' => function($query) {
                $query->where('status', 'published');
            }])->get();

            return view('indexfk_sains_aktuaria', compact(
                'articles',
                'category',
                'popularArticles',
                'categories'
            ));

        } catch (\Exception $e) {
            \Log::error('Error di InController@sains_aktuaria: ' . $e->getMessage());
            return abort(404, 'Halaman tidak ditemukan');
        }
    }
}
