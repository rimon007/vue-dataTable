<?php

namespace App\Http\Controllers;

use App\Acme\Filters\DataTableFilters;
use App\SearchInfo;
use Illuminate\Http\Request;

class PaginationController extends Controller
{
    public function index(DataTableFilters $filters) {
    	$data = SearchInfo::filter($filters)->paginate(request('limit') ?? 25);
    	if(request()->ajax()) {
			return $data;
		}
    	return view('server-side-dataTable', compact('data'));
    }
    
    public function vTable() {
    	$data = SearchInfo::paginate(request('limit') ?? 15);
    	if(request()->ajax()) {
			return $data;
		}
		return view('v-table', compact('data'));
    }
}
