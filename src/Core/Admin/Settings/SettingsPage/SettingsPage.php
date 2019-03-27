<?php

namespace  ItForFree\WpAddons\Core\Admin\Settings\SettingsPage;

/**
 * Класс для описания страницы раздела "Настройки".
 * 
 * В даной реализации работает с одной натсройкой и произвольном числом разделов и полей
 * 
 * В основе идеи пример: http://fkn.ktu10.com/?q=node/10801
 */
class SettingsPage {
    
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
     * Группа настроек даной страницы
     * @var \ItForFree\WpAddons\Core\Admin\Settings\SettingsEntity
     */
    protected $settingsEntity;
    

    /**
     * 
     * @params string $optionPageUniqueId
     */
    public function __construct($optionPageUniqueId, $title, $menuTitle, $capability = 'manage_options' ) {
        
        if (!empty($optionPageUniqueId)) {
            
            $this->pageIdStr = $optionPageUniqueId;
            $this->title = $title;
            $this->menuTitle = $menuTitle;
            $this->capability = $capability;
            
            $this->slug = $this->pageIdStr . '-options-page';
            
           $this->register();
        } else {
           throw new \Exception('Не передан id страницы!'); 
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

    
    public function register()
    {

        $this->registerPagePrint();
        

    }
    
    protected function registerPageItems()
    {
        add_action('admin_init', 'htpu_register_settings');
        
        
        function htpu_register_settings() {
            register_setting('htpu_options', 'htpu_options', 'htpu_options_validate');

            add_settings_section('htpu_settings', 'Используемые таксономии', 'htpu_section_text', 'htpu-options');
            add_settings_field('htpu_checked_taxonomies', 'Включить плагин для таксономий:', 'htpu_checked_taxonomies', 'htpu-options', 'htpu_settings');
        }
    }
    
    
    /**
     * Отвечает за вывод (средства WP API) страницы в конечном итоге 
     */
    protected function registerPagePrint()
    {
        $settingsPageSlug = $this->slug;
        $capability = $this->capability;
        $SettingsEntity = $this->settingsEntity;
         
        $printPageContent = function() use ($settingsPageSlug, $capability, $SettingsEntity) {
            if (!current_user_can($capability)) {
                wp_die('У вас нет прав на доступ к этой странице.');
    //            $options = get_option('htpu_options');
            }
            
            if (!empty($SettingsEntity)) { 
                ?>
                <div class="wrap">
                    <h2><?php echo 'Hierarchical URLs'; ?></h2>
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
    
    public function addSection($SettingsPageSection)
    {
        $this->sections = $SettingsPageSection; 
    }

    
}
