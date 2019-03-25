DataGridBundle
==============
General:
[![Build Status](https://travis-ci.com/pfilsx/DataGridBundle.svg?branch=master)](https://travis-ci.com/pfilsx/DataGridBundle)
[![Latest Stable Version](https://poser.pugx.org/pfilsx/data-grid-bundle/v/stable)](https://packagist.org/packages/pfilsx/data-grid-bundle)
[![License](https://poser.pugx.org/pfilsx/data-grid-bundle/license)](https://packagist.org/packages/pfilsx/data-grid-bundle)

Quality:
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/pfilsx/DataGridBundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/pfilsx/DataGridBundle/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/pfilsx/DataGridBundle/badges/build.png?b=master)](https://scrutinizer-ci.com/g/pfilsx/DataGridBundle/build-status/master)
[![Coverage Status](https://coveralls.io/repos/github/pfilsx/DataGridBundle/badge.svg?branch=master)](https://coveralls.io/github/pfilsx/DataGridBundle?branch=master)
[![Code Intelligence Status](https://scrutinizer-ci.com/g/pfilsx/DataGridBundle/badges/code-intelligence.svg?b=master)](https://scrutinizer-ci.com/code-intelligence)

Numbers:
[![Total Downloads](https://poser.pugx.org/pfilsx/data-grid-bundle/downloads)](https://packagist.org/packages/pfilsx/data-grid-bundle)
[![Monthly Downloads](https://poser.pugx.org/pfilsx/data-grid-bundle/d/monthly)](https://packagist.org/packages/pfilsx/data-grid-bundle)
[![Daily Downloads](https://poser.pugx.org/pfilsx/data-grid-bundle/d/daily)](https://packagist.org/packages/pfilsx/data-grid-bundle)



Introduction
------------

The bundle provides a Data Grid Tables integration for your Symfony Project. It automatically registers
the new DataGridFactory and Twig GridExtension which can be easily as well as highly configured.

Features
--------
* Display a Data Grid from a `Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository`
* Automatic filter(with bundle js included)
* Automatic Sorting on columns
* Easy to configure
* Easy to extend
* Documented (in Resources/doc)
* Change of DataGrid presentation with override default twig template or just configure css classes on each column/filter.

Requirement
-----------
* PHP 7.1+
* Symfony 4+
* Twig 2+

Installation
------------

Via bash:
```bash
$ composer require pfilsx/data-grid-bundle
```
Via composer.json:

You need to add the following lines in your deps :
```json
{
    "require": {
        "pfilsx/data-grid-bundle": ">=2.0"
    }
}
```

For non symfony-flex apps dont forget to add bundle:
``` php
$bundles = array(
    ...
    new Pfilsx\DataGrid\DataGridBundle(),
);
```

Documentation
-------------

Please, read the [docs](https://github.com/pfilsx/DataGridBundle/tree/master/src/Resources/doc).

License
-------

This bundle is released under the MIT license.

Contribute
----------

If you'd like to contribute, feel free to propose a pull request! Or just contact me :) 
