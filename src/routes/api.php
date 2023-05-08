<?php

use App\Events\CaseDone;
use App\Filters\DatasetFilters;
use App\Http\Controllers\Api\DatasetController;
use App\Http\Controllers\Api\UploadFileController;
use App\Http\Resources\DatasetCollection;
use App\Models\Category;
use App\Models\Dataset;
use App\Models\Gender;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/dataset-export', [DatasetController::class, 'export']);

Route::prefix('dataset')->group(function () {
    Route::get('/', [DatasetController::class, 'index']);
    Route::get('/category', function (Request $request) {
        return Category::all()->pluck('name');
    });

    Route::get('/gender', function (Request $request) {
        return Gender::all()->pluck('name');
    });
    Route::get('/age', function (Request $request) {
        return [
            'min' => Carbon::parse(Dataset::max('birthDate'))->age,
            'max' => Carbon::parse(Dataset::min('birthDate'))->age,
        ];
    });
    Route::get('/dateRange', function (Request $request) {
        return [
            'min' => Dataset::min('birthDate'),
            'max' => Dataset::max('birthDate'),
        ];
    });
    Route::post('/upload', [UploadFileController::class, 'store']);
});


