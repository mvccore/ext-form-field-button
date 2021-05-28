# MvcCore - Extension - Form - Field - Button

[![Latest Stable Version](https://img.shields.io/badge/Stable-v5.1.6-brightgreen.svg?style=plastic)](https://github.com/mvccore/ext-form-field-button/releases)
[![License](https://img.shields.io/badge/License-BSD%203-brightgreen.svg?style=plastic)](https://mvccore.github.io/docs/mvccore/5.0.0/LICENSE.md)
![PHP Version](https://img.shields.io/badge/PHP->=5.4-brightgreen.svg?style=plastic)

MvcCore form extension with fields based on elements `<button>` and `<input>` with types `button`, `submit` and `reset`.

## Installation
```shell
composer require mvccore/ext-form-field-button
```

## Fields
There are no validator for any button field in this package.
- `input:button`
- `input:reset`
- `input:submit`
- `input:image`
- `button:button`
- `button:reset`
- `button:submit`

## Features
- always server side checked attributes `required`, `disabled` and `readonly`
- all HTML5 specific and global atributes (by [Mozilla Development Network Docs](https://developer.mozilla.org/en-US/docs/Web/HTML/Reference))
- every field has it's build-in specific validator described above
- every build-in validator adds form error (when necessary) into session
  and than all errors are displayed/rendered and cleared from session on error page, 
  where user is redirected after submit
- any field is possible to render naturally or with custom template for specific field class/instance
- very extensible field classes - every field has public template methods:
	- `SetForm()`		- called immediatelly after field instance is added into form instance
	- `PreDispatch()`	- called immediatelly before any field instance rendering type
	- `Render()`		- called on every instance in form instance rendering process
		- submethods: `RenderNaturally()`, `RenderTemplate()`, `RenderControl()`, `RenderLabel()` ...
	- `Submit()`		- called on every instance when form is submitted

## Examples
- [**Application - Questionnaires (mvccore/app-questionnaires)**](https://github.com/mvccore/app-questionnaires)

## Basic Example

```php
$form = (new \MvcCore\Ext\Form($controller))->SetId('demo');
...
// buttons has not labels, only values:
$reset = new \MvcCore\Ext\Forms\Fields\ResetInput();
$reset
	->SetName('reset_form')
	->SetValue('Clean all values:');
$submit = new \MvcCore\Ext\Forms\Fields\SubmitButton([
	'name'		=> 'send',
	'value'		=> 'Save',
]);
...
$form->AddFields($reset, $submit);
```
