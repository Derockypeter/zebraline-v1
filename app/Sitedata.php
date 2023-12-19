<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sitedata extends Model
{
    use HasFactory;

    protected $fillable = ['bodyFont', 'headlineFont', 'backgroundColor', 'buttonColor', 'logo', 'favicon', 'name', 'aboutus', 'language', 'currency', 'measurement', 'country', 'state', 'timezone'];
}
