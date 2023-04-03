<?php

namespace Grimzy\LaravelMysqlSpatial\Eloquent;

use Illuminate\Database\Grammar;
use Illuminate\Database\Query\Expression;

class SpatialExpression extends Expression
{
    public function getValue(Grammar $grammar = null)
    {
        return 'ST_GeomFromText(?)';
    }

    public function getSpatialValue()
    {
        return $this->value->toWkt();
    }
}
