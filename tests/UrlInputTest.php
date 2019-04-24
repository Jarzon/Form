<?php
declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Jarzon\Form;

class UrlInputTest extends TestCase
{
    /**
     * @expectedException     \Jarzon\ValidationException
     * @expectedExceptionMessage test is not a valid url
     */
    public function testInvalidUrl()
    {
        $form = new Form(['test' => 'asdf']);

        $form
            ->url('test');

        $form->validation();
    }

    public function testGetFormsUrl()
    {
        $form = new Form(['test' => 'a']);

        $form
            ->url('test')
            ->min(4)
            ->max(10);

        $this->assertEquals(
            '<input name="test" type="url" minlength="4" maxlength="10">',
            $form->getInput('test')->html
        );
    }
}