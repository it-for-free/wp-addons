<?php

namespace ItForFree\WpAddons\Helper\Debug;

/**
 * Для подключения JS и CSS файлах на разных страницах 
 * (в планах/уже есть: сам сайт, форма входа логина, админка)
 *
 */
class AssetsDebugger 
{
    
    public static function printIds()
    {
        $handler = function () {
            //  имена всех загруженных JS Scripts
            global $wp_scripts;
            $js = array();
            foreach($wp_scripts->queue as $script):
                $js[] =  $script;
            endforeach;

            //  имена (идентификаторы) всех загруженных CSS
            global $wp_styles;
            $css = array();
            foreach($wp_styles->queue as $style):
                $css[] =  $style;
            endforeach;

            vpre($js, 'Javascript files ids');
            vpre($css, 'CSS files ids');
        };
  
        add_action('wp_print_scripts', $handler);
    }
}
