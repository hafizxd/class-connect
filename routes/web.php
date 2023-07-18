<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Livewire\Courses\CourseList;
use App\Http\Livewire\Courses\CourseCreate;
use App\Http\Livewire\Courses\CourseDetail;
use App\Http\Livewire\Courses\CourseEdit;
use App\Http\Livewire\Threads\ThreadList;
use App\Http\Livewire\Threads\ThreadDetail;
use App\Http\Livewire\Forums\ForumList;

use App\Http\Livewire\Admin\Users\UserList;

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

Route::get('/', function () {
    return view('landing-page');
})->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::group(['prefix' => '/forums'], function () {
        Route::get('/', ForumList::class)->name('forum.index');

        Route::group(['prefix' => '/{forum:id}', 'as' => 'thread.'], function () {
            Route::get('/', ThreadList::class)->name('index');
            Route::get('/{thread:slug}', ThreadDetail::class)->name('detail');
        });
    });
    
    Route::group(['prefix' => '/classes', 'as' => 'class.'], function () {
        Route::get('/', CourseList::class)->name('index');
        Route::get('/create', CourseCreate::class)->name('create')->middleware('affiliator');
        Route::get('/{course:slug}', CourseDetail::class)->name('detail');
        Route::get('/{course:slug}/edit', CourseEdit::class)->name('edit')->middleware('affiliator');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');



    // Admin
    Route::group(['prefix' => '/admin', 'as' => 'admin.', 'middleware' => 'admin'], function () {
        Route::get('/', UserList::class)->name('user');
    });
});

require __DIR__.'/auth.php';
