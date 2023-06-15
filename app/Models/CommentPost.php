<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentPost extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'comment_name','comment','comment_date','comment_post_id','comment_parent_comment'
    ];
    protected $primaryKey = 'comment_id';
    protected $table = 'tbl_post_comment';

    public function post(){
        return $this->belongsTo(Post::class,'comment_post_id','post_id');
    }
}
