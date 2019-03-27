<?php

namespace  ItForFree\WpAddons\Core\Admin\Settings;

/**
 * 
 */
class SettingsEntity {
    
    protected $groupName = '';
    protected $name= '';

    
    public function __construct($groupName, $name) {
        $this->groupName = $groupName;
        $this->name = $name;
    }
    
    public function getGroupName()
    {
        return $this->groupName;
    }
    
    public function getName()
    {
        return $this->name;
    }

    /**
     * 
     * @param \ItForFree\WpAddons\Core\Admin\Settings\SettingsPage\SettingsPage $SettingsPage
     */
    public static function getForSettingsPage($SettingsPage)
    {
        $name = $SettingsPage->getIdStr() . '_options';
        return new SettingsEntity($name,  $name);
    }
    
    /**
     * Регистрирует сущность.
     * 
     * @todo нет валидатора
     */
    public function register()
    {
        // Валидация настроек
        $options_validate = function($input) {
            return $input;
        };
    
        register_setting($this->groupName, $this->name, $options_validate);
    }
    
}
