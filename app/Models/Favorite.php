<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'product_id','user_id','del_flg'
    ];
    protected $primaryKey = 'product_favorite_id';
    protected $table = 'tbl_favorite';
    public function getProductFavorite(){
        return $this->belongsTo(Product::class,'product_id','product_id');
    }
}
