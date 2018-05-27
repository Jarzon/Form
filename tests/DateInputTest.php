<?php
declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Tests\Mock\Forms;

class DateInputTest extends TestCase
{
    public function testValidDate()
    {
        $forms = new Forms(['test' => '12/04/2014']);

        $forms
            ->date('test');

        $values = $forms->verification();

        $this->assertEquals(['test' => '12/04/2014'], $values);
    }

    /**
     * @expectedException     \Exception
     * @expectedExceptionMessage test is not a valid date
     */
    public function testInvalidDate()
    {
        $forms = new Forms(['test' => '00/00/0000']);

        $forms
            ->date('test');

        $forms->verification();
    }

    public function testGetFormsDate() {
        $forms = new Forms(['test' => 'a']);

        $forms
            ->date('test');

        $content = $forms->getForms();

        $this->assertEquals('<input name="test" type="text" pattern="(0?[1-9]|[12][0-9]|3[01])[- /.](0?[1-9]|1[012])[- /.](19|20)\d\d">', $content['test']['html']);
    }
}