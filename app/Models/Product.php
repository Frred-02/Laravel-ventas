<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    /*comentario */
    protected $fillable =['name','barcode','cost','price','stock','alerts','image','category_id'];

    public function category()
    {
        return $this->belongsTO(category::class);
    }

    public function  getImagenAttribute()
    {
        if($this->image !=null )
        return   (file_exists('storage/products/' . $this->image ) ? $this->image : 'noimg.jpg');
        else
        return 'noimg.jpg';
    }
}
