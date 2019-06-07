# Пространство `Helper/Debug` (средства отладки)

* `Routing` - для отладки маршрутов (связь url с скриптами, роутинг)
* `Template` - для отладки шаблонов
* `AssetsDebugger` -- позволяет узнать ID зарегистрированных CSS и JS файлов, 
    чтобы указать их как зависимсоти и загружать на страницу в правильном порядке.


## `AssetsDebugger`

Можно использовать в файле, который включен в `functions.php` вашей темы, например:

```php
use ItForFree\WpAddons\Helper\Debug\AssetsDebugger;

AssetsDebugger::printIds();
```
