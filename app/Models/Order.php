<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_name',
        'customer_email',
        'customer_phone',
        'total_amount',
        'status'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
    ];

    public function orderItems():HasMany  
    {
        return $this->hasMany(OrderItem::class);
    }

    // use helper method to get stores through order items
    public function stores()
    {
        return $this->hasManyThrough(
            Store::class, 
            OrderItem::class, 
            'order_id', 
            'id', 
            'id', 
            'store_id')->distinct();
    }
}