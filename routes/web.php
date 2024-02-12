<?php

use App\Http\Livewire\Dash;
use App\Http\Livewire\Select2;
use App\Http\Livewire\Component1;
use App\Http\Livewire\PosController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use App\Http\Livewire\CoinsController;
use App\Http\Livewire\RolesController;
use App\Http\Livewire\UsersController;
use App\Http\Livewire\AsignarController;
use App\Http\Livewire\CashoutController;
use App\Http\Livewire\SaboresController;
use App\Http\Livewire\ReportsController;
use App\Http\Livewire\PermisosController;
use App\Http\Livewire\ProductsController;
use App\Http\Controllers\ProductoWooController;
use App\Http\Controllers\ExportController;
use App\Http\Livewire\CategoriesController;
use App\Http\Livewire\LotesNew;
use App\Http\Livewire\InsumosController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\WebhookProductController;
use App\Http\Controllers\VentaWoocomerce;
use App\Http\Controllers\CustomerResetPasswordController;
use App\Http\Controllers\ClienteWoocomerce;
use App\Http\Controllers\QuickBooksController;
use App\Http\Livewire\QuickBooks;
use Illuminate\Http\Request;
use App\Http\Livewire\InspectorsController;
use App\Http\Livewire\ClientesController;
use App\Http\Livewire\DespachosController;
use App\Http\Livewire\TransportesController;
use App\Http\Livewire\EnviosController;
use App\Http\Livewire\CustomerController;
use App\Http\Livewire\SendMessageForm;


Route::post('/webhook', [WebhookProductController::class, 'woocommerceStockUpdate'])
    ->middleware('webhook');
Route::post('/crearpedido', [VentaWoocomerce::class, 'CrearVenta'])->middleware('webhook');
Route::post('/actualizarpedido', [VentaWoocomerce::class, 'ActualizarVenta'])->middleware('webhook');
Route::post('/ClientesWebhook', [ClienteWoocomerce::class, 'CrearCliente'])->middleware('webhook');
Route::post('/ClientesACTWebhook', [ClienteWoocomerce::class, 'ActualizarCliente'])->middleware('webhook');
Route::get('/', function () {
    return view('auth.login');
});

Route::get('resetpassword', [CustomerResetPasswordController::class, 'showResetForm'])->name('customer.password.reset');

Route::post('/products/sync', [ProductoWooController::class, 'syncProducts'])->name('products.sync');


//Auth::routes();
Route::get('quickbooks', QuickBooks::class);

Auth::routes(['register' => false]); // deshabilitamos el registro de nuevos users

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware('Employee')->group(function () {
    Route::get('products', ProductsController::class);
});

Route::middleware(['auth'])->group(function () {

    Route::get('categories', CategoriesController::class)->middleware('role:Admin');
    Route::get('products', ProductsController::class);
    Route::get('coins', CoinsController::class);
    Route::get('pos', PosController::class);
    Route::get('lotes', LotesNew::class);
    Route::get('inspectors', InspectorsController::class);




   /* function qrCode($qr)
    {
        $lote = Lote::where('CodigoBarras', $qr)->first();
        return $lote->id;
        return response()->json(['success' => true]);
    }*/


    //Route::post('/webhook', [WebhookController::class, 'handleWebhook']);

    Route::get('clientes', ClientesController::class);
    Route::get('despachos', DespachosController::class);

    Route::group(['middleware' => ['role:Admin']], function () {
        Route::get('/home', Dash::class);
        Route::get('roles', RolesController::class);
        Route::get('permisos', PermisosController::class);
        Route::get('sabores', SaboresController::class);
        Route::get('asignar', AsignarController::class);
        Route::get('insumos', InsumosController::class);
        Route::get('cashout', CashoutController::class);
        Route::get('reports', ReportsController::class);
        Route::get('dash', Dash::class)->name('dash');
        Route::get('envios', EnviosController::class);

        Route::post('/qr/{id}', [EnviosController::class, 'processQRCode']);
        Route::post('/Busc/{qr}/{ventaId}', [EnviosController::class, 'BusquedaQRCode']);

        Route::post('/update-actual/{id}', [EnviosController::class, 'updateActual'])->name('update-actual');

        Route::post('/guardar-firma', [EnviosController::class, 'guardarFirma'])->name('guardar-firma');

        Route::post('/update-fin/{id}', [EnviosController::class, 'updateFin'])->name('update-fin');

        Route::get('transportes', TransportesController::class);
        Route::get('clientes/{$id}', [ClientesController::class, 'info']);

        //reportes PDF
        Route::get('report/pdf/{user}/{type}/{f1}/{f2}', [ExportController::class, 'reportPDF']);
        Route::get('report/pdf/{user}/{type}', [ExportController::class, 'reportPDF']);
        //detalle lote
        Route::get('detail/pdf/{id}', [ExportController::class, 'detail']);
        Route::get('InspectorsPDF', [ExportController::class, 'InspectorsPDF']);
        //CLIENTES
        Route::get('historial/pdf/{id}', [ExportController::class, 'historial']);

        Route::get('users', UsersController::class);
    });
    Route::get('/products/{id}/image', [ProductsController::class, 'showImage']);

    Route::get('messages', SendMessageForm::class);



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




    Route::get('/send-products-to-woocommerce', [ProductsController::class, 'sendProductsToWooCommerce']);





    //reportes EXCEL
    Route::get('report/excel/{user}/{type}/{f1}/{f2}', [ExportController::class, 'reporteExcel']);
    Route::get('report/excel/{user}/{type}', [ExportController::class, 'reporteExcel']);
});






