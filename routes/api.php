<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Livewire\ProductsController;
use App\Http\Controllers\ProductImageController;
use App\Http\Livewire\CategoriesController;
use App\Http\Livewire\ClientesController;
use App\Http\Livewire\SaboresController;
use App\Http\Livewire\InsumosController;
use App\Http\Livewire\LotesNew;
use App\Http\Livewire\PosController;
use App\Http\Livewire\DespachosController;
use App\Http\Livewire\EnviosController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Livewire\UsersController;

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

/*API REST FOR APP */

Route::put('update-token', [UsersController::class, 'updateNotificationToken']);
Route::post('login-user', [UsersController::class, 'LoginUserAdmin']);
Route::post('login-client', [UsersController::class, 'LoginUserClient']);
//Route::post('/create-client', [UsersController::class, 'createCustomer']);
//Route::post('/create-user', [UsersController::class, 'createUser']);

/*Despachos */
Route::post('verify-qrcode', [EnviosController::class, 'verifyQRCode']);
Route::post('updateActualSales', [EnviosController::class, 'updateSalesStatusAPI']);
Route::post('/add-product-to-sale', [DespachosController::class, 'addProductToSale']);
Route::post('/update-sale', [DespachosController::class, 'updateSaleAPI']);
Route::delete('/sales/borrar/{saleDetailId}', [DespachosController::class, 'removeProductFromSale']);
Route::get('/despachos', [DespachosController::class, 'getAllSales']);
Route::get('/despachos-pending', [DespachosController::class, 'getAllSalesPending']);
Route::get('/sales/{id}', [DespachosController::class, 'getSaleDetails']);
Route::put('/sales/cargar/{id}', [DespachosController::class, 'cargarSale']);
Route::put('/sales/FIN/{id}', [EnviosController::class, 'updateFinApi']);


/* PRODUCTOS*/
Route::get('products', [ProductsController::class, 'ShowAll']);
Route::get('showAllKEY', [ProductsController::class, 'showAllKEY']);
Route::get('products/create', [ProductsController::class, 'CreateApi']);
Route::get('products/update/{id}', [ProductsController::class, 'UpdateApi']);
Route::get('products/find/{id}', [ProductsController::class, 'FindApi']);
Route::get('products/findprod/{id}', [ProductsController::class, 'findProductsByCategory']);
Route::get('products/crudos', [ProductsController::class, 'ProductsCrudos']);
Route::get('products/precocidos', [ProductsController::class, 'ProductsPreCocidos']);


/*CATEGORIAS */
Route::get('categories', [CategoriesController::class, 'ShowAll']);
Route::get('categories/create', [CategoriesController::class, 'CreateApi']);
Route::get('categories/update/{id}', [CategoriesController::class, 'UpdateApi']);
Route::get('categories/find/{id}', [CategoriesController::class, 'FindApi']);

/*LOTES */
Route::get('lotes', [LotesNew::class, 'ShowAll']);
Route::get('lotes/create', [LotesNew::class, 'CreateApi']);
Route::get('lotes/update/{id}', [LotesNew::class, 'UpdateApi']);
Route::get('lotes/find/{barcode}', [LotesNew::class, 'FindApi']);

/*SABORES */
Route::get('sabores', [SaboresController::class, 'ShowAll']);
Route::get('sabores/create', [SaboresController::class, 'CreateApi']);
//Route::get('lotes/update/{id}', [SaboresController::class, 'UpdateApi']);
Route::get('sabores/find/{barcode}', [SaboresController::class, 'FindApi']);

/*INSUMOS */
Route::get('insumos', [InsumosController::class, 'ShowAll']);
Route::get('insumos/create', [InsumosController::class, 'CreateApi']);
//Route::get('lotes/update/{id}', [InsumosController::class, 'UpdateApi']);
Route::get('insumos/find/{barcode}', [InsumosController::class, 'FindApi']);

/*CLIENTES */

