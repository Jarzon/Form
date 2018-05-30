<?php
declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Tests\Mock\Form;

class HiddenInputTest extends TestCase
{
    public function testGetFormsHidden()
    {
        $forms = new Form(['test' => 'a']);

        $forms
            ->hidden('test');

        $content = $forms->getForms();

        $this->assertEquals('<input name="test" type="hidden">', $content['test']->html);
    }
}