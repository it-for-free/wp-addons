<?php

namespace ItForFree\WpAddons\Core\Common;


/**
 * Общие вызовы для WP
 */
class WP
{
    /**
     * Проверит что результат не пуст, и что это не ошибка класса WP_Error
     * @param mixed $value
     * @return type boolean
     */
   public static function notEmptyNotError($value)
   {
       $isWpError = !empty($value) 
            && is_object($value) 
            && (get_class($value) === 'WP_Error');
       $result = !empty($value) && !$isWpError;
       
       return  $result;
   }
}