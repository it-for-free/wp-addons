<?php

namespace  ItForFree\WpAddons\Core\Admin\Settings\SettingsPage;

use ItForFree\WpAddons\Core\Exception\WpAddonsCoreException;
use ItForFree\WpAddons\Core\Admin\Settings\SettingsPage\Section\SettingsPageSection;
use ItForFree\WpAddons\Core\Admin\Settings\SettingsEntity;

/**
 * Класс для описания страницы раздела "Настройки".
 * 
 * В даной реализации работает с одной насnройкой и произвольном числом разделов и полей,
 * являясь для них первичным объектом - -те..е сначала надо создать, объект страрницы, потом добавить в неё:
 * настройку, секции и поля и потом зарегистрировать страницу, что приведе к регитсрации и добавленных сущностей.
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
     * Секции страницы
     * @var \ItForFree\WpAddons\Core\Admin\Settings\SettingsPage\Section\SettingsPageSection[]
     */
    protected $sections;
    
    /**
     * 
     * @param string $optionPageUniqueId  уникальный строковый ключ (кратко характеризующий страницу, можно абривеатуру, используется для формаирования различных имен)
     * @param string $title       Заголовок страницы 
     * @param string $menuTitle   название пункта меню для даной страницы
     * @param string $formTitle   (необязательно) Заголовок фромы настроек
     * @param string $capability  (необязательно) название вида доступа, по-умолчанию 'manage_options'
     * @throws WpAddonsCoreException
     */
    public function __construct($optionPageUniqueId, $title, $menuTitle, 
            $formTitle = 'Настройки плагина', $capability = 'manage_options' ) {
        
        if (!empty($optionPageUniqueId)) {
            
            $this->pageIdStr = $optionPageUniqueId;
            $this->title = $title;
            $this->menuTitle = $menuTitle;
            $this->capability = $capability;
            $this->formTitle = $formTitle;
            
            $this->slug = $this->pageIdStr . '-options-page';
            
           $this->register(); // можно вызывать и снаружи.
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
     * -- фактически подключает сформированный объект страницы, и вложенные в него объекты элементов к 
     * стандартному хуку WP.
     * 
     * ВНИМАНИЕ: вызывайте этот метод ПОСЛЕ добавления всех сущностей
     *  (на данный момент автоматически вызывается конструктором, 
     * так как обработчик хука фактически ссылается на объект, 
     * который дополняется уже после фромальной регистрации, 
     * но хук по факту вызывается после заполнения объекта сущностями,
     *  если возникнут проблемы - придётся делать этот метод публичным и
     * вызывать снаружи, А не автоматически в конструкторе).
     */
    protected function register()
    {  
        $thisPage = $this;
        $registerPageElementsCallback = function() use ($thisPage) {
            $thisPage->registerPagePrint();
            $thisPage->registerPageItems();
        };
        add_action('admin_menu', $registerPageElementsCallback);
    }
    
    /**
     * Регистрация сущностей страницы средствами WP API 
     */
    protected function registerPageItems()
    {
        $settingsEntity = $this->settingsEntity;
        $sections = $this->sections;

        $registerPageItemsCallback = function() use ($settingsEntity, $sections) {

            $settingsEntity->register();
            
            foreach ($sections as $Section) {
                $Section->register();
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
        $pageTitle = $this->title;
         
        $printPageContent = function() use ($settingsPageSlug, $capability, 
            $SettingsEntity, $pageTitle) {
            if (!current_user_can($capability)) {
                wp_die('У вас нет прав на доступ к этой странице.');
    //            $options = get_option('htpu_options');
            }
            
            if (!empty($SettingsEntity)) { 
                ?>
                <div class="wrap">
                    <h2><?php echo $pageTitle; ?></h2>
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
        return $this;
    }
    
    /**
     * Создаст и добавит настройку, с которой и должна работать страница
     * 
     * @todo в теории этот вызом можно было бы делать прямо в конструкторе, но тогда он был бы утяжелен 
     * 
     * @param callable $validateCallback необязательный колбек валидации значения
     * @return $this
     */
    public function createAndAddSettingsEntity($validateCallback = null)
    {
        $name = $this->pageIdStr . '_options';
        $entity = new SettingsEntity($name,  $name, $validateCallback);
        $this->addSettingsEntity($entity);
        return $this;
    }
    
    /**
     * Создаст и добавит секцию (раздел) на данную страницу настроек
     * 
     * @param string $machineName   уникальное (в рамках страницы) машинное имя
     * @param string $sectionTitle   заголовок раздела
     * @param string $sectionContent текст/контент секци (помимо полей форм, который описываются отдельно)
     * @return $this
     */
    public function createAndAddSection($machineName, $sectionTitle, $sectionContent)
    {
//        $strId = $this->getIdStr() . "_$machineName" . '_settings';
        $strId = $machineName;
        $Section = new SettingsPageSection($strId, $sectionTitle, $this,
            $sectionContent);
        $this->addSection($Section);
        return $this;
    }
     
    /**
     * Добавление секции на страницу
     * 
     * @param \ItForFree\WpAddons\Core\Admin\Settings\SettingsPage\Section\SettingsPageSection $SettingsPageSection раздел страницы
     * @return $this
     */
    public function addSection($SettingsPageSection)
    {
        $this->sections[] = $SettingsPageSection; 
        return $this;
    }
    
    /**
     * Поиск раздела страницы настроек по id (имени)
     * 
     * @param srting $sectionMachineName  уникальное в рамках страницы име секции (задается при создании)
     * @return \ItForFree\WpAddons\Core\Admin\Settings\SettingsPage\Section\SettingsPageSection|null
     */
    public function getSectionById($sectionMachineName)
    {
        $ResultSection = null;
        foreach ($this->sections as $Section)
        {
            if ($Section->getStrId() == $sectionMachineName) {
                $ResultSection = $Section;
                break;
            }
        }
        return $ResultSection;
    }
}
