<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
// use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant\SiteData;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;


class SiteDataController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeFaviconLogo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'favicon' => 'nullable',
            'logo' => 'required',
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
    
                $sitedata = new SiteData();
    
                $updatedSiteData = $sitedata->first()->update($inputs);
    
                if (!$updatedSiteData) {
                    return response()->json(['message' => 'Problem creating your website, please try again'], 500);
                }
    
                return response()->json([
                    'message' => 'Success!!!',
                    'data' => $updatedSiteData,
                ], 200);
            } catch (\Exception $e) {
                return response()->json(['message' => 'Internal server error', 'error' => $e->getMessage()], 500);
            }
        }
    }

    public function storeOthers(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'language' => 'nullable',
            'currency' => 'nullable',
            'measurement' => 'nullable',
            'country' => 'nullable',
            'state' => 'nullable',
            'timezone' => 'nullable',
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

                $sitedata = new SiteData();
    
                $updatedSiteData = $sitedata->first()->update($inputs);
    
                if (!$updatedSiteData) {
                    return response()->json(['message' => 'Problem creating your website, please try again'], 500);
                }
    
                return response()->json([
                    'message' => 'Success!!!',
                    'data' => $updatedSiteData,
                ], 200);
            } catch (\Exception $e) {
                return response()->json(['message' => 'Internal server error', 'error' => $e->getMessage()], 500);
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeFontColor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bodyFont' => 'nullable',
            'headlineFont' => 'nullable',
            'backgroundColor' => 'nullable',
            'buttonColor' => 'nullable',
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
    
                // Create a new Tenant
                $newSiteData = SiteData::create($inputs);
    
                if (!$newSiteData) {
                    return response()->json(['message' => 'Problem creating your website, please try again'], 500);
                }
    
                return response()->json([
                    'message' => 'Success!!!',
                    'data' => $newSiteData,
                ], 201);
            } catch (\Exception $e) {
                return response()->json(['message' => 'Internal server error', 'error' => $e->getMessage()], 500);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SiteData  $siteData
     * @return \Illuminate\Http\Response
     */
    public function show(SiteData $siteData)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SiteData  $siteData
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SiteData $siteData)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SiteData  $siteData
     * @return \Illuminate\Http\Response
     */
    public function destroy(SiteData $siteData)
    {
        //
    }
}
