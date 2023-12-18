<?php

namespace App\Http\Controllers;

use Iodev\Whois\Factory;
use Iodev\Whois\Modules\Tld\TldServer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DomainCheckerController extends Controller
{
    public function check(Request $request)
    {
        // return gettype($request->domain);
        $whois = Factory::get()->createWhois();
        try {
            $data = json_decode($request->domain);
            $domain = $request->domain;

            if ($data != null) {
                $newData = [];
                foreach ($data as $key => $value) {
                    if ($whois->isDomainAvailable($value->name)) {
                        array_push($newData, ['name' => $value->name, 'availability' => 1]);
                    }
                }
                return response()->json(['available' => $newData, 'status' => 200]);
            } elseif (!empty($domain)) {
                if ($whois->isDomainAvailable($domain)) {
                    return response()->json(['message' => "The domain available", 'status' => 1]);
                }
                return response()->json(['message' => "The domain is not available", 'status' => 0]);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Domain Name Checker on the local
     *
     * Tenant App\Models\Tenant
     * @param Request $request
     * @return json
     */
    public function checkLocalDomain (Request $request) {
        $domain = $request->domain;
        if(!empty($domain)) {
            $cleanedDomain = str_replace(".com", "", $domain);
            $domainToCheck = str_replace(".store", "", $cleanedDomain);
            $checker = DB::table('domains')->where('domain', $domainToCheck)->first();
            if (empty($checker)) {
                return response()->json(['passed' => 1]);
            } else {
                return response()->json(['passed' => 0]);
            }
        }
    }
}