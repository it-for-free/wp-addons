<?php

namespace ItForFree\WpAddons\Modules\HierarhicalUrls;

use ItForFree\WpAddons\Core\Taxonomy\TaxonomyCategory;

/**
 * Иерархические ссылки для категорий пользовательской таксономии и пользовательского типа контента.
 */
class HierarhicalUrls
{
    /**
     * 
     * @param srting $taxonomyName  название типа таксономии, элементы которого будут использоваться для построения иерархического slug
     */
    public function init($taxonomyName) {
        $this->setHierarhicalPostLinks($taxonomyName);
        $this->setHierarhicalTaxonomyForItems();
    }
    
    
    /**
     * Включит поддержку иерархических ссылок для пользовательского типа записей 
     * 
     * ВНИМАНИЕ: подразумевается что для вашего posttypename и таксономии taxonomyname
     * слизняк (rewrite slug) выставлен как что-то вроде: posttypename/%taxonomyname%
     * 
     * @param type $taxonomyName
     */
    protected function setHierarhicalPostLinks($taxonomyName) 
    {
        
        $postLinkHandler = function ($post_link, $post, $leavename, $sample) use ($taxonomyName) 
        {
             if (false !== strpos($post_link, "%$taxonomyName%")) {
                 $taxonomyItems = get_the_terms($post->ID, $taxonomyName);
                 if (!empty($taxonomyItems)) {
                     $firstRelatedTaxonomyItem = array_pop($taxonomyItems);
                     $post_link = str_replace("%$taxonomyName%", 
                            TaxonomyCategory::getHierarhicalUrl($firstRelatedTaxonomyItem), $post_link);
                 } else {
                     $post_link = str_replace("%$taxonomyName%", 'uncategorized', $post_link);
                 }
             }
             return $post_link;
        };
        
        add_filter('post_type_link', $postLinkHandler, 10, 4);
    }
    
    
    /**
     * Включит поддержку иерархических ссылок для катгеорий пользовательских таксономий 
     * 
     * ВНИМАНИЕ: подразумевается что для вашего posttypename и таксономии с именем taxonomyname
     * слизняк (rewrite slug) выставлен как что-то вроде: posttypename/%taxonomyname%
     * 
     * @param type $taxonomyName
     */
    protected function setHierarhicalTaxonomyForItems()
    {
        $taxonomyRewriteRouteHandler = function ($wp_rewrite) 
        {
        //    ppre('generate_rewrite_rules');
            $rules = array();
            // получаем все пользовательские таксономии
            $taxonomies = get_taxonomies(array('_builtin' => false), 'objects');
            // получаем все поьлзовательские типы записей
            $post_types = get_post_types(array('public' => true, '_builtin' => false), 'objects');
            foreach ($post_types as $post_type) {
                foreach ($taxonomies as $taxonomy) {

                    //перебираем все типы_записей связанные с данной таксономией
                    foreach ($taxonomy->object_type as $object_type) {
                        // проверяем зарегистрирована ли таксономия для данного пользовательского типа (подразумевается, чтобы его слаг совпадает с имененем)
                        if ($object_type == str_replace('/%' . $taxonomy->name . '%',
                                '', $post_type->rewrite['slug'])) {

                            // получаем все категории (разделы, описаные в данной таксономии)
                            $terms = get_categories(array('type' => $object_type, 'taxonomy' => $taxonomy->name, 'hide_empty' => 0));

                            foreach ($terms as $term) {
        //                        ppre(TaxonomyCategory::getHierarhicalUrl($term, $terms));
                                $rules[$object_type 
                                    . '/' . TaxonomyCategory::getHierarhicalUrl(
                                        $term, $terms) . '/?$'] =  'index.php?' . $term->taxonomy . '=' . $term->slug;
                            }
                        }
                    }
                }
            }
            // merge with global rules
        //    ppre('------------');
            ppre($rules);
            $wp_rewrite->rules = $rules + $wp_rewrite->rules;
//            ppre($wp_rewrite->rules);
        };

//        flush_rewrite_rules(); // в продакшене не нужно (сброс маршрутов)
        add_filter('generate_rewrite_rules', $taxonomyRewriteRouteHandler, 0);
    }
    
        public static function bkr()
    {
        
         if ( is_tax() ) {
            echo "<pre>";
                    // Get the current term
            $term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
            
            vpre($term, 'Текущий элемент таксономии');
            
            $parents = TaxonomyCategory::getParentsList($term);
            
//            vpre($parents, 'Родители');
            
            if(!empty($parents)):
//            $parents = array_reverse($parents);
            // For each parent, create a breadcrumb item
            foreach ($parents as $parent):
                $url = get_bloginfo('url').'/'.$parent->taxonomy.'/'.$parent->slug;
                echo '<li><a href="'.$url.'">'.$parent->name.'</a></li>';
            endforeach;
            endif;
            // Display the current term in the breadcrumb
            echo '<li>'.$term->name.'</li>';
            echo "</pre>";
        }
    }
}
