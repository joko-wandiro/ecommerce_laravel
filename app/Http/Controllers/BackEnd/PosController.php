<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Scaffolding;
use Form;
use DB;
use Validator;
use App\Models\Categories;
use App\Models\Products;
use App\Models\Orders;
use App\Models\OrderProducts;

class PosController extends BackEndController
{

    protected $table = 'orders';
    protected $masterView = 'backend.themes.vish.pos';

    /**
     * Constructor
     * 
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->js = array();
        $this->css = array();
        // Add css files
        $this->addCSS('font.awesome', 'css/font-awesome.min.css');
        $this->addCSS('jquery.ui', 'css/jquery-ui.css');
        $this->addCSS('bootstrap', 'css/bootstrap.min.css');
        $this->addCSS('dkscaffolding', 'css/vishscaffolding.css');
        $this->addCSS('ie10', 'css/ie10-viewport-bug-workaround.css');
        $this->addCSS('themes', 'css/themes/vish/theme.css');
        $this->addCSS('responsive', 'css/themes/vish/responsive.css');
        $this->addJS('jquery', 'js/jquery.min.js');
        $this->addJS('jquery.ui', 'js/jquery-ui.min.js');
        $this->addJS('jquery.blockUI', 'js/jquery.blockUI.js');
        $this->addJS('bootstrap', 'js/bootstrap.min.js');
        $this->addJS('jquery.validate', 'js/jquery.validate.min.js');
        $this->addJS('ie10', 'js/ie10-viewport-bug-workaround.js');
        $this->addJS('zerobox', 'js/zerobox.js');
        $this->addJS('zerovalidation', 'js/zerovalidation.js');
        $this->addJS('zeromask', 'js/zeromask.js');
        $this->addJS('site', 'js/backend/site.js');
        $this->addJS('ingoods', 'js/backend/order.js');
        $this->addJS('exgoodsdetail', 'js/backend/order_product.js');
//        $Products = new Products;
//        $this->jsParameters['products'] = $Products->gets();
    }

    public function show($id)
    {
        // Render
        $ProductsModel = new Products;
        $products = $ProductsModel->gets();
        $order = Orders::gets($id);
        // Is valid record
        if (!$order) {
            // Redirect to List Page
            // Set session flash for notify Entry is not exist
            return redirect(action(config('app.backend_namespace') . 'OrdersController@index'))
                            ->with('dk_orders_info_error', trans('dkscaffolding.no.entry'));
        }
        $order_products = Orders::get_products($id);
        $parameters = array(
            'id_page' => "struk",
            'products' => $products,
            'order' => $order,
            'order_products' => $order_products,
        );
        $this->masterView = 'backend.themes.vish.struk';
        return $this->render($parameters);
    }

}
