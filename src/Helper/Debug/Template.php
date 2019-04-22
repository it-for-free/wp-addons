<?php

namespace ItForFree\WpAddons\Helper\Debug;

/**
 * Для отладки всего, связанного с шаблонами (их файлами)
 *
 */
class Template 
{
    
    /**
     * Путь к файлу шаблона, обертка над get_page_template() 
     * (по факту "конечным" может оказаться другой шаблон)
     * 
     * @return string
     */
    public static function getCurrentTemplatePath()
    {
        return get_page_templates(); 
    }
    
    /**
     * Распечатает путь к файлу шаблона, обертка над get_page_template() 
     * (по факту "конечным" может оказаться другой шаблон)
     */
    public static function printCurrentTemplatePath()
    {
        ppre(get_page_template(), 'Текущий шаблон');  
    }
    
    public static function printTemplateInfo()
    {
        ppre(get_template_directory());
    }
}
