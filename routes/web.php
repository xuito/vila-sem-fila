<?php

use App\Http\Middleware\StoreParametersMiddleware;
use App\Http\Middleware\StoreAuthMiddleware;
use App\Http\Controllers\Site\StoreController;
use App\Http\Controllers\Site\BillController;
use App\Http\Controllers\Site\PaymentController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Site\TestController;
use App\Http\Controllers\Dashboard\DashboardController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Redirects
|--------------------------------------------------------------------------
*/

/*
Route::get('/rio-preto', function () {
    return redirect('https://viladionisio.com.br/rio-preto/sem-filas');
});

Route::get('/ribeirao', function () {
    return redirect('https://viladionisio.com.br/ribeirao/sem-filas');
});
*/

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';

//PAGINA DE TESTES
//Route::get('/teste', [TestController::class, 'index'])->name('test');

//PAGINA INICIAL - SELETOR DE UNIDADE
Route::get('/', [StoreController::class, 'selector'])->name('site.store.selector');

//ROTAS VINCULADAS A UNIDADE
Route::middleware(StoreParametersMiddleware::class)->group(function()
{
    //GERAR CHAVE PUBLICA (DESATIVAR EM PRODUCAO)
    //Route::get('/gateway/public-key', [PaymentController::class, 'generatePublicKey'])->name('site.public-key');

    //FORMULÁRIO PARA BUSCA DA CONTA
    Route::get('/formulario', [BillController::class, 'create'])->name('site.bill.create');
    Route::post('/bill-store', [BillController::class, 'store'])->name('site.bill.store');

    //PAGAMENTO
    Route::prefix('/pagamento')->group(function()
    {
        Route::post('/', [PaymentController::class, 'create'])->name('site.payment.create');
        Route::post('/store', [PaymentController::class, 'store'])->name('site.payment.store');
        Route::post('/pix', [PaymentController::class, 'pixPayment'])->name('site.payment.pix');

        //SIMULAR PAGAMENTO DE PIXA (DESATIVAR EM PRODUCAO)
        //Route::get('/simular/{qrCode}', [PaymentController::class, 'simulatePIXPayment'])->name('site.payment.pix.simulate');
    });

    //RETORNO
    Route::get('/retorno/{status}/{paymentHash}/{message?}', [PaymentController::class, 'paymentResponse'])->name('site.payment.response');

});

//ROTAS AUTENTICADAS
Route::middleware('auth')->group(function ()
{
    //GERENCIAMENTO DA CONTA
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //DASHBOARD
    Route::middleware('can:basic')->prefix('/dashboard')->group(function ()
    {
        //Índice
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    });
});

//REDIRECIONAMENTO PARA LOJA
Route::get('/{slug}', [StoreController::class, 'redirect'])->name('site.store.redirect');
