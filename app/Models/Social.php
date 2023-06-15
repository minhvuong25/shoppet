<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;

class Social extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'provider_user_id', 'provider', 'user', 'provider_user_email'
    ];

    protected $primaryKey = 'user_id';
    protected $table = 'tbl_social';
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'user');
    }
}
