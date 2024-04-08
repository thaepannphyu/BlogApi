<?php

namespace App\Models;

use App\Http\Resources\V1\Category\CategoryCollection;
use App\Http\Resources\V1\Category\CategoryResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Blog extends Model
{
    use HasFactory;
    protected $with=["category","author"];

    public function scopeFlop($query, $filter)
    {
        $query->when($filter["search"]??false,function($query,$search){
            $query->
                where(
                function($query) use($search){  
                $query->
                where("title","LIKE","%".$search."%")
                ->orWhere("body","LIKE","%".$search."%");})
                ;
            });
   

            $query->when($filter["category"]??false,function($query,$category){
                $query->whereHas("category",function($query) use($category){
                    $query->where("name","like","%".$category."%");
                });
            });

            $query->when($filter["author"]??false,function($query,$user){
                $query->whereHas("author",function($query) use($user){
                    $query->where("name","like","%".$user."%");
                });
            });
    }

    /**
     * relationship with category
     * one to many with blog_category
     *
     */
    public function category(){
        return $this->belongsToMany(Category::class);
    }

    /**
     * one to many with user
     */
    public function author(){
        return $this->belongsTo(User::class,"user_id");
    }

     /**
     * one to many with Comment
     */
    public function comments(){
        return $this->belongsToMany(User::class,"comments","blog_id",'user_id')->withPivot('body');
    }

    public function attachComment($validated){
        return $this->comments()->attach(Auth::user()->id,['body' =>$validated["body"] ]);
    }

    public function detachComment(){
        return $this->comments()->detach(Auth::user()->id);
    }


}
