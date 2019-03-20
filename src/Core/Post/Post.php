<?php

namespace ItForFree\WpAddons\Core\Post;

/**
 * Класс для работы с конкретной записью (WP_Post) 
 */
class Post
{
    
    /**
     * Получит первый элемент указанной таксономии (если таковые вообще есть),
     *  ассоциированный с указанным постом
     * 
     * @param WP_Post $WP_Post      объект текущего поста
     * @param string $taxonomyName  имя таксономии
     * @return WP_Term|boolean
     */
    public static function getFirstTaxonomyItem($WP_Post, $taxonomyName)
    {
        $taxonomyItems = get_the_terms($WP_Post->ID, $taxonomyName);
        if (!empty($taxonomyItems)) {
            return $taxonomyItems[0];
         } else {
             return false;
         }
    }
}
