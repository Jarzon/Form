<?php
declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Tests\Mock\Form;

class NumberInputTest extends TestCase
{
    public function testGetFormsNumber()
    {
        $forms = new Form(['test' => 'a']);

        $forms
            ->number('test')
            ->min(4)
            ->max(10);

        $content = $forms->getForms();

        $this->assertEquals('<input name="test" type="number" step="1" min="4" max="10">', $content['test']->html);
    }
}