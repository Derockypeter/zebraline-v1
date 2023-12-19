<?php

namespace App\Http\Controllers\Tenant;

use App\Models\Tenant;
use Illuminate\Http\Request;
use App\Models\Tenant\Sitedata;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

class SitedataController extends Controller
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
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeFaviconLogo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'favicon' => 'nullable',
            'logo' => 'nullable',
            'name' => 'nullable'
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
                if ($request->hasFile('logo') && $request->logo !== null) {
                    $imageToSave = $request->file('logo');
                    $safeName = strtolower($tenant->id.'logo') . '.png';
                    $savePath = public_path(). '/media/tenants/' . strtolower($tenant->id) . '/img/';

                    if (!file_exists($savePath)) {
                        mkdir($savePath, 0755, true);
                    }

                    $file = $savePath . $safeName;

                    // Process and save the new image

                    // Delete the previous image if it exists
                    if (file_exists(public_path($file))) {
                        unlink(public_path($file));
                    }
                    Image::make($imageToSave)->save($file);
                    $inputs['logo'] = '/media/tenants/'. strtolower($tenant->id) .'/img/'.$safeName; 
                }
                if ($request->hasFile('favicon') && $request->favicon !== null) {
                    $imageToSave = $request->file('favicon');
                    $safeName = strtolower($tenant->id.'favicon') . '.png';
                    $savePath = public_path(). '/media/tenants/' . strtolower($tenant->id) . '/img/';

                    if (!file_exists($savePath)) {
                        mkdir($savePath, 0755, true);
                    }

                    $file = $savePath . $safeName;

                    // Process and save the new image

                    // Delete the previous image if it exists
                    if (file_exists(public_path($file))) {
                        unlink(public_path($file));
                    }
                    Image::make($imageToSave)->save($file);
                    $inputs['favicon'] = '/media/tenants/'. strtolower($tenant->id) .'/img/'.$safeName; 
                }
                $updatedSiteData = $sitedata->take(1)->first()->update($inputs);
    
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
     * Remove the specified resource from storage.
     */
    public function destroy(Sitedata $sitedata)
    {
        //
    }
}
