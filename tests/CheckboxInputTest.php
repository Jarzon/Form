<?php
declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Jarzon\Form;

class CheckboxInputTest extends TestCase
{
    public function testChecked()
    {
        $form = new Form(['test' => '1234']);

        $form
            ->checkbox('test')
            ->value('testy');

        $values = $form->validation();

        $this->assertEquals('testy', $values['test']);
    }

    public function testUnchecked()
    {
        $form = new Form([]);

        $form
            ->checkbox('test')
            ->value('testy');

        $values = $form->validation();

        $this->assertEquals(false, $values['test']);
    }

    public function testCheckedBool()
    {
        $form = new Form(['test' => '1234']);

        $form
            ->checkbox('test')
            ->value(true);

        $values = $form->validation();

        $this->assertEquals(true, $values['test']);
    }

    public function testUncheckedBool()
    {
        $form = new Form([]);

        $form
            ->checkbox('test')
            ->value(true);

        $values = $form->validation();

        $this->assertEquals(false, $values['test']);
    }

    public function testGetInputs()
    {
        $form = new Form([]);

        $form
            ->checkbox('test')
            ->value('test')
            ->selected();

        $this->assertEquals(
            '<input name="test" type="checkbox" value="test" checked>',
            $form->getInput('test')->html
        );

        $form->validation();

        $this->assertEquals(
            '<input name="test" type="checkbox" value="test">',
            $form->getInput('test')->html
        );
    }

    public function testUpdateValues()
    {
        $form = new Form(['fruits' => 'apples']);

        $form
            ->checkbox('fruits')
            ->value('apples');

        $this->assertEquals(
            '<input name="fruits" type="checkbox" value="apples">',
            $form->getInput('fruits')->html
        );

        $form->validation();

        $this->assertEquals(
            '<input name="fruits" type="checkbox" value="apples" checked>',
            $form->getInput('fruits')->html
        );
    }

    public function testRepeated()
    {
        $form = new Form(['test' => [['1234', null]]]);

        $form->repeat()
            ->checkbox('test')
            ->value('testy');

        $values = $form->validation();

        $this->assertEquals([0 => ['test' => 'testy']], $values);
    }
}