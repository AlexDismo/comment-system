<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


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

Route::get('/comment', [\App\Http\Controllers\Api\CommentController::class, 'index']);
Route::post('/comment', [\App\Http\Controllers\Api\CommentController::class, 'store']);
Route::get('/comment/{id}', [\App\Http\Controllers\Api\CommentController::class, 'show']);

Route::get('/session/user-input', [\App\Http\Controllers\Api\SessionController::class, 'getUserInput']);



use Symfony\Component\Mime\MimeTypes;

Route::get('/images/{filename}', function ($filename) {
    $path = storage_path('app/public/images/' . $filename);
    if (!file_exists($path)) {
        abort(404);
    }
    $file = file_get_contents($path);
    $mimeTypes = new MimeTypes();
    $type = $mimeTypes->guessMimeType($path);
    if($type === null){
        $type = 'image/jpeg';
    }
    return response($file, 200)->header("Content-Type", $type);
});
