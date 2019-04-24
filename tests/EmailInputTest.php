<?php
declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Jarzon\Form;

class EmailInputTest extends TestCase
{
    /**
     * @expectedException     \Jarzon\ValidationException
     * @expectedExceptionMessage test is not a valid email
     */
    public function testInvalidEmail()
    {
        $form = new Form(['test' => 'asdf']);

        $form
            ->email('test');

        $form->validation();
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

    /**
     * @expectedException     \Jarzon\ValidationException
     * @expectedExceptionMessage test is not a valid email
     */
    public function testRepeatedInvalidEmail()
    {
        $form = new Form(['test' => ['test@exemple.com', 'NaN']]);

        $form->repeat()
            ->email('test');

        $values = $form->validation();

        $this->assertEquals(['1234', 'NaN'], $values['test']);
    }
}