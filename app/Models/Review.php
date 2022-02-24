<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;
    protected $fillable=['rental_id','user_id','message','stars'];
    public function rental(){
        $this->belongsTo(Rental::class);
    }
    public function user(){
        $this->belongsTo(User::class);
    }
}
