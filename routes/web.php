<?php

use App\Http\Controllers\Admin\AdminNftController;
use App\Http\Controllers\Admin\AdminSignalController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\Admin\DepositController as AdminDepositController;
use App\Http\Controllers\InvestmentController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\WithdrawalController;
use App\Http\Controllers\Admin\WithdrawalController as AdminWithdrawalController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\BonusController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ImpersonationController;
use App\Http\Controllers\Admin\InvestmentPlanController;
use App\Http\Controllers\Admin\TraderController;
use App\Http\Controllers\CopiedTraderController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\TradeController;
use App\Http\Controllers\TraderController as ControllersTraderController;
use App\Http\Controllers\UserNftController;
use App\Http\Controllers\UserSignalController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('homepage');
});
Route::get('/contact', function () {
    return view('contact');
});
Route::get('/about', function () {
    return view('about');
});
Route::get('/plans', function () {
    return view('plans');
});
Route::get('/privacy-policy', function () {
    return view('privacy_policy');
});
Route::get('/faqs', function () {
    return view('faqs');
});

//Auth routes
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('show.register');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('show.login');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/email/verify', [AuthController::class, 'showVerifyEmailForm'])->name('verify.email.show');
Route::post('/email/verify', [AuthController::class, 'verifyEmailCode'])->name('verify.email');
Route::post('/resend-otp', [AuthController::class, 'resendEmailOtp'])->name('otp.resend');
Route::get('/forgot-password', [AuthController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'reset'])->name('password.update');


// Dashboard Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/user/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/user/profile', [DashboardController::class, 'showProfile'])->name('show.profile');
    Route::post('/update-profile', [DashboardController::class, 'updateProfile'])->name('user.update.profile');
    Route::post('/update-password', [DashboardController::class, 'updatePassword'])->name('user.update.password');

    Route::get('/user/deposit', [DepositController::class, 'showDepositForm'])->name('show.deposit');
    Route::post('/user/deposit', [DepositController::class, 'createDeposit'])->name('deposit');
    Route::get('/user/complete-deposit', [DepositController::class, 'showCompleteDeposit'])->name('show.cdeposit');
    Route::get('/user/transactions', [TransactionController::class, 'showTransactions'])->name('transactions');
    Route::get('/user/withdraw', [WithdrawalController::class, 'showWithdrawalForm'])->name('show.withdraw');
    Route::post('/withdraw/submit', [WithdrawalController::class, 'submitWithdrawal'])->name('withdraw.submit');

    Route::get('/user/investments', [InvestmentController::class, 'showInvestments'])->name('show.investment');
    Route::get('/user/plans', [InvestmentController::class, 'showPlans'])->name('show.plans');
    Route::post('/invest', [InvestmentController::class, 'invest'])->name('user.invest');

    Route::get('/user/traders', [ControllersTraderController::class, 'index'])->name('user.traders.index');
    Route::post('user/copy-trader', [CopiedTraderController::class, 'store'])->name('copy.trader');
    Route::delete('user/copy-trader/{id}', [CopiedTraderController::class, 'destroy'])->name('uncopy.trader');
    Route::get('user/copied-traders', [CopiedTraderController::class, 'history'])->name('user.copied.traders');

    // NFT
    Route::post('/nfts/mint', [UserNftController::class, 'mint'])->name('user.nfts.mint');
    Route::get('/nfts/marketplace', [UserNftController::class, 'marketplace'])->name('nfts.marketplace');
    Route::get('/nfts/mint', [UserNftController::class, 'showMintForm'])->name('nfts.mint.form');
    Route::get('/nfts/{id}', [UserNftController::class, 'show'])->name('nfts.show');
    Route::get('/my-nfts', [UserNftController::class, 'myNfts'])->name('user.nfts.my');
    Route::post('/nfts/{nft}/buy', [UserNftController::class, 'buy'])->name('nfts.buy')->middleware('auth');

    //TRADE
    Route::get('/trade', [TradeController::class, 'index'])->name('trade.index');
    Route::post('/trade/place', [TradeController::class, 'placeTrade'])->name('trade.place');

    //NEWS
    Route::get('/user/news/{category?}', [NewsController::class, 'index'])->name('dashboard.news');

    // Show all available signals
    Route::get('/dashboard/subscribe-signals', [UserSignalController::class, 'index'])
        ->name('user.signals.index');

    // Subscribe to a signal
    Route::post('/dashboard/subscribe-signal/{signal}', [UserSignalController::class, 'subscribe'])
        ->name('user.signals.subscribe');

    // My Signal Plans
    Route::get('/dashboard/my-signal-plans', [UserSignalController::class, 'myPlans'])
        ->name('user.signals.myPlans');


});

//Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Admin auth routes
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('show.login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login');

    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
        Route::get('/users', [AdminDashboardController::class, 'allUsers'])->name('users');
        Route::get('/users/{id}', [AdminDashboardController::class, 'showUser'])->name('users.show');
        Route::delete('/users/{id}', [AdminDashboardController::class, 'deleteUser'])->name('users.destroy');

        // investment routes
        Route::post('/investment-plans/create', [InvestmentPlanController::class, 'addInvestmentPlan'])->name('plans.add');
        Route::get('/investment-plans', [InvestmentPlanController::class, 'showPlansForm'])->name('plans');
        Route::get('/investment-plans/all', [InvestmentPlanController::class, 'showAllPlans'])->name('show.plans');
        Route::delete('/investment-plans/{id}', [InvestmentPlanController::class, 'deletePlan'])->name('plans.destroy');

        Route::post('/deposits/{id}/approve', [AdminDashboardController::class, 'approveDeposit'])->name('deposits.approve');
        Route::get('/deposits', [AdminDashboardController::class, 'allDeposits'])->name('deposits.all');

        Route::get('/change-password', [AdminAuthController::class, 'showChangePasswordForm'])->name('password.change');
        Route::put('/change-password', [AdminAuthController::class, 'updatePassword'])->name('password.update');

        Route::get('/send-mail', [AdminDashboardController::class, 'createMail'])->name('mail.create');
        Route::post('/send-mail', [AdminDashboardController::class, 'sendMail'])->name('mail.send');

        Route::post('/users/{user}/topup-profit', [AdminDashboardController::class, 'topUpProfit'])->name('topup.profit');
        Route::post('/users/{user}/profit/debit', [AdminDashboardController::class, 'debitProfit'])->name('debit.profit');
        Route::post('/users/{user}/debit', [AdminDashboardController::class, 'debitBalance'])->name('debit.balance');
        Route::post('/admin/users/{user}/generate-otp', [AdminDashboardController::class, 'generateOtp'])->name('generate.otp');

        Route::get('/wallet/update', [AdminDashboardController::class, 'showWalletForm'])->name('wallet.update');
        Route::post('/wallet/save', [AdminDashboardController::class, 'saveWallet'])->name('wallet.save');
        Route::get('/my-wallets', [AdminDashboardController::class, 'showWallets'])->name('wallets');
        Route::delete('/wallets/{id}', [AdminDashboardController::class, 'deleteWallet'])->name('wallets.destroy');

        Route::get('/withdrawals', [WithdrawalController::class, 'showWithdrawalHistory'])->name('withdrawals');
        Route::post('/withdrawals/{id}/approve', [AdminWithdrawalController::class, 'approve'])->name('withdrawals.approve');
        Route::post('/withdrawals/{id}/decline', [AdminWithdrawalController::class, 'declineWithdrawal'])->name('withdrawals.decline');
        Route::post('/deposits/{id}/decline', [AdminDepositController::class, 'decline'])->name('deposits.decline');

        // Update (add or debit) user balance
        Route::post('/users/{user}/balance', [AdminDashboardController::class, 'updateUserBalance'])->name('users.update.balance');

        // NFTS
        Route::post('/nfts/mint', [AdminNftController::class, 'mint'])->name('nfts.mint');
        Route::get('/nfts/create', [AdminNftController::class, 'create'])->name('nfts.create');
        Route::get('/nfts', [AdminNftController::class, 'index'])->name('nfts.index');
        Route::delete('/nfts/{nft}/delete', [AdminNftController::class, 'delete'])->name('nfts.delete');
        Route::get('/nfts/{nft}/edit', [AdminNftController::class, 'edit'])->name('nfts.edit');
        Route::post('/nfts/{nft}/update', [AdminNftController::class, 'update'])->name('nfts.update');

        //traders
        Route::get('/traders/create', [TraderController::class, 'showTradersForm'])->name('traders.create');
        Route::post('/traders', [TraderController::class, 'addTrader'])->name('traders.store');
        Route::get('/traders', [TraderController::class, 'fetchTraders'])->name('traders.index');
        Route::delete('/traders/{id}', [TraderController::class, 'deleteTrader'])->name('traders.destroy');

        Route::post('/users/{user}/impersonate', [ImpersonationController::class, 'impersonate'])->name('users.impersonate');
        Route::post('/admin/impersonate/leave', [ImpersonationController::class, 'leave'])->name('impersonate.leave');

        Route::post('/users/{user}/bonus', [BonusController::class, 'store']);
        Route::delete('/users/{user}/bonus', [BonusController::class, 'destroy']);

        // SIGNALS (Admin)
        Route::get('/signals', [AdminSignalController::class, 'index'])->name('signals.index');
        Route::get('/signals/create', [AdminSignalController::class, 'create'])->name('signals.create');
        Route::post('/signals/store', [AdminSignalController::class, 'store'])->name('signals.store');
        Route::get('/signals/{signal}/edit', [AdminSignalController::class, 'edit'])->name('signals.edit');
        Route::post('/signals/{signal}/update', [AdminSignalController::class, 'update'])->name('signals.update');
        Route::delete('/signals/{signal}/delete', [AdminSignalController::class, 'delete'])->name('signals.delete');

    });
});





