<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InController;
use App\Http\Controllers\LoginController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


/*
*
* Route Login
*
*/
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
*
* Route Index
*
*/
Route::get('/', [InController::class, 'index'])->name('itesa.index');

Route::prefix('fk')->name('fakultas.')->group(function () {
    Route::get('/management-Rtl', [InController::class, 'management_retail'])->name('management');
    Route::get('/rpl', [InController::class, 'show_rpl'])->name('rpl');
    Route::get('/sains-aktuaria', [InController::class, 'sains_aktuaria'])->name('sains');
});
/*
*
* Route Berita
*
*/
Route::prefix('berita')->name('berita.')->group(function () {
    Route::get('/', [BeritaController::class, 'index'])->name('index');
    Route::get('/archive/{year}/{month}', [BeritaController::class, 'archive'])->name('archive');
    Route::get('/category/{slug?}', [BeritaController::class, 'category'])->name('category');
    Route::get('/search', [BeritaController::class, 'search'])->name('search');
    Route::get('/{slug}', [BeritaController::class, 'show'  ])->name('show');
    Route::get('/agenda', [BeritaController::class, 'agendaShow'])->name('agendaShow');
});


/*
*
* Route Admin
*
*/
Route::middleware(['auth', 'admin'])->prefix('dash')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');

    /*
    *
    * Route Article
    *
    */
    Route::prefix('article')->name('admin.article.')->group(function () {
        Route::get('/', [DashboardController::class, 'article'])->name('index');
        Route::get('/create', [DashboardController::class, 'createarticle'])->name('create');
        Route::post('/store', [DashboardController::class, 'storeArticle'])->name('store');
        Route::get('/show/{slug}', [DashboardController::class, 'show'])->name('show');
        Route::get('/edit/{id}', [DashboardController::class, 'editarticle'])->name('edit')->middleware('admin');
        Route::put('/update/{id}', [DashboardController::class, 'updatearticle'])->name('update')->middleware('admin');
        Route::delete('/{article}', [DashboardController::class, 'destroyarticle'])->name('destroy');
    });

    /*
    *
    * Route Category
    *
    */
    Route::prefix('category')->name('admin.category.')->group(function () {
        Route::get('/', [DashboardController::class, 'category'])->name('index');
        Route::get('/create', [DashboardController::class, 'createcategory'])->name('create');
        Route::post('/store', [DashboardController::class, 'storecategory'])->name('store');
        Route::get('/{category}/edit', [DashboardController::class, 'editcategory'])->name('edit');
        Route::put('/{category}', [DashboardController::class, 'updatecategory'])->name('update');
        Route::delete('/{category}', [DashboardController::class, 'deletecategory'])->name('destroy');
    });

    /*
    *
    * Route Comment
    *
    */
    Route::prefix('comment')->name('admin.comment.')->group(function () {
        Route::get('/', [DashboardController::class, 'comment'])->name('index');
        Route::get('/{comment}/edit', [DashboardController::class, 'editcomment'])->name('edit');
        Route::delete('/{comment}', [DashboardController::class, 'destroycomment'])->name('destroy')->middleware('admin');
    });

    /*
    *
    * Route Agenda
    *
    */
    Route::prefix('agenda')->name('admin.agenda.')->group(function () {
        Route::get('/', [DashboardController::class, 'agenda'])->name('index');
        Route::get('/create', [DashboardController::class, 'createAgenda'])->name('create');
        Route::post('/store', [DashboardController::class, 'storeAgenda'])->name('store');
        Route::get('/{agenda}/edit', [DashboardController::class, 'editAgenda'])->name('edit');
        Route::put('/{agenda}', [DashboardController::class, 'updateAgenda'])->name('update');
        Route::delete('/{agenda}', [DashboardController::class, 'destroyAgenda'])->name('destroy');
    });


    Route::post('/upload/image', [DashboardController::class, 'uploadImage'])->name('admin.upload.image');
});

/*
*
* Debug Routes
*
*/
if (config('app.debug')) {
    Route::get('/debug/articles', function() {
        // Get all articles
        $allArticles = \App\Models\Article::all();

        // Get articles with relations
        $articlesWithRelations = \App\Models\Article::with(['author', 'category'])->get();

        // Get published articles
        $publishedArticles = \App\Models\Article::where('status', 'published')->get();

        return response()->json([
            'total_articles' => $allArticles->count(),
            'articles' => $allArticles,
            'with_relations' => $articlesWithRelations,
            'published' => $publishedArticles,
            'database_name' => \DB::getDatabaseName(),
            'tables' => \DB::select('SHOW TABLES')
        ]);
    });

    Route::get('/debug/check-db', function() {
        try {
            return response()->json([
                'connection' => \DB::connection()->getDatabaseName(),
                'tables' => \DB::select('SHOW TABLES'),
                'article_columns' => \Schema::getColumnListing('articles')
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    });
}

