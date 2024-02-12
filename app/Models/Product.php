<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\Lotes;
use App\Models\Sabores;
use App\Models\User;
class Product extends Model
{
	use HasFactory;
	

	protected $fillable = [
		'id',
		'name',
		'EstaEnWoocomerce',
		'barcode',
		'sabor_id',
		'cost',
		'price',
		'stock',
		'alerts',
		'image',
		'category_id',
		'descripcion',
		'estado',
		'TieneKey',
		'KeyProduct',
		'user_id',
		'visible',
		'tam2',
		'tam1'
	];


 public function sabor()
    {
        return $this->belongsTo(Sabores::class, 'sabor_id');
    }
     public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
 public function categoria(){
        return $this->belongsTo(Product::class, 'category_id', 'id');
    }

 public function producto(){
        return $this->belongsTo(Product::class);
    }

	public function category()
	{
		return $this->belongsTo(Category::class);
	}
  public function lots()
    {
        return $this->belongsTo(Lotes::class);
    }
	public function ventas()
	{
		return $this->hasMany(SaleDetail::class);
	}


	public function getImagenAttribute()
	{	
		if($this->image != null)
			return (file_exists('storage/products/' . $this->image) ? $this->image : 'noimg.jpg');
		else
			return 'noimg.jpg';		
		
	}

public function showImage(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $path = storage_path('app/public/storaje/' . $product->image);

        if (file_exists($path)) {
            $file = file_get_contents($path);
            $type = mime_content_type($path);

            return response()->json([
                'type' => $type,
                'data' => base64_encode($file),
            ]);
        } else {
            return response()->json([
                'error' => 'Image not found',
            ]);
        }
    }

	public function getPriceAttribute($value)
	{
		//comma por punto
		//return str_replace('.', ',', $value);
		// punto por coma
		return str_replace(',', '.', $value);
	}
	public function setPriceAttribute($value)
	{
        //$this->attributes['price'] = str_replace(',', '.', $value);
		$this->attributes['price'] =str_replace(',', '.', preg_replace( '/,/', '', $value, preg_match_all( '/,/', $value) - 1));

	}


	


}
