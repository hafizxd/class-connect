<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Thread;
use App\Models\Category;
use App\Models\ForumCategory;

class Forum extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function categories() {
        return $this->belongsToMany(Category::class, 'forum_category');
    }

    public function threads() {
        return $this->hasMany(Thread::class);
    }
}
