<?php
declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Jarzon\Form;

class NumberInputTest extends TestCase
{
    public function testGetFormsNumber()
    {
        $forms = new Form(['test' => 'a']);

        $forms
            ->number('test')
            ->min(4)
            ->max(10);

        $content = $forms->getForms();

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
}