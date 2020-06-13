<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use Auth;
use MenuBuilder;
use Route;
use App\Models\Menus;
use App\Models\Settings;

class FrontEndController extends Controller
{

    public function __construct()
    {
        // Add css files
        $this->addCSS('bootstrap', 'css/frontend/bootstrap.min.css');
        $this->addCSS('font.awesome', 'css/font-awesome.min.css');
        $this->addCSS('dkscaffolding', 'css/vishscaffolding.css');
        $this->addCSS('themes', 'css/themes/vish/theme.css');
        $this->addCSS('responsive', 'css/themes/vish/responsive.css');
        $this->addCSS('owl.carousel', 'css/frontend/owl.carousel.min.css');
        $this->addCSS('owl.theme.default', 'css/frontend/owl.theme.default.min.css');
        $this->addCSS('all', 'css/frontend/all.css');
        $this->addCSS('blog', 'css/frontend/blog.css');
        $this->addCSS('main', 'css/frontend/main.css');
        // Add javascript files
        $this->addJS('jquery', 'js/frontend/jquery.min.js');
        $this->addJS('jquery.validate', 'js/jquery.validate.min.js');
        $this->addJS('jquery.validate.additional', 'js/additional-methods.min.js');        
        $this->addJS('jquery.blockUI', 'js/jquery.blockUI.js');
        $this->addJS('owl.carousel', 'js/frontend/owl.carousel.min.js');
        $this->addJS('dkscaffolding', 'js/jquery.dkscaffolding.js');
        $this->addJS('main', 'js/themes/vish/main.js');
        $this->addJS('homepage', 'js/frontend/homepage.js');
        // Get settings value
        $settings = Settings::get_parameters();
        $GLOBALS['settings'] = $settings;
        $this->jsParameters['settings'] = $settings;
    }

    /**
     * Get parameters
     * 
     * @return  array
     */
    public function getParameters()
    {
//        // Build Menu
//        $columns = array(
//            'menus.page_id',
//            'menus.parent_id',
//            'menus.order',
//            'pages.title',
//            'pages.url',
//        );
//        $Model = new Menus;
//        $records = $Model->join('pages', 'pages.id', '=', 'menus.page_id')
//                        ->orderBy('menus.id', 'ASC')->select($columns)->get();
//        $menus = array();
//        $ct = 1;
//        foreach ($records as $record) {
//            $url = $record['url'];
//            if (settings('homepage') == $record['id']) {
//                $url = '';
//            }
//            $menus[] = array(
//                'id' => $record['page_id'],
//                'name' => $record['title'],
//                'url' => url($url),
//                'parent_id' => $record['parent_id'],
//                'order' => $record['order'],
//            );
//            $ct++;
//        }
//        $menuAttributes = array('class' => 'nav navbar-nav');
//        $dropdownListAttributes = array('class' => 'dropdown');
//        $dropdownMenuAttributes = array('class' => 'dropdown-menu');
//        $dropdownLinkAttributes = array('class' => 'dropdown-toggle', 'data-toggle' => 'dropdown');
//        $MenuBuilder = new MenuBuilder($menus);
//        $menu = $MenuBuilder->setMenuAttributes($menuAttributes)->setCurrentUrl(request()->getUri())->setDropdownListAttributes($dropdownListAttributes)->setDropdownLinkAttributes($dropdownLinkAttributes)->setDropdownMenuAttributes($dropdownMenuAttributes)->setDropdownListElement('<span class="caret"></span>')->setDropdownListElementAttributes(array())->get();        
        $parameters = array(
            'js' => $this->js,
            'css' => $this->css,
            'jsParameters' => array(
                'urls' => array(
                    'site' => url(''),
                    'search' => url('search'),
                ),
            ),
            'author' => 'Joko Wandiro',
//            'menu' => $menu,
        );
        return $parameters;
    }

    /**
     * Add a javascript file
     * 
     * @param string $name
     * @param string $src
     * 
     * @return void
     */
    public function addJS($name, $src)
    {
        $this->js[$name] = $src;
    }

    /**
     * Remove a javascript file
     * 
     * @param string $name
     * 
     * @return void
     */
    public function removeJS($name)
    {
        unset($this->js[$name]);
    }

    /**
     * Add a css file
     * 
     * @param string $name
     * @param string $src
     * 
     * @return void
     */
    public function addCSS($name, $src)
    {
        $this->css[$name] = $src;
    }

    /**
     * Remove a css file
     * 
     * @param string $name
     * 
     * @return void
     */
    public function removeCSS($name)
    {
        unset($this->css[$name]);
    }

}
