# Пространство `Helper/Assets` 

* `Assets` - класс позволяет подключать JS и CSS файлы на нужных страницах 
    с возможностью поддержки порядка подключений (через указание зависимсотей).


## Примеры


Можно использовать в файле, который включен в `functions.php` вашей темы, например:
```php
<?php
use ItForFree\WpAddons\Helper\Assets\Assets;

Assets::addCssToLoginPage('custom_login_style', CHILD_URL . '/css/custom-login-style.css', 
    ['login'], null, 'all');

Assets::addJs('custom-main', get_template_directory_uri() . '/js/custom/main.js',
        array('jquery'), 1.1, true);
```

