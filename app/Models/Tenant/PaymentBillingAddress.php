<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentBillingAddress extends Model
{
    use HasFactory;
    protected $fillable = ['streetAddress', 'city', 'postalCode', 'country', 'state'];
}
