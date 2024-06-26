<?php

namespace App\Models;

use App\Filter\ScopeFilter;
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
    public function comments() {
        return $this->hasMany(Comment::class);
    }

    // public function attachComment($validated){

    //     return $this->comments()->attach(Auth::user()->id,['body' =>$validated["body"] ]);
    // }

    // public function detachComment($commentId){
    //      // Find the comment
    //      $comment = $this->comments()->where('comments.id', $commentId)
    //      ->where('comments.user_id', Auth::id())
    //      ->first();

    //      // Check if the comment exists and belongs to the authenticated user
    //      if ($comment) {
    //          return $comment->delete();
    //      } else {
    //          return false; // Or throw an exception if preferred
    //      }
    // }


}
