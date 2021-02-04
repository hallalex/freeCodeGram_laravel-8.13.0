<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


//Route::view('/', 'welcome');


Auth::routes();

// Show the email (only for demonstation/development)
Route::get('/email', function () {  return new App\Mail\NewUserWelcomeMail(); });

// Called by the "Follow button (FollowButton.vue)"
/*Route::post('follow/{user}', function() {
  return 'success';
});*/
Route::post('follow/{user}', [App\Http\Controllers\FollowsController::class, 'store']);

Route::get('/profile/{user}/edit', [App\Http\Controllers\ProfilesController::class, 'edit'])->name('profile.edit');
Route::get('/profile/{user}', [App\Http\Controllers\ProfilesController::class, 'index'])->name('profile.show');
Route::patch('/profile/{user}', [App\Http\Controllers\ProfilesController::class, 'update'])->name('profile.update');

Route::get('/', [PostsController::class, 'index']);
Route::get('/p/create', [PostsController::class, 'create']);
Route::get('/p/{post}', [PostsController::class, 'show']);
Route::post('/p', [PostsController::class, 'store']);

// Test
Route::get('/r/{id}', function ($id) {
    return 'ID är: '.$id;
});

/*Route::get('/', function () {
    return view('welcome');
});*/

// To get URL/storage/ linked to storage/app/public/
// Just visit /linkstorage once to enable link
Route::get('/linkstorage', function (){ Artisan::call('storage:link'); });
