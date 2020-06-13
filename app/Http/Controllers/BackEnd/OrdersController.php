<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use Scaffolding;
use Form;
use App\Models\Products;
use App\Models\ExitGoodsDetail;
use App\Models\Warehouses;
use DB;

class OrdersController extends BackEndController
{

    protected $table = 'orders';

    /**
     * Build order page
     *
     * @return Illuminate\View\View
     */
    public function index()
    {
        $Scaffolding = clone $this->scaffolding;
        $Scaffolding->join('customers', 'customers.id', '=', 'orders.id_customer', 'INNER');
        // Set columns properties
        $parameters = array(
            array(
                'name' => 'orders.id',
                'width' => '10%',
            ),
            array(
                'name' => 'DATE_FORMAT(date, "%d %M %Y") AS date',
                'width' => '10%',
            ),
            array(
                'name' => 'customers.phone',
                'width' => '10%',
            ),
            array(
                'name' => 'customers.address',
                'width' => '10%',
            ),
            array(
                'name' => 'total',
                'width' => '10%',
            ),
            // Add Actions custom column
            array(
                'name' => 'xaction',
                'label' => '&nbsp;',
                'width' => '10%',
                'callback' => array($this, 'action_column'),
            ),
        );
        $Scaffolding->setColumnProperties($parameters);
        $visibility = array(
            'create_button' => FALSE,
        );
        $Scaffolding->setVisibilityListElements($visibility);
        // Set formatter for image column
        $Scaffolding->addFormatterColumn('total', array($this, 'formatter_total'));
        // Hooks Action for delete operation ( AJAX Request )
        $Scaffolding->addHooks("deleteModifyResponse", array($this, "deleteModifyResponse"));
        $content = $Scaffolding->render();
        $parameters = array(
            'scaffolding' => $content,
        );
        return $this->render($parameters);
    }

    /**
     * Formatter for total column
     * 
     * @param  \App\Libraries\Scaffolding\Model $model
     * 
     * @return  void
     */
    public function formatter_total($model)
    {
        echo getThousandFormat($model['total']);
    }

    /**
     * Actions column
     * 
     * @param  \App\Libraries\Scaffolding\Model $record
     * @param  \App\Libraries\Scaffolding\ScaffoldingTable $Scaffolding
     * 
     * @return  void
     */
    public function action_column($record, $Scaffolding)
    {
        $url = $Scaffolding->getActionButtonUrls($record);
        $url_view = action(config('app.backend_namespace') . 'PosController@show', array('id' => $record['id']));
        $url_view .= "?action=view";
        echo '<div class="text-center">
            <a href="' . $url_view . '" class="btn btn-view btn-blue" title="' . trans('main.view') . '"><i class="fa fa-file"></i></a>
	    <a href="' . $url['delete'] . '" class="btn btn-remove btn-blue" data-id="' .
        $record['id'] . '" title="' . trans('main.remove') . '"><i class="fa fa-trash"></i></a>
	    </div>';
    }

}
