<?php

/**
 * Controller In
 *
 * @package App\Http\Controllers
 * @description Controller untuk mengelola halaman utama dan kategori artikel di ITESA
 */

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Agenda;
use App\Models\categori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class InController
 * Mengelola tampilan halaman utama dan kategori artikel
 *
 * @package App\Http\Controllers
 */
class InController extends Controller
{
    /**
     * Menampilkan halaman utama website
     * Memuat artikel terbaru, agenda mendatang, artikel populer, dan kategori
     *
     * @return \Illuminate\View\View
     * @throws \Exception Ketika terjadi kesalahan dalam memuat data
     *
     * @response {
     *   "view": "itesa_ac_id.index",
     *   "data": {
     *     "latestArticles": "Collection",
     *     "upcomingAgendas": "Collection",
     *     "popularArticles": "Collection",
     *     "categories": "Collection",
     *     "articlesByCategory": "Collection"
     *   }
     * }
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
                    ->whereNotNull('published_at')
                    ->latest('published_at')
                    ->take(8)
                    ->get();

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

    /**
     * Menampilkan halaman kategori Management Retail
     *
     * @return \Illuminate\View\View
     * @throws ModelNotFoundException Ketika kategori tidak ditemukan
     */
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

    /**
     * Menampilkan halaman kategori Teknologi Informasi
     *
     * @return \Illuminate\View\View
     * @throws ModelNotFoundException Ketika kategori tidak ditemukan
     */
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

    /**
     * Menampilkan halaman kategori Sains Aktuaria
     *
     * @return \Illuminate\View\View
     * @throws ModelNotFoundException Ketika kategori tidak ditemukan
     */
    public function sains_aktuaria()
    {
        try {
            $category = categori::where('slug', 'sains-aktuaria')->firstOrFail();

            $articles = Article::with(['author', 'category'])
                ->where('status', 'published')
                ->where('category_id', $category->id)
                ->latest('published_at')
                ->paginate(6);

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
