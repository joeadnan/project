<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\MovieController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cookie;

/*
|--------------------------------------------------------------------------
| Dummy Data
|--------------------------------------------------------------------------
*/
$movies = collect(range(0, 9))->map(function ($i) {
    return [
        'title'  => "Movie $i",
        'year'   => '2020',
        'gender' => 'Action',
    ];
});

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => view('welcome'));

Route::get('/pricing', fn () => 'Please, buy membership!')
    ->name('pricing');

Route::get('/login', fn () => 'Login page')
    ->name('login');

/*
|--------------------------------------------------------------------------
| Request & Response Testing
|--------------------------------------------------------------------------
*/
Route::post('/request', function (Request $request) {
    $request->merge(['email' => 'a@b.com']);

    return $request->missing('email')
        ? 'Emailnya tidak Ada'
        : 'Emailnya Ada';
});

Route::get('/response', fn () =>
    response('OK')->header('Content-Type', 'text/plain')
);

Route::get('/cache-control', fn () =>
    response('page allow to cache')
        ->header('Cache-Control', 'public, max-age=3600')
);

/*
|--------------------------------------------------------------------------
| Cached Routes
|--------------------------------------------------------------------------
*/
Route::middleware('cache.headers:public;max_age=3600')->group(function () {

    Route::get('/home', [HomeController::class, 'index'])
        ->name('home');

    Route::get('/dashboard', function () {
        return response('login success')
            ->cookie('user', 'Admin');
    });

    Route::get('/privacy', fn () => response('Privacy Policy'));
});

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
Route::get('/logout', function () {
    return redirect()
        ->action([HomeController::class, 'index'])
        ->withCookie(Cookie::forget('user'));
});

Route::get('/external', function () {
    return redirect('https://www.google.com');
});

/*
|--------------------------------------------------------------------------
| Movie Routes (Protected)
|--------------------------------------------------------------------------
*/
Route::prefix('movie')
    ->name('movie.')
    ->group(function () {

        Route::get('/', [MovieController::class, 'index']);
        Route::get('/{id}', [MovieController::class, 'show']);

        Route::post('/', [MovieController::class, 'store']);

        Route::match(['put', 'patch'], '/{id}', [MovieController::class, 'update']);

        Route::delete('/{id}', [MovieController::class, 'destroy']);
    });