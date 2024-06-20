<?php

namespace App\Models;

use App\Filter\ScopeFilter;
use App\Http\Resources\V1\Category\CategoryCollection;
use App\Http\Resources\V1\Category\CategoryResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Blog extends Model
{
    use HasFactory;
    use HasSlug;
    protected $with=["category","author",'comments'];
     //spatie package

     public function getSlugOptions() : SlugOptions
     {
         return SlugOptions::create()
             ->generateSlugsFrom('title')
             ->saveSlugsTo('slug')
             ->slugsShouldBeNoLongerThan(50) 
             ->preventOverwrite();
             ;
     }
 


    public function getIntroAttribute()
    {
        
        return  strip_tags(substr($this->body, 0, 50)); // Adjust the length as needed
    }

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


            $query= new ScopeFilter($query,$filter);
            
            $query->sort();//return query
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
