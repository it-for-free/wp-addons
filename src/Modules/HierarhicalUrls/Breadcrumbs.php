<?php

namespace ItForFree\WpAddons\Modules\HierarhicalUrls;

use ItForFree\WpAddons\Core\Taxonomy\TaxonomyCategory;
use ItForFree\rusphp\Common\Ui\Breadcrumbs\Breadcrumbs as IffBreadcrumbs;

/**
 * Хлебные крошки для модуля иерахических ссылок
 */
class Breadcrumbs
{

    
    public static function getForTaxonomyItem()
    {
            $term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
            
            
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
    

    protected static function getUrlForTerm($WP_Term)
    {
        $taxonomySlug = TaxonomyCategory::getTaxonomy($WP_Term)->slug;
        vpre(TaxonomyCategory::getTaxonomy($WP_Term), 'taxonomy');
        $hierarhicalPath =  TaxonomyCategory::getHierarhicalUrl($WP_Term);
        return "/$taxonomySlug/$hierarhicalPath/$WP_Term->slug";
    }
}
