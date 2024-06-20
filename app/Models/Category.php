<?php

namespace App\Models;

use App\Filter\ScopeFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Category extends Model
{
    use HasFactory;
    use HasSlug;
    protected $hidden = [];

    

       //spatie package

       public function getSlugOptions() : SlugOptions
       {
           return SlugOptions::create()
               ->generateSlugsFrom('name')
               ->saveSlugsTo('slug')
               ->slugsShouldBeNoLongerThan(50) 
               ->preventOverwrite();
               ;
       }
       
    public function scopeFilter($query,$filter)
    {
        $scope= new ScopeFilter($query,$filter);
           
        $scope->sort();

        $query->when($filter["paginateOffset"]??false,function($query,$paginateOffset){
            $query->where("id",">",$paginateOffset)->orderBy("id");
        });

       
    }

    public function blog()
    {
        return $this->belongsToMany(Blog::class);
    }

    public function getIsDeleableAttribute()
    {
        return $this->blog()->count() === 0;
    }

   
}
