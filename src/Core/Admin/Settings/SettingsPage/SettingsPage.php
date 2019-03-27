<?php

namespace  ItForFree\WpAddons\Core\Admin\Settings\SettingsPage;

use ItForFree\WpAddons\Core\Exception\WpAddonsCoreException;

/**
 * Класс для описания страницы раздела "Настройки".
 * 
 * В даной реализации работает с одной натсройкой и произвольном числом разделов и полей
 * 
 * В основе идеи пример: http://fkn.ktu10.com/?q=node/10801
 */
class SettingsPage 
{
    
    protected $pageIdStr = '';
    protected $title = 'Заголовок страницы';
    protected $menuTitle = 'Элемент меню';
    
    /**
     * @var string название вида доступа 
     */
    protected $capability = 'manage_options';
    
    /**
     * @var string url-имя страниц 
     */
    protected $slug = '';
    
    /**
     *
     * @var srting Заголовок фромы настроек
     */
    protected $formTitle = '';
    
    
    /**
     * Группа настроек даной страницы
     * @var \ItForFree\WpAddons\Core\Admin\Settings\SettingsEntity
     */
    protected $settingsEntity;
    
    /**
     * Поля секций
     * @var \ItForFree\WpAddons\Core\Admin\Settings\SettingsPage\Section\Filed\BaseSectionField[] 
     */
    protected $sectionFields;
    
    /**
     * Секции страницы
     * @var \ItForFree\WpAddons\Core\Admin\Settings\SettingsPage\Section\SettingsPageSection[]
     */
    protected $sections;
    
    /**
     * 
     * @params string $optionPageUniqueId
     */
    public function __construct($optionPageUniqueId, $title, $menuTitle, $formTitle = 'Настройки плагина', $capability = 'manage_options' ) {
        
        if (!empty($optionPageUniqueId)) {
            
            $this->pageIdStr = $optionPageUniqueId;
            $this->title = $title;
            $this->menuTitle = $menuTitle;
            $this->capability = $capability;
            $this->formTitle = $formTitle;
            
            $this->slug = $this->pageIdStr . '-options-page';
            
           $this->register();
        } else {
           throw new WpAddonsCoreException('Не передан id страницы!'); 
        }
    }
    
    /**
     * Вернет объект настройки, с которым работает данная страница
     * 
     * @return \ItForFree\WpAddons\Core\Admin\Settings\SettingsEntity
     */
    public function getSettingsEntity()
    {
        return $this->settingsEntity;
    }
    
    public function getIdStr()
    {
        return $this->pageIdStr;
    }
    
    
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Регистрирует страницу и все добавленные на неё сущности
     * (автоматически проводит регистрацию как минимум: настройки, секций и полей)
     * 
     * ВНИМАНИЕ: вызывайте этот метод после добавления всех сущностей
     */
    public function register()
    {
        $this->registerPagePrint();
        $this->registerPageItems();

    }
    
    /**
     * Регистрация сущностей страницы средствами WP API 
     */
    protected function registerPageItems()
    {
        $settingsEntity = $this->settingsEntity;
        $sections = $this->sections;
        $fields = $this->sectionFields;

        $registerPageItemsCallback = function() use ($settingsEntity, $sections, $fields) {

            $settingsEntity->register();
            
            foreach ($sections as $Section) {
                $Section->register();
            }
            foreach ($fields as $Field) {
                 $Field->register();
                
            }
        };
        
        add_action('admin_init', $registerPageItemsCallback);
    }
    
    
    /**
     * Отвечает за вывод (средства WP API) страницы в конечном итоге 
     */
    protected function registerPagePrint()
    {
        $settingsPageSlug = $this->slug;
        $capability = $this->capability;
        $SettingsEntity = $this->settingsEntity;
        $formTitle = $this->formTitle;
         
        $printPageContent = function() use ($settingsPageSlug, $capability, 
            $SettingsEntity, $formTitle) {
            if (!current_user_can($capability)) {
                wp_die('У вас нет прав на доступ к этой странице.');
    //            $options = get_option('htpu_options');
            }
            
            if (!empty($SettingsEntity)) { 
                ?>
                <div class="wrap">
                    <h2><?php echo $formTitle; ?></h2>
                    <form method="post" action="options.php">
                        <?php settings_fields($SettingsEntity->getGroupName()); ?>
                        <?php do_settings_sections($settingsPageSlug); ?>
                        <?php submit_button(); ?>
                    </form>
                </div>
                <?php
            }
        };
        
        add_options_page($this->title, $this->menuTitle, $this->capability, $this->slug, $printPageContent);
    }
    
    
    /**
     * Добавит настройку, с которой и должна работать страница
     * 
     * @param  \ItForFree\WpAddons\Core\Admin\Settings\SettingsEntity $SettingsEntity
     */
    public function addSettingsEntity($SettingsEntity)
    {
        $this->settingsEntity = $SettingsEntity; 
    }
    
    /**
     * Добавление секции на страницу
     * 
     * @param \ItForFree\WpAddons\Core\Admin\Settings\SettingsPage\Section\SettingsPageSection $SettingsPageSection
     */
    public function addSection($SettingsPageSection)
    {
        $this->sections[] = $SettingsPageSection; 
    }
    
    /**
     * Добавление поля секции на страницу
     * 
     * @param \ItForFree\WpAddons\Core\Admin\Settings\SettingsPage\Section\Filed\BaseSectionField $SectionField
     */
    public function addSectionField($SectionField)
    {
        $this->sectionFields[] = $SectionField; 
    }
}
