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

        $options = [
            (object)[
                'id' => 0,
                'text' => 'test2',
                'value' => 'test2',
                'customAttr' => 'test2'
            ]
        ];

        $form
            ->select('test')
            ->addOption('test', 'test')

            ->groupBind('group')
            ->groupAction('callbackName', 'edit')
            ->bindValues($options)
            ->bindOptionAttribute('customAttr', 'customAttr')

            ->value('test');

        $this->assertEquals(
            '<select name="test"><option value="test" selected>test</option><optgroup label="group" data-actionCallback="callbackName" data-actionContent="edit"><option value="test2" customAttr="test2">test2</option></optgroup></select>',
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
            ->bindValues(['test2' => 'test', 'empty string' => '']);

        $values = $form->validation();

        $this->assertEquals('', $values['test']);
    }

    public function testFirstValueIsSelected()
    {
        $form = new Form(['select' => 'firstValue']);

        $form
            ->select('select')
            ->bindValues(['firstOption' => (object)['text' => 'text', 'value' => 'firstValue'], 'secondOption' => (object)['text' => 'text2', 'value' => '']])
            ->bindOptionText('text')
            ->bindOptionValue('value')
            ->selected('firstValue');

        $values = $form->validation();

        $this->assertEquals(0, count($values));
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
            ->select('fruits')
            ->bindValues($options);

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
