<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = ['name', 'longitude', 'latitude'];

    use HasFactory;

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }
}
