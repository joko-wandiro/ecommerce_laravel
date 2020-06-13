<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Scaffolding;
use Form;
use Html;
use DB;

class CategoriesController extends ApiController
{

    protected $table = 'categories';

    /**
     * Posts Page
     *
     * @return Illuminate\View\View
     */
    public function index()
    {
        $Scaffolding = clone $this->scaffolding;
        // Modify record for ListView
        $Scaffolding->addHooks("listModifyRecord", array($this, "listModifyRecord"));
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
            $record['url'] = url_category($record['categories.name']);
            unset($record['actions']);
            $result[] = $record;
        }
        return $result;
    }

}
