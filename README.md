# Redys Laravel

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Total Downloads][ico-downloads]][link-downloads]

## Introducción

Tras finalizar la actualización de la clases [Redsys][link-redsys] para trabajar con sha256, he aprovechado en crear este package para laravel, de esta forma hacemos más ameno el trabajar con este framework.

## Instalación

Via Composer

**Laravel 5.1**
``` bash
$ composer require "ssheduardo/redsys-laravel=1.0.*"
```

**Laravel 5.2, 5.3, 5.4**
``` bash
$ composer require "ssheduardo/redsys-laravel=~1.1.0"
```
**Laravel 5.5, 5.6, 5.7, 5.8**
``` bash
$ composer require "ssheduardo/redsys-laravel=~1.3.0"
```
**Laravel 6.0, 7.x, 8.x, 9.x, 10.x**
``` bash
$ composer require "ssheduardo/redsys-laravel=~1.4.0"
```

O si lo prefieres, puedes agregarlo en la sección **require** de tu composer.json

**Laravel 5.1**
```bash
  "ssheduardo/redsys-laravel": "1.0.*"
```

**Laravel 5.2, 5.3, 5.4**
```bash
  "ssheduardo/redsys-laravel": "~1.1.0"
```
**Laravel 5.5, 5.6, 5.7, 5.8**
```bash
  "ssheduardo/redsys-laravel": "~1.3.0"
```

 **Laravel 6.0, 7.x, 8.x, 9.x, 10.x**
```bash
  "ssheduardo/redsys-laravel": "~1.4.0"
```

Ahora debemos cargar nuestro Services Provider dentro del array **'providers'** (config/app.php)
>Si usas Laravel 5.5 o superior, no necesitas cargar el services provider
```php
Ssheduardo\Redsys\RedsysServiceProvider::class
```

Creamos un alias dentro del array **'aliases'** (config/app.php)
>Si usas Laravel 5.5 o superior no necesitas crear el alias
```php
'Redsys'    => Ssheduardo\Redsys\Facades\Redsys::class,
```

Y finalmente publicamos nuestro archivo de configuración
```bash
php artisan vendor:publish --tag="redsys-config"
```
>Esto nos creará un archivo llamado *redsys.php* dentro de config, en este archivo debemos configurar nuestra key, url ok y ko.

## Uso
Imaginemos que tenemos esta ruta http://ubublog.com/redsys que enlaza con **RedsysController@index**

```php

Route::controller(RedsysController::class)->prefix('redsys')
    ->group(function () {
        Route::get('/', 'index');
    });
```

Y el contenido del controlador **RedsysController** sería este:
``` php
<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Ssheduardo\Redsys\Facades\Redsys;

class RedsysController extends Controller
{
    public function index()
    {
        try {
            $key = config('redsys.key');
            $code = config('redsys.merchantcode');

            Redsys::setAmount(rand(10, 600));
            Redsys::setOrder(time());
            Redsys::setMerchantcode($code); //Reemplazar por el código que proporciona el banco
            Redsys::setCurrency('978');
            Redsys::setTransactiontype('0');
            Redsys::setTerminal('1');
            Redsys::setMethod('T'); //Solo pago con tarjeta, no mostramos iupay
            Redsys::setNotification(config('redsys.url_notification')); //Url de notificacion
            Redsys::setUrlOk(config('redsys.url_ok')); //Url OK
            Redsys::setUrlKo(config('redsys.url_ko')); //Url KO
            Redsys::setVersion('HMAC_SHA256_V1');
            Redsys::setTradeName('Tienda S.L');
            Redsys::setTitular('Pedro Risco');
            Redsys::setProductDescription('Compras varias');
            Redsys::setEnviroment('test'); //Entorno test

            $signature = Redsys::generateMerchantSignature($key);
            Redsys::setMerchantSignature($signature);

            $form = Redsys::createForm();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        return view('redsys', compact('form'));
    }
}


```
Podemos hacer un pequeño diseño usando una plantilla blade quedando algo así

![image](https://user-images.githubusercontent.com/1160138/219521074-86ceb930-e7c9-4008-bcca-e7a8aab0c1fa.png)


Esta clase hereda de mi clase principal https://github.com/ssheduardo/sermepa, aquí encontrarán más ejemplos de los métodos que trae la clase **Tvp.php**

## Notas adicionales

Dentro del archivo /config/redsys.php, se debe configurar el FUC (Merchant Code) y nuestra key. Puntos a tener en cuenta de la configuración si no has trabajado con redsys-laravel anteriormente:

- Si queremos usar el entorno de producción debemos usar el string 'live' como environment.

- El FUC en el entorno de pruebas debe ser real, de otro modo se obtendrá el error de importe 0 (https://github.com/ssheduardo/redsys-laravel#20)

- La url OK ('url_ok') se usa para redireccionar tras un pago correcto (no contiene información del pago), lo mismo ocurre con la url de KO ('url_ko'). La url que tiene información del pago realizado es la URL de notificación ('url_notification') que deberá comprobar la firma de la información del siguiente modo:

```php
  $key = config('redsys.key');
  $parameters = Redsys::getMerchantParameters($request->input('Ds_MerchantParameters'));
  $DsResponse = $parameters["Ds_Response"];
  $DsResponse += 0;

  if (Redsys::check($key, $request->input()) && $DsResponse <= 99) {
      // lo que quieras que haya si es positiva la confirmación de redsys


  } else {
      //lo que quieras que haga si no es positivo

  }

```


## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.


## Security

If you discover any security related issues, please email :author_email instead of using the issue tracker.

## Credits

- [Eduardo][link-author]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Donación

¿Te gustaría apoyarme?
¿Aprecias mi trabajo?
¿Lo usas en proyectos comerciales?

¡Siéntete libre de hacer una pequeña [donación](https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=ssh%2eeduardo%40gmail%2ecom&lc=ES&currency_code=EUR&bn=PP%2dDonationsBF%3abtn_donate_LG%2egif%3aNonHosted)! :wink:

[![paypal](https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif)](https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=ssh%2eeduardo%40gmail%2ecom&lc=ES&currency_code=EUR&bn=PP%2dDonationsBF%3abtn_donate_LG%2egif%3aNonHosted)

[ico-version]: https://img.shields.io/packagist/v/ssheduardo/redsys-laravel.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/ssheduardo/redsys-laravel.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/ssheduardo/redsys-laravel
[link-downloads]: https://packagist.org/packages/ssheduardo/redsys-laravel
[link-author]: https://github.com/ssheduardo
[link-contributors]: ../../contributors
[link-redsys]: https://github.com/ssheduardo/sermepa
