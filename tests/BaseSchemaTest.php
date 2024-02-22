<?php
namespace Rotexsoft\SqlSchema;

use \PHPUnit\Framework\TestCase as PHPUnit_Framework_TestCase;

// No longer declaring this class as abstract.
// Moved the common code to CommonSchemaTestCodeTrait
// This class is still useful so that its child classes don't need to 
// add the 
//      use \PHPUnit\Framework\TestCase as PHPUnit_Framework_TestCase;
//      
// It extends \PHPUnit\Framework\TestCase here once and its child classes
// don't have to have that use statement declared again.
// 
// No way to move that use statement code to CommonSchemaTestCodeTrait as
// it's obviously not possible based on how PHP traits work.

class BaseSchemaTest extends PHPUnit_Framework_TestCase
{
    public $setup;
    
    protected $expect_fetch_table_list_schema;
    
    protected $extension;

    protected $pdo_type;

    protected $schema;

    protected $expect_fetch_table_list;

    protected $expect_fetch_table_cols;

    protected $expect_quote_name = '"one"."two"';
    
    public function testDummy(): void
    {
        // put this one test here so that PHPUnit test runner will not generate the warning below:
        //  'No tests found in class "Rotexsoft\SqlSchema\BaseSchemaTest".'
        $this->assertTrue(true);
    }
}
