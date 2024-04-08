<?php

namespace App\Filter;

use App\Http\Middleware\TrimStrings;
use Illuminate\Http\Request;

class Filter
{

    /**
     * The list of the URL operators which can be use 
     * in the attributes of model 
     * 
     * @var array<string, array>
     * 
     * Example::  
     * ["attribute" => ["like",...suitable operators for attribute]]
     */
    protected  $safeQueryParams;

    /**
     * The list is used while the colun name of model in database is not 
     * the same with the column in json response
     * 
     * @var array<string, string>
     */
    protected $columnMap;

    /**
     * the list of SQL operators which used in sql statement 
     * @var array<string, string>
     */
    protected $opeartorMap;

    /**
     * Action: Transform the URL to SQL Query
     * Usage: Use this methods when query with WHERE
     * Return: The list of [column, operator, value]
     * format:[[column, operator, value],[column, operator, value]]
     * 
     * 
     */
    public function transform(Request $request)
    {
        $transQuery = [];

        foreach ($this->safeQueryParams as $param => $operators) {
            $query = $request->query(strtolower($param));
            if (!isset($query)) {
                continue;
            }

            if(!is_array($operators)){

                $operators= [$operators];
            }

            $column = $this->getColumn($param);
           
            if (!is_string($query)) {
             
                $query =$this->arrayKeyValueToLower($query);
                $transQuery= $this->processNonStringQuery($transQuery,$operators,$query, $column);
            } else {
               
                $transQuery=$this->processStringQuery($transQuery, $column, $query);
            }
        }
        return $transQuery;
    }

private function arrayKeyValueToLower($arr){

    $lowerArr=[];
    foreach ($arr as $key => $value) {
       
       if(is_string($key)){
        $lowerArr[ strtolower($key)]= strtolower($value);
       }else{
        $lowerArr[ $key]= strtolower($value);
       }
    }
    return $lowerArr;
}


    /**
     * Process non-string values
     */

    private function processNonStringQuery( $transQuery,$operators, $query, $column)
    {   
        //from safe query
        $operators=$this->arrayKeyValueToLower($operators);
         //to transfer url to sql query
        $operatormap= $this->arrayKeyValueToLower($this->opeartorMap);
        $key=key($query);
        $value = $query[strtolower( $key)];

       
        foreach ($operators as $operator) {

            if (isset($query[$operator])) {
                if ($key== "like") {
                    $stringValue = "%" . $value . "%";
                    $transQuery[] = [$column,  $operatormap[$operator], $stringValue];
                } else {
                    $transQuery[] = [$column,  $operatormap[$operator], $value];
                }}
        }
        return $transQuery;
    }

    /**
     * Process string values
     */
    private function processStringQuery($transQuery, $column, $value)
    {
        $transQuery[] = [$column, "LIKE", "%" . $value . "%"];
        return $transQuery;
    }


    /**
     * get column of the column map
     */
    private function getColumn($param)
    {
        return $this->columnMap[$param] ?? $param;
    }
}
