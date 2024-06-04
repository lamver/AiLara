<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Services\Translation\Translation;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {

    foreach (Translation::getLanguagesForRoute() as $lang) {

        Route::prefix($lang)->group(function () use ($lang) {

            Route::get('register', [RegisteredUserController::class, 'create'])
                ->name($lang . 'register');

            Route::post('register', [RegisteredUserController::class, 'store']);

            Route::get('login', [AuthenticatedSessionController::class, 'create'])
                ->name($lang . 'login');

            Route::post('login', [AuthenticatedSessionController::class, 'store']);

            Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
                ->name($lang . 'password.request');

            Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
                ->name($lang . 'password.email');

            Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
                ->name($lang . 'password.reset');

            Route::post('reset-password', [NewPasswordController::class, 'store'])
                ->name($lang . 'password.store');
        });

    }

    Route::middleware('auth')->group(function () {
        foreach (Translation::getLanguagesForRoute() as $lang) {

            Route::prefix($lang)->group(function () use ($lang) {
                Route::get('verify-email', EmailVerificationPromptController::class)
                    ->name($lang.'verification.notice');

                Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
                    ->middleware(['signed', 'throttle:6,1'])
                    ->name($lang.'verification.verify');

                Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                    ->middleware('throttle:6,1')
                    ->name($lang.'verification.send');

                Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
                    ->name($lang.'password.confirm');

                Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

                Route::put('password', [PasswordController::class, 'update'])->name($lang.'password.update');

                Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
                    ->name($lang.'logout');
            });

        }
    });
});
