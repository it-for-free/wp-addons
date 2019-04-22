# Пространство `Helper/Breadcrumbs` (средства отладки)

* `Breadcrumbs` - частные функции, напри для получения списка на основе вложеннсти категорий таксономии (возвращает данные)
* `DimoxBreadcrumbs` - условно "универсальные" хлебные крошки, класс непосредственно осуществулят распечатку (для использования в шаблоне)


## `DimoxBreadcrumbs`


Пример использования в шаблоне:

```php
use ItForFree\WpAddons\Helper\Breadcrumbs\DimoxBreadcrumbs;


// breadcrumbs(); // стандатрнаяфункция черри фреймворка (выводит почему-то единственно е число вместо вмножетсвенного для слова "Новости") + нам нужно русское название главной страницы.
$Brkms = new DimoxBreadcrumbs();
$Brkms->containerStart = '<ul class="breadcrumb breadcrumb__t">';
$Brkms->containerEnd = '</ul>';
$Brkms->elementStart = '<li><a href="%1$s">';
$Brkms->elementEnd = '</a></li>';
$Brkms->separator = '<li class="divider"></li>';
$Brkms->currentStart = '<li class="active">';
$Brkms->currentEnd = '</li>';
$Brkms->show(); // вызов распечатки крошек
```

Реализация основана на коде: http://dimox.name/wordpress-breadcrumbs-without-a-plugin/