<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Log;
use App\Http\Livewire\Scaner;
use App\Models\Category;
use App\Models\Product;
use App\Models\Sabores;
use App\Models\Lotes;
use App\Models\Carrito;
use App\Models\Favoritos;
use Illuminate\Validation\Validator;
use Illuminate\Support\Facades\Http;
use Livewire\Component;
use App\Models\Inspectors;
use Automattic\WooCommerce\Client;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Facades\View;
use App\Traits\CartTrait;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
//api
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;


class ProductsController extends Component
{
    use WithPagination;
    use WithFileUploads;
    use CartTrait;

    public function ScanCode($code)
    {
        $this->ScanearCode($code);
        $this->emit('global-msg', "Se agrego el producto al carrito ");
    }

    public $name, $barcode, $descripcion, $saborID, $cost, $estado, $price, $stock, $alerts, $categoryid, $search, $image, $selected_id, $pageTitle, $componentName,$tam2,$tam1;
    private $pagination = 5;
    private $pagination2 = 5;

    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }


    public function mount()
    {
        $this->pageTitle = 'Listado';
        $this->componentName = 'Productos';
        $this->categoryid = 'Elegir';
        $this->estado = 'Elegir';
        $this->saborID = 'Elegir';

    }



    // En tu componente de Livewire

    public function openPrecocidosModal()
    {
        $this->emit('openPrecocidosModal');
    }

    public function openCrudosModal()
    {
        $this->emit('openCrudosModal');
    }




    public function render()
    {
        $productsOutOfStock = Product::where('stock', '<', 90)->get();
        $categories = Category::orderBy('name', 'asc')->get();
        View::share('productsOutOfStock', $productsOutOfStock);
        View::share('categories', $categories);

        $query = Product::join('categories as c', 'c.id', 'products.category_id')
            ->select('products.*', 'c.name as category')
            ->orderBy('products.name', 'asc');
        $query2 = clone $query; // Crear una copia del objeto de consulta original

        if (strlen($this->search) > 0) {
            $query->where(function ($q) {
                $q->where('products.name', 'like', '%' . $this->search . '%')
                    ->orWhere('products.barcode', 'like', '%' . $this->search . '%')
                    ->orWhere('c.name', 'like', '%' . $this->search . '%');
            });
        }

        $products = $query->get();
        $products2 = $query2->get();
       $sabores = Sabores::orderBy('nombre', 'asc')->get();


        return view('livewire.products.component', [
            'data' => $products,
            'data2' => $products2,
            'categories' => $categories,
            'sabores' => $sabores,
        ])
            ->extends('layouts.theme.app')
            ->section('content');
    }



    public function Store()
    {
            // Validación adicional para los campos select
    if ($this->categoryid === 'Elegir' || $this->estado === 'Elegir' || $this->saborID === 'Elegir') {
        $this->emit('global-msg', 'Por favor, selecciona opciones válidas para los campos select.');
        return;
    }
            // Define las reglas de validación
    $rules = [
        'name' => 'required',
        'cost' => 'required|numeric',
        'price' => 'required|numeric',
        'estado' => 'required',
        'barcode' => 'required|unique:products,barcode',
        'stock' => 'required|numeric',
        'descripcion' => 'required',
        'alerts' => 'required',
        'categoryid' => 'required',
        'saborID' => 'required',
        'tam1' => 'required',
        'tam2' => 'required',
    ];

    // Define los mensajes de error personalizados
    $messages = [
        'required' => 'El campo :attribute es obligatorio.',
        'numeric' => 'El campo :attribute debe ser un número.',
        'unique' => 'El :attribute ya está en uso.',
    ];

    // Realiza la validación
    $this->validate($rules, $messages);
        $userid = Auth()->user()->id;
        $categoryName = $this->categoryid;
        $categoryId = $this->getCategoryIdFromWooCommerce($categoryName);

        $barcodeNumber = rand(pow(10, 9), pow(10, 10) - 1);
        $product = Product::create([
            'name' => $this->name,
            'cost' => $this->cost,
            'price' => $this->price,
            'estado' => $this->estado,
            'barcode' => $this->barcode,
            'stock' => $this->stock,
            'descripcion' => $this->descripcion,
            'alerts' => $this->alerts,
            'category_id' => $this->categoryid,
            'user_id' => $userid,
            'sabor_id' => $this->saborID,
            'tam1' => $this->tam1,
            'tam2' => $this->tam2,
        ]);
      //  $this->createProductInWooCommerce($product, $categoryId);

        if ($this->image) {
            $customFileName = uniqid() . '_.' . $this->image->extension();
            $this->image->storeAs('public/products', $customFileName);
            $product->image = $customFileName;
            $product->save();
        }
        $user = Auth()->user()->name;
        $inspector = Inspectors::create([
            'user' => $user,
            'action' => 'Creo el producto: ' . $this->name,
            'seccion' => 'Products'
        ]);
        $this->resetUI();
         $this->emit('global-msg', 'Producto Agregado');
        $this->emit('product-added', 'Producto Registrado', $barcodeNumber);
    }
    private function getCategoryIdFromWooCommerce($categoryName)
    {
        // Configurar la URL y los datos para la solicitud a la API de WooCommerce para obtener las categorías
        $url =  'https://kdlatinfood.com/wp-json/wc/v3/products';

        $response = Http::withBasicAuth('ck_8e38a879e7f6ce0d56e34c525de194a60c2e2ce5', 'cs_723eab16e53f3607fd38984b00f763310cc4f473')->get($url);

        if ($response->successful()) {
            $categories = $response->json();
            foreach ($categories as $category) {
                if ($category['name'] == $categoryName) {
                    return $category['id'];
                }
            }
        }

        // Si no se encuentra la categoría en WooCommerce, puedes manejarlo según tus necesidades
        // Por ejemplo, lanzar una excepción o asignar un valor predeterminado
        return 0;
    }

    private function createProductInWooCommerce($product, $categoryId)
    {
        // Configurar la URL y los datos para la solicitud a la API de WooCommerce para crear el producto
        $url = 'https://kdlatinfood.com/wp-json/wc/v3/products';

        $response = Http::withBasicAuth('ck_8e38a879e7f6ce0d56e34c525de194a60c2e2ce5', 'cs_723eab16e53f3607fd38984b00f763310cc4f473')->post($url, [
            'name' => $product->name,
            'sku' => $product->barcode,
            'regular_price' => $product->price,
            'stock_quantity' => $product->stock,
            'category_ids' => [$categoryId],
            // Otros campos del producto...
        ]);

        if ($response->successful()) {
            // El producto se creó correctamente en WooCommerce
            // Puedes realizar alguna acción adicional si lo deseas
            // Por ejemplo, registrar una entrada en los archivos de registro
            Log::info("Producto creado en WooCommerce con SKU: {$product->barcode}");
        } else {
            // Error al crear el producto en WooCommerce
            // Puedes manejar el error según tus necesidades
            Log::error("Error al crear el producto en WooCommerce con SKU: {$product->barcode}");
        }

        $user = Auth()->user()->name;
        $inspector = Inspectors::create([
            'user' => $user,
            'action' => 'Creo un producto en woocomerce ',
            'seccion' => 'Products'
        ]);
         $this->emit('global-msg', 'Producto Registrado en WooCommerce');
    }

    public function CrearProWoo($id)
    {
        // Obtener datos del producto
        $prod = Product::find($id);
        $sku = $prod->barcode;
        $nombre = $prod->name;
        $precio = $prod->price;
        $descripcion = $prod->descripcion;
        $stock = $prod->stock;

        // Mostrar mensaje de carga
        // Mostrar Swal de carga

        $this->emit('swal-loading', 'Creando producto en WooCommerce. Por favor, espera...');

        // Crear producto en WooCommerce
        // Configurar la URL y los datos para la solicitud a la API de WooCommerce para crear el producto
        $url = 'https://kdlatinfood.com/wp-json/wc/v3/products';

        // Obtener la ruta completa de la imagen del producto
        $imagePath = public_path('storage/products/' . $prod->image);

        // Crear el array con los datos del producto
        $data = [
            'name' => $nombre,
            'sku' => $sku,
            'regular_price' => $precio,
            'stock_quantity' => $stock,
            // Otros campos del producto...
        ];

        // Agregar la imagen al array de datos
        $data['image'] = Http::attach(
            'image',
            file_get_contents($imagePath),
            $prod->image,
            [
                'Content-Type' => 'image/jpeg',
                'Content-Disposition' => 'attachment; filename=' . $prod->image
            ]
        );


        // Realizar la solicitud HTTP con los datos del producto, incluida la imagen
        $response = Http::withBasicAuth('ck_8e38a879e7f6ce0d56e34c525de194a60c2e2ce5', 'cs_723eab16e53f3607fd38984b00f763310cc4f473')
            ->post($url, $data);


        // Actualizar atributo 'EstaEnWoocomerce' a 'si'
        $prod->EstaEnWoocomerce = 'si';
        $prod->save();

        // Mostrar mensaje de éxito
        $this->emit('global-msg', "SE CREO CORRECTAMENTE");
        $this->emit('producto-creado');

        // RELOAD 
        $user = Auth()->user()->name;
        $inspector = Inspectors::create([
            'user' => $user,
            'action' => 'Creo un producto en woocomerce ',
            'seccion' => 'Products'
        ]);
    }
    public function GenerateKey($id)
    {
        // Obtener el producto
        $product = Product::find($id);

        // Generar la clave aleatoria de 90 caracteres
        $key = Str::random(90);

        // Asignar la clave al campo KeyProduct
        $product->KeyProduct = $key;

        // Establecer el campo TieneKey como 'SI'
        $product->TieneKey = 'SI';

        // Guardar los cambios en la base de datos
        $product->save();

        // Imprimir la clave generada para verificar
        $this->emit('global-msg', "Key Generada Correctamente");
        $this->emit('producto-creado');
        $user = Auth()->user()->name;
        $inspector = Inspectors::create([
            'user' => $user,
            'action' => 'Creo una key de producto ',
            'seccion' => 'Products'
        ]);
    }

    public function visible($id)
    {
        // Obtener el producto
        $product = Product::find($id);

        

        // Asignar la clave al campo KeyProduct
        $product->visible = 'no';

        $product->save();

        // Imprimir la clave generada para verificar
        $this->emit('global-msg', "El producto ha sido ocultado");
        $this->emit('producto-creado');
        $user = Auth()->user()->name;
        $inspector = Inspectors::create([
            'user' => $user,
            'action' => 'oculto un producto ',
            'seccion' => 'Products'
        ]);
    }
        public function novisible($id)
    {
        // Obtener el producto
        $product = Product::find($id);

        

        // Asignar la clave al campo KeyProduct
        $product->visible = 'si';

        $product->save();

        // Imprimir la clave generada para verificar
        $this->emit('global-msg', "El producto ha sido PUBLICADO");
        $this->emit('producto-creado');
        $user = Auth()->user()->name;
        $inspector = Inspectors::create([
            'user' => $user,
            'action' => 'publico un producto ',
            'seccion' => 'Products'
        ]);
    }
    public function Edit(Product $product)
    {
        $this->selected_id = $product->id;
        $this->name = $product->name;
        $this->barcode = $product->barcode;
        $this->cost = $product->cost;
        $this->estado = $product->estado;
        $this->descripcion = $product->descripcion;
        $this->price = $product->price;
        $this->stock = $product->stock;
        $this->alerts = $product->alerts;
        $this->categoryid = $product->category_id;
        $this->saborID = $product->sabor_id;
         $this->tam1 = $product->tam1;
          $this->tam2 = $product->tam2;
        $this->image = null;

        $this->emit('modal-show', 'Show modal');
    }



    public function Update()
    {
        $rules  = [
            'name' => "required|min:3",
            'cost' => 'required',
            'price' => 'required',
            'stock' => 'required',
            'alerts' => 'required',
            'categoryid' => 'required|not_in:Elegir'
        ];

        $messages = [
            'name.required' => 'Nombre del producto requerido',

            'name.min' => 'El nombre del producto debe tener al menos 3 caracteres',
            'cost.required' => 'El costo es requerido',
            'price.required' => 'El precio es requerido',
            'stock.required' => 'El stock es requerido',
            'alerts.required' => 'Ingresa el valor mínimo en existencias',
            'categoryid.not_in' => 'Elige un nombre de categoría diferente de Elegir',
        ];

        $this->validate($rules, $messages);

        $product = Product::find($this->selected_id);

     //   $product = Product::find($this->selected_id);

        // Verificar si se ha cambiado el campo "estado" a "PRE-COCIDO"
     
            // Actualizar el producto existente
            $product->update([
                'name' => $this->name,
                'cost' => $this->cost,
                'price' => $this->price,
                'barcode' => $this->barcode,
                'stock' => $this->stock,
                'estado' => $this->estado,
                'descripcion' => $this->descripcion,
                'alerts' => $this->alerts,
                'category_id' => $this->categoryid,
                'tam1' => $this->tam1,
            'tam2' => $this->tam2,
                'sabor_id' => $this->saborID,
            ]);
        

        if ($this->image) {
            $customFileName = uniqid() . '_.' . $this->image->extension();
            $this->image->storeAs('public/products', $customFileName);
            $imageTemp = $product->image; // imagen temporal
            $product->image = $customFileName;
            $product->save();

            if ($imageTemp != null) {
                if (file_exists('storage/products/' . $imageTemp)) {
                    unlink('storage/products/' . $imageTemp);
                }
            }
        }
        //$this->updateWooCommerceStock($this->barcode, $this->stock);

        $user = Auth()->user()->name;
        $inspector = Inspectors::create([
            'user' => $user,
            'action' => 'Actualizo : ' . $this->name,
            'seccion' => 'Products'
        ]);
        $this->resetUI();
        $this->emit('product-updated', 'Producto Actualizado');
         $this->emit('global-msg', 'Producto Actualizado');
    }

    private function updateWooCommerceStock($barcode, $stock)
    {
        // Configurar la URL y los datos para la solicitud a la API de WooCommerce
        $url = 'https://kdlatinfood.com/wp-json/wc/v3/products';
        $productId = null;

        // Buscar el producto en WooCommerce por SKU ($barcode)
        $response = Http::withBasicAuth('ck_8e38a879e7f6ce0d56e34c525de194a60c2e2ce5', 'cs_723eab16e53f3607fd38984b00f763310cc4f473')
            ->get($url, ['sku' => $barcode]);

        if ($response->successful()) {
            $products = $response->json();
            if (!empty($products)) {
                // Obtener el ID del producto en WooCommerce
                $productId = $products[0]['id'];
            }
        }

        if ($productId) {
            // Actualizar el stock del producto en WooCommerce
            $response = Http::withBasicAuth('ck_8e38a879e7f6ce0d56e34c525de194a60c2e2ce5', 'cs_723eab16e53f3607fd38984b00f763310cc4f473')
                ->put("$url/$productId", ['stock_quantity' => $stock]);

            if ($response->successful()) {
                // El stock se actualizó correctamente en WooCommerce
                // Puedes realizar alguna acción adicional si lo deseas
                // Por ejemplo, registrar una entrada en los archivos de registro

            } else {
                // Error al actualizar el stock en WooCommerce
                // Puedes manejar el error según tus necesidades

            }
        } else {
            // No se encontró el producto en WooCommerce por SKU
            // Puedes manejar esta situación según tus necesidades
            dd('No se Econtro el producto');
        }
    }
    public function resetUI()
    {
        $this->name = '';
        $this->barcode = '';
        $this->cost = '';
        $this->price = '';
        $this->stock = '';
        $this->alerts = '';
        $this->search = '';
        $this->descripcion = '';
        $this->categoryid = 'Elegir';
        $this->image = null;
        $this->saborID = 'Elegir';
         $this->estado = 'Elegir';
        $this->selected_id = 0;
        $this->resetValidation();
    }

    protected $listeners = [
        'deleteRow' => 'Destroy'

    ];

    public function recargarPagina()
    {
        $this->emit('global-msg', "SE CREO CORRECTAMENTE");
        $this->dispatchBrowserEvent('recargar-pagina');
    }


    public function Destroy(Product $product)
    {
        $user = Auth()->user()->name;
        if ($product->estado == 'PRECOCIDO') {
            $originalProduct = Product::where('barcode', $product->barcode - 1)->first();

            if ($originalProduct && $originalProduct->image == $product->image) {
                // La imagen está asociada solo con el producto "PRECOCIDO"
                // No se debe eliminar la imagen
            } else {
                // La imagen está asociada a otro producto o no existe
                // Se puede eliminar la imagen
                if ($product->image != null) {
                    if (file_exists('storage/products/' . $product->image)) {
                        unlink('storage/products/' . $product->image);
                    }
                }
            }
        } else {
            // Producto no es "PRECOCIDO"
            // Se puede eliminar la imagen asociada
            if ($product->image != null) {
                if (file_exists('storage/products/' . $product->image)) {
                    unlink('storage/products/' . $product->image);
                }
            }
        }

        $product->delete();

        $this->resetUI();
        $this->emit('product-deleted', 'Producto Eliminado');
         $this->emit('global-msg', 'Producto Eliminado');
        $inspector = Inspectors::create([
            'user' => $user,
            'action' => 'Elimnino un producto ',
            'seccion' => 'Products'
        ]);
    }


    /*API */
