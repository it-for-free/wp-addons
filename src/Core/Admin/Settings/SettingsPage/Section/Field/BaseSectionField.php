<?php

namespace  ItForFree\WpAddons\Core\Admin\Settings\SettingsPage\Section\Filed;

/**
 * Базовый класс  для работы с полем страницы настроек (относящимся к какой-либо секции этой страницы)
 * 
 */
class BaseSectionField 
{
    
    protected $strId = '';
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
     * @param string $strId
     * @param string $title
     * @param \ItForFree\WpAddons\Core\Admin\Settings\SettingsPage\Section\SettingsPageSection $SettingsPageSection
     *
     */
    public function __construct($SettingsPageSection, $machineFiledName, $fieldTitle) 
    {
        $this->title = $fieldTitle;
        $this->pageSlug = $SettingsPageSection->getSettingsPage()->getSlug();
        $this->strId = $SettingsPageSection->getSettingsPage()->getIdStr() 
                . '_' . $machineFiledName;
        $this->SettingsPageSection = $SettingsPageSection;     
    }

    
    /**
     * Регистрирует сущность.
     *
     */
    public function register()
    {
        add_settings_field($this->strId, $this->title, $$this->getFieldHtmlCallback(),
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

            $disabled_taxonomies = array('nav_menu', 'link_category', 'post_format');
            foreach (get_taxonomies() as $tax) : if (in_array($tax, $disabled_taxonomies))
                    continue;
                ?>
                <input type="checkbox" name="<?= $optionName ?>[<?= $filedName ?>][<?php echo $tax ?>]" value="<?php echo $tax ?>" <?php checked(isset($options[$filedName][$tax])); ?> /> <?php echo $tax; ?><br />
            <?php
            endforeach;
        };
        
        return $fieldHtmlCallback;
    }
    
}
