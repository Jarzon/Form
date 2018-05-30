<?php
declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Jarzon\Form;

class SelectInputTest extends TestCase
{
    public function testGetFormsSelect()
    {
        $forms = new Form(['test' => 'a']);

        $forms
            ->select('test')
            ->value(['test' => 'test'])
            ->selected('test');

        $content = $forms->getForms();

        $this->assertEquals('<select name="test"><option value="test" selected>test</option></select>', $content['test']->html);
    }

    public function testUpdateValuesSelect()
    {
        $forms = new Form(['fruits' => 'oranges']);

        $forms
            ->select('fruits')
            ->value(['apples' => 'apples', 'oranges' => 'oranges']);

        $content = $forms->getForms();

        $this->assertEquals('<select name="fruits"><option value="apples">apples</option><option value="oranges">oranges</option></select>', $content['fruits']->html);

        $forms->validation();

        $content = $forms->getForms();

        $this->assertEquals('<select name="fruits"><option value="apples">apples</option><option value="oranges" selected>oranges</option></select>', $content['fruits']->html);

    }
}