<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Tag extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'tags';
    
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_tags','tag_id' ,'product_id');
    }
}
