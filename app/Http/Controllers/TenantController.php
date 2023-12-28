<?php

namespace App\Http\Controllers;

use Ramsey\Uuid\Uuid;
use App\Models\Tenant;

use App\Models\TenantOtp;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Tenant\SiteVisibility;
use App\Models\Tenants\MetaSEO;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Stancl\Tenancy\Exceptions\DomainOccupiedByOtherTenantException;

class TenantController extends Controller
{
    private function generatedCode()
    {
        return Uuid::uuid4()->toString();
    }
    private function generateCodeOtp()
    {
        return rand(100000, 999999);
    }

    public function create(Request $request)
    {
        // Validate the request data
        $validator = $this->validateRequest($request);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()], 400);
        }

        try {
            $domain = $validator->validated()['domain'];
            $domainConnect = $validator->validated()['domainConnect'];
            $customEmail = $validator->validated()['customEmail'];
            $parts = explode('.', $domain);
            $result = $parts[0];

            // Create a new Tenant
            $newTenant = Tenant::create([
                'user_id' => auth()->user()->id,
                'domainName' => $domain,
                'customEmail' => $customEmail,
                'domainConnect' => $domainConnect,
            ]);

            if (!$newTenant) {
                return response()->json(['message' => 'Problem creating your website, please try again'], 500);
            }

            // Create a new Domain
            $newDomain = $newTenant->domains()->create(['domain' => $result]);

            return response()->json([
                'message' => 'Success!!!',
                'tenant' => $newTenant,
                'domain' => $newDomain,
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Internal server error', 'error' => $e->getMessage()], 500);
        }
    }

    public function createComponentsForTenant(Request $request) {
        $validator = Validator::make($request->all(), [
            "navBarTemplateId" => 'required|integer',
            "heroTemplateId" => 'required|integer',
            "categoryTemplateId" => 'required|integer',
            "featuredProductsTemplateId" => 'required|integer',
            "blogTemplateId" => 'required|integer',
            "offersTemplateId" => 'required|integer',
            "reviewsTemplateId" => 'required|integer',
            "sellingPointTemplateId" => 'required|integer',
            "footerPointTemplateId" => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()], 400);
        }

        try {
            $inputs = $validator->validated();
            $userId = auth()->user()->id;
            $tenants = new Tenant();
            $tenant = $tenants->where('user_id', $userId)->first();
            $tenantDb =$tenant->tenancy_db_name;
            Config::set('database.connections.mysql.database', $tenantDb);

            DB::connection('mysql')->reconnect();
            DB::setDatabaseName($tenantDb);
            $data = [
                'navbar_tempId' => $inputs['navBarTemplateId'],
                'hero_tempId' => $inputs['heroTemplateId'],
                'category_tempId' => $inputs['categoryTemplateId'],
                'featured_tempId' => $inputs['featuredProductsTemplateId'],
                'blog_tempId' => $inputs['blogTemplateId'],
                'offer_tempId' => $inputs['offersTemplateId'],
                'sellin_point_tempId' => $inputs['sellingPointTemplateId'],
                'review_tempId' => $inputs['reviewsTemplateId'],
                'footer_tempId' => $inputs['footerPointTemplateId'],
            ];
            $newData = DB::table('components')->insert($data);
            if (!$newData) {
                return response()->json(['message' => 'Problem creating your website, please try again'], 500);
            }

            return response()->json([
                'message' => 'Success!!!',
            ], 201);
        }
        catch (\Exception $e) {
            return response()->json(['message' => 'Internal server error', 'error' => $e->getMessage()], 500);
        }
    }

    public function reverseTenantCreate($tenant) {
        if ($tenant) {
            $tenants = new Tenant();
            $tenantToRemove = $tenants->where('id', $tenant)->delete();
            if ($tenantToRemove) {
                DB::connection()->statement("DROP DATABASE IF EXISTS `tenant$tenant`");
                return response()->json('Deleted', 204);
            }
        }
    }

    public function getdomainName() {
        if (auth()->check()) {
            $tenant = Tenant::where('user_id', auth()->user()->id)->first();

            return response()->json(['message' => 'Success!', 'domain' => $tenant->domains[0]->domain]);
        }
    }

    // Helper method to validate the request data
    private function validateRequest(Request $request)
    {
        return Validator::make($request->all(), [
            'domain' => 'required',
            'customEmail' => 'required',
            'domainConnect' => 'required'
        ]);
    }

    // Helper method to check if the brandName is available
    private function isBrandNameAvailable($brandName)
    {
        return !Tenant::where('id', $brandName)->exists()
            && !DB::table('domains')->where('domain', $brandName)->exists();
    }

    public function dashboard()
    {
        if (!Auth::check()) {
            $tenancies = new Tenant();
            $tenant = $tenancies->find(tenant('id'));
            $data = json_decode($tenant->site_data);
            $theme_color = $data->themeColor;
            $brand_name = $data->brandName;
            return view('websites.auth.login', compact('theme_color', 'brand_name'));
        } else {
            return redirect()->intended();
        }
    }

    public function user() {
        $data = tenant();
        $tenant = $data->user;
        $tenantId = $data->id;
        $email = $data->user->email;
        return response()->json(['user' => $tenant->id, 'tenantId' => $tenantId, 'email' => $email], 200);
    }

    // Renders / of template
    public function template(Request $request)
    {
        $visibility = new SiteVisibility();
        $visibilityCheck = $visibility->first();

        $passwordRequired = true; // Logic to determine if password is required
        $siteVisibility = "public";
        if ($visibilityCheck !== null) {
            $siteVisibility = $visibilityCheck->siteVisibility;
            if ($siteVisibility !== 'password') {
                $passwordRequired = false;
            }
        }
        $storedPassword = session('guest_password');
    
        // Check if password is stored in the session
        if ($passwordRequired && !$storedPassword) {
            // Redirect back to password entry form if password is required but not stored
            return redirect()->route('password.entry');
        }
        // Get the template_id and get its details
        $tenancies = new Tenant();
        $tenant = $tenancies->find(tenant('id'));
        $templatesToRend = DB::table('components')->first();
        
        $navbar_tempId = $templatesToRend->navbar_tempId;
        $hero_tempId = $templatesToRend->hero_tempId;
        $category_tempId = $templatesToRend->category_tempId;
        $featured_tempId = $templatesToRend->featured_tempId;
        $blog_tempId = $templatesToRend->blog_tempId;
        $offer_tempId = $templatesToRend->offer_tempId;
        $sellin_point_tempId = $templatesToRend->sellin_point_tempId;
        $review_tempId = $templatesToRend->review_tempId;
        $footer_tempId = $templatesToRend->footer_tempId;
        $data = json_decode($tenant->site_data);
        // $theme_color = $data->themeColor;
        // $brand_name = $data->brandName;
        // $brandDesc = $data->brandDesc;
        $storeTypeId = $tenant->storetype_id;
        // $metaData = MetaSEO::first();
        $email = DB::connection('mysql')->table('users')->where('id', tenant()->user_id)->first('email');
        if ($email !== null) {
            $email = $email->email;
        }
        // // Checks if  a user is subscribed
        // $userSubscribed = $tenant->user->subscribed('premium');
        return view('websites.renderer', compact('siteVisibility', 'review_tempId', 'footer_tempId', 'sellin_point_tempId', 'offer_tempId', 'hero_tempId', 'category_tempId', 'featured_tempId', 'blog_tempId', 'navbar_tempId', 'email', 'storeTypeId')); // , 'metaData'

    }

    public function signup()
    {
        if (!Auth::check()) {
            $tenancies = new Tenant();
            $tenant = $tenancies->find(tenant('id'));
            $data = json_decode($tenant->site_data);
            $theme_color = $data->themeColor;
            $brand_name = $data->brandName;
            return view('websites.auth.signup', compact('theme_color', 'brand_name'));
        } else {
            return redirect()->intended();
        }
    }

    public function user_dash()
    {
        $tenancies = new Tenant();
        $tenant = $tenancies->find(tenant('id'));
        $folder_name = $tenant->folder_name;
        return view('websites.user', compact('folder_name'));
    }

    public function vendor_dash()
    {
        $tenancies = new Tenant();
        $tenant = $tenancies->find(tenant('id'));
        $folder_name = $tenant->folder_name;
        $data = json_decode($tenant->site_data);
        $brand_name = $data->brandName;
        return view('websites.vendor', compact('folder_name', 'brand_name'));
    }

    public function updateBrandColorDesc(Request $request)
    {
        $tenantId= tenant()->id;
        $tenant = Tenant::find($tenantId);
        $data = json_decode($tenant->site_data);

        // Update specific fields in the JSON data
        $data->brandName = $request->input('brand');
        $data->themeColor = $request->input('themeColor');
        $data->brandDesc = $request->input('brandDesc');

        // Update the JSON column in the database
        $tenant->update(['site_data' => json_encode($data)]);

        return response()->json(['message' => 'Item updated successfully']);
    }
}
