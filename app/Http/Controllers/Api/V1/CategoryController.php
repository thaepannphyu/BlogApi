<?php

namespace App\Http\Controllers\Api\V1;

use App\Filter\V1\CategoryFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Category\StoreCategoryRequest;
use App\Http\Requests\V1\Category\UpdateCategoryRequest;
use App\Http\Resources\V1\Category\CategoryCollection;
use App\Http\Resources\V1\Category\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;


class CategoryController extends Controller
{


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $limit = $request->input('limit', 10);
        $sortDeletable = $request->input('deleable', false);
        $attributeFilter = new CategoryFilter();
        $query = $attributeFilter->transform($request);

        //sort the 
       

        if(count( $query )==0){
            //filtered with relationship and search
             //syntax in sql=> sql or query 
            $categories=Category::latest()->orderBy("id","desc")->filter(request(["deleable"]))->paginate($limit);

            if ($sortDeletable) {
                $categories = $categories->sortBy(function ($category) {
                    return $category->is_deletable ? 0 : 1;
                });
            } 
            return new CategoryCollection($categories);
        }else{

            //filter with the attribute or column in the model.
            //just append query in pagination to return correct meta link
             //syntax in sql => sql and query

            $categories=Category::where($query)->paginate($limit);
            return new CategoryCollection($categories->appends($request->query()));
        }
        
        
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $formdata = $request->validated();

        $category = Category::create($formdata);
        
        return new CategoryResource($category);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return new CategoryResource($category);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $formdata = $request->validated();

        $category->update($formdata);

        return [
            "update category" => new CategoryResource($category),
            'message' => 'category update successfully'
        ];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $category = Category::find($id);
       


        if (!$category) {
            return response()->json(['error' => 'category not found'], 404);
        }

         if($category->blog->count()<=0){
            
            $category->blog()->detach();
            $category->delete();
            return response()->json(['message' => 'category delete successfully'], 200);
         }

         return  response()->json(
            ['message' => 'cannot able to delete since it have '.$category->blog->count()." child"], 404);
    }
    
}
