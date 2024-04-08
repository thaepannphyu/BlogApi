<?php

namespace App\Http\Controllers\Api\V1;

use App\Exceptions\CustomeException;
use App\Filter\V1\BlogFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Blog\StoreBlogRequest;
use App\Http\Requests\V1\Blog\UpdateBlogRequest;
use App\Http\Resources\V1\Blog\BlogCollection;
use App\Http\Resources\V1\Blog\BlogResource;
use App\Models\Blog;
use Exception;
use Illuminate\Http\Request;

// use Illuminate\Validation\Rule;

class BlogController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum')->only(['update','destory']);
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $attributeFilter = new BlogFilter();
        $query = $attributeFilter->transform($request);
        
        if(count( $query )==0){
            //filtered with relationship
            return new BlogCollection(Blog::latest()->flop(request(["search", "category", "author"]))->paginate(6)->withQueryString());
        }else{
            //filter with the attribute or column in the model.
            //just append query in pagination to return correct meta link
            $blogs=Blog::where($query)->paginate();
            return new BlogCollection( $blogs->appends($request->query()));
        }
        
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBlogRequest $request)
    {
        $formdata = $request->validated();
        $blog = Blog::create($formdata);
        return new BlogResource($blog);
    }

    /**
     * Display the specified resource.
     */
    public function show(Blog $blog)
    {

        return  new BlogResource($blog);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBlogRequest $request, Blog $blog)
    {
        $this->middleware('auth:sanctum');
        $newData = $request->validated();
        $blog->update($newData);
        return new BlogResource($blog);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Blog $blog)
    {
        $blog->delete();
        return  $blog;
    }
}
