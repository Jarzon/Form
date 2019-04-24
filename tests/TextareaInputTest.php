<?php
declare(strict_types=1);

namespace Tests;

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
}