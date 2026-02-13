
<?php

use App\Http\Controllers\Admin\UserRegionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\BokingController;
use App\Http\Controllers\CostumerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InputLapanganController;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;


Route::controller(CostumerController::class)->group(function () {
    // Redirect root ke padang dashboard
    Route::get('/', 'informasi')->name('informasi');
    Route::get('/Futsal Rhafely', 'informasi')->name('informasi');
    Route::get('/Futsal', 'informasi')->name('informasi.alt');

    // Public routes untuk melihat lapangan per region (tanpa auth)
    Route::get('/region/bypass', 'bypass')->name('web.region.Bypass');
    Route::get('/region/ulak karang', 'ulakKarang')->name('web.region.Ulak Karang');
    Route::get('/region/batu sangkar', 'batuSangkar')->name('web.region.BatuSangkar');

    // Redirect /region/{region} ke controller
    Route::get('/region/{region}', function ($region) {
        $region = strtolower($region);
        $validRegions = ['bypass', 'ulak karang', 'batu sangkar'];

        if (!in_array($region, $validRegions)) {
            abort(404, 'Region tidak ditemukan');
        }

        return redirect("region/{$region}");
    })->name('web.region');
});

Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'ShowLogin')->name('login');
    Route::post('/login', 'login')->name('login.post');
    Route::get('/register', 'Register')->name('register');
    Route::post('/register', 'store')->name('register.store');

    Route::get('/admin/login', 'ShowLoginAdmin')->name('loginAdmin');
    Route::post('/admin/login', 'adminLogin')->name('admin.login.post');
    Route::get('/admin/register', 'adminRegister')->name('admin.register.show');
    Route::post('/admin/register', 'storeAdmin')->name('admin.register.store');
    
    Route::post('/logout', 'logout')->name('logout');

});


// Test routes untuk debugging
Route::get('/test-images', function () {
    return view('test_images');
});

Route::controller(\App\Http\Controllers\TestBookingController::class)->prefix('test-booking')->group(function () {
    Route::get('/timezone', 'checkTimezone')->name('test.timezone');
    Route::get('/bookings', 'getBookingsForField')->name('test.bookings');
    Route::get('/availability', 'checkAvailability')->name('test.availability');
});

Route::middleware(['auth.admin'])->prefix('admin')->group(function () {
    Route::controller(DashboardController::class)->group(function () {
        // Master Admin Dashboard - lihat semua region
        Route::get('/dashboard', 'homeAdmin')
            ->name('admin.dashboard');
        Route::get('/dashboard/{region}', 'adminDashboard')
            ->middleware('region.access')
            ->name('admin.dashboard.region');

        // API endpoint untuk ambil data region
        Route::get('/api/region/{region}', 'getRegionData')->name('admin.region.data');
        Route::get('/api/regions', 'getRegions')->name('admin.regions.list');

        // Admin Profile
        Route::get('/profile', 'profile')->name('admin.profile');

        // New separated dashboard pages (only for master admin)
        Route::get('/statistics', 'statistics')->name('admin.statistics')->middleware('admin.master');
        Route::get('/booking-report', 'bookingReport')->name('admin.booking.report')->middleware('admin.master');
        Route::get('/admin-list', 'adminList')->name('admin.list')->middleware('admin.master');
        
        // Master Data - All Regions
        Route::get('/customers', 'customersList')->name('admin.customers.list')->middleware('admin.master');
        Route::get('/users', 'usersList')->name('admin.users.list')->middleware('admin.master');
        Route::get('/users/paid', 'usersPaid')->name('admin.users.paid')->middleware('admin.master');
        Route::get('/users/pending', 'usersPending')->name('admin.users.pending')->middleware('admin.master');
        
        // Regional Users Data
        Route::get('/region/{region}/users', 'regionUsersList')
            ->middleware('region.access')
            ->name('admin.region.users.list');
        Route::get('/region/{region}/users/paid', 'regionUsersPaid')
            ->middleware('region.access')
            ->name('admin.region.users.paid');
        Route::get('/region/{region}/users/pending', 'regionUsersPending')
            ->middleware('region.access')
            ->name('admin.region.users.pending');
        Route::get('/region/{region}/users/{userId}/detail', 'regionUserDetail')
            ->middleware('region.access')
            ->name('admin.region.user.detail');
    });

    Route::controller(InputLapanganController::class)->prefix('input-lapangan')->group(function () {
        // Lapangan
        Route::get('/inputLapangan', 'inputLapangan')->name('inputLapangan.Lapangan');
        Route::get('/daftarLapangan', 'daftarLapangan')->name('lapangan.daftar.Lapangan');
        Route::post('/inputLapangan', 'store')->name('lapangan.store');
        Route::get('/viewLapangan', 'viewLapangan')->name('lapangan.view');
        Route::get('/editLapangan/{id}', 'editLapangan')->name('lapangan.edit');
        Route::put('/updateLapangan/{id}', 'update')->name('lapangan.update');
        Route::delete('/deleteLapangan/{id}', 'destroy')->name('lapangan.destroy');

        // Slider
        Route::get('/slider', 'slider')->name('lapangan.slider');
        Route::post('/slider', 'storeSlider')->name('lapangan.slider.store');
        Route::get('/slider/{id}/edit', 'editSlider')->name('lapangan.slider.edit');
        Route::put('/slider/{id}', 'updateSlider')->name('lapangan.slider.update');
        Route::delete('/slider/{id}', 'destroySlider')->name('lapangan.slider.destroy');

        // Event
        Route::get('/event', 'event')->name('lapangan.event');
        Route::post('/event', 'storeEvent')->name('lapangan.event.store');
        Route::get('/event/{id}/edit', 'editEvent')->name('lapangan.event.edit');
        Route::put('/event/{id}', 'updateEvent')->name('lapangan.event.update');
        Route::delete('/event/{id}', 'destroyEvent')->name('lapangan.event.destroy');
    });
});

