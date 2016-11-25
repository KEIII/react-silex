# React Silex
Integrates [Silex](https://github.com/silexphp/Silex/tree/master) with [React](https://github.com/reactphp).

## Run
```bash
$ cd example && ./console server:run -p 8080
```

## How to use
```php
<?php

use KEIII\ReactSilex\ReactSilexServiceProvider;
use Silex\Application;

$app = new Application();
$app->get('/', function () {
    return 'Hello from reactor!';
});
$app->register(new ReactSilexServiceProvider());
$app['react.console']->run();
```

## Similar
- [reactive-silex](https://github.com/kpacha/reactive-silex) Mixup silex and react (with a little help from espresso)
- [espresso](https://github.com/reactphp/espresso) Silex wired with radioactive caffeine.
- [react-bundle](https://github.com/jogaram/react-bundle) ReactPHP Bundle for Symfony2
