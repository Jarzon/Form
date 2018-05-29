<?php
declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Tests\Mock\Forms;

class FloatInputTest extends TestCase
{
    public function testGetFormsFloat()
    {
        $forms = new Forms(['test' => 'a']);

        $forms
            ->float('test')
            ->min(4)
            ->max(10);

        $content = $forms->getForms();

        $this->assertEquals('<input name="test" type="number" step="0.01" min="4" max="10">', $content['test']->html);
    }
}