<?php

namespace App\Http\Controllers\Tenant;

use App\Models\Tenant;
use App\Models\Tenant\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;

class ImageController extends Controller
{
    public function unlinkImage(Request $request)
    {
        // dd($request['url']);
        $userId = auth()->user()->id;
        $tenants = new Tenant();
        $tenant = $tenants->where('user_id', $userId)->first();
        $tenantDb =$tenant->tenancy_db_name;
        Config::set('database.connections.mysql.database', $tenantDb);

        DB::connection('mysql')->reconnect();
        DB::setDatabaseName($tenantDb);
        $url = $request['url'];
        $path = public_path($url); // Change to your image path

        // Unlink (delete) the image file
        if (file_exists($path)) {
            $image = Image::find($request['id']);

            if ($image) {
                $image->delete();
            }
            unlink($path);
            return response()->json(['status' => 'Content removed'], 204);
        }
    }
}
