<?php
declare(strict_types=1);

namespace Tests;

use Jarzon\ValidationException;
use PHPUnit\Framework\TestCase;
use Jarzon\Form;

class TimeInputTest extends TestCase
{
    public function testValidTime()
    {
        $form = new Form(['test' => '22:00']);

        $form
            ->time('test');

        $values = $form->validation();

        $this->assertEquals(['test' => '22:00'], $values);
    }

    public function testInvalidTime()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('test is not a valid time');

        $form = new Form(['test' => '00h00']);

        $form
            ->time('test')
            ->pattern();

        $form->validation();
    }

    public function testGetFormsTime() {
        $form = new Form(['test' => 'a']);

        $form
            ->time('test');

        $this->assertEquals(
            '<input name="test" type="time">',
            $form->getInput('test')->html
        );
    }
}