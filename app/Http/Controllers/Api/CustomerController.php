<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Scaffolding;
use Form;
use Html;
use DB;
use Validator;
use Illuminate\Http\JsonResponse;
use \Firebase\JWT\JWT;
use App\Models\Customers;
use Mailer;

class CustomerController extends ApiController
{

    protected $table = 'categories';

    /**
     * Do Login
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
            $error_messages = $validator->errors()->getMessages();
            $response = array(
                'errors' => $error_messages,
                'type' => 'error',
            );
            $JsonResponse = new JsonResponse($response, 200);
            $JsonResponse->send();
            exit;
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
            $jwt = JWT::encode($parameters, config('auth.jwt_key'));
            // Send Response
            $response = array(
                'token' => $jwt,
                'type' => 'success'
            );
            $JsonResponse = new JsonResponse($response, 200);
            $JsonResponse->send();
            exit;
        } else {
            $response = array(
                'login' => trans('form.login.failure'),
                'type' => 'error',
            );
            $JsonResponse = new JsonResponse($response, 200);
            $JsonResponse->send();
            exit;
        }
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
            $error_messages = $validator->errors()->getMessages();
            $response = array(
                'content' => $error_messages,
                'type' => 'error',
            );
            $JsonResponse = new JsonResponse($response, 200);
            $JsonResponse->send();
            exit;
        }
        // Authenticate
        $Model = new \App\Models\Customers;
        $record = $Model->where('email', '=', request('email'))->first();
        if ($record) {
            $error_messages = $validator->errors()->getMessages();
            $response = array(
                'content' => 'Email sudah terdaftar.',
                'type' => 'error',
            );
            $JsonResponse = new JsonResponse($response, 200);
            $JsonResponse->send();
            exit;
        }
        // Create token
        $token = getUniqueFilename();
        $url = action(config('app.frontend_namespace') . 'CustomerController@confirm', array('token' => $token));
        // Insert record
        $parameters = request()->all();
        $parameters['password'] = password_hash($parameters['password'], PASSWORD_DEFAULT);
        $parameters['token'] = $token;
        $Customers = new Customers;
        $result = $Customers->create($parameters);
        // Send Notification
        $parameters = array(
            'url' => $url,
        );
        $content = view('frontend.themes.ecommerce.email.register', $parameters)->render();
        $Mailer = new Mailer;
        $Mailer->send($result->email, $result->name, 'Akun Baru', $content);
        $response = array(
            'content' => 'Email sudah dikirim. Silakan dikonfirmasi.',
            'type' => 'success',
        );
        $JsonResponse = new JsonResponse($response, 200);
        $JsonResponse->send();
        exit;
    }

    /**
     * Process Forgot Password Page
     * 
     * @return Illuminate\View\View
     */
    public function do_forgot_password()
    {
        $rules = array(
            'email' => 'max:255|string|email|required',
        );
        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            $error_messages = $validator->errors()->getMessages();
            $response = array(
                'content' => $error_messages,
                'type' => 'error',
            );
            $JsonResponse = new JsonResponse($response, 200);
            $JsonResponse->send();
            exit;
        }
        // Authenticate
        $Model = new \App\Models\Customers;
        $record = $Model->where('email', '=', request('email'))->first();
        if (!$record) {
            $error_messages = $validator->errors()->getMessages();
            $response = array(
                'content' => 'Email tidak terdaftar.',
                'type' => 'error',
            );
            $JsonResponse = new JsonResponse($response, 200);
            $JsonResponse->send();
            exit;
//            return back()->with('forgot_password_error_message', 'Email tidak terdaftar.')->withInput();
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
        $response = array(
            'content' => 'Silakan cek email untuk merubah password anda.',
            'type' => 'success',
        );
        $JsonResponse = new JsonResponse($response, 200);
        $JsonResponse->send();
//        return back()->with('forgot_password_success_message', 'Silakan cek email untuk merubah password anda.');
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
        $Scaffolding->setContentType('json')->render();
    }

    /**
     * Home Page
     * 
     * @return Illuminate\View\View
     */
    public function profile()
    {
        $jwt_parameters = jwt_parameters();
        // Set request uri parameters
        $Request = request();
        $Request->query->set('id', $jwt_parameters->id);
        $Request->query->set('action', 'edit');
        $Request->query->set('indexes', array(
            'customers.id'
        ));
        $Request->query->set('identifier', 'customers');
        $httpVerb = request()->getMethod();
        // Scaffolding
        $Scaffolding = new Scaffolding("customers");
        $Scaffolding->setAppType('api');
        switch ($httpVerb) {
            case "PUT":
                // Modify validation rules
                $Scaffolding->addHooks("updateModifyValidationRules", array($this, "modifyValidation"));
                // Set password
                $Scaffolding->addHooks("updateModifyRequest", array($this, "setPassword"));
                $Scaffolding->processUpdate();
                break;
        }
        exit;
//        $Scaffolding->setTemplate('frontend.themes.ecommerce.profile');
//        // Modify form layout
//        $Scaffolding->addHooks("modifyLayout", array($this, "modifyLayout"));
//        // Modify properties of columns
//        $Scaffolding->addHooks("modifyColumnsProperties", array($this, "modifyColumnsProperties"));
//        $Scaffolding->addHooks("modifyValidationRulesJS", array($this, "modifyValidationRulesJS"));
//        $Scaffolding->render();
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
