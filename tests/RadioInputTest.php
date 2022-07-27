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
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage("test value isn't part of the list");

        $form = new Form(['test' => '123456789ab']);

        $form
            ->radio('test')
            ->addOptions(['test' => 'test']);

        $form->validation();
    }

    public function testRadioExistingValue()
    {
        $form = new Form(['test' => 'test']);

        $form
            ->radio('test')
            ->addOptions(['test' => 'test']);

        $values = $form->validation();

        $this->assertEquals('test', $values['test']);
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

    public function testUpdateValuesFormsRadio()
    {
        $form = new Form(['test' => 'a']);

        $form
            ->radio('test')
            ->addOptions(['test' => 0]);

        $this->assertEquals(
            '<input name="test" type="radio" id="test_0" value="0">',
            $form->getInput('test')->html[0]['html']
        );

        $form->updateValues(['test' => '0']);

        $this->assertEquals(
            '<input name="test" type="radio" id="test_0" value="0" checked>',
            $form->getInput('test')->html[0]['html']
        );
    }
}