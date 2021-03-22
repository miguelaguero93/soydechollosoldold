<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
	use softDeletes;
    
    protected $perPage = 100;
}
