# Form

```php
<?php
$form = new Jarzon\Form($_POST);

// Create your form
$form
  ->text('name')
  ->label('Name:')
  ->min(2)
  ->max(100)
  ->placeholder('Joe Doe')

  ->number('age')
  ->label('Age:')
  ->min(0)
  ->max(100)
  
  ->submit();

// Fetch the inputs to transfer it into the view
$inputs = $form->getForms();

// Show inputs in the view
foreach ($rowsforms as $form):?>
    <?=(isset($form->label))? $form->label: ''?><?=$form->html?>
<?php endforeach;

// On submit validate the form values
if($form->submitted()) {
    try {
        // Does the inputs validation based on their types
        if($values = $form->validation()) {
            // Do what you want with values
        }
    }
    catch (Exception $e) {
        // ->validation() throw Exception if the is invalid values
    }
}
```