<?php

use App\Http\Controllers\Api\WebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('webhook/pedidos', [WebhookController::class, 'handle'])->name('api.webhook.pedidos');
