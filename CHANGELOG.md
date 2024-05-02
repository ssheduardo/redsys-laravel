# Changelog

All Notable changes to `Redsys` will be documented in this file

## 1.4.6 (2024-05-02)

### Added
- Updated README (testing in Laravel 11)

## 1.4.5 (2023-03-22)

### Added
- Updated README (publish config)
- Added mergeConfigFrom
- Changed name tag config to redsys-config

## 1.4.4 (2023-02-17)

### Added
- Updated README (testing in Laravel 10)

## 1.4.3 (2022-02-12)

### Added
- Updated README (testing in Laravel 9)
## 1.4.2 (2020-09-20)

### Added
- Updated README (testing in Laravel 8)

## 1.4.1 (2020-04-15)

### Added
- Updated README (testing in Laravel 7)

## 1.4.0 (2019-09-11)

### Added
- Updated README (testing in Laravel 6.0)

## 1.3.2 (2019-03-21)

### Added
- Updated README (testing in Laravel 5.6 or superior)

### Deprecated
- Nothing

### Fixed
- Nothing

## 1.3.1 (2018-09-07)

### Added
- Changed config.php, uses env variables

### Deprecated
- Nothing

### Fixed
- Nothing

## 1.3 (2018-08-06)

### Added
- Library Redsys with composer

### Deprecated
- Tpv.php

### Fixed
- Nothing

## 1.2.1 (2018-04-04)

### Added
- Updated Tpv.php, changed validation for setCurrency, not limit to 978, 840, 826, 392

### Deprecated
- Nothing

### Fixed
- Nothing

## 1.2 (2017-09-02)

### Added
- Update to Laravel 5.5 auto package discovery

### Deprecated
- Nothing

### Fixed
- Nothing

## 1.1.3 (2017-06-29)

### Added
- Updated Tpv.php, added new methods: setPan, setExpiryDate and setCVV2

### Deprecated
- Nothing

### Fixed
- Nothing

## 1.1.2 (2017-01-27)

### Added
- Updated Tpv.php - Changed the function mcrypt_encrypt to openssl_encrypt, with the new updated of PHP 7.1 the function mcrypt_encrypt is deprecated.

### Deprecated
- Nothing

### Fixed
- Nothing

## 1.1.1 (2016-10-27)

### Added
- Updated Tpv.php added new methods for pay for reference

### Deprecated
- Nothing

### Fixed
- Nothing

## 1.1 (2016-10-20)

### Added
- Updated to Larevel 5.2/5.3

### Deprecated
- Nothing

### Fixed
- Nothing

## 1.0.5 (2016-06-11)

### Added
- Updated class Tpv.php [added new option in setAttributesSubmit]

### Deprecated
- Nothing

### Fixed
- Nothing

## 1.0.4 (2016-02-25)

### Added
- Updated class Tpv.php

	- New method: getParameters
	- Text: T = Pago con Tarjeta + iupay , R = Pago por Transferencia, D = Domiciliacion, C = Sólo Tarjeta (mostrará sólo el formulario para datos de tarjeta)] por defecto es T

### Deprecated
- Nothing

### Fixed
- Nothing

## 1.0.3 (2015-11-26)

### Added
- In config.php new parameter (key)
- New method for convert correctly price.

### Deprecated
- Nothing

### Fixed
- Nothing

### Removed
- In Tpv.php removed injection by default the config.

### Security
- Nothing

## 1.0.0 (2015-11-15)

### Added
- First version, support sha256.

### Deprecated
- Nothing

### Fixed
- Nothing

### Removed
- Nothing

### Security
- Nothing
