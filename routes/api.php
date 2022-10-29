<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

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

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/products/create', [ProductController::class, 'create']);
    Route::put('/products/{id}', [ProductController::class, 'edit']);
    Route::patch('/products/{id}', [ProductController::class, 'edit']);
    Route::delete('/products/{id}', [ProductController::class, 'delete']);
    
    Route::post('/logout', function (Request $request) {
        $request->user()->tokens()->delete();

        return response()->json(['success' => true, 'message' => 'Logged out'], 200);
    });
});


// Manual Auth
Route::post('/register', function (Request $request) {
    $credentials = $request->validate([
        'name' => ['required', 'string'],
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    $user = User::create([
        'name' => $credentials['name'],
        'email' => $credentials['email'],
        'password' => bcrypt($credentials['password'])
    ]);
    $token = $user->createToken("appToken")->plainTextToken;

    return response()->json(['success' => true, 'user' => $user, 'token' => $token], 200);
});

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);
    
    if (!Auth::attempt($credentials)) {
        return response()->json(['success' => false, 'message' => 'Bad credentials'], 200);
    }
    $user = User::where('email', $credentials['email'])->first();
    $token = $user->createToken("appToken")->plainTextToken;

    return response()->json(['success' => true, 'user' => $user, 'token' => $token], 200);
});