<?php

namespace App\Http\Controllers\Tenant;

use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\Tenant\SiteVisibility;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

class SiteVisibilityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function checkPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required',
        ]);
        

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()], 400);
        } else {
            try {
                $inputs = $validator->validated();
                
                $siteVisibility = new SiteVisibility();
                if (Hash::check($inputs['password'], $siteVisibility->first()->password)) {
                    // Store the password in the session for the guest user
                    session(['guest_password' => $request->password]);
                    return redirect()->route('userHome');
                }
                else {
                    return response()->json(['message' => 'Incorrect password, please contact admin/site owner'], 500);
                }
            } catch (\Exception $e) {
                return response()->json(['message' => 'Internal server error', 'error' => $e->getMessage()], 500);
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeOrUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'siteVisibility' => 'required',
            'ipAddress' => 'nullable',
            'password' => 'nullable',
        ]);
        

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()], 400);
        } else {
            try {
                $inputs = $validator->validated();
                $userId = auth()->user()->id;
                $tenants = new Tenant();
                $tenant = $tenants->where('user_id', $userId)->first();
                $tenantDb =$tenant->tenancy_db_name;
                Config::set('database.connections.mysql.database', $tenantDb);

                DB::connection('mysql')->reconnect();
                DB::setDatabaseName($tenantDb);

                $siteVisibility = new SiteVisibility();
                if ($inputs['siteVisibility'] == 'password') {
                    $inputs['password'] = Hash::make($inputs['password']);
                }
                if ($siteVisibility->first() === null) {
                    $data = $siteVisibility->create($inputs);
                } else {
                    $data = $siteVisibility->first()->update($inputs);
                }
    
                if (!$data) {
                    return response()->json(['message' => 'Problem creating website visibility, please try again'], 500);
                }
    
                return response()->json([
                    'message' => 'Success!!!',
                    'data' => $data,
                ], 200);
            } catch (\Exception $e) {
                return response()->json(['message' => 'Internal server error', 'error' => $e->getMessage()], 500);
            }
        }
    }
}
