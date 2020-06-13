<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use Scaffolding;
use Form;
use DB;
use Storage;
use App\Models\Orders;

class DashboardController extends BackEndController
{

    protected $table = 'products';
    protected $masterView = 'backend.themes.vish.dashboard';

    /**
     * Build products page
     *
     * @return Illuminate\View\View
     */
    public function index()
    {
        $orders = Orders::get_total_sales();
        $parameters = array(
            'id_page' => "dashboard",
            'orders' => $orders,
        );
        return $this->render($parameters);
    }

}
