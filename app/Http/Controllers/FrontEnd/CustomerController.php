<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use Auth;
use MenuBuilder;
use Route;
use App\Models\Categories;
use App\Models\Products;
use App\Models\Orders;
use App\Models\Customers;
use DB;
use Scaffolding;
use Form;
use Validator;
use Mailer;

class CustomerController extends FrontEndController
{

    /**
     * Constructor
     * 
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Dashboard Page
     * 
     * @return Illuminate\View\View
     */
    public function index()
    {
        $parameters = $this->getParameters();
        $parameters['id_page'] = "account-dashboard";
        return view('frontend.themes.ecommerce.dashboard', $parameters)->render();
    }

    /**
     * Dashboard Page
     * 
     * @return Illuminate\View\View
     */
    public function login()
    {
        if (session('id')) {
            // Redirect to customer dashboard
            return redirect(action(config('app.frontend_namespace') . 'CustomerController@index'));
        }
        $parameters = $this->getParameters();
        $parameters['id_page'] = "account-login";
        return view('frontend.themes.ecommerce.login', $parameters)->render();
    }

    /**
     * Register
     * 
     * @return Illuminate\View\View
     */
    public function do_login()
    {
        $rules = array(
            'email' => 'required|email',
            'password' => 'required',
        );
        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        // Authenticate
        $Model = new \App\Models\Customers;
        $record = $Model->where('email', '=', request('email'))
                        ->where('active', '=', 1)->first();
        if (password_verify(request('password'), $record['password'])) {
            // Set session parameters
            $parameters = array(
                'id' => $record['id'],
                'name' => $record['name'],
                'email' => $record['email'],
                'phone' => $record['phone'],
                'address' => $record['address'],
            );
            session($parameters);
            // Redirect to customer dashboard
            return redirect(action(config('app.frontend_namespace') . 'CustomerController@index'));
        } else {
            return back()->with('login_failure', trans('form.login.failure'));
        }
    }

    /**
     * Register Page
     * 
     * @return Illuminate\View\View
     */
    public function register()
    {
        if (session('id')) {
            // Redirect to customer dashboard
            return redirect(action(config('app.frontend_namespace') . 'CustomerController@index'));
        }
        $parameters = $this->getParameters();
        $parameters['id_page'] = "account-register";
        return view('frontend.themes.ecommerce.register', $parameters)->render();
    }

    /**
     * Process Register
     * 
     * @return Illuminate\View\View
     */
    public function do_register()
    {
        $rules = array(
            'name' => 'max:255|string|required',
            'email' => 'max:255|string|email|required',
            'password' => 'max:255|string|required',
            'password_confirm' => 'max:255|string|required|same:password',
            'phone' => 'max:255|string|required',
            'address' => 'required',
        );
        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        // Authenticate
        $Model = new \App\Models\Customers;
        $record = $Model->where('email', '=', request('email'))->first();
        if ($record) {
            return back()->with('register_message', 'Email sudah terdaftar.')->withInput();
        }
        // Create token
        $token = getUniqueFilename();
        $url = action(config('app.frontend_namespace') . 'CustomerController@confirm', array('token' => $token));
        // Insert record
        $Customers = new Customers;
        $parameters = request()->all();
        $parameters['password'] = password_hash($parameters['password'], PASSWORD_DEFAULT);
        $parameters['token'] = $token;
        $result = $Customers->create($parameters);
        // Send Notification
        $parameters = array(
            'url' => $url,
        );
        $content = view('frontend.themes.ecommerce.email.register', $parameters)->render();
        $Mailer = new Mailer;
        $Mailer->send($result->email, $result->name, 'Akun Baru', $content);
        return back()->with('register_message', 'Email sudah dikirim. Silakan dikonfirmasi.');
    }

    /**
     * Process Forgot Password Page
     * 
     * @return Illuminate\View\View
     */
    public function confirm($token)
    {
        // Authenticate
        $Model = new Customers;
        $record = $Model->where('token', '=', $token)->get()->first();
        $url = action(config('app.frontend_namespace') . 'CustomerController@login');
        if (!$record) {
            return redirect($url);
        }
        // Update token
        $parameters = array(
            'active' => '1',
            'token' => '',
        );
        $Model = new Customers;
        $record = $Model->where('token', '=', $token)->update($parameters);
        return redirect($url);
    }

    /**
     * Forgot Password Page
     * 
     * @return Illuminate\View\View
     */
    public function forgot_password()
    {
        $parameters = $this->getParameters();
        $parameters['id_page'] = "account-forgot-password";
        return view('frontend.themes.ecommerce.forgot_password', $parameters)->render();
    }

