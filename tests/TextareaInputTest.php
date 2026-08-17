<?php
declare(strict_types=1);

namespace Tests;

use Jarzon\ValidationException;
use PHPUnit\Framework\TestCase;
use Jarzon\Form;

class TextareaInputTest extends TestCase
{
    public function testGetFormsTextarea()
    {
        $form = new Form(['test' => 'a']);

        $form
            ->textarea('test')
            ->min(0)
            ->max(500);

        $this->assertEquals(
            '<textarea name="test" minlength="0" maxlength="500"></textarea>',
            $form->getInput('test')->html
        );
    }

    public function testTextareaMax()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('test is too long');
        $form = new Form(['test' => 'aadsfasdf33','test2' => '','estimate_number' => '']);

        $form
            ->textarea('test')
            ->max(10)
            ->textarea('test2')
            ->max(500)
            ->number('estimate_number')
            ->min(1)
            ->max(2147483647)
            ->required();

        $form->validation();
    }
}