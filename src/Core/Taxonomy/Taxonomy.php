<?php

namespace ItForFree\WpAddons\Core\Taxonomy;

/**
 * Класс для работы сущностями типа "таксономия".
 * Обертка над $WP_Taxonomy.
 * 
 */
class Taxonomy
{
    /**
     * @var WP_Taxonomy стандартный для WP объект таксономии 
     */
    protected $wpTaxonomy = null;
    
    
    public function __construct($WP_Taxonomy) {
        $this->wpTaxonomy = $WP_Taxonomy;
    }
    
    /**
     * Создаст объект таксономии по имени
     * 
     * @param string $taxonomyName
     * @return \ItForFree\WpAddons\Core\Taxonomy\Taxonomy|false
     */
    public static function getByName($taxonomyName)
    {
        $wpTaxonomy = get_taxonomy($taxonomyName);
        
        if (!empty($wpTaxonomy)) {        
            return new Taxonomy($wpTaxonomy);
        } else {
            return false;
        }
    }
    
    /**
     * Вернёт слаг таксономии (если он не пуст), в противном случае - имя.
     * 
     * @return string
     */
    public function getUrlName()
    {
        if (!empty($this->wpTaxonomy->rewrite['slug'])
                && (trim($this->wpTaxonomy->rewrite['slug']) !== '')) {        
            return $this->wpTaxonomy->rewrite['slug'];
        } else {
            return $this->wpTaxonomy->name;
        }
    }
    
    /**
     * Вернет элементы данной таксономии (по умолчанию - все)
     * 
     * @param type $options опции поиска  - передаются в стандартную get_terms()
     * @return WP_Term[]
     */
    public function getTerms($options = array())
    {
        if (empty($options)) {
           $options = array('hide_empty' => false);
        }
        $options  = array_merge($options, 
                array('taxonomy' => $this->wpTaxonomy->name));
        $terms = get_terms($options);
        return $terms;
    }
}
