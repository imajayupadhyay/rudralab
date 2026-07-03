<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Home/index');
});

Route::get('/verify-certificate', function () {
    return Inertia::render('VerifyCertificate/index');
})->name('certificate.verify');

Route::get('/contact', function () {
    return Inertia::render('Contact/index');
})->name('contact');
