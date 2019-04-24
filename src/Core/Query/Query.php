<?php

namespace ItForFree\WpAddons\Core\Query;

/**
 * Обертки над WP_Query
 *
 */
class Query 
{
    /**
     * Вернет все записи, соответсвующие данному запросу в виде массива.
     * 
     * Обертка над WP_Query->getPosts()
     * 
     * Пример использования:
     * <pre>
     * use ItForFree\WpAddons\Core\Query\Query;
     * 
     * $currentPost = get_post();   
     * $posts = Query::getPosts(array( // извлекаем все дочерние страницы
     *      'post_type'      => 'page',
     *      'posts_per_page' => -1,
     *      'post_parent'    => $currentPost->ID,
     *      'order'          => 'ASC',
     *      'orderby'        => 'menu_order'
     *  ));
     *
        foreach( $posts as $post ): ...
     * </pre>
     * 
     * @param array $args  аргументы как для  WP_Query @see https://developer.wordpress.org/reference/classes/wp_query/
     * @return WP_Post[]|int[]  массив объектов записей или их id-шников
     */
    public static function getPosts($args)
    {
        $posts = new \WP_Query($args);
        return $posts->get_posts();
    }
}
