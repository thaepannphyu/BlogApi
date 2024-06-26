<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Filter\ScopeFilter;
use Hamcrest\Type\IsBoolean;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'is_admin'
    ];


    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function scopeFilter($query, $filter){

        $query->when($filter["is_admin"]??false,function ($query,$is_admin){
          
            if($is_admin=="true"){
                return   $query->where("is_admin","=",true);
            }else{
                return $query;
            }

          
        });
       
        $scope= new ScopeFilter($query,$filter);
           
            $scope->sort();
    }


    /**
     * The relationships ith blogs. Show the blogs which the user write
     */
    public function blogs()
    {
        return $this->hasMany(Blog::class);
    }

    /**
     * relationship with blog through pivot table comments
     * the list of comments which the users write are displyed
     * {{not used}} 
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Self relationship through user to user ( which is follower)
     * 
     */
    public function subscribers()
    {
        return $this->belongsToMany(User::class, 'user_subscribers', 'user_id', 'follower_id');
    }

    /**
     * Checking the follower already followed the author
     */
    public function isSubscribed()
    {
        return $this->subscribers()->where('follower_id', auth()->id())->exists();
    }

    /**
     * The subscribers subscribe the user
     * attach the auth to the user subscribers table
     */
    public function subscribeTo()
    {
        
       $this->subscribers()->attach(auth()->id());
       return "subcribed successfully";
    }


     /**
     * The subscribers unsubscribe the user(author)
     * detach the auth to the user subscribers table
     */
    public function unSubscribeTo()
    {
       $this->subscribers()->detach(auth()->id());
        return "Unsubcribed successfully";
       
    }

    public function getIsAdminBoolAttribute(){
        return $this->is_admin==0?"false":"true";
    }
}
