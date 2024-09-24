<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExcelDataController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/items', function () {
    return [
        ['id' => 1, 'name' => 'Item 1'],
        ['id' => 2, 'name' => 'Item 2'],
        ['id' => 3, 'name' => 'Item 3'],
    ];
});

// Route::get('/pipes', function () {
//     return [
//         'products' => [
//             ['id' => 1, 'name' => 'Casing'],
//             ['id' => 2, 'name' => 'Tubing'],
//         ],
//         'grades' => [
//             ['id' => 1, 'name' => 'Grade A', 'product_id' => 1],
//             ['id' => 2, 'name' => 'Grade B', 'product_id' => 2],
//         ],
//         'sizes' => [
//             ['id' => 1, 'name' => '10', 'grade_id' => 1],
//             ['id' => 2, 'name' => '5', 'grade_id' => 2],
//         ],
//         'connections' => [
//             ['id' => 1, 'name' => 'Threaded', 'size_id' => 1],
//             ['id' => 2, 'name' => 'Welded', 'size_id' => 2],
//         ],
//     ];
// });

Route::get('/pipes', [ExcelDataController::class, 'getDataFromExcel']);

