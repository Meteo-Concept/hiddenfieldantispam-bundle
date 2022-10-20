Hidden-field-based antispam bundle for Symfony 5+ [![Build Status v1](https://api.travis-ci.com/Meteo-Concept/hiddenfieldantispam-bundle.svg?branch=main)](https://api.travis-ci.com/Meteo-Concept/hiddenfieldantispam-bundle.svg?branch=main)
============

This bundle adds into each form a hidden field with an empty value.
If the field comes back with a non-empty value, then we assume that
the form was filled in by a bot and the form is rejected.

Installation
----------


### Applications that use Symfony Flex

Open a command console, enter your project directory and execute:

```console
$ composer require meteo-concept/hiddenfieldantispam-bundle
```


### Applications that don't use Symfony Flex

#### Step 1: Download the Bundle

Install the bundle with one of the commands above. You now have to enable
it and configure it without the recipe.

#### Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles
in the `config/bundles.php` file of your project:

```php
// config/bundles.php

return [
    // ...
    MeteoConcept\HiddenFieldAntispamBundle\MeteoConceptHiddenFieldAntispamBundle::class => ['all' => true],
];
```

Configuration
------

Configure the bundle, for instance in
`config/packages/meteo_concept_hidden_field_antispam.yml`:

```yaml
meteo_concept_hidden_field_antispam:
    enabled: true # this is the default
    field_name: "meteo_concept_sentinel" # this is the default

twig:
    form_themes:
        - '@MeteoConceptHiddenFieldAntispam/hidden_field_antispam_form.html.twig' # to hide the antispam field
```

Usage
------

You have nothing else to do, the field is automatically added to all root (i.e.
non-embedded forms).


Todo
----

* Randomize the field name, just in case bots would become smart
* More advanced, randomize the field type while leaving it invisible