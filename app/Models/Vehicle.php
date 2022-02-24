<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;
    protected $fillable=['name','vendor_id','type_id','model','color','total_seats','rental_price','description','terms','image','condition','is_available','has_driver','is_approved'];




    public function Vendor(){
        return $this->bellongsTo(Vendor::class);
    }
    public function Type(){
        return $this->belongsTo(Types::class);
    }

}
