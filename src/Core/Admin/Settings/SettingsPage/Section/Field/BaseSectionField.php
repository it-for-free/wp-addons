<?php

namespace  ItForFree\WpAddons\Core\Admin\Settings\SettingsPage\Section\Field;

/**
 * Базовый класс  для работы с полем страницы настроек (относящимся к какой-либо секции этой страницы)
 * 
 * В потомках надо, как минимум переопределить getFieldHtmlCallback()
 * 
 */
class BaseSectionField 
{
    protected $strId = '';
    
    /**
     * Описание поля
     * @var srting
     */
    protected $title= '';
    protected $pageSlug='';
    
    /**
     *
     * @var string машинное имя, используется при формировании id, а также для атрибута name на форме 
     */
    public $machineName = '';
    
    /**
     * Секция, к которой относится данное поле
     * @var ItForFree\WpAddons\Core\Admin\Settings\SettingsPage\Section\SettingsPageSection $SettingsPageSection 
     */
    protected $SettingsPageSection = null;

    /**
     * 
     * @param \ItForFree\WpAddons\Core\Admin\Settings\SettingsPage\Section\SettingsPageSection $SettingsPageSectio секция, в которую добавляется поле
     * @param string $machineFiledName  машинное имя поля (без пробла) используется в частности для построения полного имени атрибута 
     * @param string $fieldTitle        описание поля. выводимое на форму
     */
    public function __construct($SettingsPageSection, $machineFiledName, $fieldTitle) 
    {
        
        if (empty($SettingsPageSection->getSettingsPage()->getSettingsEntity())) {
            throw new NoSettingsEntityForPageException();
        }
        
        $this->title = $fieldTitle;
        $this->pageSlug = $SettingsPageSection->getSettingsPage()->getSlug();
        $this->strId = $SettingsPageSection->getSettingsPage()->getIdStr() 
                . '_' . $machineFiledName;
        $this->SettingsPageSection = $SettingsPageSection;   
        $this->machineName = $machineFiledName;
    }

    
    /**
     * Регистрирует сущность.
     *
     */
    public function register()
    {
        add_settings_field($this->strId, $this->title, $this->getFieldHtmlCallback(),
            $this->pageSlug, $this->SettingsPageSection->getStrId());
    }
    
    protected function getFieldHtmlCallback()
    {
        
        $SettingsEntity = $this->SettingsPageSection->getSettingsPage()->getSettingsEntity();

        $optionName = $SettingsEntity->getName();
        $filedName = $this->machineName;
        // Вывод чекбоксов для таксономий
        $fieldHtmlCallback = function () use ($optionName, $filedName) {
            $options = get_option($optionName);

            echo "Это БАЗОВОЕ поле, используйте классы-потомки!";
        };
        
        return $fieldHtmlCallback;
    }
    
}
