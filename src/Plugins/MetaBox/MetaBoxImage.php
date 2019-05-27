<?php
namespace ItForFree\WpAddons\Plugins\MetaBox;

use ItForFree\rusphp\PHP\ArrayLib\ArrayElement;

/**
 * Дополнение для работы с дополнительными полями-изображениями. созданными с помощью metabox
 *
 * @author qwe
 */
class MetaBoxImage {
    //put your code here
    
    /**
     * Вернет относительный url картинки (первой, если поле хранит массив) --
     * метод работает на базе вызова.
     * Протестировано на работе с полем типа "image_advanced".
     * 
     * @param string   $key     Meta key. Required.
     * @param array    $args    Array of arguments. Optional.
     * @param int|null $post_id Post ID. null for current post. Optional.
     * @param string $base     Опционально: базовый путь к папке загрзуок, по умолчанию: '/wp-content/uploads/'
     * @return srting         ПУть к изображению или пустая строка в случае неудачи
     */
    public static function getPath($key, $args = array(), 
            $post_id = null, $base = '/wp-content/uploads/')
    {
        
        $result  = '';
        /**
         * @todo для Advanced Image плагин метабокс судя по всему всегда хранит массив, даже если в настройках указано, что загружать нужно одно изображение
         */
        $filedData = ArrayElement::getFirst(
            rwmb_meta($key, $args, $post_id)
        );
        
        
        if (!empty($filedData)) {
            $result = $base . $filedData['file'];
        }
        
        return $result;
    }
}
