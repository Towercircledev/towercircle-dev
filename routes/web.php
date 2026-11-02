<?php

use App\Http\Controllers\LeadController;
use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;

Route::controller(SiteController::class)->group(function () {
    Route::get('/', 'home')->name('home');
    Route::get('/about', 'about')->name('about');
    Route::get('/services', 'services')->name('services');
    Route::get('/services/{service}', 'service')->name('services.show');
    Route::get('/work', 'work')->name('work');
    Route::get('/work/{project}', 'project')->name('work.show');
    Route::get('/blog', 'blog')->name('blog');
    Route::get('/blog/{post}', 'post')->name('blog.show');
    Route::get('/faq', 'faq')->name('faq');
    Route::get('/team', 'team')->name('team');
    Route::get('/contact', 'contact')->name('contact');
    Route::get('/lp/{landingPage}', 'landing')->name('landing');
    Route::get('/thank-you', 'thanks')->name('thanks');
    Route::get('/sitemap.xml', 'sitemap')->name('sitemap');
    Route::get('/robots.txt', 'robots');
});

Route::post('/enquiry', [LeadController::class, 'store'])->middleware('throttle:6,10')->name('lead.store');
