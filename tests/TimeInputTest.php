<?php
declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Tests\Mock\Forms;

class TimeInputTest extends TestCase
{
    public function testValidTime()
    {
        $forms = new Forms(['test' => '22:00']);

        $forms
            ->time('test');

        $values = $forms->validation();

        $this->assertEquals(['test' => '22:00'], $values);
    }

    /**
     * @expectedException     \Exception
     * @expectedExceptionMessage test is not a valid time
     */
    public function testInvalidTime()
    {
        $forms = new Forms(['test' => '00h00']);

        $forms
            ->time('test')
            ->pattern();

        $forms->validation();
    }

    public function testGetFormsTime() {
        $forms = new Forms(['test' => 'a']);

        $forms
            ->time('test');

        $content = $forms->getForms();

        $this->assertEquals('<input name="test" type="time">', $content['test']->html);
    }
}