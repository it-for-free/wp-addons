<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Assets
 *
 * @author qwe
 */
class Assets {
    
    public static function printNames()
    {
        function crunchify_print_scripts_styles() {
    //  имена всех загруженных JS Scripts
    global $wp_scripts;
    foreach( $wp_scripts->queue as $script ) :
        echo $script . '  **  ';
    endforeach;
  
    //  имена (идентификаторы) всех загруженных CSS
    global $wp_styles;
    foreach( $wp_styles->queue as $style ) :
        echo $style . '  ||  ';
    ppre($style);
    endforeach;
}
  
add_action( 'wp_print_scripts', 'crunchify_print_scripts_styles' );
    }
}
