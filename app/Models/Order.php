<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'customer_name', 'customer_email', 'customer_mobile', 'status', 'request_id', 'process_url', 'product_id'
    ];

    // Una orden le pertenece un producto
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
