<?php

namespace  ItForFree\WpAddons\Core\Admin\Settings\SettingsPage\Section\Filed;


/**
 * Исключение отсутствия  сущности настройки для страницы настроек 
 */
class NoSettingsEntityForPageException extends \ItForFree\WpAddons\Core\Exception\WpAddonsCoreException
{
    
    protected $message = "Для страницы настроек не указана сущность настройки! "
            . "Её необходимо указать до того, как вы будтее добавлять поле";
    
    /**
     * Исключение отсутствия  сущности настройки для страницы настроек
     * 
     * @param string $message  сообщение
     * @param int $code        код ошибки
     * @param \Exception $previous
     */
    public function __construct($message = '', $code = 1, $previous = null) {
         
        if (empty($message)) {
            $message = $this->message;
        }
        
        // убедитесь, что все передаваемые параметры верны
        parent::__construct($message, $code, $previous);
    }

 
}