<?php
declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Tests\Mock\Forms;

class PasswordInputTest extends TestCase
{
    public function testGetFormsColor()
    {
        $forms = new Forms(['test' => 'df65g4651geg']);

        $forms
            ->password('test');

        $content = $forms->getForms();

        $this->assertEquals('<input name="test" type="password">', $content['test']->html);
    }
}