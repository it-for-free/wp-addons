<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ItForFree\WpAddons\Helper\Debug;

/**
 * Для отладки маршрутов и всего что с ними связано
 */
class Template {
    
    /**
     * Вернет использование 
     * 
     * @global WP_Rewrite $wp_rewrite
     * @retrun array
     */
    public static function getCurrentFile()
    {
        global $wp_rewrite;  
        return $wp_rewrite->rules;
    }
}