public function showAll()
{
    try {
        $products = Product::with('category')
            ->where('visible', 'si')
            ->where('TieneKey', 'si')
            ->get()
            ->sortBy(function ($product) {
                return $product->category->name;
            })
            ->values()
            ->all();

        $formattedProducts = [];
        foreach ($products as $product) {
            $formattedProducts[] = [
                'id' => $product->id,
                'name' => $product->name,
                'EstaEnWoocomerce' => $product->EstaEnWoocomerce,
                'barcode' => $product->barcode,
                'sabor_id' => $product->sabor->nombre,
                'cost' => floatval($product->cost),
                'price' => floatval($product->price),
                'stock' => $product->stock,
                'alerts' => $product->alerts,
                'image' => asset('storage/products/' . $product->image),
                'category_id' => $product->category->name,
                'descripcion' => $product->descripcion,
                'estado' => $product->estado,
                'TieneKey' => $product->TieneKey,
                'KeyProduct' => $product->KeyProduct,
                'user_id' => $product->user_id,
                'tam1' => $product->tam1,
                'tam2' => $product->tam2,
            ];
        }

        return response()->json($formattedProducts);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'An error occurred',
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}

public function ProductsPreCocidos()
{
    try {
        $products = Product::with('category')
            ->where('estado', 'PRECOCIDO')
            ->where('visible', 'si')
            ->where('TieneKey', 'si')
            ->get()
            ->sortBy(function ($product) {
                return $product->category->name;
            })
            ->values()
            ->all();

        $formattedProducts = [];
        foreach ($products as $product) {
            $formattedProducts[] = [
                'id' => $product->id,
                'name' => $product->name,
                'EstaEnWoocomerce' => $product->EstaEnWoocomerce,
                'barcode' => $product->barcode,
                'sabor_id' => $product->sabor->nombre,
                'cost' => floatval($product->cost),
                'price' => floatval($product->price),
                'stock' => $product->stock,
                'alerts' => $product->alerts,
                'image' => asset('storage/products/' . $product->image),
                'category_id' => $product->category->name,
                'descripcion' => $product->descripcion,
                'estado' => $product->estado,
                'TieneKey' => $product->TieneKey,
                'KeyProduct' => $product->KeyProduct,
                'user_id' => $product->user_id,
                'tam1' => $product->tam1,
                'tam2' => $product->tam2,
            ];
        }

        return response()->json($formattedProducts);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'An error occurred',
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
public function ProductsCrudos()
{
    try {
        $products = Product::with('category')
            ->where('estado', 'CRUDO')
            ->where('visible', 'si')
            ->where('TieneKey', 'si')
            ->get()
            ->sortBy(function ($product) {
                return $product->category->name;
            })
            ->values()
            ->all();

        $formattedProducts = [];
        foreach ($products as $product) {
            $formattedProducts[] = [
                'id' => $product->id,
                'name' => $product->name,
                'EstaEnWoocomerce' => $product->EstaEnWoocomerce,
                'barcode' => $product->barcode,
                'sabor_id' => $product->sabor->nombre,
                'cost' => floatval($product->cost),
                'price' => floatval($product->price),
                'stock' => $product->stock,
                'alerts' => $product->alerts,
                'image' => asset('storage/products/' . $product->image),
                'category_id' => $product->category->name,
                'descripcion' => $product->descripcion,
                'estado' => $product->estado,
                'TieneKey' => $product->TieneKey,
                'KeyProduct' => $product->KeyProduct,
                'user_id' => $product->user_id,
                'tam1' => $product->tam1,
                'tam2' => $product->tam2,
            ];
        }

        return response()->json($formattedProducts);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'An error occurred',
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
public function showAllKEY()
{
    try {
        $products = Product::with('category')
           //   ->where('visible', 'no')
          ->where('TieneKey', 'si')
            ->get()
            ->sortBy(function ($product) {
                return $product->category->name;
            })
            ->values()
            ->all();

        $formattedProducts = [];
        foreach ($products as $product) {
            $formattedProducts[] = [
                'id' => $product->id,
                'name' => $product->name,
                'EstaEnWoocomerce' => $product->EstaEnWoocomerce,
                'barcode' => $product->barcode,
                'sabor_id' => $product->sabor->nombre,
                'cost' => floatval($product->cost),
                'price' => floatval($product->price),
                'stock' => $product->stock,
               
            ];
        }

        return response()->json($formattedProducts);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'An error occurred',
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}    


    public function createApi(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'cost' => 'required',
                'price' => 'required',
                'stock' => 'required',
                'category_id' => 'required',
            ]);

            $product = Product::create($request->all());

            return response()->json([
                'message' => 'Product created successfully',
                'data' => $product,
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateApi(Request $request, $id)
    {
        try {
            $product = Product::findOrFail($id);

            $request->validate([
                'name' => 'required',
                'cost' => 'required',
                'price' => 'required',
                'stock' => 'required',
                'category_id' => 'required',
            ]);

            $product->update($request->all());

            return response()->json([
                'message' => 'Product updated successfully',
                'data' => $product,
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Product not found',
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function findProductsByCategory($categoryId)
    {
        try {
            $products = Product::whereHas('category', function ($query) use ($categoryId) {
                $query->where('id', $categoryId);
            })->get();

            $responseData = [];

            foreach ($products as $product) {
                $responseData[] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'cost' => $product->cost,
                    'price' => $product->price,
                    'stock' => $product->stock,
                    'image' => asset('storage/products/' . $product->image),
                    'category' => [
                        'id' => $product->category->id,
                        'name' => $product->category->name,
                    ],
                ];
            }

            return response()->json([
                'data' => $responseData,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function findApi($id)
    {
        try {
            $product = Product::with('category')->findOrFail($id);

            return response()->json([
                'data' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'cost' => $product->cost,
                    'price' => $product->price,
                    'stock' => $product->stock,
                    'category' => [
                        'id' => $product->category->id,
                        'name' => $product->category->name,
                    ],
                ],
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Product not found',
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function Addfavorite($id_producto, $id_cliente)
    {
        $favorito = new Favoritos([
            'id_producto' => $id_producto,
            'id_cliente' => $id_cliente,
        ]);
        $favorito->save();

        return response()->json(['message' => 'Producto añadido a favoritos'], 201);
    }

    public function Deletefavorite($id_producto, $id_cliente)
    {
        try {
            $favorito = Favoritos::where('id_producto', $id_producto)
                ->where('id_cliente', $id_cliente)
                ->first();

            if ($favorito) {
                $favorito->delete();
                return response()->json(['message' => 'Producto eliminado de favoritos'], 200);
            } else {
                return response()->json(['message' => 'Producto no encontrado en favoritos'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al eliminar producto de favoritos'], 500);
        }
    }

    public function GetAllfavorite($id_cliente)
    {
        try {
            $favoritos = Favoritos::where('id_cliente', $id_cliente)
                ->with('producto')
                ->get();

            if ($favoritos->isEmpty()) {
                return response()->json(['message' => 'El cliente no tiene productos favoritos'], 404);
            }

            $productosFavoritos = $favoritos->pluck('producto');

            return response()->json($productosFavoritos, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'El cliente no existe'], 404);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Error al obtener productos favoritos'], 500);
        }
    }

    public function AddCart($id_producto, $id_cliente, $items)
    {
        try {
            $carrito = Carrito::where('id_producto', $id_producto)
                ->where('id_cliente', $id_cliente)
                ->first();

            if ($carrito) {
                $carrito->items += $items;
                $carrito->save();
            } else {
                $carrito = new Carrito([
                    'id_producto' => $id_producto,
                    'id_cliente' => $id_cliente,
                    'items' => $items,
                ]);
                $carrito->save();
            }

            return response()->json(['message' => 'Producto(s) agregado(s) al carrito'], 201);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Producto o cliente no encontrado'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al agregar producto al carrito'], 500);
        }
    }
    public function DeleteItemCart($id_producto, $id_cliente)
    {
        try {
            $carrito = Carrito::where('id_producto', $id_producto)
                ->where('id_cliente', $id_cliente)
                ->first();

            if ($carrito) {
                $carrito->delete();
                return response()->json(['message' => 'Producto eliminado del carrito'], 200);
            } else {
                return response()->json(['message' => 'Producto no encontrado en el carrito'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al eliminar producto del carrito'], 500);
        }
    }
    public function UpdateItemCart($id_producto, $id_cliente, $items)
    {
        try {
            $carrito = Carrito::where('id_producto', $id_producto)
                ->where('id_cliente', $id_cliente)
                ->first();

            if ($carrito) {
                $carrito->items += $items; // Sumamos la cantidad actual con la nueva cantidad
                $carrito->save();
                return response()->json(['message' => 'Cantidad de items actualizada'], 200);
            } else {
                return response()->json(['message' => 'Producto no encontrado en el carrito'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al actualizar cantidad de items'], 500);
        }
    }

    public function GetAllCart($id_cliente)
    {
        try {
            $carrito = Carrito::where('id_cliente', $id_cliente)
                ->with('producto')
                ->get();

            if ($carrito->isEmpty()) {
                return response()->json(['message' => 'El cliente no tiene productos en el carrito'], 404);
            }

            $totalProductos = $carrito->sum('items'); // Calculamos la cantidad total de productos en el carrito

            $response = [
                'message' => 'Carrito del cliente obtenido exitosamente',
                'totalProductos' => $totalProductos,
                'carrito' => $carrito,
            ];

            return response()->json($response, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al obtener productos del carrito'], 500);
        }
    }
    public function DecrementItemCart($id_producto, $id_cliente, $items)
    {
        try {
            $carrito = Carrito::where('id_producto', $id_producto)
                ->where('id_cliente', $id_cliente)
                ->first();

            if ($carrito) {
                $nuevaCantidad = $carrito->items - $items;

                if ($nuevaCantidad < 0) {
                    return response()->json(['message' => 'La cantidad no puede ser negativa'], 400);
                }

                if ($nuevaCantidad == 0) {
                    // Si la nueva cantidad es 0, eliminamos el producto del carrito
                    $carrito->delete();
                } else {
                    // Si la nueva cantidad no es 0, actualizamos la cantidad de items en el carrito
                    $carrito->items = $nuevaCantidad;
                    $carrito->save();
                }

                return response()->json(['message' => 'Cantidad de items actualizada'], 200);
            } else {
                return response()->json(['message' => 'Producto no encontrado en el carrito'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al actualizar cantidad de items'], 500);
        }
    }
}
