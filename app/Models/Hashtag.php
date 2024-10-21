<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hashtag extends Model
{
    use HasFactory;

    // 大量割り当て可能なフィールドを指定
    protected $fillable = ['name', 'genre'];

    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }
}