Route::post('clientes/create', [ClientesController::class, 'createApi']);
Route::put('clientes/update/{id}', [ClientesController::class, 'editApi']);
Route::get('clientes/find/{id}', [ClientesController::class, 'getByIdApi']);
Route::get('clientes/findUser/{id}', [ClientesController::class, 'FindUser']);
Route::put('clientes/edit-address/{id}', [ClientesController::class, 'editApiaAdress']);

//Route::post('login', [LoginController::class, 'loginApi']);
Route::post('logout', [LoginController::class, 'logoutApi']);

/*compra*/
Route::post('PosAPI/payWithCredit', [PosController::class, 'payWithCreditApi']);

Route::get('storage/images', function () {
    $imageExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    $imageDirectories = ['categories', 'denominations', 'products', 'users'];
    $images = [];

    foreach ($imageDirectories as $directory) {
        $path = storage_path('app/public/' . $directory);
        if (File::exists($path)) {
            $files = array_diff(scandir($path), ['.', '..']);
            $imagesInDirectory = array_filter($files, function ($file) use ($imageExtensions) {
                $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                return in_array($extension, $imageExtensions);
            });
            $images = array_merge($images, $imagesInDirectory);
        }
    }

    return response()->json($images);
});
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


//FAVORITOS
Route::prefix('favoritos')->group(function () {
    // Ruta para añadir un producto a favoritos
    Route::post('/add/{id_producto}/{id_cliente}', [ProductsController::class, 'Addfavorite']);

    // Ruta para eliminar un producto de favoritos
    Route::delete('/delete/{id_producto}/{id_cliente}', [ProductsController::class, 'Deletefavorite']);

    // Ruta para obtener todos los productos favoritos de un cliente
    Route::get('/all/{id_cliente}', [ProductsController::class, 'GetAllfavorite']);
});


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//CARRITO
Route::prefix('carrito')->group(function () {
    // Ruta para agregar un producto al carrito con una cantidad específica
    Route::post('/add/{id_producto}/{id_cliente}/{items}', [ProductsController::class, 'AddCart']);

    // Ruta para eliminar un producto completo del carrito de compras
    Route::delete('/delete/{id_producto}/{id_cliente}', [ProductsController::class, 'DeleteItemCart']);

    // Ruta para actualizar la cantidad de items de un producto en el carrito
    Route::put('/update/{id_producto}/{id_cliente}/{items}', [ProductsController::class, 'UpdateItemCart']);

    // Ruta para listar todos los productos en el carrito de un cliente
    Route::get('/all/{id_cliente}', [ProductsController::class, 'GetAllCart']);

    // Ruta para vaciar el carrito de compras de un cliente
    Route::delete('/vaciar/{id_cliente}', [ProductsController::class, 'VaciarAllCart']);

    Route::put('/decrement/{id_producto}/{id_cliente}/{items}', [ProductsController::class, 'DecrementItemCart']);
});

Route::post('comando', [PosController::class, 'comando']);

/*Route::get('/products/images', function () {
    $path = storage_path('app/public/products');
    $files = File::allFiles($path);

    $images = array_map(function ($file) {
        $name = $file->getRelativePathname();
        $url = url('/api/products/' . $name);

        return [
            'name' => $name,
            'url' => $url,
        ];
    }, $files);

    return $images;
});

Route::get('/categories/images', function () {
    $path = storage_path('app/public/categories');
    $files = File::allFiles($path);

    $images = array_map(function ($file) {
        $name = $file->getRelativePathname();
        $url = url('/api/categories/' . $name);

        return [
            'name' => $name,
            'url' => $url,
        ];
    }, $files);

    return $images;
});

Route::get('/users/images', function () {
    $path = storage_path('app/public/users');
    $files = File::allFiles($path);

    $images = array_map(function ($file) {
        $name = $file->getRelativePathname();
        $url = url('/api/users/' . $name);

        return [
            'name' => $name,
            'url' => $url,
        ];
    }, $files);

    return $images;
});*/

Route::get('/products/images', [ProductImageController::class, 'index']);
