<?php

namespace App\Filters;

use Illuminate\Http\Request;

class ApiFilter
{
    protected $safeParms = [];

    protected $columnMap = [];

    protected $operatorMap = [];

    public function transform(Request $request)
    {
        $eloQuery = [];

        foreach ($this->safeParms as $parm => $operators) {
            $query = $request->query($parm); // ['eq' => 'value', ...]

            if (!isset($query)) { // if the query parameter is not set
                continue; // skip to the next parameter
            }

            $column = $this->columnMap[$parm] ?? $parm; // if the column name is not in the map, use the parameter name

            foreach ($operators as $operator) {
                $value = $query[$operator] ?? null; // if the operator is not set, use null

                if (!isset($value)) { // if the value is not set
                    continue; // skip to the next operator
                }

                $eloQuery[] = [$column, $this->operatorMap[$operator], $value]; // add the query to the array
            }
        }
        return $eloQuery;
    }
}