Route::middleware('auth')->group(function () {
    Route::controller(CostumerController::class)->group(function () {
        Route::get('/dashboard/bypass', 'bypass')->name('costumers.dashboard.bypass');
        Route::get('/dashboard/ulak-karang', 'ulakKarang')->name('costumers.dashboard.ulakkarang');
        Route::get('/dashboard/batu-sangkar', 'batuSangkar')->name('costumers.dashboard.batusangkar');
    });
});


// ✅ Booking routes protected dengan auth middleware
Route::middleware('auth')->controller(BokingController::class)->prefix('boking')->group(function () {
    Route::get('/', 'bookingForm')->name('boking.form');
    Route::post('/bokingForm', 'store')->name('boking.store');
    Route::get('/payment', 'payment')->name('boking.payment');
});

// Payment success page
Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');

// Invoice Routes
Route::middleware('auth')->group(function () {
    Route::get('/payment/history', [PaymentController::class, 'history'])->name('payment.history');
    Route::get('/payment/history', [PaymentController::class, 'history'])->name('show.payment'); // Alias untuk backward compatibility
    Route::get('/payment/invoice/{bookingId}', [PaymentController::class, 'showInvoice'])->name('payment.invoice.show');
    Route::get('/payment/invoice/download/{bookingId}', [PaymentController::class, 'downloadInvoice'])->name('payment.invoice.download');
});


Route::controller(MidtransController::class)->prefix('midtrans')->group(function () {
    Route::get('/checkout/{order}', 'checkout')->name('checkout');
    Route::get('/debug', 'debugConfig')->name('midtrans.debug');
    Route::post('/token/{order}', 'token')->name('midtrans.token');
    Route::post('/notification', 'notification')->name('midtrans.notification');
    Route::post('/verify-payment', 'verifyPayment')->name('midtrans.verify-payment');

    // Testing endpoints (development only)
    Route::get('/test-webhook', 'testWebhook')->name('midtrans.test-webhook');
    Route::get('/manual-confirm', 'manualConfirm')->name('midtrans.manual-confirm');
});

// Availability endpoints
Route::controller(AvailabilityController::class)->prefix('api')->group(function () {
    Route::get('/available-hours', 'getAvailableHours')->name('api.available-hours');
    Route::get('/booked-slots', 'getBookedSlots')->name('api.booked-slots');
});

// Payment API endpoint
Route::post('/api/payment-token', [MidtransController::class, 'token'])->name('api.payment-token');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile'])->name('user.profile');
    Route::put('/profile/update', [AuthController::class, 'updateProfile'])->name('user.profile.update');
    });
Route::middleware(['auth.admin'])->prefix('admin')->group(function () {
    Route::controller(UserRegionController::class)->group(function () {
        Route::get('/user-region', 'index')->name('user.region.list');
        Route::get('/user-region/paid', 'paid')->name('user.region.paid');
        Route::get('/user-region/pending', 'pending')->name('user.region.pending');
        Route::get('/user-region/{id}/detail', 'detail')->name('user.region.detail');
    });
});
