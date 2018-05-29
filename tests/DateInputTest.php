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

        $values = $forms->validation();

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
            ->date('test')
            ->pattern();

        $forms->validation();
    }

    public function testGetFormsDate() {
        $forms = new Forms(['test' => 'a']);

        $forms
            ->date('test');

        $content = $forms->getForms();

        $this->assertEquals('<input name="test" type="date">', $content['test']->html);
    }
}