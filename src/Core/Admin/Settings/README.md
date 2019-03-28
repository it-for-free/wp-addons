## Страница настроек 


Используйте 

`\ItForFree\WpAddons\Core\Admin\Settings\SettingsPage\SettingsPage`

для описания страницы настроек, например решение вида:

```php
use ItForFree\WpAddons\Core\Admin\Settings\SettingsPage\SettingsPage;
use ItForFree\WpAddons\Core\Admin\Settings\SettingsPage\Section\Field\Html\TaxonomiesCheckboxList;


$Page = new SettingsPage('htpu', 
    "Плагин иерархических URL для элементов таксономии и связанных с ними записей",
    "Плагин иерархических URL");
$Page->createAndAddSettingsEntity()
    ->createAndAddSection('main', 'Настройки плагина', 'Используйте форму ниже, чтобы задать настройки. <br>'
            . 'ВНИМАНИЕ: работа плагина требует, чтобы тип контента с тем же самым slug базовым был зарегистрирован раньше, чем таксономия '
            . '(например. может потребовать правка в модуле CPT UI). ');

$Page->getSectionById('main')->addSectionField(
   new TaxonomiesCheckboxList($Page->getSectionById('main'), 'checked_taxonomies',
        'Выбирите типы такосономий, дя которых следует активировать плагин')
);
```

Заменяет куда более длинный и сложный для восприятия код вида: http://fkn.ktu10.com/?q=node/10801
