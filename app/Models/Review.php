<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;
    protected $fillable = ['vehicle_id', 'user_id', 'message', 'stars'];
    public function vehicle()
    {
        return $this->belongsTo(Rental::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
