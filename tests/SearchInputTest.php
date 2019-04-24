<?php
declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Jarzon\Form;

class SearchInputTest extends TestCase
{
    public function testGetFormsSearch()
    {
        $form = new Form(['test' => 'a']);

        $form
            ->search('test');

        $this->assertEquals(
            '<input name="test" type="search">',
            $form->getInput('test')->html
        );
    }
}