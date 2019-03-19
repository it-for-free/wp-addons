<?php

namespace ItForFree\WpAddons\Core;

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
}