    /**
     * Process Forgot Password Page
     * 
     * @return Illuminate\View\View
     */
    public function process_forgot_password()
    {
        $rules = array(
            'email' => 'max:255|string|email|required',
        );
        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        // Authenticate
        $Model = new \App\Models\Customers;
        $record = $Model->where('email', '=', request('email'))->first();
        if (!$record) {
            return back()->with('forgot_password_error_message', 'Email tidak terdaftar.')->withInput();
        }
        // Create token
        $token = getUniqueFilename();
        $url = action(config('app.frontend_namespace') . 'CustomerController@change_password', array('token' => $token));
        // Update token
        $parameters = array(
            'token' => $token,
        );
        $Model = new Customers;
        $result = $Model->where('email', '=', request('email'))->update($parameters);
        // Send Notification
        $parameters = array(
            'url' => $url,
        );
        $content = view('frontend.themes.ecommerce.email.forgot_password', $parameters)->render();
        $Mailer = new Mailer;
        $Mailer->send($record->email, $record->name, 'Forgot Password', $content);
        return back()->with('forgot_password_success_message', 'Silakan cek email untuk merubah password anda.');
    }

    /**
     * Process Forgot Password Page
     * 
     * @return Illuminate\View\View
     */
    public function change_password($token)
    {
        // Authenticate
        $Model = new Customers;
        $record = $Model->where('token', '=', $token)->first();
        if (!$record) {
            $url = action(config('app.frontend_namespace') . 'DeliveryInfoController@index');
            return redirect($url);
        }
        $parameters = $this->getParameters();
        $parameters['id_page'] = "change-password";
        $parameters['token'] = $token;
        return view('frontend.themes.ecommerce.change_password', $parameters)->render();
    }

    /**
     * Process Forgot Password Page
     * 
     * @return Illuminate\View\View
     */
    public function process_change_password($token)
    {
        // Validate
        $rules = array(
            'password' => 'max:255|string|required',
            'password_confirm' => 'max:255|string|required|same:password',
        );
        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        // Authenticate
        $Model = new Customers;
        $record = $Model->where('token', '=', $token)->first();
        if (!$record) {
            $url = action(config('app.frontend_namespace') . 'DeliveryInfoController@index');
            return redirect($url);
        }
        // Update password
        $parameters = array(
            'password' => password_hash(request('password'), PASSWORD_DEFAULT),
        );
//        dd($parameters, $record->email);
        $Model = new Customers;
        $record = $Model->where('email', '=', $record->email)->update($parameters);
        return back()->with('change_password_success_message', 'Password berhasil dirubah.');        // Send Notification
    }

    /**
     * Home Page
     * 
     * @return Illuminate\View\View
     */
    public function profile()
    {
        // Set request uri parameters
        $Request = request();
        $Request->query->set('action', 'edit');
        $Request->query->set('indexes', array(
            'id' => session('id')
        ));
        $httpVerb = request()->getMethod();
        // Scaffolding
        $Scaffolding = new Scaffolding("customers");
        switch ($httpVerb) {
            case "PUT":
                // Modify validation rules
                $Scaffolding->addHooks("updateModifyValidationRules", array($this, "modifyValidation"));
                // Set password
                $Scaffolding->addHooks("updateModifyRequest", array($this, "setPassword"));
                $Scaffolding->processUpdate();
                break;
        }
        $Scaffolding->setTemplate('frontend.themes.ecommerce.profile');
        // Modify form layout
        $Scaffolding->addHooks("modifyLayout", array($this, "modifyLayout"));
        // Modify properties of columns
        $Scaffolding->addHooks("modifyColumnsProperties", array($this, "modifyColumnsProperties"));
        $Scaffolding->addHooks("modifyValidationRulesJS", array($this, "modifyValidationRulesJS"));
        $form = $Scaffolding->renderEdit();
        $parameters = $this->getParameters();
        $parameters['id_page'] = "account-profile";
        $parameters['form'] = $form;
        return view('frontend.themes.ecommerce.account_profile', $parameters)->render();
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
        $action = request('action');
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
        unset($rules['email']);
        return $rules;
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
        $password_confirm = array(
            array(
                array(
                    'attributes' => array(
                        'class' => 'col-sm-12',
                    ),
                    'name' => 'password_confirm',
                ),
            )
        );
        array_splice($layout, 3, 0, $password_confirm);
        unset($layout[1], $layout[6]);
        return $layout;
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
        $action = request('action');
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
//        dd($columns);
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

    public function logout()
    {
        session()->flush();
        return redirect(action(config('app.frontend_namespace') . 'CustomerController@login'));
    }

    public function order()
    {
        // Scaffolding
        $records_per_pages = array(
            8 => sprintf(trans('dkscaffolding.show.entries'), 8),
        );
        $Scaffolding = new Scaffolding("orders");
        $Scaffolding->setTemplate("vishfrontend");
        $Scaffolding->setListOfRecordsPerPage($records_per_pages);
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
                'name' => 'total',
                'width' => '10%',
            ),
        );
        $Scaffolding->setColumnProperties($parameters);
        $content = $Scaffolding->render();
        $parameters = $this->getParameters();
        $parameters['id_page'] = "account-orders";
//        $parameters['orders'] = Orders::get_orders(session('id'));
        $parameters['scaffolding'] = $content;
        return view('frontend.themes.ecommerce.account_orders', $parameters)->render();
    }

}
