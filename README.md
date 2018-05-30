# Form

```php
<?php
$form = new Form($_POST);

// Create your form
$form
  ->text('name')
  ->min(2)
  ->max(100)
  ->placeholder('Joe Doe')

  ->number('age')
  ->min(0)
  ->max(100);

// Fetch the inputs to transfer it into the view
$inputs = $form->getForm();

// Show inputs in the view
foreach($inputs as $input) {
    echo $input->html;
}

// On submit validate the form values
$form->validation();
```