<?php

namespace App\Http\Controllers\Tenant;

use App\Models\Tenant;
use Illuminate\Http\Request;
use App\Models\Tenant\MetaSEO;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use App\Models\Tenant\PaymentBillingAddress;
use Illuminate\Support\Facades\Validator;

class PaymentBillingAddressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'streetAddress' => ['required', 'string'],
            'city' => ['required', 'string'],
            'country' => ['required', 'string'],
            'state' => ['required', 'string'],
            'postalCode' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()], 400);
        } else {
            $userId = auth()->user()->id;
            $tenants = new Tenant();
            $tenant = $tenants->where('user_id', $userId)->first();
            $tenantDb =$tenant->tenancy_db_name;
            Config::set('database.connections.mysql.database', $tenantDb);
    
            DB::connection('mysql')->reconnect();
            DB::setDatabaseName($tenantDb);
    
            $inputs = $validator->validated();
            $paymentBillingAddress = PaymentBillingAddress::create($inputs);
        }

        return response()->json(['message' => 'New Billing added.', 'meta' => $paymentBillingAddress], 201);   
    }

    /**
     * Display the specified resource.
     */
    public function show(PaymentBillingAddress $paymentBillingAddress)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PaymentBillingAddress $paymentBillingAddress)
    {
        //
    }
}
