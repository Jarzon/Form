<?php
declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Jarzon\Form;

class FormTest extends TestCase
{
    public function testGetFormHtml()
    {
        $forms = new Form([]);

        $forms
            ->text('username')
            ->min(4)
            ->max(10)

            ->password('password')
            ->min(10)
            ->max(100)

            ->submit()
            ->value('Save');

        $tags = $forms->getForms();

        $output = '';

        foreach ($tags as $tag) {
            $output .= $tag->html;
        }

        $this->assertEquals('<form method="POST"><input name="username" type="text" minlength="4" maxlength="10"><input name="password" type="password" minlength="10" maxlength="100"><input type="submit" name="submit" value="Save"></form>', $output);
    }

    public function testInputRowAttribute()
    {
        $forms = new Form([]);

        $forms
            ->text('username')
            ->min(4)
            ->max(10)

            ->password('password')
            ->min(10)
            ->max(100)

            ->submit()
            ->value('Save');

        $tags = $forms->getForms();

        $output = '';

        foreach ($tags as $tag) {
            $output .= $tag->row;
        }

        $this->assertEquals('<form method="POST"><input name="username" type="text" minlength="4" maxlength="10"><input name="password" type="password" minlength="10" maxlength="100"><input type="submit" name="submit" value="Save"></form>', $output);
    }

    public function testFormSubmited()
    {
        $_POST = ['username' => 'Joe Doe', 'submit' => 'Save'];

        $forms = new Form($_POST);

        $forms
            ->text('username')
            ->min(4)
            ->max(10)

            ->submit()->value('Save');

        $tags = $forms->getForms();

        $this->assertEquals(true, $forms->submitted());
    }
}