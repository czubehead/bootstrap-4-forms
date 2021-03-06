# Bootstrap 4 forms for Nette

**Please use English in potential issues, let's keep it clean, shall we?**

This is a library that lets you use Bootstrap 4 forms in 
[Nette framework](http://nette.org). 

Rather than being just a renderer, this introduces a custom set of controls 
(which covers all default controls) and a renderer.

Note that **this is an alpha**, so it may be buggy. That is where you can 
help by reporting issues.

[See example here](https://codepen.io/czubehead/pen/ZryJQd?editors=1000)

## Features

- [Bootstrap 4 forms](http://getbootstrap.com/docs/4.0/components/forms/) HTML generation
- All layout modes: vertical, side-by-side and inline
- TextInput placeholders
- Highly configurable renderer
- [Custom Bootstrap controls](http://getbootstrap.com/docs/4.0/components/forms/#custom-forms)
- DateTime picker, variety of human readable date/time formats, placeholder example generation
- [Validation styles](http://getbootstrap.com/docs/4.0/components/forms/#server-side)
- Programmatically generated [Bootstrap grid](https://getbootstrap.com/docs/4.1/layout/grid/)
- Assisted manual rendering
 
## Installation

The best way is via composer:

```cmd
composer require czubehead/bootstrap-4-forms
```

*Note that if you simply clone the main branch from this repo, it is not guaranteed to work, use releases instead*

### Requirements

- Works with `Nette\Application\UI\Form`, not `Nette\Forms\Form`, so you need the
  whole Nette framework.
- PHP 5.6+
- Client-side bootstrap 4 stylesheets and JS (obviously)

### Compatibility

This package is compatible with any version version of Bootstrap 4 
(last tested on v4.0.0-beta.2)

## How to use

### Form

Probably the main class you will be using is `Czubehead\BootstrapForms\BootstrapForm`.
It has all the features of this library pre-configured and extends 
`Nette\Application\UI\Form` functionality by:
 - Only accepts `Czubehead\BootstrapForms\BootstrapRenderer` or its children (which is default)
 - Built-in AJAX support (adds `ajax` class upon rendering) via `ajax`(bool) property
 - Has direct access to render mode property of renderer (property `renderMode`)
 - All add* methods are overridden by bootstrap-enabled controls

```php
$form = new BootstrapForm;
$form->renderMode = RenderMode::Vertical;		
```

It will behave pretty much the same as the default Nette form, with the exception of not grouping buttons. 
That feature would only add unnecessary and deceiving overhead to this library,
**use grid instead, it will give you much finer control**

#### Render modes
 1. **Vertical** (`Enums\RenderMode::VerticalMode`) all controls are below their labels
 2. **Side-by-side** (`Enums\RenderMode::SideBySideMode`) controls have their labels
 on the left. It is made up using [Bootstrap grid](http://v4-alpha.getbootstrap.com/layout/grid/).
 The default layout is 3 columns for labels and 9 for controls. This can be altered
 using `BootstrapRenderer::setColumns($label, $input)`.
 3. **Inline** `Enums\RenderMode::Inline` all controls and labels will be in one
 enormous line

### Controls / inputs

Each default control has has been extended bootstrap-enabled controls and
will render itself correctly even without the renderer. You can distinguish
them easily - they all have `Input` suffix.

#### TextInput

TextInput can have placeholder set (`$input->setPlaceholder($val)`). All text-based
inputs (except for TextArea) inherit from this control.

#### DateTimeInput

Its format can be set (`$input->setFormat($str)`), the default is d.m.yyyy h:mm
(though you must specify it in standard PHP format!).

You may use DateTimeFormats class constants as a list of pretty much all formats:
```php
DateTimeFormat::D_DMY_DOTS_NO_LEAD . ' ' . DateTimeFormat::T_24_NO_LEAD
```
is the default format for DateTime. See its PhpDoc for further explanation.

#### UploadInput

Nothing out of ordinary, but it **Needs `<html lang="xx">` attribute** to work.

Has property `buttonCaption`, which sets the text on the button on the left. 
The right button is set by [Bootstrap CSS](http://getbootstrap.com/docs/4.0/components/forms/#file-browser), which depends `<html lang="xx">`.

#### SelectInput, MultiSelectInput

These can accept nested arrays of options.

```php
[
    'sub' => [
        1 => 'opt1',
        2 => 'opt2'
    ],
    3     => 'opt3',
]
```
will generate
```html
<optgroup label="sub">
    <option value="1">opt1</option>
    <option value="2">opt2</option>
</optgroup>
<option value="3">opt3</option>
```

### Renderer

The renderer is enhanced by the following API:

|property|type|meaning|
|----|---|-----|
|mode|int constant|see render mode above in form section|
|gridBreakPoint|string / null|Bootstrap grid breakpoint for side-by-side view. Default is 'sm'|
|groupHidden| bool| if true, hidden fields will be grouped at the end. If false, hidden fields are placed where they were added. Default is true.|

### Grid

The library provides a way to programmatically place controls into Bootstrap grid and thus
greatly reduces the need for manual rendering.

Simply add a new row like this:
```php
$row = $form->addRow();
$row->addCell(6)
    ->addText('firstname', 'First name');
$row->addCell(6)
    ->addText('surname', 'Surname');
```

And firstname and surname will be beside each other.

#### Notes

- By calling `getElementPrototype()` on row or cell, you can influence the elements of row / cell
- A cell can only hold one control (or none)
- You are not limited to numerical column specification. 
Also check out `\Czubehead\BootstrapForms\Grid\BootstrapCell::COLUMNS_NONE` 
and `\Czubehead\BootstrapForms\Grid\BootstrapCell::COLUMNS_AUTO`

# Assisted manual rendering

Why do we use manual rendering? Mostly to just rearrange the inputs, we rarely
create a completely different feel.
But there is a hefty price for using manual rendering - we have to do almost everything
ourselves, even the things the renderer could do for us. Only if there were a way to
let the renderer do most of the work...

## What can it do

Assisted manual rendering will render label-input pairs for you using a filter. 
This means that it will take care of wrapping things into `div.form-group` and validation 
messages - the most mundane thing to implement in a template. 

## Implementation

First of all, **you must implement this yourself, this won't work out of the box!**
The implementation is quite dirty, but I think the benefits outweigh this cost.

It works like this: 
### 1. Implement a filter
add a new filter to your latte engine, for example:
```php
$this->template->addFilter('formPair', function ($control) {
    /** @var BootstrapRenderer $renderer */
    $renderer = $control->form->renderer;
    $renderer->attachForm($control->form);

    return $renderer->renderPair($control);
});
```
### 2. Use it
```php
{$form['firstname']|formPair|noescape}
```

That will result in
```html
<div class="form-group row">
    <label for="frm-form-firstname" class="col-sm-3">First name</label>

    <div class="col-sm-9">
        <input type="text" name="firstname" id="frm-form-firstname" class="form-control">
    </div>
</div>
```

------

- Made by [czubehead](https://petrcech.eu)
- [API documentation](https://czubehead.github.io/bootstrap-4-forms/)
- [Componette link](https://componette.com/czubehead/bootstrap-4-forms/)
- [Packagist link](https://packagist.org/packages/czubehead/bootstrap-4-forms)
