<?php
declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Tests\Mock\Form;

class TelInputTest extends TestCase
{
    public function testGetFormsColor()
    {
        $forms = new Form(['test' => '012-345-6789']);

        $forms
            ->tel('test');

        $content = $forms->getForms();

        $this->assertEquals('<input name="test" type="tel">', $content['test']->html);
    }
}