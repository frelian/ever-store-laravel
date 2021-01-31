<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Requisitos de sistema
* PHP 7.3
* MySQL
* Composer

## Sistema propuesto
Tienda muy básica, donde un cliente puede comprar un solo producto con un valor fijo. El cliente necesita únicamente proporcionar su nombre, email y su número de celular.
Usuarios de pruebas:
* user1_ever_store@yopmail.com y contraseña: 123
* user2_ever_store@yopmail.com y contraseña: 123

## Registro de cambios
* Se agrega sistema básico de login laravel con bootstrap
* Se crea carpeta Models para mover los modelos en app/Models (se corrige la ruta del modelo en los controladores y en config/auth.php)
* Se instala Debugbar
* php artisan make:model "Models\Product" -mcr
* php artisan make:model "Models\Order" -mcr
* Uso los seeder para agregar usuarios de pruebas para la tienda: php artisan make:seeder UsersTableSeeder   
* Se agregan datos de prueba (seeders) para Products
* Agregado el valor del producto para ejemplificar mejor las pruebas

