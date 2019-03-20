<?php

namespace ItForFree\WpAddons\Modules\HierarhicalUrls;

use ItForFree\WpAddons\Core\Taxonomy\TaxonomyCategory;
use ItForFree\rusphp\Common\Ui\Breadcrumbs\Breadcrumbs as IffBreadcrumbs;
use ItForFree\WpAddons\Core\Post\Post;

/**
 * Хлебные крошки для модуля иерахических ссылок
 */
class Breadcrumbs
{
    /**
     * Построит хлебные крошки  для текущей страницы таксономии или для переданного элемента
     * 
     * @return \ItForFree\rusphp\Common\Ui\Breadcrumbs\Breadcrumbs объект хлебных крошек
     */
    public static function getForTaxonomyItem($term = null)
    {
        if (empty($term)) {    
            $term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
        }
            
//            vpre($term, 'term');
            
            $parents = TaxonomyCategory::getParentsList($term);
            
            $Bcmrs = new IffBreadcrumbs();
//            vpre($parents, 'Родители');
            
            if(!empty($parents)) {
                // For each parent, create a breadcrumb item
                foreach ($parents as $parent) {
                    $Bcmrs->add($parent->name, static::getUrlForTerm($parent));
                }
            
            }
            // Display the current term in the breadcrumb
            $Bcmrs->current = $term->name;
            
            return  $Bcmrs;
        
    }
    
    /**
     * Построит хлебные крошки для переданного поста
     * 
     * @param WP_Post $WP_Post
     * @param string $taxonomyName  имя таксономии
     * @return \ItForFree\rusphp\Common\Ui\Breadcrumbs\Breadcrumbs
     */
    public static function getForPost($WP_Post, $taxonomyName)
    {
        $Term = Post::getFirstTaxonomyItem($WP_Post, $taxonomyName);
        $Bcmrs = static::getForTaxonomyItem($Term);
        $Bcmrs->add($Term->name, static::getUrlForTerm($Term)); // добавляем непосредственного родителя нашей записи
        $Bcmrs->current = $WP_Post->post_title; // устанавливаем имя

        return  $Bcmrs;
    }
    

    protected static function getUrlForTerm($WP_Term)
    {
        $taxonomySlug = TaxonomyCategory::getTaxonomy($WP_Term)->getUrlName();
//        vpre(TaxonomyCategory::getTaxonomy($WP_Term), 'taxonomy');
        $hierarhicalPath =  TaxonomyCategory::getHierarhicalUrl($WP_Term);
        return "/$taxonomySlug/$hierarhicalPath";
    }
}
