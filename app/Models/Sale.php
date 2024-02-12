<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;
use App\Models\Envio;
use App\Models\User;
class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'woocommerce_order_id',
        'total','items','cash','change','status','user_id','status_envio','CustomerID','editado'];

        public function customer()
        {
            return $this->belongsTo(Customer::class, 'CustomerID');
        }
        

    public function user()
    {
        return $this->belongsTo(User::class);
    }
     public function client(){
        return $this->belongsTo(Customer::class, 'CustomerID','id');
    }
   public function sales(){
        return $this->belongsTo(Envio::class, 'id_sale','id');
    }
    public function salesDetails()
{
    return $this->hasMany(SaleDetail::class, 'sale_id');
}


    // MUTATORS
    /*
    public function setTotalAttribute($value)
    {
        $priceBeforeSave = $this->attributes['total'];

        $priceFilter  = filter_var($priceBeforeSave, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

        $this->attributes['total'] = $priceFilter;
    }
    */

}
