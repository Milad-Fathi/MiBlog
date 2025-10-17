<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth;

use App\Http\Controllers\BlogController;


Route::get('/', 'App\Http\Controllers\BlogController@index')->name('home');
Route::get('/blog/{id}', 'App\Http\Controllers\BlogController@show')->name('blog.show');
Route::get('/search', 'App\Http\Controllers\BlogController@search')->name('search');
// Route::get('/myblogs', 'App\Http\Controllers\BlogController@myblogs')->name('myblogs');


Route::get('/dashboard/writer-blogs', [BlogController::class, 'showWriterBlogs'])->name('writer.blogs');

Route::get('/blog/{id}/summarize', [BlogController::class, 'summarize'])->name('blog.summarize');


Route::get('/dashboard/recommendation', [BlogController::class, 'recommendations'])->name('blog.recommendations');





Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/blogs/create', 'App\Http\Controllers\BlogController@create')->middleware('check.writer.role')->name('blog.create');
    Route::post('/blog', 'App\Http\Controllers\BlogController@store')->name('blog.store');
    Route::post('/comments', 'App\Http\Controllers\CommentController@store')->name('comment.store');
});

require __DIR__ . '/auth.php';
