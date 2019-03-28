<?php

namespace  ItForFree\WpAddons\Core\Admin\Settings;

/**
 * Класс для описания сущности настройки (параметра хранимого в БД через админку)
 */
class SettingsEntity 
{
    /**
     *
     * @var srting имя группы настроек
     */
    protected $groupName = '';
    
    /**
     *
     * @var srting собственное имя
     */
    protected $name= '';
    
    /**
     *  Необязательный колбек валидации значения настройки 
     * @var callable 
     */
    protected $validateCallback = null;

    /**
     * 
     * @param string $groupName  имя группы (машинное, лучше без пробелов)
     * @param string $name       имя  (машинное, лучше без пробелов)
     * @param callable $validateCallback необязательный колбек валидации значения
     */
    public function __construct($groupName, $name, $validateCallback = null) {
        $this->groupName = $groupName;
        $this->name = $name;
        $this->validateCallback = $validateCallback;
        
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
     * Регистрирует сущность.
     * 
     * @todo нет валидатора
     */
    public function register()
    {
        if (empty($this->validateCallback)) {
            // Валидация настроек
            $this->validateCallback = function($input) {
                return $input;
            };
        }
    
        register_setting($this->groupName, $this->name, $this->validateCallback);
    } 
}
