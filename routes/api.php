<?php

use App\Http\Controllers\Api\DocumentController;
use App\Http\Controllers\Api\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// list all ducument
Route::get('/documents', [DocumentController::class, 'index']);
Route::get('/documents/{id}/content', [DocumentController::class, 'getContent']);
Route::get('/categories', [CategoryController::class, 'index']);
