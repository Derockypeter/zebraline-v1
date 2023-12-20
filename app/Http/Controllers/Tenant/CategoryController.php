<?php

namespace App\Http\Controllers\Tenant;

use Illuminate\Http\Request;
use App\Models\Tenant\Category;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Intervention\Image\Facades\Image;

class CategoryController extends Controller
{
    private function keyering() {
        return tenant()->id.'-'.'category';
    }

    private function rememberCacheMethod()
    {
        // $values = Cache::store('redis')->rememberForever($this->keyering(), function () {
            return Category::with('subcategories')->get();
        // });
        // return $values;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = $this->rememberCacheMethod();
        return response()->json(['message' => 'Categories fetched successfuly', 'categories' => $categories]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $categories = $request->get('categories');
        $categoryArray = explode(',', $categories);
        foreach ($categoryArray as $category) {
            Category::create(['name' => $category]);
        }
        // Create the welcome
        return response()->json(['ok'], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Category::where('id', $id)->first();
        return response()->json(['category' => $category, 'message' => 'Retrieved Success'], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $data = $request->input('data');

        if ($data === null) {
            return response()->json(['status' => 'Invalid JSON data'], 400);
        }

        $categoryUpdates = [];

        foreach ($data as $row) {
            $id = $row['id'];
            $existingCategory = Category::find($id);

            if (!$existingCategory) {
                $existingCategory = new Category();
            }

            $existingCategory->name = $row['name'];
            $existingCategory->description = $row['description'] ?? null;

            $existingCategory->save();

            if (isset($row['subcategories'])) {
                $this->updateSubcategories($existingCategory, $row['subcategories']);
            }
            $width = $request->input('width');
            $height = $request->input('height');
            $backgroundColor = $request->input('back$backgroundColor');

            $this->updateCategoryImage($row, $existingCategory->id, $width, $height, $backgroundColor);
        }

        Cache::store('redis')->forget($this->keyering());

        $categories = $this->rememberCacheMethod();

        return response()->json(['message' => 'Categories updated successfully', 'category' => $categories], 200);
    }

    private function stripSpecialChars($inputString)
    {
        $cleanedString = preg_replace('/[^A-Za-z0-9]/', '', $inputString);

        // Remove spaces
        $cleanedString = str_replace(' ', '', $cleanedString);

        return $cleanedString;
    }

    public function updateCategoryImage($request, $categoryId, $width, $height, $backgroundColor)
    {
        try {

            if (!is_numeric($categoryId) || $categoryId <= 0) {
                return response()->json(['Invalid category ID'], 400);
            }

            // Find the category by ID
            $category = Category::find($categoryId);

            if (!$category) {
                return response()->json(['status' => 'Category not found'], 404);
            }

            // Check if an image file was submitted
            if ($request['image']) {
                $imageToSave = $request['image'];
                $safeName = strtolower(tenant('id')) . $this->stripSpecialChars($category->name) . '.png';
                $savePath = public_path() . '/media/tenants/' . strtolower(tenant('id')) . '/img/';

                if (!file_exists($savePath)) {
                    mkdir($savePath, 0755, true);
                }

                $file = $savePath . $safeName;

                // Process and save the new image

                // Delete the previous image if it exists
                if (file_exists(public_path($file))) {
                    unlink(public_path($file));
                }
                Image::make($imageToSave)->fit($width, $height, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($file);

                // Update the category's image property
                $category->image = '/media/tenants/' . strtolower(tenant('id')) . '/img/' . $safeName;
                $category->save();

                return response('Category image updated successfully', 200);
            } elseif ($request['image'] == null) {
                // Delete the category's image if requested
                if ($category->image && file_exists(public_path($category->image))) {
                    unlink(public_path($category->image));
                    $category->image = null;
                    $category->save();
                }

                return response()->json(['Category image deleted successfully'], 200);
            }

            return response()->json(['Invalid image data'], 400);
            // }
        } catch (\Exception $e) {
            return response('An error occurred: ' . $e->getMessage(), 500);
        }
    }

    public function unlinkImage(Request $request, $categoryId)
    {
        if ($request['image'] != null) {
            $category = Category::find($categoryId);
            $file = $category->image;
            $category->image = null;
            $category->save();
            // Delete the previous image if it exists
            if (file_exists(public_path($file))) {
                unlink(public_path($file));
            }
        }
        return response()->json(['status' => 'Deleted'], 204);
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Category::where('id', $id)->delete();

        Cache::store('redis')->forget($this->keyering());
        
        return response()->json(['message' => 'Archived successfuly'], 204);
    }
}
