# Laravel Pretty Handler

The package will allow you to define the error view based on model and based on the request.

**Frontend view of Shop Model Not Found Exception**

![Frontend Example](https://raw.githubusercontent.com/pyaesone17/laravel-pretty-handler/master/examples/1st.png)

**Backend view of Shop Model Not Found Exception**

![Backend Example](https://raw.githubusercontent.com/pyaesone17/laravel-pretty-handler/master/examples/2nd.png)

## Install

Via Composer

``` bash
$ composer require pyaesone17/laravel-pretty-handler
```
## Usage
Firstly register the serviceprovider in config/app.php like this

``` php
   [ ....
       Pyaesone17\LaravelPrettyHandler\PrettyServiceProvider::class       
   ],

```

Resolve **\Pyaesone17\LaravelPrettyHandler\PrettyHandler** like this in the render method of App\Exceptions\Handler.

``` php

$prettyResponse = ( resolve(\Pyaesone17\LaravelPrettyHandler\PrettyHandler::class)) ($e);

if($prettyResponse){
    return $prettyResponse;
}

```

In the model you have to implement **\Pyaesone17\LaravelPrettyHandler\Pretty** trait and set up using setUp method.

**prettyDefaultView** will be default view of the App\User not found exception.

**prettyRules** will accept the array list with url and view.

In the following example, if the exception occurs in admin section errors.backend page will show.

If request does not match any url value, it will show default page of the pretty hanlder 

``` php

class User extends Model
{
    use Pretty;

    public function setUpPretty()
    {
        $this->prettyDefaultView = 'errors.coming';
        $this->prettyRules = [
            ['url' => 'admin/*','view' => 'errors.backend'],
            ['url' => 'frontend/*','view' => 'errors.frontend'],
            ['url' => 'shop/*', 'view' => 'errors.coming']
        ];
    } 
}

```
## Note
Do not use  **\Pyaesone17\LaravelPrettyHandler\PrettyHandler::class** directly in Handler because it recieve constructor value from the Service Container.

You have to resolve the class fromn the container.

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.


## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

