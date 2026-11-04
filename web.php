<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// papar login form
Route::get('/studentlogin', function () {
    return view('students.login');
})->name('login');

// submit login form
Route::post('/studentlogin', [AuthController::class, 'login'])->name('studentlogin');

// submit logout
Route::get('/studentlogout', [AuthController::class, 'logout'])->name('studentlogout');

?>
