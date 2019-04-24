<?php
declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Jarzon\Form;

class RangeInputTest extends TestCase
{
    public function testGetFormsRange()
    {
        $form = new Form(['test' => 'a']);

        $form
            ->range('test')
            ->min(0)
            ->max(500);

        $this->assertEquals(
            '<input name="test" type="range" min="0" max="500">',
            $form->getInput('test')->html
        );
    }
}