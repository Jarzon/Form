<?php
declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Jarzon\Form;

class HiddenInputTest extends TestCase
{
    public function testGetFormsHidden()
    {
        $form = new Form(['test' => 'a']);

        $form
            ->hidden('test');

        $this->assertEquals('<input name="test" type="hidden">', $form->getInput('test')->html);
    }
}