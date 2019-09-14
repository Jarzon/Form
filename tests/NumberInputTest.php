<?php
declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Jarzon\Form;

class NumberInputTest extends TestCase
{
    public function testGetFormsNumber()
    {
        $form = new Form(['test' => 'a']);

        $form
            ->number('test')
            ->min(4)
            ->max(10);

        $content = $form->getForms();

        $this->assertEquals('<input name="test" type="number" step="1" min="4" max="10">', $content['test']->html);
    }

    public function testRepeated()
    {
        $form = new Form(['test' => ['1234', 'NaN']]);

        $form->repeat()
            ->number('test');

        $values = $form->validation();

        $this->assertEquals([['test' => 1234], ['test' => 0]], $values);
    }

    public function testIsUpdated()
    {
        $form = new Form(['test' => '10']);

        $form
            ->number('test');

        $values = $form->validation();

        $this->assertEquals(['test' => '10'], $values);
    }

    public function testIsNotUpdated()
    {
        $form = new Form(['test' => '0']);

        $form
            ->number('test');

        $values = $form->validation();

        $this->assertEquals([], $values);
    }
}