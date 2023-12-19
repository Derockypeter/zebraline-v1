<?php

namespace App\Http\Controllers;

use App\Models\Theme;
use App\Models\Specialty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ThemeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $themes = Theme::where([['approved', 'T'], ['toDelete', null]])->orderBy('title')->get();
        return response(['themes' => $themes, 'message' => 'Retrieved Success'], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $inputs = Validator::make($request->all(), [
            'title' => 'required',
            'imageUrl' => 'required|image',
            'description' => 'required',
        ]);
        if ($inputs->fails()) {
            return response($inputs->errors()->all(), 400);
        } else {
            $input = $inputs->validated();
            if($request->hasFile('imageUrl')) {
                $image = $request->file('imageUrl');
                $ext = $request->file('imageUrl')->getClientOriginalExtension();
                $name = strtolower($input['title']).'.'.$ext;
                $image->move(public_path('/media/templateSnaps/'.$input['title']), $name);
                $input['imageUrl'] = $name;
            }
            $theme = Theme::create($input);
            return response(['theme' => $theme, 'message' => 'Created Success'], 201);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Theme  $theme
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $theme = Theme::where([['specialty', $id], ['approved', 'T']])->with('profession')->get();
        return response(['themes' => $theme, 'message' => 'Retrieved Success'], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Theme  $theme
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $themeId)
    {
        $inputs = Validator::make($request->all(), [
            'title' => 'required',
            'imageUrl' => 'nullable|file',
            'description' => 'required'
        ]);
        if ($inputs->fails()) {
            return response($inputs->errors()->all(), 400);
        } else {
            $input = $inputs->validated();
            if($request->hasFile('imageUrl')) {
                $image = $request->file('imageUrl');
                $ext = $request->file('imageUrl')->getClientOriginalExtension();
                $name = strtolower($input['title']).'.'.$ext;
                $image->move(public_path('/media/img/themeThumbnail/'.$input['title']), $name);
                $input['imageUrl'] = $name;
            }
            $themes = new Theme();
            $theme2Update = $themes->find($themeId);
            $theme2Update->update($input);
            if ($theme2Update == true) {
                return response()->json(['message' => 'Updated Successfully', 'theme' => $theme2Update, 'status' => 200], 200);
            } else {
                return response()->json(['message' => 'Failed', 'theme' => $theme2Update], 501);
            }
        }
    }

     /**
     * Lets an admin to partial delete a theme
     */
    public function delete($themeID)
    {
        $theme = Theme::find($themeID);
        $theme->toDelete = 1;
        $theme->save();
        return response(['theme' => $theme, 'message' => 'Moved to Archive', 'status' => 200], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Theme  $theme
     * @return \Illuminate\Http\Response
     */
    public function destroy($themeId)
    {
        $theme = Theme::where('id', $themeId)->first();
        $theme->toDelete = null;
        $theme->save();
        $theme->delete();
        return response(['message' => 'Archived successfuly'], 204);
    }

    public function deletedThemes()
    {
        // $profession = Profession::onlyTrashed()->paginate(10);
        $themes = Theme::onlyTrashed();
        return response(['themes' => $themes, 'message' => 'Retrieved Success'], 200);
    }

    public function restoreDeletedTheme($themeId)
    {
        $theme = Theme::where('id', $themeId)->withTrashed()->first();
        $theme->restore();

        return response(['message' => 'Resource Unarchived successfuly'], 204);
    }

    /**
     * Gets the whole themes for super admin to or view approve
     */
    public function getIndex()
    {
        $themes = Theme::orderBy('updated_at')->with('specialty')->get();
        return response(['themes' => $themes, 'message' => 'Retrieved Success'], 200);
    }

    /**
     * Gets the theme to approve
     */
    public function approve(Request $request, $themeID)
    {
        $theme = Theme::find($themeID);
        $theme->approved = $request->approved;
        $theme->save();
        if (!empty($theme)) {
            return response(['theme' => $theme, 'message' => 'Updated Success', 'status' => 200], 200);
        }
    }

    public function renderTheme($themeID)
    {
        $theme = Theme::find($themeID);
        // $profession = $theme->profession->name;
        $themeCSS = $theme->styleFile;
        $theme_id = $theme->id;
        $theme = $theme->title;
        $tenantID = strtolower(tenant('id')); // For getting the file location;
        $preview = true;
        $can = false;
        $email = '';
        $user_id = 0;
        $code = null;
        $meta = [
            'description' => "Experience the future of healthcare at WhiteCoatDomain.com/preview/1. Our cutting-edge platform revolutionizes healthcare delivery, connecting patients and providers seamlessly. Discover intuitive features, personalized care options, and streamlined workflows for enhanced efficiency. Join us on the forefront of healthcare innovation and elevate your practice to new heights. Sign up for a preview today!",
            'image' => "/media/img/themes/$theme_id/physicianHeroWhiteMale.jpg",
        ];

        return view('websites.theme', compact('meta', 'preview', 'theme', 'themeCSS', 'tenantID', 'can', 'code', 'email', 'user_id', 'theme_id'));
    }
}
