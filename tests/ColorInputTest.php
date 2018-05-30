<?php
declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Tests\Mock\Form;

class ColorInputTest extends TestCase
{
    public function testGetFormsColor()
    {
        $forms = new Form(['test' => 'a']);

        $forms
            ->color('test');

        $content = $forms->getForms();

        $this->assertEquals('<input name="test" type="color">', $content['test']->html);
    }
}