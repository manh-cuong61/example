<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use App\Models\Product;

class Category extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $guarded = [];
    protected $table = 'categories';
    use Sluggable;
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => ['name']
            ]
        ];
    }

    public function products(){
        return $this->hasMany(Product::class, 'category_id', 'id');
    }
}
