<?php

use App\Http\Controllers\CoreController;

Route::middleware('auth', 'auth.session', 'confirm.setup')->group(function () {
   Route::get('/pin/verify', [CoreController::class, 'coreAuth'])->name('core.auth');
   Route::post('/pin/verify', [CoreController::class, 'pinVerify'])->name('pin.verify'); 
   
}); 
Route::middleware('auth', 'auth.session', 'confirm.setup', 'require_pin')->group(function () {
    /*
    * CORE JANNAH: BY ANI ANTHONY
    * KEY: {{ LOCKED: AGBTLX565901205732578834328365487635347896972563890373463524376385373675356389352098276265253Y7358927T37628O7Y23T87623Y826T23Y782367YRT237622G2F26T2V T62T7623R76CTV76C7TV T627627T726T727 276T2T768 27T682 T768276T2Y2IBT76IXIB7 GYC76C  6 CTYF3T I23TYIT2 TYR3TU23T23GYIGUIQYIUKQ278T2QG76T32GF6RFT3FT }}
    */ 
    Route::get('/notifications', [CoreController::class, 'notifications'])->name('core.notifications');
    Route::get('/support', [CoreController::class, 'support'])->name('core.support');
    Route::get('/transactions', [CoreController::class, 'transactions'])->name('core.transactions');
    Route::get('/transaction/{session_id}', [CoreController::class, 'transaction'])
    ->name('core.transaction')
    ->where('session_id', '[0-9]+');
    
    Route::get('/deposit', [CoreController::class, 'deposit'])->name('core.deposit');
    Route::get('/deposit/manual', [CoreController::class, 'depositManual'])->name('core.deposit.manual');
    
    Route::get('/deposit/onetime', [CoreController::class, 'depositOnetime'])->name('core.deposit.onetime');
    
    Route::post('/deposit/onetime/verify', [CoreController::class, 'depositOnetimeVerify'])->name('core.deposit.onetime.verify');
    
    Route::get('/deposit/monnify', [CoreController::class, 'depositMonnify'])->name('core.deposit.monnify');
    
    Route::get('/user/edit', [CoreController::class, 'userEdit'])->name('core.user.edit');
    Route::get('/user/pin', [CoreController::class, 'userPin'])->name('core.user.pin');
    Route::get('/user/password', [CoreController::class, 'userPassword'])->name('core.user.password');
    Route::get('/user/developer', [CoreController::class, 'userDeveloper'])->name('core.user.developer');
    Route::get('/support', [CoreController::class, 'support'])->name('core.support');
    
    
    Route::get('/services', [CoreController::class, 'service'])->name('core.service');
    Route::get('/transfer', [CoreController::class, 'transfer'])->name('core.transfer');
    Route::get('/earn', [CoreController::class, 'earn'])->name('core.earn');
    Route::get('/rewards', [CoreController::class, 'reward'])->name('core.reward');
    Route::get('/scan', [CoreController::class, 'scan'])->name('core.scan');
    Route::get('/airtime', [CoreController::class, 'airtime'])->name('core.airtime');
    Route::get('/data', [CoreController::class, 'data'])->name('core.data');
    Route::get('/tv', [CoreController::class, 'tv'])->name('core.tv');
    Route::get('/electricity', [CoreController::class, 'electricity'])->name('core.electricity');
    Route::get('/betting', [CoreController::class, 'betting'])->name('core.betting');
    Route::get('/recharge', [CoreController::class, 'recharge'])->name('core.recharge');
    Route::get('/more', [CoreController::class, 'more'])->name('core.more');
    Route::get('/epin', [CoreController::class, 'epin'])->name('core.epin');
    Route::get('/kyc-verification', [CoreController::class, 'kyc'])->name('core.kyc');
    
    ## TAG
    Route::get('/loans', [CoreController::class, 'loan'])->name('core.loan');
    Route::get('/loans/eligibility', [CoreController::class, 'loanRequirements'])->name('core.loan.requirements');
    Route::get('/upgrade/agent', [CoreController::class, 'upgradeAgent'])->name('core.user.upgrade');
    Route::get('/statistics', [CoreController::class, 'statistics'])->name('core.statistics');
    Route::get('/withdraw', [CoreController::class, 'withdraw'])->name('core.withdrawal');
    
    ##@ TAG
    
    #PUT 
    
    Route::post('/user/edit', [CoreController::class, 'userUpdate'])->name('core.user.update');
    Route::post('/user/pin', [CoreController::class, 'userPinUpdate'])->name('core.user.pin.update');
    Route::post('/user/password', [CoreController::class, 'userPasswordUpdate'])->name('core.user.password.update');
    Route::post('/kyc-verification', [CoreController::class, 'kycVerify'])->name('core.kyc.verify');
    
    Route::post('/airtime', [CoreController::class, 'buyAirtime'])->name('core.purchase.airtime');
    Route::post('/data', [CoreController::class, 'buyData'])->name('core.purchase.data');
    Route::post('/iuc/verify', [CoreController::class, 'VerifyIUC'])->name('core.verify.iuc');
    Route::post('/tv', [CoreController::class, 'subTV'])->name('core.purchase.tv');
    Route::post('/meter/verify', [CoreController::class, 'VerifyMeter'])->name('core.verify.meter');
    Route::post('/electricity', [CoreController::class, 'payElectricity'])->name('core.purchase.electricity');
    Route::post('/epin', [CoreController::class, 'payEpin'])->name('core.purchase.epin');
    Route::post('/recharge', [CoreController::class, 'printRecharge'])->name('core.purchase.recharge');
    Route::post('/betting/verify', [CoreController::class, 'VerifyBettingID'])->name('core.verify.betting');
    Route::post('/betting', [CoreController::class, 'subBetting'])->name('core.purchase.betting');
    
    
    ##@TAG
    Route::post('/loans', [CoreController::class, 'loanGet'])->name('core.loan.borrow');
    Route::post('/upgrade/agent', [CoreController::class, 'userUpgradeRole'])->name('core.user.upgrade.init');
    Route::post('/transfer/now', [CoreController::class, 'walletTransfer'])->name('core.transfer.init');
    
    ##@TAG
    
    /*
    * SERVICES JANNAH: ANI ANTHONY
     * KEY: {{ SECURED: PGRQW565901205732578834328365487635347896972563890373463524376385373675356389352098276265253Y7358927T37628O7Y23T87623Y826T23Y782367YRT237622G2F26T2V T62T7623R76CTV76C7TV T627627T726T727 276T2T768 27T682 T768276T2Y2IBT76IXIB7 GYC76C  6 CTYF3T I23TYIT2 TYR3TU23T23GYIGUIQYIUKQ278T2QG76T32GF6RFT3FT }}
     * TOKEN: DARK_+==+==+==+==+==+==+==+==+==+==+==+==+==+==+==++=+==++++++++_CRACK_AYUWEREYUI_DESKGHT
     * KILLER: AVYU**************************************************************************************************AUTH_ACCESS_BLANK
     * CMD: SHELL====++++==+++==++==+++++++=
     * MIGHTY: {{ LOCKED: UNABLE TO LOAD RESOURCE.... }}
     * BASH -> TRUE
     * MSK -> DESTRUCT ******************************************** UNABLE TO LOAD RESOURCE 
     * DIRECT_ENCRYPTION_PROTOCOL -> AYE 404 ACCESS DENIED BLOCK BY SKIN_*************
    */
    
}); 