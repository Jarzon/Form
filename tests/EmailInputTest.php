<?php
declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Tests\Mock\Forms;

class EmailInputTest extends TestCase
{
    /**
     * @expectedException     \Exception
     * @expectedExceptionMessage test is not a valid email
     */
    public function testInvalidEmail()
    {
        $forms = new Forms(['test' => 'asdf']);

        $forms
            ->email('test');

        $forms->verification();
    }

    public function testGetFormsEmail()
    {
        $forms = new Forms(['test' => 'a']);

        $forms
            ->email('test')
            ->min(4)
            ->max(10);

        $content = $forms->getForms();

        $this->assertEquals('<input name="test" type="email" minlength="4" maxlength="10">', $content['test']['html']);
    }
}