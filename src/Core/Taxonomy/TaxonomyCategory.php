<?php

namespace ItForFree\WpAddons\Core\Taxonomy;

/**
 * Класс для работы с категрией (конкретной записью, таксономии)
 * 
 * @todo Оптимизировать поиск родителя опционально передавая массив, в котором будем искать
 */
class TaxonomyCategory
{
    /**
     * Получит иерерахический слаг для данной категории таксономии - с учетом вложенности
     * 
     * @param WP_Term $Category     элемент таксономии для которого надо построить слаг
     * @param WP_Term[] $allCurrentTaxonomyCategories  напр. результат стандартного вызова get_categories()
     * @return string иерархический slug (фрагмент url) c учетом вложенности категорий
     */
    public static function getHierarhicalUrl($Category, $allCurrentTaxonomyCategories = [])
    {
        $elements[] = $Category->slug;
        while ($Category = static::getParent($Category)) {
           $elements[] .= $Category->slug;
        }
        $result  = implode('/', array_reverse($elements));
        return $result;
        
    }
    
    /**
     * Получит родителя для переданного элемента таксономии (если он есть)
     * 
     * @param WP_Term $WP_Term  элемент таксономии
     * @return WP_Term|false
     */
    public static function getParent($WP_Term)
    {
        $result = false;
        if (!empty($WP_Term->parent)) {
           $result = get_term_by('term_id', $WP_Term->parent, $WP_Term->taxonomy);
        }
        return $result;
    }
    
    
    /**
     * Список всех родителей (ветка в дереве иерархии)
     *  для данной категории таксономии
     * 
     * @param WP_Term $WP_Term  элемент таксономии
     * @return WP_Term[]
     */
    public static function getParentsList($WP_Term)
    {
        $parents = array();
        $Category = $WP_Term;
        while ($Category = static::getParent($Category)) {
           $parents[] = $Category;
        }
        
        if (!empty($parents)) {
            $parents = array_reverse($parents);
        }
        
        return $parents;
    }
    
    
    /**
     * Вернёт объект таксономии,  к которой относится переданный элемент $WP_Term
     * 
     * @param WP_Term $WP_Term элемент таксономии (стандартный для WP)
     * @return \ItForFree\WpAddons\Core\Taxonomy\Taxonomy|false
     */
    public static function getTaxonomy($WP_Term)
    {
        return  Taxonomy::getByName($WP_Term->taxonomy);
    }
}
