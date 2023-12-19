<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetaSEO extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'description', 'keyword'];
}
