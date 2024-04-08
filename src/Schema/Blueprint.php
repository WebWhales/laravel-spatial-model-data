<?php

namespace Grimzy\LaravelMysqlSpatial\Schema;

use Illuminate\Database\Schema\Blueprint as IlluminateBlueprint;
use Illuminate\Database\Schema\ColumnDefinition;

class Blueprint extends IlluminateBlueprint
{
    /**
     * Add a point column on the table.
     */
    public function point(string $column, int $srid = 0): ColumnDefinition
    {
        return $this->geography($column, subtype: 'point', srid: $srid);
    }

    /**
     * Add a linestring column on the table.
     */
    public function lineString(string $column): ColumnDefinition
    {
        return $this->geometry($column, subtype: 'linestring');
    }

    /**
     * Add a polygon column on the table.
     */
    public function polygon(string $column): ColumnDefinition
    {
        return $this->geometry($column, subtype: 'polygon');
    }

    /**
     * Add a multipoint column on the table.
     */
    public function multiPoint($column): ColumnDefinition
    {
        return $this->geometry($column, subtype: 'multipoint');
    }

    /**
     * Add a multilinestring column on the table.
     */
    public function multiLineString($column): ColumnDefinition
    {
        return $this->geometry($column, subtype: 'multilinestring');
    }

    /**
     * Add a multipolygon column on the table.
     */
    public function multiPolygon($column): ColumnDefinition
    {
        return $this->geometry($column, subtype: 'multipolygon');
    }

    /**
     * Add a geometrycollection column on the table.
     */
    public function geometryCollection($column): ColumnDefinition
    {
        return $this->geometry($column, subtype: 'geometrycollection');
    }
}
