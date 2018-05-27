<?php
declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Tests\Mock\Forms;

class TextareaInputTest extends TestCase
{
    public function testGetFormsTextarea()
    {
        $forms = new Forms(['test' => 'a']);

        $forms
            ->textarea('test')
            ->min(0)
            ->max(500);

        $content = $forms->getForms();

        $this->assertEquals('<textarea name="test" minlength="0" maxlength="500"></textarea>', $content['test']['html']);
    }
}