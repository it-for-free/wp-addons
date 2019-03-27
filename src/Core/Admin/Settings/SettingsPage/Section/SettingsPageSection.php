<?php

namespace  ItForFree\WpAddons\Core\Admin\Settings\SettingsPage\Section;

/**
 * Класс для описания секции страницы настроек
 * 
 * В основе идеи пример: http://fkn.ktu10.com/?q=node/10801
 */
class SettingsPageSection {
    
    protected $strId = '';
    protected $title= '';
    protected $pageSlug='';
    
    
    /**
     * Cтраница, к которой относится секция
     * @var \ItForFree\WpAddons\Core\Admin\Settings\SettingsPage\SettingsPage 
     */
    protected $settingsPage = null;
    
    public $content = '';

    
    /**
     * 
     * @param string $strId  уникальное строковое имя
     * @param string $title  заголовок раздела
     * @param \ItForFree\WpAddons\Core\Admin\Settings\SettingsPage\SettingsPage $SettingsPage  страница, к которой относится раздел
     * @param string $content контент раздела
     *
     */
    public function __construct($strId, $title, $SettingsPage,   $content = '[Текст, описывающий эту секцию(раздел)]') {
        $this->strId = $strId;
        $this->title = $title;
        $this->settingsPage = $SettingsPage;
        $this->pageSlug = $SettingsPage->getSlug();
        $this->content = $content;
    }
    
    /**
     * Страница, к которой относится данная секция
     * 
     * @return \ItForFree\WpAddons\Core\Admin\Settings\SettingsPage\SettingsPage
     */
    public function getSettingsPage()
    {
        return $this->settingsPage;
    }
    
    public function getStrId()
    {
        return $this->strId;
    }

    /**
     * Обертка для более краткого получаения объекта (стандартизация некоторых машинных имен)
     * 
     * @param \ItForFree\WpAddons\Core\Admin\Settings\SettingsPage\SettingsPage $SettingsPage
     */
    public static function getForSettingsPage($SettingsPage, $sectionTitle, $sectionContent)
    {
        $strId = $SettingsPage->getIdStr() . '_settings';
        return new SettingsPageSection($strId, $sectionTitle, 
            $SettingsPage->slug, $sectionContent);
    }
    
    /**
     * Регистрирует сущность.
     * 
     * @todo нет валидатора
     */
    public function register()
    {
        
        $content = $this->content;
        // Валидация настроек
        $sectionContentCallback = function() use ($content) {
            echo $content;
        };

        add_settings_section($this->strId, $this->title, $sectionContentCallback, $this->pageSlug);
    }
    
    protected function getContentCallback()
    {
        
    }
    
}
