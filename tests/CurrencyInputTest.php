<?php
declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Jarzon\Form;

class CurrencyInputTest extends TestCase
{
    public function testGetFormsFloat()
    {
        $form = new Form(['test' => 'a']);

        $form
            ->currency('test')
            ->min(4)
            ->max(10);

        $this->assertEquals(
            '<input name="test" type="number" step="0.01" min="4" max="10">',
            $form->getInput('test')->html
        );
    }

    public function testShouldCorrectlyDetectDecimals()
    {
        $form = new Form(['test' => '0.5']);

        $form
            ->currency('test');

        $values = $form->validation();

        $this->assertEquals(['test' => '0.5'], $values);
    }

    public function testRepeated()
    {
        $form = new Form(['test' => ['1234', 'NaN']]);

        $form->repeat()
            ->currency('test');

        $values = $form->validation();

        $this->assertEquals([['test' => 1234.0], ['test' => 0.0]], $values);
    }
}