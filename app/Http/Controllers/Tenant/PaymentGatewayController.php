<?php

namespace App\Http\Controllers\Tenant;

use App\Models\Tenant;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Tenant\PaymentGateway;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

class PaymentGatewayController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->user()->id;
        $tenants = new Tenant();
        $tenant = $tenants->where('user_id', $userId)->first();
        $tenantDb =$tenant->tenancy_db_name;
        Config::set('database.connections.mysql.database', $tenantDb);

        DB::connection('mysql')->reconnect();
        DB::setDatabaseName($tenantDb);
        return response()->json(PaymentGateway::all(), 200);
    }


    // For SAVING VENDOR KEYS
    public function store(Request $request)
    {
        $data = json_decode($request->data, true);
        $rules = [
            'provider' => 'required',
            'public_key' => 'required',
            'secret_key' => 'required',
            'email' => 'nullable',
            'callback' => 'nullable',
            'encryption_key' => 'nullable',
            'default' => 'nullable'
        ];

        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            return response() ->json(['errors' => $validator->errors()], 422);
        } else {
            $validated = $validator->validated();
            $userId = auth()->user()->id;
            $tenants = new Tenant();
            $tenant = $tenants->where('user_id', $userId)->first();
            $tenantDb =$tenant->tenancy_db_name;
            Config::set('database.connections.mysql.database', $tenantDb);
    
            DB::connection('mysql')->reconnect();
            DB::setDatabaseName($tenantDb);
            $paymentGateway = PaymentGateway::create([
                'provider' => $validated['provider'],
                'data' => json_encode($request->data),
                'default' => $validated['default'] ?? '0',
            ]);

            if ($paymentGateway) {
                return response()->json(['status' => 'Successful', 'data' => $paymentGateway], 201);
            } else {
                return response()->json(['status' => 'Unable to save payment'], 500);
            }
        }
    }

    public function updateDefault(Request $request) {
        $id = $request->id;
        if($id) {
            $userId = auth()->user()->id;
            $tenants = new Tenant();
            $tenant = $tenants->where('user_id', $userId)->first();
            $tenantDb =$tenant->tenancy_db_name;
            Config::set('database.connections.mysql.database', $tenantDb);
    
            DB::connection('mysql')->reconnect();
            DB::setDatabaseName($tenantDb);
            $payments = new PaymentGateway();
            $payments->where('default', '1')->update(['default' => '0']);
            $payments->find($id)->update(['default'=> '1']);
            return response()->json($payments->all(), 200);
        }
    }


    // Makes an api call to flutterwave to verify payment
    public function verifyPayment(Request $request)
    {
        $provider = $request->provider;
        $userId = auth()->user()->id;
        $tenants = new Tenant();
        $tenant = $tenants->where('user_id', $userId)->first();
        $tenantDb =$tenant->tenancy_db_name;
        Config::set('database.connections.mysql.database', $tenantDb);

        DB::connection('mysql')->reconnect();
        DB::setDatabaseName($tenantDb);
        $check = PaymentGateway::where('provider', $provider)->first();
        switch ($provider) {
            case 'paystack':
                $curlUrl = "https://api.paystack.co/transaction/verify/$request->reference";
                $key = json_decode($check->data);
                $sec_key = json_decode($key)->secret_key;
                break;
            case 'flwave':
                $curlUrl = "https://api.flutterwave.com/v3/transactions/$request->reference/verify";
                $key = json_decode($check->data);
                $sec_key = json_decode($key)->secret_key;
                break;
            default:
                break;
        }

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $curlUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Authorization: Bearer " . $sec_key,
                "Cache-Control: no-cache",
            ),
          ));


        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo $response;
        }
    }

    public function checkDefaultGateway()
    {
        #TODO TEST REMOVE LATEST
        $paymentDefault = PaymentGateway::where('default', '1')->first();
        if ($paymentDefault !== null) {
            $provider = $paymentDefault->provider;
            $public_key = json_decode($paymentDefault->data);
            $key = json_decode($public_key)->public_key;
            return response()->json(['public_key' => $key, 'provider' => $provider]);
        }
    }
}
