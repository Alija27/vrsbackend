<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rental extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['user_id', 'vehicle_id', 'start_date', 'end_date', 'destination', 'is_approved', 'is_complete', 'total_amount', 'remarks'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vehicle()
    {
        return  $this->belongsTo(Vehicle::class);
    }
}
