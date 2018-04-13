<?php 

namespace App\Acme\Filters;

use App\Acme\Filters\QueryFilter;

class DataTableFilters extends QueryFilter {

	protected $searchColumns;
	protected $orderColumn;

	/**
	 * Get request search columns for filters result
	 * @param  String $columns 
	 * @return Array
	 */
	public function searchColumns($columns) {
		if(is_string($columns)) {
			$this->searchColumns = explode(',', preg_replace('/\s+/', '', $columns));
		}
	}

	/**
	 * Search by table columns name
	 * @param  string $str 
	 * @return Query Builder
	 */
	public function query($str = '') {
		if(empty($str) && empty($this->searchColumns) && is_null($this->searchColumns)) {
			return;
		}
		
		$searchColumns = null;
		$totalColumn = count($this->searchColumns) - 1;
		foreach($this->searchColumns as $key => $column) {
			$searchColumns .= ($totalColumn !== $key) ? "`$column`, ' ', " : "`$column`";
		}
		
		return $this->builder->whereRaw("(CONCAT($searchColumns) LIKE ?)", ['%'.$str.'%']);
	}

	/**
	 * Get order by columns names
	 * @param  string $column
	 * @return String
	 */
	public function orderColumn($column = 'created_at'){		
		return $this->orderColumn = $column;
	}

	/**
	 * Filter by user given order
	 * @param  string $orderBy
	 * @return Query Builder
	 */
	public function orderBy($orderBy = 'desc') {
		return $this->builder->orderBy("$this->orderColumn", $orderBy);
	}
}