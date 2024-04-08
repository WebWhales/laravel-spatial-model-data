<?php

namespace Grimzy\LaravelMysqlSpatial\Tests\Unit\Schema;

use Grimzy\LaravelMysqlSpatial\Schema\Blueprint;
use Grimzy\LaravelMysqlSpatial\Tests\BaseTestCase;
use Illuminate\Database\Schema\ColumnDefinition;

class BlueprintTest extends BaseTestCase
{
    public function testPoint()
    {
        $expectedCol = new ColumnDefinition([
            'type'    => 'geography',
            'name'    => 'col',
            'subtype' => 'point',
            'srid'    => 0,
        ]);

        $result = (new Blueprint('test'))->point('col');

        $this->assertSame($expectedCol->getAttributes(), $result->getAttributes());
    }

    public function testLinestring()
    {
        $expectedCol = new ColumnDefinition([
            'type'    => 'geometry',
            'name'    => 'col',
            'subtype' => 'linestring',
            'srid'    => 0,
        ]);

        $result = (new Blueprint('test'))->linestring('col');

        $this->assertSame($expectedCol->getAttributes(), $result->getAttributes());
    }

    public function testPolygon()
    {
        $expectedCol = new ColumnDefinition([
            'type'    => 'geometry',
            'name'    => 'col',
            'subtype' => 'polygon',
            'srid'    => 0,
        ]);

        $result = (new Blueprint('test'))->polygon('col');

        $this->assertSame($expectedCol->getAttributes(), $result->getAttributes());
    }

    public function testMultiPoint()
    {
        $expectedCol = new ColumnDefinition([
            'type'    => 'geometry',
            'name'    => 'col',
            'subtype' => 'multipoint',
            'srid'    => 0,
        ]);

        $result = (new Blueprint('test'))->multipoint('col');

        $this->assertSame($expectedCol->getAttributes(), $result->getAttributes());
    }

    public function testMultiLineString()
    {
        $expectedCol = new ColumnDefinition([
            'type'    => 'geometry',
            'name'    => 'col',
            'subtype' => 'multilinestring',
            'srid'    => 0,
        ]);

        $result = (new Blueprint('test'))->multilinestring('col');

        $this->assertSame($expectedCol->getAttributes(), $result->getAttributes());
    }

    public function testMultiPolygon()
    {
        $expectedCol = new ColumnDefinition([
            'type'    => 'geometry',
            'name'    => 'col',
            'subtype' => 'multipolygon',
            'srid'    => 0,
        ]);

        $result = (new Blueprint('test'))->multipolygon('col');

        $this->assertSame($expectedCol->getAttributes(), $result->getAttributes());
    }

    public function testGeometryCollection()
    {
        $expectedCol = new ColumnDefinition([
            'type'    => 'geometry',
            'name'    => 'col',
            'subtype' => 'geometrycollection',
            'srid'    => 0,
        ]);

        $result = (new Blueprint('test'))->geometrycollection('col');

        $this->assertSame($expectedCol->getAttributes(), $result->getAttributes());
    }
}
