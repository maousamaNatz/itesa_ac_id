<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CommentController;
use App\Models\User;
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
    Route::get('/{slug}', [BeritaController::class, 'show'])->name('show');
    Route::get('/agenda', [BeritaController::class, 'agendaShow'])->name('agendaShow');
});

/*
*
* Route User Profile
*
*/
Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::get('/profile/edit', [UserController::class, 'edit'])->name('edit');
    Route::get('/profile/comment', [UserController::class, 'comment'])->name('comment');
    Route::post('/user/profile/photo', [UserController::class, 'uploadPhoto'])->name('photo.upload');
    Route::post('/profile/update', [UserController::class, 'update'])->name('update');
    Route::delete('/profile/delete', [UserController::class, 'destroy'])->name('destroy');
    Route::get('/profile', [BeritaController::class, 'userProfile'])->name('profile');
});


/*
*
* Route Admin
*
*/
Route::middleware(['auth'])->group(function () {
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::post('/comments/reply', [CommentController::class, 'reply'])->name('comments.reply');
});

Route::middleware(['auth', 'admin'])->prefix('dash')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/dashboard/profile', [DashboardController::class, 'showProfile'])->name('profile.show');
    Route::get('/dashboard/profile/edit', [DashboardController::class, 'editProfile'])->name('profile.edit');
    Route::post('/profile/update', [DashboardController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/upload', [DashboardController::class, 'updateImgs'])->name('profile.upload');
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
        Route::get('/edit/{id}', [DashboardController::class, 'editarticle'])->name('edit');
        Route::put('/update/{id}', [DashboardController::class, 'updatearticle'])->name('update');
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
        Route::delete('/{comment}', [DashboardController::class, 'destroycomment'])->name('destroy');
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

Route::get('/api/users/search', function(Request $request) {
    $query = $request->get('query');
    return User::where('username', 'LIKE', "%{$query}%")
               ->select('id', 'username', 'profile_photo')
               ->limit(5)
               ->get();
});

Route::get('/comments/load-new/{article}/{lastId}', [CommentController::class, 'loadNew'])->name('comments.loadNew');
Route::get('/comments/get-article-comments/{articleId}', [CommentController::class, 'getArticleComments']);
Route::get('/comments/count/{articleId}', [CommentController::class, 'getCommentCount']);

// Routes untuk registrasi
Route::middleware('guest')->group(function () {
    Route::get('/register', [App\Http\Controllers\RegisterController::class, 'index'])->name('register');
    Route::post('/register', [App\Http\Controllers\RegisterController::class, 'store']);
});
