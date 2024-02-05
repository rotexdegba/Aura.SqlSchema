<?php
namespace Aura\SqlSchema;

use \PHPUnit\Framework\TestCase as PHPUnit_Framework_TestCase;

abstract class AbstractSchemaTest extends PHPUnit_Framework_TestCase
{
    public $setup;
    public $expect_fetch_table_list_schema;
    protected $extension;

    protected $pdo_type;

    protected $schema;

    protected $expect_fetch_table_list;

    protected $expect_fetch_table_cols;

    protected $expect_quote_name = '"one"."two"';

    protected function setUp(): void
    {
        // skip if we don't have the extension
        if (! extension_loaded($this->extension)) {
            $this->markTestSkipped("Extension '{$this->extension}' not loaded.");
        }

        // convert column arrays to objects
        foreach ($this->expect_fetch_table_cols as $name => $info) {
            $this->expect_fetch_table_cols[$name] = new Column(
                $info['name'],
                $info['type'],
                $info['size'],
                $info['scale'],
                $info['notnull'],
                $info['default'],
                $info['autoinc'],
                $info['primary']
            );
        }

        // database setup
        $setup_class = 'Aura\SqlSchema\Setup\\' . ucfirst((string) $this->pdo_type) . 'Setup';
        $this->setup = new $setup_class;

        // schema class same as this class, minus "Test"
        $class = substr(static::class, 0, -4);
        $this->schema = new $class(
            $this->setup->getPdo(),
            new ColumnFactory
        );
    }

    public function testGetColumnFactory(): void
    {
        $actual = $this->schema->getColumnFactory();
        $this->assertInstanceOf(\Aura\SqlSchema\ColumnFactory::class, $actual);
    }

    public function testFetchTableList(): void
    {
        $actual = $this->schema->fetchTableList();
        $this->assertEquals($this->expect_fetch_table_list, $actual);
    }

    public function testFetchTableList_schema(): void
    {
        $schema2 = $this->setup->getSchema2();
        $actual = $this->schema->fetchTableList($schema2);
        $this->assertEquals($this->expect_fetch_table_list_schema, $actual);
    }

    public function testFetchTableCols(): void
    {
        $table  = $this->setup->getTable();
        $actual = $this->schema->fetchTableCols($table);
        $expect = $this->expect_fetch_table_cols;
        ksort($actual);
        ksort($expect);
        $this->assertSame(count($expect), count($actual));
        foreach (array_keys($expect) as $name) {
            $this->assertEquals($expect[$name], $actual[$name]);
        }
    }

    public function testFetchTableCols_schema(): void
    {
        $table  = $this->setup->getTable();
        $schema2 = $this->setup->getSchema2();
        $actual = $this->schema->fetchTableCols("{$schema2}.{$table}");
        $expect = $this->expect_fetch_table_cols;
        ksort($actual);
        ksort($expect);
        $this->assertSame(count($expect), count($actual));
        foreach ($expect as $name => $info) {
            $this->assertEquals($expect[$name], $actual[$name]);
        }
    }

    public function testQuoteName(): void
    {
        $actual = $this->schema->quoteName('one.two');
        $this->assertSame($this->expect_quote_name, $actual);
    }
}
