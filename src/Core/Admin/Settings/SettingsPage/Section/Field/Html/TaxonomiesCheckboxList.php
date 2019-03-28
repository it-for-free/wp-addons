<?php

namespace  ItForFree\WpAddons\Core\Admin\Settings\SettingsPage\Section\Field\Html;

use ItForFree\WpAddons\Core\Admin\Settings\SettingsPage\Section\Field\BaseSectionField;

/**
 * Список таксономий с возможностью выбрать нужные
 */
class TaxonomiesCheckboxList extends BaseSectionField 
{
    
    protected function getFieldHtmlCallback()
    {
        
        $SettingsEntity = $this->SettingsPageSection->getSettingsPage()->getSettingsEntity();

        $optionName = $SettingsEntity->getName();
        $filedName = $this->machineName;
        // Вывод чекбоксов для таксономий
        $fieldHtmlCallback = function () use ($optionName, $filedName) {
            $options = get_option($optionName);

            $disabled_taxonomies = array('nav_menu', 'link_category', 'post_format');
            foreach (get_taxonomies() as $tax): 
                if (in_array($tax, $disabled_taxonomies)) {
                    continue;
                }    
                ?>
                <input type="checkbox" name="<?= $optionName ?>[<?= $filedName ?>][<?php echo $tax ?>]" value="<?php echo $tax ?>" <?php checked(isset($options[$filedName][$tax])); ?> /> <?php echo $tax; ?><br />
            <?php
            endforeach;
        };
        
        return $fieldHtmlCallback;
    }
    
}
