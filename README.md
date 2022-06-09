# Application kernel

Provides an application kernel.

## Install

The recommended way to install this library is [through composer](http://getcomposer.org).

```sh
composer require xylemical/kernel
```

## Usage

```php
<?php

declare(strict_types=1);

use Xylemical\Config\ConfigFactory;
use Xylemical\Config\Source\ConfigFileSource;
use Xylemical\Kernel\Container\ConfigContainerSource;
use Xylemical\Kernel\Information;
use Xylemical\Kernel\Kernel;

require 'vendor/autoload.php';

$info = new Information('dummy', '');
$config = new ConfigFileSource('config.php');
$factory = new ConfigFactory($config);
$container = new ConfigContainerSource($factory->get('container'));

$kernel = new Kernel($info, $config, $container, __DIR__);
exit($kernel->run());
```

## License

MIT, see LICENSE.
