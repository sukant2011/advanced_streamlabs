<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'braintree_plan', 'cost', 'description'];

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
