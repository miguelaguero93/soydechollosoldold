<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use softDeletes;
    protected $perPage = 100;

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id', 'id');
    }
}
