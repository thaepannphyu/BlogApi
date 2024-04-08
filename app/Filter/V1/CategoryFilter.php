<?php

namespace App\Filter\V1;


use App\Filter\Filter;


class CategoryFilter extends Filter
{

    protected $safeQueryParams = [
        "name" => ["LIKE", "eq"],
        "description" => ["LIKE", "eq"],
    ];

    protected $opeartorMap=[
        "like"=>"like",
        "eq"=>"="
    ];
   
}
