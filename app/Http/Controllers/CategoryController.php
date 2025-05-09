<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session; // Import the Session facade

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Fix: Use Laravel's built-in pagination instead of a custom method
        // Assuming getAllCategory might have included nested categories logic,
        // you might need to adjust the query here based on how you want to display them.
        // For a simple list with pagination, this is sufficient.
        // If you need nested display, you might revert to your custom method
        // but ensure it returns a Paginator or handle display differently.
        // Assuming a simple list with pagination is desired:
        $categories = Category::orderBy('id', 'DESC')->paginate(10); // Paginate with 10 items per page

        // return $category; // This was commented out, good.
        return view('backend.category.index')->with('categories', $categories);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $parent_cats = Category::where('is_parent', 1)->orderBy('title', 'ASC')->get();
        return view('backend.category.create')->with('parent_cats', $parent_cats);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request->all(); // Commented out, good.
        $this->validate($request, [
            'title' => 'string|required',
            'summary' => 'string|nullable',
            // Changed photo validation to accept string or file if using file upload later
            // 'photo' => 'string|nullable', // Original validation
             'photo' => 'nullable|string', // Keeping string for now based on input type 'text' and filemanager
            'status' => 'required|in:active,inactive',
            'is_parent' => 'sometimes|in:1', // 'sometimes' means it's validated only if present
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        $data = $request->all();
        $slug = Str::slug($request->title);
        $count = Category::where('slug', $slug)->count();
        if ($count > 0) {
            $slug = $slug . '-' . date('ymdis') . '-' . rand(0, 999);
        }
        $data['slug'] = $slug;
        // The checkbox sends 'is_parent=1' only when checked.
        // Using input('is_parent', 0) correctly defaults to 0 if not present.
        $data['is_parent'] = $request->input('is_parent', 0);

        // Ensure parent_id is set to null if it's a parent category
        if ($data['is_parent'] == 1) {
            $data['parent_id'] = null;
        }


        // return $data; // Commented out, good.
        $status = Category::create($data);

        if ($status) {
            Session::flash('success', 'Category added successfully');
        } else {
            Session::flash('error', 'Error occurred, Please try again!');
        }
        return redirect()->route('category.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Method is empty, looks fine if not needed.
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $parent_cats = Category::where('is_parent', 1)->get();
        $category = Category::findOrFail($id);
        return view('backend.category.edit')->with('category', $category)->with('parent_cats', $parent_cats);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // return $request->all(); // Commented out, good.
        $category = Category::findOrFail($id);
        $this->validate($request, [
            'title' => 'string|required',
            'summary' => 'string|nullable',
             'photo' => 'nullable|string', // Keeping string for now based on input type 'text' and filemanager
            'status' => 'required|in:active,inactive',
            'is_parent' => 'sometimes|in:1',
            'parent_id' => 'nullable|exists:categories,id',
        ]);
        $data = $request->all();
        // Use input('is_parent', 0) to handle case where checkbox is unchecked (not present in request)
        $data['is_parent'] = $request->input('is_parent', 0);

         // Ensure parent_id is set to null if it's a parent category
         if ($data['is_parent'] == 1) {
             $data['parent_id'] = null;
         }

        // return $data; // Commented out, good.
        $status = $category->fill($data)->save();
        if ($status) {
            Session::flash('success', 'Category updated successfully');
        } else {
            Session::flash('error', 'Error occurred, Please try again!');
        }
        return redirect()->route('category.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        // This line finds children, but the logic for shifting children
        // is assumed to be within the Category::shiftChild method or handled differently.
        // If Category::shiftChild is supposed to *be* called with the child IDs,
        // then the logic is here. Let's assume shiftChild handles the re-parenting.
         $child_cat_id = Category::where('parent_id',$id)->pluck('id'); // Get IDs of children

        $status = $category->delete();

        if ($status) {
             // If there are children and the parent is deleted, call shiftChild
             // Assuming shiftChild exists and handles re-parenting or deletion of children.
             // The original code checked count($child_cat_id) > 0 before calling,
             // which is correct.
             if(count($child_cat_id)>0){
                 Category::shiftChild($child_cat_id); // Call custom method to handle children
             }
            Session::flash('success', 'Category deleted');
        } else {
            Session::flash('error', 'Error while deleting category');
        }
        return redirect()->route('category.index');
    }

    /**
     * Get child categories by parent ID (likely for AJAX).
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getChildByParent(Request $request)
    {
        // return $request->all(); // Commented out, good.
        // Finding the parent category is not strictly necessary if only fetching children
        // $category = Category::findOrFail($request->id); // This line can be removed if $category is not used further

        // Assuming getChildByParentID is a custom method on the Category model
        $child_cat = Category::getChildByParentID($request->id);

        // return $child_cat; // Commented out, good.

        if (count($child_cat) <= 0) {
            return response()->json(['status' => false, 'msg' => '', 'data' => null]);
        } else {
            return response()->json(['status' => true, 'msg' => '', 'data' => $child_cat]);
        }
    }
}