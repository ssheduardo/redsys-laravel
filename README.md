# Redys Laravel

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Total Downloads][ico-downloads]][link-downloads]

## Introducción

Tras finalizar la actualización de la clases [Redsys][link-redsys] para trabajar con sha256, he aprovechado en crear este package para laravel 5.1, de esta forma hacemos más ameno el trabajar con este framework.

## Instalación

Via Composer

``` bash
$ composer require ssheduardo/redsys-laravel
```

O si lo prefieres, puedes agregarlo en la sección **require** de tu composer.json
```bash
  "ssheduardo/redsys": "1.0.*"
```
Ahora debemos cargar nuestro Services Provider dentro del array **'providers'** (config/app.php)
```php
Ssheduardo\Redsys\RedsysServiceProvider::class
```

Y finalmente creamos un alias dentro del array **'aliases'** (config/app.php)
```php
'Redsys'    => Ssheduardo\Redsys\Facades\Redsys::class,
```

## Uso

``` php

```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.


## Security

If you discover any security related issues, please email :author_email instead of using the issue tracker.

## Credits

- [Eduardo][link-author]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/ssheduardo/redsys-laravel.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/ssheduardo/redsys-laravel.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/ssheduardo/redsys-laravel
[link-downloads]: https://packagist.org/packages/ssheduardo/redsys-laravel
[link-author]: https://github.com/ssheduardo
[link-contributors]: ../../contributors
[link-redsys]: https://github.com/ssheduardo/sermepa
