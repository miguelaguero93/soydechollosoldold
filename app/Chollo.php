<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chollo extends Model
{
    use SoftDeletes;
    protected $perPage = 100;
    public $timestamps = false;

    public static function boot()
    {
        parent::boot();
        static::creating(function ($table) {
            $table->created_at = date('Y-m-d H:i:s', time());
            $table->updated_at = date('Y-m-d H:i:s', time());
        });
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->orderBy('id', 'DESC');
    }

    public function keywords()
    {
        return $this->hasMany(Keyword::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'chollo_category');
    }

    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id')->withTrashed();
    }

    public function store()
    {
        return $this->hasOne(Store::class, 'id', 'store_id');
    }

    public function brand()
    {
        return $this->hasOne(Brand::class, 'id', 'brand_id')->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }
}
