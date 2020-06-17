<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Auth;
use MenuBuilder;
use Route;
use App\Models\Settings;
use Scaffolding;
use Firebase\JWT\JWT;

class ApiController extends Controller
{

    protected $scaffolding;
    protected $jsParameters = array();
    
    /**
     * Constructor
     * 
     * @return void
     */
    public function __construct()
    {
//        $key = "example_key";
//        $payload = array(
//            "iss" => "http://example.org",
//            "aud" => "http://example.com",
//            "iat" => 1356999524,
//            "nbf" => 1357000000
//        );
//        $jwt = JWT::encode($payload, $key);
//        $decoded = JWT::decode($jwt, $key, array('HS256'));
//        dd($decoded);
        // Set default parameters for Scaffolding
        $this->scaffolding = new Scaffolding($this->table);
//        $this->scaffolding->setTemplate("standard");
    }

    /**
     * Actions column
     * 
     * @param  \App\Libraries\Scaffolding\Model $record
     * @param  \App\Libraries\Scaffolding\ScaffoldingTable $Scaffolding
     * 
     * @return  void
     */
    public function getParameters()
    {
        $parameters = array(
            'jsParameters' => $this->jsParameters,
        );
        return $parameters;
    }

    /**
     * Actions column
     * 
     * @param  \App\Libraries\Scaffolding\Model $record
     * @param  \App\Libraries\Scaffolding\ScaffoldingTable $Scaffolding
     * 
     * @return  void
     */
    public function actionColumn($record, $Scaffolding)
    {
        $url = $Scaffolding->getActionButtonUrls($record);
        echo '<div class="text-center">
	        <div class="btn-group">
	        	<a href="' . $url['edit'] . '" class="btn btn-primary btn-edit">Edit</a>
	        	<a href="' . $url['delete'] . '" class="btn btn-primary btn-remove">Remove</a>
	        </div>
	    </div>';
    }

}
