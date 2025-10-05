<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\BlogCategoryController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\SocialMediaController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\QuestionsController;
use App\Http\Controllers\Admin\AnswerController;



Route::get('login', [LoginController::class, 'create'])->name('login');
Route::prefix('admin')->name('admin.')->middleware('guest:admin')->group(function () {
    Route::get('login', [LoginController::class, 'create'])->name('login');
    Route::post('login', [LoginController::class, 'store']);
    Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
});

Route::prefix('admin')->middleware('auth:admin')->name('admin.')->group(function () {

    // Logout
    Route::post('logout', [LoginController::class, 'destroy'])->name('logout');

    // Admins CRUD
    Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('admins', [AdminController::class, 'index'])->name('admins.index');
    Route::post('admins', [AdminController::class, 'store'])->name('admins.store');
    Route::put('admins/{admin}', [AdminController::class, 'update'])->name('admins.update');
    Route::delete('admins/{admin}', [AdminController::class, 'destroy'])->name('admins.destroy');

    // Users CRUD
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::post('users', [UserController::class, 'store'])->name('users.store');
    Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    // Roles CRUD
    Route::get('roles', [RoleController::class, 'index'])->name('roles.index');
    Route::post('roles', [RoleController::class, 'store'])->name('roles.store');
    Route::put('roles/{role}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');

    //permissions
    Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
    Route::get('/permissions/create', [PermissionController::class, 'create'])->name('permissions.create');
    Route::post('/permissions', [PermissionController::class, 'store'])->name('permissions.store');
    Route::get('/permissions/{permission}', [PermissionController::class, 'show'])->name('permissions.show');
    Route::get('/permissions/{permission}/edit', [PermissionController::class, 'edit'])->name('permissions.edit');
    Route::put('/permissions/{permission}', [PermissionController::class, 'update'])->name('permissions.update');
    Route::delete('/permissions/{permission}', [PermissionController::class, 'destroy'])->name('permissions.destroy');

    // Social Media CRUD
    Route::get('social-media', [SocialMediaController::class, 'index'])->name('social_media.index');
    Route::post('social-media', [SocialMediaController::class, 'store'])->name('social_media.store');
    Route::put('social-media/{social_media}', [SocialMediaController::class, 'update'])->name('social_media.update');
    Route::delete('social-media/{social_media}', [SocialMediaController::class, 'destroy'])->name('social_media.destroy');

    //questions
    // Route::get('questions', [QuestionsController::class, 'index'])->name('questions.index');
    // Route::post('questions', [QuestionsController::class, 'store'])->name('questions.store');
    // Route::put('questions/{question}', [QuestionsController::class, 'update'])->name('questions.update');
    // Route::delete('questions/{question}', [QuestionsController::class, 'destroy'])->name('questions.destroy');

    //answer
    Route::get('questions', [AnswerController::class, 'index'])->name('questions.index');
    Route::get('answers', [AnswerController::class, 'index'])->name('answers.index');
    Route::post('questions', [AnswerController::class, 'store'])->name('questions.store');
    Route::put('questions/{question}', [AnswerController::class, 'update'])->name('questions.update');
    Route::delete('questions/{question}', [AnswerController::class, 'destroy'])->name('questions.destroy');
    Route::get('levels/{level}/questions',[AnswerController::class, 'questionsByLevel'])->name('levels.questions');
    Route::get('/questions/search', [AnswerController::class, 'search'])->name('questions.search');
    Route::get('/questions/{question}/edit', [AnswerController::class, 'edit'])->name('questions.edit');

    // Settings CRUD
    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('settings/{setting}', [SettingController::class, 'update'])->name('settings.update');
    Route::get('settings/{setting}', [SettingController::class, 'show'])->name('settings.show');

    // Categories CRUD
    Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::put('categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // Blogs CRUD
    Route::get('blogs', [BlogController::class, 'index'])->name('blogs.index');
    Route::post('blogs', [BlogController::class, 'store'])->name('blogs.store');
    Route::put('blogs/{blog}', [BlogController::class, 'update'])->name('blogs.update');
    Route::delete('blogs/{blog}', [BlogController::class, 'destroy'])->name('blogs.destroy');
    Route::get('blogs/{blog}/edit', [BlogController::class, 'edit'])->name('blogs.edit');

    Route::get('/projects', [ProjectController::class, 'index'])
        // ->middleware([CheckPermission::class . ':view_courses'])
        ->name('projects.index');
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
    Route::put('/projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
    Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');

    // Messages CRUD
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::put('/messages/{message}/toggle-read', [MessageController::class, 'toggleRead'])->name('messages.toggleRead');

    // Reviews CRUD
    Route::get('reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::post('reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::put('reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    // FAQs CRUD

    Route::get('faqs', [FaqController::class, 'index'])->name('faqs.index');
    Route::post('faqs', [FaqController::class, 'store'])->name('faqs.store');
    Route::put('faqs/{faq}', [FaqController::class, 'update'])->name('faqs.update');
    Route::delete('faqs/{faq}', [FaqController::class, 'destroy'])->name('faqs.destroy');

    // Blog Categories CRUD
    Route::get('blog_categories', [BlogCategoryController::class, 'index'])->name('blog_categories.index');
    Route::post('blog_categories', [BlogCategoryController::class, 'store'])->name('blog_categories.store');
    Route::get('blog_categories/{blog_category}', [BlogCategoryController::class, 'show'])->name('blog_categories.show');
    Route::put('blog_categories/{blog_category}', [BlogCategoryController::class, 'update'])->name('blog_categories.update');
    Route::delete('blog_categories/{blog_category}', [BlogCategoryController::class, 'destroy'])->name('blog_categories.destroy');

   });

Route::fallback(function () {
    return redirect()->route('admin.admins.index');
});



