<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    protected $fillable = [
       "product_id",
        "title",
        "make",
        "category_id",
        "description",
        "details",
        "image",
        "image_name",
        "price",
        "is_featured",
        "status"
    ];

    public function scopeCategory($query,$category_id)
    {
        return $query->select('product_id','id',
            'title','price','image_name','description')
            ->where('category_id', '=', $category_id);

    }

    public function scopeGetID($query,$slug)
    {
        return $query->where('product_id', '=', $slug);

    }

    public function scopeFeatured($query)
    {
        return $query->select('product_id','id',
            'title','price','image_name','description')->where('is_featured', '=', "1")
            ->orderBy('id', 'DESC')->take(9)->skip(0)->get();
    }

    public function scopePriceFromProductID($query,$product_id)
    {
        return $query->select('price')
            ->where('product_id', '=', $product_id);

    }

    public function scopeProductSummaryProductID($query,$product_id)
    {
        return $query->select('title','price','image_name','description')
            ->where('product_id', '=', $product_id);

    }

}