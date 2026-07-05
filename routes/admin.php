<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\CertificateController;
use App\Http\Controllers\Admin\ContactPageController;
use App\Http\Controllers\Admin\ContactSubmissionController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\HomePageController;
use App\Http\Controllers\Admin\VerifyCertificatePageController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware('noindex')->group(function () {
    Route::get('rbtl-secure-login', [AuthController::class, 'showLogin'])->name('admin.login');
    Route::post('rbtl-secure-login', [AuthController::class, 'login'])->name('admin.login.store');
});

Route::prefix('rbtl')->name('admin.')->middleware('noindex')->group(function () {
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('dashboard', DashboardController::class)->name('dashboard');
        Route::get('homepage', [HomePageController::class, 'edit'])->name('homepage.edit');
        Route::put('homepage', [HomePageController::class, 'update'])->name('homepage.update');
        Route::get('verify-certificate-page', [VerifyCertificatePageController::class, 'edit'])->name('verify-page.edit');
        Route::put('verify-certificate-page', [VerifyCertificatePageController::class, 'update'])->name('verify-page.update');
        Route::get('contact-page', [ContactPageController::class, 'edit'])->name('contact-page.edit');
        Route::put('contact-page', [ContactPageController::class, 'update'])->name('contact-page.update');
        Route::resource('contact-submissions', ContactSubmissionController::class)->only(['index', 'show', 'destroy']);
        Route::resource('users', AdminUserController::class)->except(['show']);
        Route::resource('certificates', CertificateController::class)->except(['show']);
        Route::get('content', fn () => Inertia::render('Admin/Placeholder', [
            'title' => 'Content Blocks',
            'description' => 'This module will manage page sections, headings, paragraphs, buttons, lists, stats, and repeatable content blocks.',
        ]))->name('content.index');
        Route::get('media', fn () => Inertia::render('Admin/Placeholder', [
            'title' => 'Media Library',
            'description' => 'This module will manage uploaded images and reusable media assets for public pages and certificates.',
        ]))->name('media.index');
        Route::get('settings', fn () => Inertia::render('Admin/Placeholder', [
            'title' => 'Site Settings',
            'description' => 'This module will manage global lab details, contact information, SEO defaults, and footer content.',
        ]))->name('settings.index');
    });
});
