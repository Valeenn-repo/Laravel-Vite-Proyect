<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Make;

class CarModel extends Model
{
    public function make()
    {
        return $this->belongsTo(Make::class);
    }
}


