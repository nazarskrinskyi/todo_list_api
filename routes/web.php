<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/setup', function () {
    $credentials = [
        'email' => 'admin@admin.com',
        'password' => 'password',
    ];
    if (!Auth::attempt($credentials)) {
        $user = new \App\Models\User();
        $user->name = 'Admin';
        $user->email = $credentials['email'];
        $user->password = \Illuminate\Support\Facades\Hash::make($credentials['password']);

        $user->save();
    }
    if (Auth::attempt($credentials)) {
        $user = Auth::user();

        $accessToken = $user->createToken('accessToken', ['update', 'delete']);

        return [
            'accessToken' => $accessToken->plainTextToken,
        ];
    }
});


Auth::routes(['verify' => true]);


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])
    ->middleware('verified')
    ->name('home');
