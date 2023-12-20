<?php

namespace App\Http\Controllers\Tenant;

use App\Models\Tenant;
use Illuminate\Http\Request;
use App\Models\Tenant\Product;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Config;

class ProductController extends Controller
{
    private function keyering()
    {
        return tenant()->id . '-' . 'products';
    }

    private function rememberCacheMethod()
    {
        $key = $this->keyering();

        return Cache::store('redis')->rememberForever($key, function () {
            $products = new Product();
            return $products->with('images', 'category', 'variants')->latest()->get();
        });
    }


    private function stripSpecialChars($inputString)
    {
        $cleanedString = preg_replace('/[^A-Za-z0-9]/', '', $inputString);

        // Remove spaces
        $cleanedString = str_replace(' ', '', $cleanedString);

        return $cleanedString;
    }
    
    public function index()
    {
        // Cache::store('redis')->forget($this->keyering());
        
        // $products = $this->rememberCacheMethod();

        // return response()->json($products);
        $userId = auth()->user()->id;
        $tenants = new Tenant();
        $tenant = $tenants->where('user_id', $userId)->first();
        $tenantDb =$tenant->tenancy_db_name;
        Config::set('database.connections.mysql.database', $tenantDb);

        DB::connection('mysql')->reconnect();
        DB::setDatabaseName($tenantDb);

        $products = new Product();
        return response()->json(['products' => $products->with('images', 'category')->latest()->paginate()]);
    }

    public function show($name)
    {
        $products = new Product();
        $product = $products->where('title', $name)->with('images', 'category', 'variants', 'reviews.user')->first();
        $related = $products->where([['category_id', $product->category_id], ['id', '!=', $product->id]])->with('images', 'category', 'variants', 'reviews.user')->latest()->take(4)->get();
        return response()->json(['product' => $product, 'related' => $related], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:75'],
            'category_id' => ['required'],
            'description' => ['required', 'string'],
            'price' => ['required'],
            'slug' => ['nullable', 'string'],
            'brand' => ['nullable', 'string'],
            'tags' => ['nullable', 'string'],
            // 'images.*' => 'required|file|mimes:jpg,png,pdf|max:2048',
            // 'images' => 'required',
            'variants' => 'nullable',
            'compareAtPrice' => 'nullable', 
            'taxed' => 'nullable', 
            'visible' => 'nullable', 
            'lowstockthreshold' => 'nullable'
        ]);
        $userId = auth()->user()->id;
        $tenants = new Tenant();
        $tenant = $tenants->where('user_id', $userId)->first();
        $tenantDb =$tenant->tenancy_db_name;
        Config::set('database.connections.mysql.database', $tenantDb);

        DB::connection('mysql')->reconnect();
        DB::setDatabaseName($tenantDb);
        // Create a new product
        $product = Product::create([
            'title' => $request->title,
            'tags' => $request->tags,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'slug' => $request->slug,
            'stock' => $request->stock,
            'brand' => $request->brand,
            'combinations' => $request->combinations,
            'compareAtPrice' => $request->compareAtPrice, 
            'taxed' => $request->taxed, 
            'visible' => $request->visible, 
            'lowstockthreshold' => $request->lowstockthreshold,
            'created_at' => now(),
        ]);

        // Process and save images
        $this->processAndSaveImages($request->images, $product, $tenant->id);
        if ($request->has('variants') && $request->variants != 'undefined') {
            $variantsData = json_decode($request->input('variants'));

            // Delete existing variants
            $product->variants()->delete();

            // Create new variants
            foreach ($variantsData as $variantData) {
                $name = $variantData->name;
                $price = $variantData->price ?? null;
                $stock = $variantData->stock ?? null;
                $image_id = $variantData->image_id ?? null;
                $data = json_encode($variantData->data); // Convert array to JSON string

                // Create a new variant using the extracted data
                $product->variants()->create([
                    'name' => $name,
                    'data' => $data,
                    'price' => $price,
                    'stock' => $stock ?? 0,
                    'image_id' => $image_id,
                ]);
            }
        }
        $product->load('category', 'images');
        // Cache::store('redis')->forget($this->keyering());
        return response()->json(['message' => 'New Product added.', 'product' => $product], 201);
    }

    // Method for processing and saving images
    private function processAndSaveImages($images, $product, $tenantID)
    {
        foreach ($images as $key => $value) {
            $name = $value->getClientOriginalName();
            $save_path = public_path() . '/media/tenants/' . $tenantID . '/img/products/';
            
            // Create the directory if it doesn't exist
            if (!file_exists($save_path)) {
                mkdir($save_path, 0755, true);
            }
            
            $file = $save_path . $name;
            
            // Resize and save the image
            Image::make(file_get_contents($value))->fit(1024, 1024, function ($constraint) {})->save($file);

            // Create an image record associated with the product
            $product->images()->create([
                'url' => '/media/tenants/' . $tenantID . '/img/products/' . $name,
                'imageable_id' => $product->id,
                'imageable_type' => Product::class,
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        // dd($request->input('combinations'));
        $request->validate([
            'title' => ['required', 'string', 'max:75'],
            'category_id' => ['nullable'],
            'content' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'price' => ['required'],
            'slug' => ['nullable', 'string'],
            'brand' => ['nullable', 'string'],
            'new' => ['nullable', 'string'],
            // 'images.*' => 'required|file|mimes:jpg,png,pdf|max:2048',
            // 'images' => 'required',
            'variants' => 'nullable'
        ]);

        $userId = auth()->user()->id;
        $tenants = new Tenant();
        $tenant = $tenants->where('user_id', $userId)->first();
        $tenantDb =$tenant->tenancy_db_name;
        Config::set('database.connections.mysql.database', $tenantDb);

        DB::connection('mysql')->reconnect();
        DB::setDatabaseName($tenantDb);
        // Find the product to update
        $product = Product::findOrFail($id);

        // Update product attributes
        $product->update([
            'title' => $request->input('title', $product->title),
            'category_id' => $request->input('category_id', $product->category_id),
            'tags' => $request->input('tags', $product->tags),
            'description' => $request->input('description', $product->description),
            'amount' => $request->input('amount', $product->amount),
            'slug' => $request->input('slug', $product->slug),
            'stock' => $request->stock,
            'brand' => $request->input('brand', $product->brand),
            'combinations' => $request->input('combinations', $product->combinations),
            'lowstockthreshold' => $request->input('lowstockthreshold', $product->lowstockthreshold),
            'compareAtPrice' => $request->input('compareAtPrice', $product->compareAtPrice), 
            'taxed' => $request->input('taxed', $product->taxed), 
            'visible' => $request->input('visible', $product->visible), 
            
        ]);

        // Process and save updated images
        if ($request['images']) {
            $this->processAndSaveImages($request['images'], $product, $tenant->id);
        }

        if ($request->has('variants')) {
            $variantsData = json_decode($request->input('variants'));
            // dd($variantsData);
            $product->variants()->delete();

            foreach ($variantsData as $variantData) {
                // Access the "name" and "data" properties of each object
                $name = $variantData->name;
                $data = json_encode($variantData->data); // Convert array to JSON string

                // Create a new variant using the extracted data
                $product->variants()->create([
                    'name' => $name,
                    'data' => $data,
                ]);
            }
        }

        // Process and save updated images (same as before)

        // Load the images and variants relationships
        $product->load('category', 'images');
        // Return a JSON response with the updated product
        // Cache::store('redis')->forget($this->keyering());
        return response()->json(['message' => 'Product updated.', 'product' => $product], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
