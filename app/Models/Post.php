<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    // public $timestamps = false;
    protected $fillable = [
        'customer_id','category_mxh_id','post_desc','post_content','post_like','post_image'
    ];
    protected $primaryKey = 'post_id';
    protected $table = 'tbl_post';
    public function author(){
        return $this->belongsTo(Customer::class,'customer_id');
    }
    public function commentPost(){
        return $this->belongsTo(CommentPost::class,'comment_post_id','post_id');
    }
    public function mxh(){
        return $this->belongsTo(CommentPost::class,'category_mxh_id');
    }
}
