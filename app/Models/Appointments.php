<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointments extends Model
{
    // Attributes that are mass assignable
    protected $fillable = [ 'date', 'name' ];
}