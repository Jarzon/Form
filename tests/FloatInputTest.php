<?php
declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Jarzon\Form;

class FloatInputTest extends TestCase
{
    public function testGetFormsFloat()
    {
        $forms = new Form(['test' => 'a']);

        $forms
            ->float('test')
            ->min(4)
            ->max(10);

        $content = $forms->getForms();

        $this->assertEquals('<input name="test" type="number" step="0.01" min="4" max="10">', $content['test']->html);
    }

    public function testRepeated()
    {
        $form = new Form(['test' => ['1234', 'NaN']]);

        $form->repeat()
            ->float('test');

        $values = $form->validation();

        $this->assertEquals([['test' => 1234.0], ['test' => 0.0]], $values);
    }
}