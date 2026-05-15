<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('posts.index');
});

// Auth routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.store');
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.store');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Contact routes
Route::get('/contact', [ContactController::class, 'showForm'])->name('contact');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');
// Additional routes (must be before resource to avoid conflict)
Route::get('/posts/featured', [PostController::class, 'featured'])->name('posts.featured');
Route::get('/posts/popular', [PostController::class, 'popular'])->name('posts.popular');
Route::get('/posts/category/{category:slug}', [PostController::class, 'byCategory'])->name('posts.category');
Route::get('/posts/tag/{tag:slug}', [PostController::class, 'byTag'])->name('posts.tag');
Route::get('/posts/author/{user}', [PostController::class, 'byAuthor'])->name('posts.author');
Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
// Multi-step post creation
Route::get('/posts/create/step1', [PostController::class, 'createStep1'])->name('posts.create.step1');
Route::post('/posts/create/step1', [PostController::class, 'storeStep1'])->name('posts.store.step1');
Route::get('/posts/create/step2', [PostController::class, 'createStep2'])->name('posts.create.step2');
Route::post('/posts/create/step2', [PostController::class, 'storeStep2'])->name('posts.store.step2');
Route::get('/posts/create/step3', [PostController::class, 'createStep3'])->name('posts.create.step3');
Route::post('/posts/create/multi', [PostController::class, 'storeMultiStep'])->name('posts.store.multi');

// Authenticated post routes
Route::middleware('auth')->group(function () {
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
});

// Public post routes
Route::resource('posts', PostController::class)->only(['index', 'show']);

// Trash management
Route::prefix('posts')->name('posts.')->group(function () {
    Route::get('/trash/manage', [PostController::class, 'trash'])->name('trash');
    Route::post('/trash/{id}/restore', [PostController::class, 'restore'])->name('restore');
    Route::delete('/trash/{id}/force-delete', [PostController::class, 'forceDelete'])->name('force-delete');
});

// Resource route untuk categories
Route::resource('categories', \App\Http\Controllers\CategoryController::class);

// Resource route untuk comments (except store, which is handled separately)
Route::resource('comments', \App\Http\Controllers\CommentController::class)->except(['store']);

// Single action controller (contoh)
Route::get('/dashboard', \App\Http\Controllers\DashboardController::class)->name('dashboard');
// Ajax validation
Route::post('/ajax/check-email', function(Request $request) {
    $email = $request->email;
    $exists = \App\Models\User::where('email', $email)->exists();
    return response()->json(['available' => !$exists]);
});