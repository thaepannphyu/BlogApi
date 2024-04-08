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
        $attributeFilter = new CategoryFilter();
        $query = $attributeFilter->transform($request);
        if(count( $query )==0){
            //filtered with relationship and search
             //syntax in sql=> sql or query 
             return new CategoryCollection(Category::paginate());
        }else{

            //filter with the attribute or column in the model.
            //just append query in pagination to return correct meta link
             //syntax in sql => sql and query

            $categories=Category::where($query)->paginate();
          
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
        $category->delete();
        return response()->json(['message' => 'category delete successfully'], 200);
    }

    
}
