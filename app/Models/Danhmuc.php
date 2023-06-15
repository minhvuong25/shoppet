<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Danhmuc extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'danhmuc_name','danhmuc_desc','danhmuc_status'
    ];
    protected $primaryKey = 'danhmuc_id';
    protected $table = 'tbl_danhmuc';
}
