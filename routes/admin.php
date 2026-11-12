<?php 

use App\Http\Controllers\CoreController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {
    Route::get('login', [CoreController::class, 'AdminLoginf'])->name('admin.login');
    Route::post('login', [CoreController::class, 'Adminlogin']);
    Route::post('logout', [CoreController::class, 'Adminlogout'])->name('admin.logout');

    Route::middleware('admin')->group(function () {
        Route::get('/', [CoreController::class, 'AdminDashboard'])->name('admin.dashboard');
        Route::get('/wallet-balances', [CoreController::class, 'AdminWalletBalances'])->name('admin.wallet-balances');
        Route::get('/apiconfigs', [CoreController::class, 'AdminAPIConfigs'])->name('admin.apiconfigs');
        Route::get('/transactions', [CoreController::class, 'AdminTransactions'])->name('admin.transactions');
        Route::get('/transactions/{id}', [CoreController::class, 'AdminTransaction'])->name('admin.transaction');
        Route::get('/settings', [CoreController::class, 'AdminSettings'])->name('admin.settings');
        Route::any('/analytics', [CoreController::class, 'AdminAnalytics'])->name('admin.analytics');
        Route::get('/manage-users', [CoreController::class, 'AdminUsers'])->name('admin.users');
        Route::get('/manage-users/{id}', [CoreController::class, 'AdminUser'])->name('admin.user'); 
        Route::delete('/delete-user/{id}', [CoreController::class, 'AdminUserDelete'])->name('admin.user.delete');
        Route::get('/more', [CoreController::class, 'AdminMore'])->name('admin.more');
        Route::get('/switch', [CoreController::class, 'AdminSwitch'])->name('admin.switch');
        Route::get('/bulk-mailer', [CoreController::class, 'BulkMailer'])->name('admin.bulkmailer');
        
        ##AKYLE 
        Route::get('/payment-gateways', [CoreController::class, 'AdminGateway'])->name('admin.gateways');
        Route::get('/payment-gateways/{id}', [CoreController::class, 'AdminGatewayEdit'])->name('admin.gateway.edit');
        Route::post('/payment-gateways/{id}', [CoreController::class, 'AdminGatewayUpdate'])->name('admin.gateway.update');
        Route::get('/updates', [CoreController::class, 'AdminUpdates'])->name('admin.updates');
        Route::get('/data-category-manager', [CoreController::class, 'AdminDataCategory'])->name('admin.data.category');
        Route::post('/data-category-manager', [CoreController::class, 'AdminDataCategoryUpdate'])->name('admin.data.category.update');
        
        ##BASH
        Route::get('/profile', [CoreController::class, 'AdminProfile'])->name('admin.profile');
        Route::get('/notifications', [CoreController::class, 'AdminNotifications'])->name('admin.notifications');
        Route::get('/notification/add', [CoreController::class, 'AdminNotificationAdd'])->name('admin.add.notification');
        Route::delete('/notifications', [CoreController::class, 'AdminNotificationDelete'])->name('admin.notification.delete');
        Route::get('/notifications/{id}', [CoreController::class, 'AdminNotification'])->name('admin.notification');
        Route::get('/transactions', [CoreController::class, 'AdminTransactions'])->name('admin.transactions');
        Route::delete('/transactions', [CoreController::class, 'AdminTransactionDelete'])->name('admin.transaction.delete');
        Route::get('/transactions/{id}', [CoreController::class, 'AdminTransaction'])->name('admin.transaction');
        Route::get('/themes', [CoreController::class, 'AdminThemes'])->name('admin.themes');
        
        #PRD 
        Route::get('/categories', [CoreController::class, 'AdminCategories'])->name('admin.categories');
        Route::get('/api-providers', [CoreController::class, 'AdminApiProvider'])->name('admin.providers');
        Route::get('/api-provider/add', [CoreController::class, 'AdminApiProviderAdd'])->name('admin.add.api_provider');
        Route::get('/api-providers/{id}', [CoreController::class, 'AdminApiProviderEdit'])->name('admin.edit.api_provider');
        Route::get('/services', [CoreController::class, 'AdminServices'])->name('admin.services');
        Route::get('/service/add', [CoreController::class, 'AdminServicesAdd'])->name('admin.add.service');
        Route::get('/services/{id}', [CoreController::class, 'AdminServicesEdit'])->name('admin.edit.service');
        Route::get('/products', [CoreController::class, 'AdminProducts'])->name('admin.products');
        Route::get('/product/add', [CoreController::class, 'AdminProductAdd'])->name('admin.add.product');
        Route::get('/products/{id}', [CoreController::class, 'AdminProductEdit'])->name('admin.edit.product');
        Route::get('/apiconfig/add', [CoreController::class, 'AdminAPIConfigAdd'])->name('admin.add.api_config');
        Route::get('/apiconfigs/{id}', [CoreController::class, 'AdminAPIConfigEdit'])->name('admin.edit.api_config');
        Route::match(['get', 'post'], '/update-status', [CoreController::class, 'getUpdateStatus'])->name('admin.update.status');
        
        # PUT
        Route::post('/settings', [CoreController::class, 'AdminUpdateSettings'])->name('admin.save.settings');
        Route::post('/api-providers/{id}', [CoreController::class, 'AdminApiProviderInsert'])->name('admin.update.api_provider');
        Route::post('/api-provider/add', [CoreController::class, 'AdminApiProviderInsert'])->name('admin.insert.api_provider');
        Route::post('/service/add', [CoreController::class, 'AdminServiceInsert'])->name('admin.insert.service');
        Route::post('/services/{id}', [CoreController::class, 'AdminServiceInsert'])->name('admin.update.service');
        Route::post('/notification/add', [CoreController::class, 'AdminNotificationInsert'])->name('admin.insert.notification');
        Route::post('/notifications/{id}', [CoreController::class, 'AdminNotificationInsert'])->name('admin.update.notification');
        Route::post('/transactions/{id}', [CoreController::class, 'AdminTransactionInsert'])->name('admin.update.transaction');
        Route::post('/manage-user/{id}', [CoreController::class, 'AdminUserInsert'])->name('admin.update.user');
        Route::post('/switch', [CoreController::class, 'AdminPutSwitch'])->name('admin.switch.data');
        Route::post('/fund-user/{id}', [CoreController::class, 'AdminUserFund'])->name('admin.fund.user');
        Route::post('/apiconfigs/{id}', [CoreController::class, 'AdminAPIConfigInsert'])->name('admin.update.api_config');
        Route::post('/apiconfig/add', [CoreController::class, 'AdminAPIConfigInsert'])->name('admin.insert.api_config');
        Route::post('/product/add', [CoreController::class, 'AdminProductInsert'])->name('admin.insert.product');
        Route::post('/products/{id}', [CoreController::class, 'AdminProductInsert'])->name('admin.update.product');
        Route::post('/update-init', [CoreController::class, 'initializeUpdate'])->name('admin.update.init');
        Route::post('/install-theme', [CoreController::class, 'AdminThemeInstall'])->name('admin.theme.install');
        Route::post('/uninstall-theme', [CoreController::class, 'AdminThemeUninstall'])->name('admin.theme.uninstall');
        Route::post('/upload-theme', [CoreController::class, 'AdminThemeUpload'])->name('admin.theme.upload');
        Route::post('/send-mail', [CoreController::class, 'BulkMailerSend'])->name('admin.send.bulkmail');
        
        
        #@ Plugins 
        
        Route::get('/plugins', [CoreController::class, 'AdminPlugins'])->name('admin.plugins');
        Route::post('/plugins/activate/{slug}', [CoreController::class, 'activate'])->name('admin.plugins.activate');
        Route::post('/plugins/deactivate/{slug}', [CoreController::class, 'deactivate'])->name('admin.plugins.deactivate');
        Route::delete('/plugins/uninstall/{slug}', [CoreController::class, 'uninstall'])->name('admin.plugins.uninstall');
        Route::post('/plugins/upload', [CoreController::class, 'upload'])->name('admin.plugins.upload');
        
        #@ Media
        Route::get('/media', [CoreController::class, 'AdminMediaView'])->name('admin.media.view');
        Route::get('/media/load', [CoreController::class, 'AdminMedia'])->name('admin.media');
        Route::post('/media/add', [CoreController::class, 'AdminMediaAdd'])->name('admin.media.add');
        Route::post('/media/edit', [CoreController::class, 'AdminMediaEdit'])->name('admin.media.edit');
        
        #@ Auto
        Route::post('/automate/delete', [CoreController::class, 'AdminAutomateDelete'])->name('admin.automate.delete');
        Route::post('/automate/add', [CoreController::class, 'AdminAutomateAdd'])->name('admin.automate.add');
        
        
        Route::get('/automate', [CoreController::class, 'AdminAutomate'])->name('admin.automate');
        Route::get('/monetization', [CoreController::class, 'AdminMonetization'])->name('admin.monetization');
        Route::get('/labs', [CoreController::class, 'AdminLabs'])->name('admin.labs');
        Route::get('/mobile', [CoreController::class, 'AdminMobile'])->name('admin.mobile');
        Route::get('/support', [CoreController::class, 'AdminSupport'])->name('admin.support');
    });
});
