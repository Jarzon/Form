<?php
declare(strict_types=1);

namespace Tests;

use Jarzon\ValidationException;
use PHPUnit\Framework\TestCase;
use Jarzon\Form;

class RadioInputTest extends TestCase
{
    public function testRadioValueException()
    {
        $this->expectException(\Error::class);
        $this->expectExceptionMessage('123456789ab doesn\'t exist');

        $form = new Form(['test' => '123456789ab']);

        $form
            ->radio('test')
            ->addOptions(['test' => 'test']);

        $form->validation();
    }

    public function testRadioValue()
    {
        $form = new Form(['test' => 'testy']);

        $form
            ->radio('test')
            ->addOptions(['test' => 'test', 'testy' => 'testy'])
            ->selected('test');

        $values = $form->validation();

        $this->assertEquals('testy', $values['test']);
    }

    public function testGetFormsRadio()
    {
        $form = new Form(['test' => 'a']);

        $form
            ->radio('test')
            ->addOptions(['test' => 'test']);

        $this->assertEquals(
            '<input name="test" type="radio" id="test_0" value="test">',
            $form->getInput('test')->html[0]['html']
        );

        $form->selected('test');

        $this->assertEquals(
            '<input name="test" type="radio" id="test_0" value="test" checked>',
            $form->getInput('test')->html[0]['html']
        );
    }
}