Route::post('/create-product-in-woocommerce', function (Request $request) {
    $productId = $request->input('product_id');
    $product = Product::find($productId);

    if (!$product) {
        return response()->json(['error' => 'Producto no encontrado'], 404);
    }

    // Configuración de la API de WooCommerce
    $woocommerce = new Client(
        'https://kdlatinfood.com/wp-json/wc/v3/products', // URL de tu tienda WooCommerce
        'ck_8e38a879e7f6ce0d56e34c525de194a60c2e2ce5',
        'cs_723eab16e53f3607fd38984b00f763310cc4f473',
        [
            'wp_api' => true,
            'version' => 'wc/v3',
            'verify_ssl' => true, // Solo si estás trabajando en un entorno de desarrollo local sin SSL
        ]
    );

    // Crear el producto en WooCommerce utilizando los datos del producto de tu CRM
    $newProduct = [
        'name' => $product->name,
        'sku' => $product->sku,
        'regular_price' => (string) $product->price,
        'stock_quantity' => $product->stock,

    ];

    try {
        $woocommerce->post('products', $newProduct);
        return response()->json(['message' => 'Producto creado en WooCommerce'], 200);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Error al crear el producto en WooCommerce'], 500);
    }
})->middleware('auth')->name('create-product-in-woocommerce');





Route::get('/codigo-barras/{CodigoBarras}', function ($CodigoBarras) {
    // código para generar el código de barras

    // crear una imagen en blanco de 300 x 100 pixeles
    $image = imagecreatetruecolor(300, 100);

    // definir el color de fondo en blanco
    $color_fondo = imagecolorallocate($image, 255, 255, 255);
    imagefill($image, 0, 0, $color_fondo);
    // generar el código de barras utilizando el algoritmo Code 128
    $code128 = new Code128();
    $code128->setData($CodigoBarras);
    $code128->setThickness(2);
    $code128->setQuietZone(10);
    $code128->setShowStartStop(true);
    $barcode = $code128->generateBarcodeString();
    // definir el ancho de las barras
    $ancho_barra = 2;

    // recorrer cada carácter del código de barras y agregar una barra negra o blanca según corresponda
    for ($i = 0; $i < strlen($barcode); $i++) {
        $color_barra = ($barcode[$i] == '1') ? imagecolorallocate($image, 0, 0, 0) : $color_fondo;
        imagefilledrectangle($image, $i * $ancho_barra, 0, ($i + 1) * $ancho_barra - 1, 99, $color_barra);
    }
    // guardar la imagen en formato PNG en la carpeta "public" de Laravel
    imagepng($image, public_path("codigo-barras-{$CodigoBarras}.png"));

    // mostrar la imagen en el navegador
    header("Content-type: image/png");
    imagepng($image);
});



















Route::get('conte', Component1::class);
Route::get('conte2', function () {
    return view('contenedor');
});



//rutas utils
Route::get('select2', Select2::class);

URL::forceScheme('https');