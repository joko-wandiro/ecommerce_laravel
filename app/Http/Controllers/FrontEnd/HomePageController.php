<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use Auth;
use MenuBuilder;
use Route;
use App\Models\Posts;
use App\Models\Comments;
use App\Models\Categories;
use App\Models\Tags;
use App\Models\Pages;
use App\Models\Settings;
use DB;
use Scaffolding;
use Form;

class HomePageController extends FrontEndController
{

    /**
     * Home Page
     * 
     * @return Illuminate\View\View
     */
    public function index()
    {
        // Get categories
        $categories = Categories::gets();
        $parameters = $this->getParameters();
        $parameters['id_page'] = "homepage";
        $parameters['categories'] = $categories;
        return view('frontend.themes.ecommerce.index', $parameters)->render();
    }

}
