<?php

use App\Http\Controllers\CampaignController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

Route::get('/', function () {

    if (Auth::check()) {
        return redirect()->route('campaigns.index');
    }

    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    # CAMPAIGN REGION
    Route::get('/campaigns/create', [CampaignController::class, 'create'])->name('campaigns.create');
    Route::get('/campaigns', [CampaignController::class, 'index'])->name('campaigns.index');
    Route::get('/campaigns/details/{id}', [CampaignController::class, 'edit'])->name('campaigns.edit');
    Route::post('/campaigns', [CampaignController::class, 'store'])->name('campaigns.store');

    Route::put('/campaigns/{campaign}', [CampaignController::class, 'update']);
    # END CAMPAIGN REGION


});

require __DIR__ . '/auth.php';
