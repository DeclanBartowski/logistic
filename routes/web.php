<?php

use Illuminate\Support\Facades\Route;
use App\Facade\SiteSetting;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
*/
Artisan::call('view:clear');

Route::group(
    [
        'prefix' => 'admin-area',
        'namespace' => 'App\Http\Controllers\Admin',
        'middleware' => 'admin',
        'as' => 'admin.'
    ], function () {
    Route::get('/', function () {
        return view('admin.index');
    })->name('home');
    Route::resources([
        'site-settings' => SiteSettingsController::class,
        'publications' => PublicationController::class,
        'vacancies' => VacancyController::class,
        'pages' => PageController::class,
        'menu-groups' => MenuGroupController::class,
        'menu-types' => MenuTypeController::class,
        'menu-items' => MenuItemController::class,
        'commerces' => CommerceController::class,
        'sponsors' => SponsorController::class,
        'header-banners' => HeaderBannerController::class,
        'text-pages' => TextPageController::class,
        'wage-properties' => WagePropertyController::class,
        'wages' => WageController::class,
        'seo' => SeoController::class,
        'socials' => SocialController::class,
    ], ['except' => 'show']);

        Route::get('/analytic', AnalyticController::class)->name('analytic');
        Route::post('/analytic', [App\Http\Controllers\Admin\AnalyticController::class, 'getAnalytic'])->name('analytic-filter');
        Route::get('/analytic-compare', [App\Http\Controllers\Admin\AnalyticController::class, 'compare'])->name('analytic-compare');
        Route::post('/analytic-compare', [App\Http\Controllers\Admin\AnalyticController::class, 'getCompareAnalytic'])->name('analytic-compare-filter');

});

Route::group(
    [
        'middleware' => 'guest',
    ], function () {
    Route::get('/login', [\App\Http\Controllers\AuthController::class, 'login'])->name('login');
    Route::post('/login', [\App\Http\Controllers\AuthController::class, 'authUser'])->name('user-login');
    Route::get('/registration', [\App\Http\Controllers\AuthController::class, 'register'])->name('registration');
    Route::post('/registration', [\App\Http\Controllers\AuthController::class, 'userRegister'])->name('user-register');
    Route::post('/registration-check',
        [\App\Http\Controllers\AuthController::class, 'sendMessage'])->name('check-phone');
    Route::get('/forget-password',
        [\App\Http\Controllers\AuthController::class, 'forgetPassword'])->name('forget-password');
    Route::post('/forget-password',
        [\App\Http\Controllers\AuthController::class, 'sendNewPassword'])->name('user-forget');
});
Route::group(
    [
        'middleware' => 'auth',
    ], function () {
    Route::get('/logout', function () {
        \Illuminate\Support\Facades\Auth::logout();
        return redirect(\route('home'));
    })->name('logout');
    Route::post('salary/suggestion',
        [\App\Http\Controllers\WageController::class, 'suggestion'])->name('salary.suggestion');
    Route::get('salary/self', [\App\Http\Controllers\WageController::class, 'self'])->name('salary.self');
    Route::get('salary/result',
        [\App\Http\Controllers\WageController::class, 'filterResult'])->name('salary.filter-result');
    Route::resource('salary', \App\Http\Controllers\WageController::class)->only([
        'index',
        'create',
        'store'
    ]);
});
Route::get('/', \App\Http\Controllers\IndexController::class)->name('home');
Route::resource('vacancies', \App\Http\Controllers\VacancyController::class)->only([
    'index',
    'show'
]);
Route::resource('blog', \App\Http\Controllers\PublicationController::class)->only([
    'index',
    'show'
]);

Route::get('/{page}', \App\Http\Controllers\TextPageController::class)->name('text-page');

Route::post('/send-feedback', \App\Http\Controllers\MailController::class)->name('send-feedback');



