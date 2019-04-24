<?php

namespace ItForFree\WpAddons\Core\Page;

/**
 * Класс для работы со страницей (в смысле браузерной) (напр. определения текущего контекста)
 * wordpress
 */
class CurrentPage
{
    /**
     * Проверит, что текущая страница отностися к единственной записи (посту)
     * 
     * @param string $postTypeName тип на соответствие которому надо проверить
     * @return boolean
     */
    public static function isPostOfType($postTypeName)
    {
        return (is_single() && get_post_type() == $postTypeName);
    }
    
    
    /**
     * Относится ли данная страница к таксономии (опционально можно передать тип)
     * 
     * @param  string|array $taxonomyTypeName  Taxonomy slug or slugs - подходящие типы/тип
     * @return bool
     */
    public static function isTaxonomyItem($taxonomyTypeName = '')
    {
        return is_tax($taxonomyTypeName);
    }
}
