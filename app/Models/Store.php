<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;


/**
 * @property-read \App\Models\User $user
 */
class Store extends Model
{
    use HasFactory;
    protected $fillable=[
        'user_id',
        'name',
        'description'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function products():HasMany
    {
        return $this->hasMany(Product::class);
    }
    public function orderItems():HasMany 
    {
        return $this->hasMany(OrderItem::class);
    }

}
