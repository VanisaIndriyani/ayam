<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Payment\DuitkuCallbackController;


/*
|--------------------------------------------------------------------------
| FRONTEND CONTROLLERS
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\ProductController as FrontProductController;
use App\Http\Controllers\Front\CartController;
use App\Http\Controllers\Front\CheckoutController;
use App\Http\Controllers\Front\OrderController;
use App\Http\Controllers\Front\PaymentController;
use App\Http\Controllers\Front\QuickOrderController;
use App\Http\Controllers\Front\ContactController;
use App\Http\Controllers\Front\ReviewController;

/*
|--------------------------------------------------------------------------
| ADMIN CONTROLLERS
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderAdminController;
use App\Http\Controllers\Admin\UserAdminController;
use App\Http\Controllers\Admin\PaymentAdminController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ContactAdminController;

/*
|--------------------------------------------------------------------------
| PUBLIC / LANDING
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/products', [FrontProductController::class, 'index'])
    ->name('products.index');

Route::get('/product/{slug}', [FrontProductController::class, 'show'])
    ->name('product.show');

Route::post('/contact', [ContactController::class, 'store'])
    ->name('contact.store');

Route::post('/reviews', [ReviewController::class, 'store'])
    ->middleware('auth')
    ->name('reviews.store');

/*
|--------------------------------------------------------------------------
| QUICK ORDER (LOGIN REQUIRED)
|--------------------------------------------------------------------------
*/
Route::post('/quick-order', [QuickOrderController::class, 'store'])
    ->middleware('auth')
    ->name('quick.order');

/*
|--------------------------------------------------------------------------
| USER AREA (AUTH + ROLE USER)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:user'])->group(function () {

    /* ================= CART ================= */
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('/add/{product}', [CartController::class, 'add'])->name('add');
        Route::patch('/update/{item}', [CartController::class, 'update'])->name('update');
        Route::delete('/remove/{item}', [CartController::class, 'remove'])->name('remove');
    });

    /* ================= CHECKOUT ================= */
    Route::prefix('checkout')->name('checkout.')->group(function () {
        Route::get('/', [CheckoutController::class, 'index'])->name('index');
        Route::get('/search-location', [CheckoutController::class, 'searchLocation'])->name('searchLocation');
        Route::get('/search-map-location', [CheckoutController::class, 'searchMapLocation'])->name('searchMapLocation');
        Route::post('/shipping-cost', [CheckoutController::class, 'shippingCost'])->name('shippingCost');
        // Change to match to handle potential redirect issues (GET fallback)
        Route::match(['get', 'post'], '/process', [CheckoutController::class, 'process'])->name('process');
    });

});

// Emergency Route Cache Clearer (Accessible via URL)
Route::get('/fix-routes', function() {
    \Illuminate\Support\Facades\Artisan::call('route:clear');
    \Illuminate\Support\Facades\Artisan::call('config:clear');
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    \Illuminate\Support\Facades\Artisan::call('view:clear');
    return "Routes, Config, Cache, and Views cleared!";
});

Route::middleware(['auth'])->group(function () {

    /* ================= PAYMENT (BOTH USER & ADMIN) ================= */
    Route::get('/payment/finish', [PaymentController::class, 'finish'])
        ->name('payment.finish');

    Route::get('/payment/{order}', [PaymentController::class, 'start'])
        ->name('payment.start');

    /* ================= USER ORDERS ================= */
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/{order}', [OrderController::class, 'show'])->name('show');

        Route::get('/{order}/tracking', [OrderController::class, 'tracking'])->name('tracking');
        Route::get('/{order}/tracking/data', [OrderController::class, 'getTrackingData'])->name('tracking.data');

        Route::get('/{order}/invoice', [OrderController::class, 'invoice'])
            ->name('invoice');
    });
});

/*
|--------------------------------------------------------------------------
| PAYMENT WEBHOOK (NO AUTH)
|--------------------------------------------------------------------------
| GUNAKAN SATU ENDPOINT SAJA
| MIDTRANS (lama) / DUITKU (baru)
*/
Route::post('/payment/webhook', [PaymentController::class, 'notification'])
    ->name('payment.webhook');

/*
|--------------------------------------------------------------------------
| ADMIN PANEL
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        Route::resource('products', ProductController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('users', UserAdminController::class);

        Route::prefix('orders')->name('orders.')->group(function () {
            Route::get('/', [OrderAdminController::class, 'index'])->name('index');
            Route::get('/{order}', [OrderAdminController::class, 'show'])->name('show');
            Route::post('/{order}/update-status', [OrderAdminController::class, 'updateStatus'])->name('updateStatus');
            Route::post('/{order}/update-resi', [OrderAdminController::class, 'updateResi'])->name('updateResi');
            Route::post('/{order}/location', [OrderAdminController::class, 'updateLocation'])->name('updateLocation');

            Route::get('/{order}/invoice', [OrderAdminController::class, 'invoice'])
                ->name('invoice');
        });

        Route::get('/payments', [PaymentAdminController::class, 'index'])
            ->name('payments.index');

        Route::get('/payments/{payment}', [PaymentAdminController::class, 'show'])
            ->name('payments.show');

        Route::get('/reports', [ReportController::class, 'index'])
            ->name('reports.index');

        Route::get('/contacts', [ContactAdminController::class, 'index'])
            ->name('contacts.index');

        Route::get('/contacts/{contact}', [ContactAdminController::class, 'show'])
            ->name('contacts.show');
    });

/*
|--------------------------------------------------------------------------
| PROFILE & AUTH
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Duitku Callback (Tanpa Auth)
Route::post('/payment/duitku/callback', [DuitkuCallbackController::class, 'handle'])
    ->name('payment.duitku.callback');

/*
|--------------------------------------------------------------------------
| API RAJAONGKIR
|--------------------------------------------------------------------------
*/
Route::get('/checkout/search-location', [CheckoutController::class, 'searchLocation'])
    ->name('checkout.searchLocation');

require __DIR__ . '/auth.php';

Route::get('/debug-rajaongkir', function () {
    try {
        $key = config('services.rajaongkir.key');
        $response = Illuminate\Support\Facades\Http::withHeaders([
            'key' => $key,
            'Accept' => 'application/json' 
        ])->get('https://rajaongkir.komerce.id/api/v1/province');

        return response()->json([
            'status' => $response->status(),
            'body' => $response->body(),
            'key_used' => $key
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
});
