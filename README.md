# MiniCrumbs
Super portable, lightweight breadcrumbs library

The way it works is it parses the request URI, ie. /home/about/company into an an iterable array of breadcrumb objects
so that you can render something like: "Home | About | Company" in your markup with all the necessary links without you
having to worry about the formatting, names or links.

## Examples

It's as easy as:

```php
use MiniCrumbs\MiniCrumbs;

$crumbs = new MiniCrumbs();
```

This will return you an instance of MiniCrumbs of which you can:

```php
$crumbsArray = $crumbs->parse();
```

This will give you an iterable object which you can loop in your templates, 
though a premade render function is available to the lazier ones:

```php
$crumbs->render();
```

Will render the default breadcrumbs markup

## Documentaion

The Minicrumbs constructor takes the following arguments:
```php
MiniCrumbs($format = 'standard', $home='home', $options = array())
```

#### Format: 
'upper', 'lower', ''standard' - (string) how the breadcrumbs should be formatted

#### Home: 
defaults to ''home' - (string) alias for the first breadcrumb linking to home

#### Options:
array of options - developer options for testing e.g ['test' => true]

<br>
<br>
<br>
#### Thank you and feel free to contribute!
