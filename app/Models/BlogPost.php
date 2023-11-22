<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;


class BlogPost extends Model
{
    use HasFactory;
    protected $table = 'blogposts';
    public function blogPosts()
    {
        return $this->hasMany(BlogPost::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
