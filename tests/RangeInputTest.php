<?php
declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Tests\Mock\Forms;

class RangeInputTest extends TestCase
{
    public function testGetFormsRange()
    {
        $forms = new Forms(['test' => 'a']);

        $forms
            ->range('test')
            ->min(0)
            ->max(500);

        $content = $forms->getForms();

        $this->assertEquals('<input name="test" type="range" min="0" max="500">', $content['test']['html']);
    }
}