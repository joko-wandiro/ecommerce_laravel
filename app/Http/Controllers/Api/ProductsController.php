<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Scaffolding;
use Form;
use Html;
use DB;

class ProductsController extends ApiController
{

    protected $table = 'products';

    /**
     * Product Page
     *
     * @return Illuminate\View\View
     */
    public function product($name)
    {
        $name = str_replace("-", " ", $name);
        // Set request uri parameters
        $Request = request();
        $Request->query->set('action', 'view');
        $Request->query->set('indexes', array(
            'name' => $name
        ));
        $Request->query->set('identifier', 'products');
        $this->index();
    }

    /**
     * Products Category Page
     *
     * @return Illuminate\View\View
     */
    public function products_category($category_name)
    {
        $category_name = str_replace("-", " ", $category_name);
        request()->query->set('category_name', $category_name);
        $this->index();
    }

    /**
     * Index Page
     *
     * @return Illuminate\View\View
     */
    public function index()
    {
        $Scaffolding = clone $this->scaffolding;
        $Scaffolding->join('categories', 'categories.id', '=', 'products.id_category', 'INNER');
        $records_per_pages = array(
            8 => sprintf(trans('dkscaffolding.show.entries'), 8),
        );
        $Scaffolding->setListOfRecordsPerPage($records_per_pages);
        // Modify record for ListView
        $Scaffolding->addHooks("listModifyRecord", array($this, "listModifyRecord"));
        // Modify query for search
        $Scaffolding->addHooks("listModifySearch", array($this, "listModifySearch"));
        $Scaffolding->setContentType('json')->render();
    }

    /**
     * Formatter for ListView
     * 
     * @param \App\Libraries\Scaffolding\Model $model
     * 
     * @return void
     */
    public function listModifyRecord($records)
    {
        $result = array();
        foreach ($records as $record) {
            $record['url'] = url_product($record['products.name']);
            unset($record['actions']);
            $result[] = $record;
        }
        return $result;
    }

    /**
     * Hook Filter - Modify query for search
     * 
     * @param \App\Libraries\Scaffolding\Model $model
     * 
     * @return void
     */
    public function listModifySearch($Model)
    {
        $Model = $Model->where('categories.name', '=', request('category_name'));
        return $Model;
    }

}
