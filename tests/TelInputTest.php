<?php
declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Jarzon\Form;

class TelInputTest extends TestCase
{
    public function testGetHtml()
    {
        $forms = new Form(['test' => '012-345-6789']);

        $forms
            ->tel('test');

        $content = $forms->getForms();

        $this->assertEquals('<input name="test" type="tel" pattern="(?:(?:\+?1\s*(?:[.-]\s*)?)?(?:\(\s*([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9])\s*\)|([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9]))\s*(?:[.-]\s*)?)?([2-9]1[02-9]|[2-9][02-9]1|[2-9][02-9]{2})\s*(?:[.-]\s*)?([0-9]{4})(?:\s*(?:#|x\.?|ext\.?|extension)\s*(\d+))?" title="Phone number (eg. 418-555-5555, 1-418-555-5555 #555)">', $content['test']->html);
    }

    /**
     * @expectedException     \Jarzon\ValidationException
     * @expectedExceptionMessage test is not a valid phone number
     */
    public function testInvalidNumber()
    {
        $forms = new Form(['test' => 'asdf']);

        $forms
            ->tel('test')
            ->pattern();

        $content = $forms->validation();
    }

    public function testValidNumber()
    {
        $forms = new Form(['test' => '1-012-345-6789']);

        $forms
            ->tel('test')
            ->pattern();

        $content = $forms->validation();

        $this->assertEquals(['test' => '1-012-345-6789'], $content);
    }
}