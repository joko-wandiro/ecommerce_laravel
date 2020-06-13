<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use Scaffolding;
use Form;
use Illuminate\Http\JsonResponse;
use App\Models\Users;
use App\Models\UserTypes;

class UsersController extends BackEndController
{

    protected $table = 'users';

    /**
     * Get category list
     *
     * @return array
     */
    public function get_user_types_list()
    {
        $Model = new UserTypes;
        $list = array("" => trans('main.select.default'));
        $result = $Model->get()->pluck("name", "id")->all();
        $list = $list + $result;
        return $list;
    }

    /**
     * Build admins page
     *
     * @return Illuminate\View\View
     */
    public function index()
    {
        $user_types_list = $this->get_user_types_list();
        $Scaffolding = clone $this->scaffolding;
        $Scaffolding->join('user_types', 'user_types.id', '=', 'users.id_user_type', 'INNER');
        // Define form input filler for category
        $Scaffolding->setFormInputFiller("id_user_type", $user_types_list);
        // Set columns properties
        $parameters = array(
            array(
                'name' => 'user_types.name AS type',
                'width' => '10%',
            ),
            array(
                'name' => 'name',
                'width' => '40%',
            ),
            array(
                'name' => 'email',
                'width' => '30%',
            ),
            // Add Actions custom column
            array(
                'name' => 'xaction',
                'label' => '&nbsp;',
                'width' => '20%',
                'callback' => array($this, 'actionColumn'),
            ),
        );
        $Scaffolding->setColumnProperties($parameters);
        // Set password
        $Scaffolding->addHooks("insertModifyRequest", array($this, "setPassword"));
        $Scaffolding->addHooks("updateModifyRequest", array($this, "setPassword"));
        // Modify properties of columns
        $Scaffolding->addHooks("modifyColumnsProperties", array($this, "modifyColumnsProperties"));
        // Modify validation rules
        $Scaffolding->addHooks("insertModifyValidationRules", array($this, "modifyValidation"));
        $Scaffolding->addHooks("updateModifyValidationRules", array($this, "modifyValidation"));
        $Scaffolding->addHooks("modifyValidationRulesJS", array($this, "modifyValidationRulesJS"));
        // Modify form layout
        $Scaffolding->addHooks("modifyLayout", array($this, "modifyLayout"));
        // Modify response
        $Scaffolding->addHooks("updateModifyResponse", array($this, "updateModifyResponse"));
        // Hooks Action for delete operation ( AJAX Request )
        $Scaffolding->addHooks("deleteModifyResponse", array($this, "deleteModifyResponse"));
        $content = $Scaffolding->render();
        $parameters = array(
            'scaffolding' => $content,
        );
        return $this->render($parameters);
    }

    /**
     * Set Response for AJAX delete operation
     * 
     * @param  array $rules
     * 
     * @return  array
     */
    public function updateModifyResponse($Response)
    {
        if (session('id_user_type') == 2) {
            return back()->with('dk_users_info_success', trans('dkscaffolding.notification.update.success'));
        }
        return $Response;
    }

    /**
     * Modify form layout
     * 
     * @param  array $layout
     * 
     * @return  array
     */
    public function modifyLayout($layout)
    {
        $layout[] = array(
            array(
                'attributes' => array(
                    'class' => 'col-sm-12',
                ),
                'name' => 'password_confirm',
            ),
        );
        return $layout;
    }

    /**
     * Modify javascript validation rules
     * 
     * @param  array $rules
     * 
     * @return  array
     */
    public function modifyValidationRulesJS($rules)
    {
        $action = request()->action;
        if ($action == "edit") {
            unset($rules['password']['required']);
        }
        $rules['password_confirm']['equalTo'] = ":input[name=\"password\"]";
        return $rules;
    }

    /**
     * Modify validation
     * 
     * @param  array $rules
     * 
     * @return  array
     */
    public function modifyValidation($rules)
    {
        $httpVerb = request()->getMethod();
        $rules['password_confirm'] = 'max:255|string|required|same:password';
        if ($httpVerb == "PUT" || $httpVerb == "PATCH") {
            $rules['password'] = 'max:255|string|nullable';
            $rules['password_confirm'] = 'max:255|string|nullable|same:password';
        }
        return $rules;
    }

    /**
     * Modify properties of columns
     * 
     * @param  array $columns
     * 
     * @return  array
     */
    public function modifyColumnsProperties($columns)
    {
        $action = request()->action;
        $columns['password_confirm'] = array(
            'attributes' => array(
                'class' => 'form-control dk-character',
                'placeholder' => 'Password',
            ),
            'name' => 'password_confirm',
            'label' => trans('dkscaffolding.column.password_confirm'),
            'dataType' => 'VARCHAR',
            'length' => '255',
            'range' => NULL,
            'type' => 'password',
            'require' => true,
        );
        if ($action == "edit") {
            $columns['password']['require'] = false;
            $columns['password_confirm']['require'] = false;
        }
        return $columns;
    }

    /**
     * Set image column
     * 
     * @param array $parameters
     * 
     * @return array
     */
    public function setPassword($parameters)
    {
        $httpVerb = request()->getMethod();
        if (($httpVerb == "PUT" || $httpVerb == "PATCH") && !$parameters['password']) {
            unset($parameters['password']);
        }
        if (isset($parameters['password'])) {
            $parameters['password'] = password_hash($parameters['password'], PASSWORD_DEFAULT);
        }
        return $parameters;
    }

}
