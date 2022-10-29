<h2> Demo: <a href="http://51.91.224.175:8003">http://51.91.224.175:8003</a></h2>

Small application based on Laravel.

## How to install on Linux

    $ git clone https://github.com/Prawdziwy/laravel-task laravel-task
    $ cd laravel-task # Then you should setup your .env files
    $ composer install
    $ php artisan serve

## Possible endpoints

    auth -
    $ post - /api/login
    $ post - /api/register
    $ post - /api/logout

    products - 
    $ get - /api/products   # show list of Products(you can filter, sort it as well)
    $ get - /api/products/{id}   # show more informations about one specified Product 
    $ post - /api/products/create   # create new Product
    $ put/patch - /api/products/{id}   # edit actual Product
    $ delete - /api/products/{id}   # delete Product

Example -
http://51.91.224.175:8003/api/products
