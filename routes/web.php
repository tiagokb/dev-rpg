<?php

use App\Http\Controllers\CampaignController;
use App\Http\Controllers\CharacterController;
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
    Route::get('/campaigns/details/{id}', [CampaignController::class, 'view'])->name('campaigns.view');
    Route::post('/campaigns', [CampaignController::class, 'store'])->name('campaigns.store');
    Route::put('/campaigns/{campaign}', [CampaignController::class, 'update']);
    Route::delete('/campaigns/{campaign}', [CampaignController::class, 'destroy'])->name('campaigns.destroy');
    Route::post('/campaigns/join', [CampaignController::class, 'join'])->name('campaigns.join');
    Route::put('/campaigns/transfer/{campaign}', [CampaignController::class, 'transfer'])->name('campaigns.transfer');
    Route::put('/campaigns/leave/{campaign}', [CampaignController::class, 'leave'])->name('campaigns.leave');
    # CHARACTER REGION



});

require __DIR__ . '/auth.php';
