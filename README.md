# Joomla! Module by Phproberto  

> Base classes to develop Joomla! Modules.

[![Build Status](https://travis-ci.org/phproberto/joomla-module.svg?branch=master)](https://travis-ci.org/phproberto/joomla-module)
[![Code Coverage](https://scrutinizer-ci.com/g/phproberto/joomla-module/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/phproberto/joomla-module/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/phproberto/joomla-module/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/phproberto/joomla-module/?branch=master)

**STILL NOT READY FOR PRODUCTION**

## What?  

Have you ever seen something like this?

```php
// Include the latest functions only once
JLoader::register('ModArticlesLatestHelper', __DIR__ . '/helper.php');

$list            = ModArticlesLatestHelper::getList($params);
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'), ENT_COMPAT, 'UTF-8');

require JModuleHelper::getLayoutPath('mod_articles_latest', $params->get('layout', 'default'));
```

Wouldn't be better to be able to just use something like this?

```php
JLoader::registerPrefix('ModArticlesLatest', __DIR__);

echo ModArticlesLatestModule::getInstance($module->id)->setParams($params)->render();
```

Modules are the only part where Joomla! doesn't use OOP. Start using these module classes now and take advantage of its benefits.

## Requirements

* **PHP 5.4+** Due to the use of traits
* **Joomla! CMS v3.7+**

## Benefits  

* Modules are only loaded once from database.
* Support for legacy templates system (99% backward compatible) and `JLayoutFile` layouts.
* Easily and transparently load & save module parameters.
* Unit tested to ensure that your modules are never broken.
* Built over years of experience dealing with Joomla! modules.
* 100% Opensource.

## Documentation

Check [docs](./docs) for detailed documentation.  

## License

This library is licensed under [GNU/GPL 2 license](http://www.gnu.org/licenses/gpl-2.0.html).  

Copyright (C) 2017 [Roberto Segura López](http://phproberto.com) - All rights reserved.  
