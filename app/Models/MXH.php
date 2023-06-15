<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MXH extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'category_mxh_name','category_mxh_desc','category_mxh_status'
    ];
    protected $primaryKey = 'category_mxh_id';
    protected $table = 'tbl_category_mxh';
    public function  post(){
        return $this->hasMany(Post::class, 'category_mxh_id');
    }
}
