<?php

namespace App\Filter\V1;


use App\Filter\Filter;


class BlogFilter extends Filter
{

    protected $safeQueryParams = [
        "title" => ["LIKE",'eq'],
        "intro" => ["LIKE",'eq'],
        "body" => ["LIKE",'eq'],
    ];
    protected $columnMap = [
        "title" => "title", 
        "intro" => "intro",
        "body" => "body",
    ];
    protected $opeartorMap = [
        'LIKE' => "LIKE",
        "eq" => "="
    ];
}
