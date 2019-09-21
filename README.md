⚠️ __This project is unmaintained and might eventually be removed. Use it at your own risk.__

# Configuration management

Configuration management for PHP

[![Build Status](https://travis-ci.org/glopezdetorre/config.svg?branch=master)](https://travis-ci.org/glopezdetorre/config)
[![Code Coverage](https://scrutinizer-ci.com/g/glopezdetorre/config/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/glopezdetorre/config/?branch=master)

## Example

Given a yaml config file, allows accessing config properties with dot notation interface:

```
mongo:
  user: gorka
  pass: s3cr3t
```


```php
<?php

$config = new Config('config.yml');
$config->get('mongo.user');
```


