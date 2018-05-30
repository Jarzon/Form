<?php
declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Jarzon\Form;

class TextareaInputTest extends TestCase
{
    public function testGetFormsTextarea()
    {
        $forms = new Form(['test' => 'a']);

        $forms
            ->textarea('test')
            ->min(0)
            ->max(500);

        $content = $forms->getForms();

        $this->assertEquals('<textarea name="test" minlength="0" maxlength="500"></textarea>', $content['test']->html);
    }
}