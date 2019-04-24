<?php
declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Jarzon\Form;

class ColorInputTest extends TestCase
{
    public function testGetFormsColor()
    {
        $form = new Form(['test' => 'a']);

        $form
            ->color('test');

        $this->assertEquals('<input name="test" type="color">',
            $form->getInput('test')->html
        );
    }
}