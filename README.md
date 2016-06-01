![rakitan](public/asset/img/rakitan.png)
# rakitan
Very simple component-based PHP framework

## Base Component
 - HTTP layer:  [symfony/http-foundation](https://packagist.org/packages/symfony/http-foundation)
 - Dependecy injector: [rdlowrey/auryn](https://packagist.org/packages/rdlowrey/auryn)
 - Router: [altorouter/altorouter](https://packagist.org/packages/altorouter/altorouter)
 - Middleware: [StackPHP](http://stackphp.com/)
 - Template engine: [league/plates](https://packagist.org/packages/league/plates)

## Installation
 - Clone the repo
```bash
$ git clone https://github.com/raisoblast/rakitan.git
$ cd rakitan
$ composer install
```

 - or using composer
```bash
$ composer create-project raisoblast/rakitan
```

### Web server configuration
#### Apache
mod_rewrite must be enabled
 - apache 2.2
```apache
<Directory "/var/www/rakitan">
    AllowOverride All
    Order allow,deny
    Allow from All
</Directory>
```

 - apache 2.4
```apache
<Directory "/var/www/rakitan">
    AllowOverride All
    Require all granted
</Directory>
```

#### Nginx
in progress...

## Documentation
in progress...
