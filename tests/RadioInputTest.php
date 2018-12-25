<?php
declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Jarzon\Form;

class RadioInputTest extends TestCase
{
    /**
     * @expectedException     \Error
     * @expectedExceptionMessage 123456789ab doesn't exist
     */
    public function testRadioValueException()
    {
        $forms = new Form(['test' => '123456789ab']);

        $forms
            ->radio('test')
            ->value(['test' => 'test']);

        $forms->validation();
    }

    public function testRadioValue()
    {
        $forms = new Form(['test' => 'testy']);

        $forms
            ->radio('test')
            ->value(['test' => 'test', 'testy' => 'testy'])
            ->selected('test');

        $values = $forms->validation();

        $this->assertEquals('testy', $values['test']);
    }

    public function testGetFormsRadio()
    {
        $forms = new Form(['test' => 'a']);

        $forms
            ->radio('test')
            ->value(['test' => 'test']);

        $content = $forms->getForms();

        $this->assertEquals('<input name="test" type="radio" id="test_0" value="test">', $content['test']->html[0]['html']);

        $forms->selected('test');

        $content = $forms->getForms();

        $this->assertEquals('<input name="test" type="radio" id="test_0" value="test" checked>', $content['test']->html[0]['html']);
    }
}