<?php
declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Jarzon\Form;

class UrlInputTest extends TestCase
{
    /**
     * @expectedException     \Exception
     * @expectedExceptionMessage test is not a valid url
     */
    public function testInvalidUrl()
    {
        $forms = new Form(['test' => 'asdf']);

        $forms
            ->url('test');

        $forms->validation();
    }

    public function testGetFormsUrl()
    {
        $forms = new Form(['test' => 'a']);

        $forms
            ->url('test')
            ->min(4)
            ->max(10);

        $content = $forms->getForms();

        $this->assertEquals('<input name="test" type="url" minlength="4" maxlength="10">', $content['test']->html);
    }
}