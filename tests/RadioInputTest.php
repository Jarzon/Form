<?php
declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Tests\Mock\Forms;

class RadioInputTest extends TestCase
{
    /**
     * @expectedException     \Error
     * @expectedExceptionMessage 123456789ab doesn't exist
     */
    public function testRadioValueException()
    {
        $forms = new Forms(['test' => '123456789ab']);

        $forms
            ->radio('test')
            ->value(['test' => 'test']);

        $forms->validation();
    }

    public function testRadioValue()
    {
        $forms = new Forms(['test' => 'testy']);

        $forms
            ->radio('test')
            ->value(['test' => 'test', 'testy' => 'testy'])
            ->selected('test');

        $values = $forms->validation();

        $this->assertEquals('testy', $values['test']);
    }

    public function testGetFormsRadio()
    {
        $forms = new Forms(['test' => 'a']);

        $forms
            ->radio('test')
            ->value(['test' => 'test']);

        $content = $forms->getForms();

        $this->assertEquals('<input type="radio" name="test" value="test">', $content['test']->html[0]['html']);

        $forms->selected('test');

        $content = $forms->getForms();

        $this->assertEquals('<input type="radio" name="test" value="test" checked>', $content['test']->html[0]['html']);
    }
}