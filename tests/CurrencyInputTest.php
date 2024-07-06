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
            '<input name="test" type="text" inputmode="decimal" oninput="validator(this, 4, 10, 2)">',
            $form->getInput('test')->html
        );
    }

    public function testShouldCorrectlyDetectDecimals()
    {
        $form = new Form(['test' => '0.05']);

        $form
            ->currency('test');

        $values = $form->validation();

        $this->assertEquals(['test' => '0.05'], $values);
    }

    public function testShouldCorrectlyDetectDecimalsOnUpdate()
    {
        $form = new Form(['test' => '0.05']);

        $form
            ->currency('test');

        $form->updateValues(['test' => '0.05']);

        $values = $form->validation();

        $this->assertEquals(['test' => '0.05'], $values);
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