<?php

namespace App\Http\Controllers\Tenant;

use App\Models\Tenant;
use Illuminate\Http\Request;
use App\Models\Tenant\MetaSEO;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Config;

class MetaSEOController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string'],
            'description' => ['required', 'string'],
            'keyword' => ['required', 'string'],
        ]);
        $userId = auth()->user()->id;
        $tenants = new Tenant();
        $tenant = $tenants->where('user_id', $userId)->first();
        $tenantDb =$tenant->tenancy_db_name;
        Config::set('database.connections.mysql.database', $tenantDb);

        DB::connection('mysql')->reconnect();
        DB::setDatabaseName($tenantDb);

        $meta = MetaSEO::create([
            'title' => $request->title,
            'description' => $request->description,
            'keyword' => $request->keyword,
        ]);

        return response()->json(['message' => 'New Meta added.', 'meta' => $meta], 201);
    }

    private function stripSpecialChars($inputString)
    {
        $cleanedString = preg_replace('/[^A-Za-z0-9]/', '', $inputString);

        // Remove spaces
        $cleanedString = str_replace(' ', '', $cleanedString);

        return $cleanedString;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MetaSEO  $metaSEO
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $metaSEO)
    {
        $request->validate([
            'title' => ['required', 'string'],
            'image' => ['required'],
            'description' => ['nullable', 'string'],
        ]);

        $name = $this->stripSpecialChars($request->title).'.png';
        $save_path = public_path() . '/media/tenants/' . strtolower(tenant('id')) . '/img/meta/';

        if (!file_exists($save_path)) {
            mkdir($save_path, 0755, true);
        }

        $file = $save_path . $name;

        Image::make(file_get_contents($file))->fit(1200, 630, function ($constraint) {})->save($file);

        $meta = MetaSEO::find($metaSEO)->update([
            'title' => $request->title,
            'description' => $request->description,
            'image' => '/media/tenants/' . strtolower(tenant('id')) . '/img/meta/' . $name,
        ]);

        return response()->json(['message' => 'Updated.', 'meta' => $meta], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MetaSEO  $metaSEO
     * @return \Illuminate\Http\Response
     */
    public function destroy(MetaSEO $metaSEO)
    {
        //
    }
}
