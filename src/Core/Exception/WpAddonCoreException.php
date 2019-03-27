<?php

namespace ItForFree\WpAddons\Core\Exception;


/**
 * Для описания исключительных ситуций в коде модуля, относящимуся к взаимодействию с ядром WP
 * (стандартных API)
 */
class WpAddonsCoreException extends \Exception
{
    /**
     * Для описания исключительных ситуций в коде модуля, относящимуся к взаимодействию с ядром WP
     * (стандартных API)
     * 
     * @param string $message  сообщение
     * @param int $code        код ошибки
     * @param \Exception $previous
     */
    public function __construct($message, $code = 0, $previous = null) {
        // некоторый код 
     
        // убедитесь, что все передаваемые параметры верны
        parent::__construct($message, $code, $previous);
    }
 
    // Переопределим строковое представление объекта.
    public function __toString() {
        return __CLASS__ . " [{$this->code}]: {$this->message}\n";
    }
 
}