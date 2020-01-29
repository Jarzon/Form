<?php
declare(strict_types=1);

namespace Tests;

use Jarzon\ValidationException;
use PHPUnit\Framework\TestCase;
use Jarzon\Form;

class EmailInputTest extends TestCase
{
    public function testInvalidEmail()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('test is not a valid email');

        $form = new Form(['test' => 'asdf']);

        $form
            ->email('test');

        $form->validation();
    }

    public function testEmptyEmail()
    {
        $form = new Form(['test' => '']);

        $form
            ->email('test');

        $form->validation();

        $this->assertEquals('<input name="test" type="email">',
            $form->getInput('test')->html
        );
    }

    public function testGetFormsEmail()
    {
        $form = new Form(['test' => 'a']);

        $form
            ->email('test')
            ->min(4)
            ->max(10);

        $this->assertEquals('<input name="test" type="email" minlength="4" maxlength="10">',
            $form->getInput('test')->html
        );
    }

    public function testRepeatedInvalidEmail()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('test is not a valid email');

        $form = new Form(['test' => ['test@exemple.com', 'NaN']]);

        $form->repeat()
            ->email('test');

        $values = $form->validation();

        $this->assertEquals(['1234', 'NaN'], $values['test']);
    }
}