<?php 
namespace App\Filter;


class ScopeFilter{
    protected $query;
    protected $filter;

    public function __construct($query,$filter)
    {
      $this->query=$query;
      $this->filter=$filter;
    }

    public function sort(){
      $requestSort=isset($this->filter['sort']) ;
      $orderCheck= $this->isFilter("order","desc");
      $order=$orderCheck=="asc" ||$orderCheck=="desc" ?$orderCheck:"desc";
        if( $requestSort){
          return  $this->query->orderBy($this->filter["sort"],$order);
        }
        return $this->query;
    }

    
/**
 * check filtername is included in filter. 
 * if include, return filtername result,
 *  if not, return secondOption
 */

    public function isFilter($filterName=null,$secondOption=false){
      return isset($this->filter[$filterName])?$this->filter[$filterName]:$secondOption;
    }


}