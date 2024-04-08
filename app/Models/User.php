<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
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
        return $this->belongsToMany(Blog::class,"comments","user_id","blog_id")->withPivot('body');
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
        return $this->subscribers()->attach(auth()->id());
    }


     /**
     * The subscribers unsubscribe the user(author)
     * detach the auth to the user subscribers table
     */
    public function unSubscribeTo()
    {
        return $this->subscribers()->detach(auth()->id());
    }
}
