<?php
declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Jarzon\Form;

class DataListInputTest extends TestCase
{
    public function testGetForms()
    {
        $form = new Form(['test' => 'a']);

        $options = [
            (object)[
                'text' => 'test2',
                'value' => 'test2',
                'customAttr' => 'test2'
            ]
        ];

        $form
            ->datalist('test')
            ->addOption('test', 'test')

            ->groupBind()
            ->bindValues($options)
            ->bindOptionAttribute('customAttr', 'customAttr')

            ->value('test');

        $this->assertEquals(
            '<input list="testList" name="test" value="test"><datalist id="testList"><option value="test"><option value="test2" customAttr="test2"></datalist>',
            $form->getInput('test')->html
        );

        $this->assertEquals(
            'test',
            $form->getInput('test')->value
        );
    }

    public function testValueEmptyString()
    {
        $form = new Form(['test' => '']);

        $form
            ->datalist('test')
            ->bindValues(['test2' => 'test', 'empty string' => '']);

        $values = $form->validation();

        $this->assertEquals('', $values['test']);
    }

    public function testNotExistingValue()
    {
        $form = new Form(['test' => 'test']);

        $form
            ->datalist('test')
            ->bindValues(['test2' => 'test', 'empty string' => ''])
            ->value('test');

        $values = $form->validation();

        $this->assertEquals([], $values);
    }

    public function testUpdateValues()
    {
        $form = new Form(['fruits' => 'oranges']);

        $options = [
            (object)[
                'text' => 'apples',
                'value' => 'apples'
            ],
            (object)[
                'text' => 'oranges',
                'value' => 'oranges'
            ]
        ];

        $form
            ->datalist('fruits')
            ->bindValues($options);

        $this->assertEquals(
            '<input list="fruitsList" name="fruits"><datalist id="fruitsList"><option value="apples"><option value="oranges"></datalist>',
            $form->getInput('fruits')->html
        );

        $form->validation();

        $this->assertEquals(
            '<input list="fruitsList" name="fruits" value="oranges"><datalist id="fruitsList"><option value="apples"><option value="oranges"></datalist>',
            $form->getInput('fruits')->html);
    }
}