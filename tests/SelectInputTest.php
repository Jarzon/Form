<?php
declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Jarzon\Form;

class SelectInputTest extends TestCase
{
    public function testGetForms()
    {
        $form = new Form(['test' => 'a']);

        $form
            ->select('test')
            ->value(['test' => 'test'])
            ->selected('test');

        $this->assertEquals(
            '<select name="test"><option value="test" selected>test</option></select>',
            $form->getInput('test')->html
        );

        $this->assertEquals(
            'test',
            $form->getInput('test')->selected
        );
    }

    public function testValueEmptyString()
    {
        $form = new Form(['test' => '']);

        $form
            ->select('test')
            ->value(['test2' => 'test', 'empty string' => '']);

        $values = $form->validation();

        $this->assertEquals('', $values['test']);
    }

    public function testFirstValueIsSelected()
    {
        $form = new Form(['test' => 'test']);

        $form
            ->select('test')
            ->value(['test2' => 'test', 'empty string' => ''])
            ->selected('test');

        $values = $form->validation();

        $this->assertEquals(0, count($values));
    }

    public function testUpdateValues()
    {
        $form = new Form(['fruits' => 'oranges']);

        $form
            ->select('fruits')
            ->value(['apples' => 'apples', 'oranges' => 'oranges']);

        $this->assertEquals(
            '<select name="fruits"><option value="apples">apples</option><option value="oranges">oranges</option></select>',
            $form->getInput('fruits')->html
        );

        $form->validation();

        $this->assertEquals(
            '<select name="fruits"><option value="apples">apples</option><option value="oranges" selected>oranges</option></select>',
            $form->getInput('fruits')->html);
    }
}