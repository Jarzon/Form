<?php
declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Tests\Mock\Forms;

class HiddenInputTest extends TestCase
{
    public function testGetFormsHidden()
    {
        $forms = new Forms(['test' => 'a']);

        $forms
            ->hidden('test');

        $content = $forms->getForms();

        $this->assertEquals('<input name="test" type="hidden">', $content['test']->html);
    }
}