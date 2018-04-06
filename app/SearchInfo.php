<?php

namespace App;

use App\Acme\Filters\QueryFilter;
use Illuminate\Database\Eloquent\Model;

class SearchInfo extends Model
{
    protected $table = 'student_profiles';

    public function scopeFilter($query, QueryFilter $filters) {
    	return $filters->apply($query);
    }
}
