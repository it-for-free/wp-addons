<?php

namespace ItForFree\WpAddons\Helper\Assets;

/**
 * для работы с подключением JS и CSS
 */
class Assets
{
   /**
    * Добавит CSS на страницу логина.
    * В своей основе ставит обработчик хука, в котором вызывается wp_enqueue_style()
    * 
    * Registers the style if source provided (does NOT overwrite) and enqueues.
    *
    * @see WP_Dependencies::add()
    * @see WP_Dependencies::enqueue()
    * @link https://www.w3.org/TR/CSS2/media.html#media-types List of CSS media types.
    *
    * @since 2.6.0
    *
    * @param string           $handle Уникальной имя файла стилей 
    * @param string           $src    относительный или абсоюлтный URL этого файла
    * @param array            $deps   Зависимости - массив строк идентификаторов других CSS @see http://fkn.ktu10.com/?q=node/10825
    * @param string|bool|null $ver    Опционально: версия. String specifying stylesheet version number, if it has one, which is added to the URL
    *                                 as a query string for cache busting purposes. If version is set to false, a version
    *                                 number is automatically added equal to current installed WordPress version.
    *                                 If set to null, no version is added.
    * @param string           $media  Опционально: медиа-тип. The media for which this stylesheet has been defined.
    *                                 Default 'all'. Accepts media types like 'all', 'print' and 'screen', or media queries like
    *                                 '(orientation: portrait)' and '(max-width: 640px)'.
    */
    public static function addCssToLoginPage($handle, $src = '', $deps = array(), $ver = false, $media = 'all')
    {
        $handler = function() use ($handle, $src, $deps, $ver, $media)  {  
            wp_enqueue_style($handle, $src, $deps, $ver, $media);
        };

        add_action('login_enqueue_scripts', $handler);
    }
    
    
   /**
    * Добавит JS файл на сайт (не админка, не страница логина).
    * В своей основе ставит обработчик хука, в котором вызывается wp_enqueue_style()
    * 
    * Registers the style if source provided (does NOT overwrite) and enqueues.
    *
    * @see WP_Dependencies::add()
    * @see WP_Dependencies::enqueue()
    * @link https://www.w3.org/TR/CSS2/media.html#media-types List of CSS media types.
    *
    *
    * @param string           $handle Уникальной имя файла скрипта
    * @param string           $src    относительный или абсоюлтный URL этого файла
    * @param array            $deps   Зависимости - массив строк идентификаторов других CSS @see http://fkn.ktu10.com/?q=node/10825
    * @param string|bool|null $ver    Опционально: версия. String specifying stylesheet version number, if it has one, which is added to the URL
    *                                 as a query string for cache busting purposes. If version is set to false, a version
    *                                 number is automatically added equal to current installed WordPress version.
    *                                 If set to null, no version is added.
    * @param bool             $in_footer Опционально: добавлять ли в футтер (по умолчанию  - в хэдере).
    *                                        Whether to enqueue the script before </body> instead of in the <head>.
    *                                    Default 'false'.
    */
    public static function addJs($handle, $src = '', $deps = array(), $ver = false, $in_footer = false )
    {
        $handler = function() use ($handle, $src, $deps, $ver,  $in_footer)  {  
            wp_enqueue_script($handle, $src, $deps, $ver, $in_footer);
        };

        add_action('wp_head', $handler);
    }
    
    
   /**
    * Добавит CSS на все страницы сайта (не админка и не страница логина)
    * В своей основе ставит обработчик хука, в котором вызывается wp_enqueue_style()
    * 
    * Registers the style if source provided (does NOT overwrite) and enqueues.
    *
    * @see WP_Dependencies::add()
    * @see WP_Dependencies::enqueue()
    * @link https://www.w3.org/TR/CSS2/media.html#media-types List of CSS media types.
    *
    * @since 2.6.0
    *
    * @param string           $handle Уникальной имя файла стилей 
    * @param string           $src    относительный или абсоюлтный URL этого файла
    * @param array            $deps   Зависимости - массив строк идентификаторов других CSS @see http://fkn.ktu10.com/?q=node/10825
    * @param string|bool|null $ver    Опционально: версия. String specifying stylesheet version number, if it has one, which is added to the URL
    *                                 as a query string for cache busting purposes. If version is set to false, a version
    *                                 number is automatically added equal to current installed WordPress version.
    *                                 If set to null, no version is added.
    * @param string           $media  Опционально: медиа-тип. The media for which this stylesheet has been defined.
    *                                 Default 'all'. Accepts media types like 'all', 'print' and 'screen', or media queries like
    *                                 '(orientation: portrait)' and '(max-width: 640px)'.
    */
    public static function addCss($handle, $src = '', $deps = array(), $ver = false, $media = 'all')
    {
        $handler = function() use ($handle, $src, $deps, $ver, $media)  {  
            wp_enqueue_style($handle, $src, $deps, $ver, $media);
        };

        add_action('wp_enqueue_scripts', $handler);
    }
}
