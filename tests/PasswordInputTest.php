<?php
declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Jarzon\Form;

class PasswordInputTest extends TestCase
{
    public function testGetFormsColor()
    {
        $form = new Form(['test' => 'df65g4651geg']);

        $form
            ->password('test');

        $this->assertEquals(
            '<input name="test" type="password">',
            $form->getInput('test')->html
        );
    }
}