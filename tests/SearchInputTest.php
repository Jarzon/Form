<?php
declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Tests\Mock\Forms;

class SearchInputTest extends TestCase
{
    public function testGetFormsSearch()
    {
        $forms = new Forms(['test' => 'a']);

        $forms
            ->search('test');

        $content = $forms->getForms();

        $this->assertEquals('<input name="test" type="search">', $content['test']->html);
    }
}