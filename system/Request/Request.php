<?php

namespace System\Request;

use stdClass;
use System\Config\Config;
use System\Request\Traits\HasFileValidationRules;
use System\Request\Traits\HasRunValidation;
use System\Request\Traits\HasValidationRules;
use System\Security\Security;

class Request extends stdClass
{
    use HasFileValidationRules;
    use HasRunValidation;
    use HasValidationRules;

    protected $errorExist = false;

    protected $request;

    protected $files = null;

    protected $errorVariablesName = [];

    protected $customErrors = [];

    public function __construct($redirect = true)
    {
        if (getMethod() === 'post') {
            $this->verifyToken();
        }
        if (isset($_POST)) {
            $this->postAttributes();
        }
        if (! empty($_FILES)) {
            $this->files = $_FILES;
        }
        $rules = $this->rules();
        empty($rules) ? null : $this->run($rules);
        $this->errorRedirect($redirect);
    }

    protected function rules()
    {
        return [];
    }

    protected function run($rules)
    {
        $rules = $this->checkRulesArray($rules);
        foreach ($rules as $att => $values) {
            $ruleArray = explode('|', $values);
            if (in_array('file', $ruleArray)) {
                unset($ruleArray[array_search('file', $ruleArray)]);
                $this->fileValidation($att, $ruleArray);
            } elseif (in_array('number', $ruleArray)) {
                $this->numberValidaiton($att, $ruleArray);
            } else {
                $this->normalValidaiton($att, $ruleArray);
            }
        }
    }

    private function verifyToken()
    {
        $unVerifyRouteNames = Config::get('app.UN_VERIFY_TOKEN_ROUTE');
        if ($unVerifyRouteNames[0] === '*') {
            return true;
        }
        foreach ($unVerifyRouteNames as $routeName) {
            if (strpos(preg_replace('/{.*}/', '', currentUrl()), preg_replace('/{.*}/', '', safeRoute($routeName))) === 0) {
                return true;
            }
        }
        $token = isset($_POST['_token']) ? $_POST['_token'] : $_GET['_token'];
        if (empty($token)) {
            http_response_code(400);
            header($_SERVER['SERVER_PROTOCOL'].' 400 Bad Request');
            $view400 = Config::get('app.ERRORS.400');
            if ($view400) {
                view($view400);
            } else {
                view('errors.400');
            }
            exit;
        }
        if (! Security::veirfyCsrf($token)) {
            http_response_code(401);
            header($_SERVER['SERVER_PROTOCOL'].' 401 Unauthorized');
            $view401 = Config::get('app.ERRORS.401');
            if ($view401) {
                view($view401);
            } else {
                view('errors.401');
            }
            exit;
        }

        return true;
    }

    private function checkRulesArray($rules)
    {
        if (! (isset($rules['errors']) && is_array($rules['errors']))) {
            return $rules['rules'];
        }
        foreach ($rules['errors'] as $errorName => $errorValue) {
            $error = explode('|', $errorValue);
            foreach ($error as $err) {
                $err = explode('!', $err);
                $this->customErrors[$errorName][$err[0]] = $err[1];
            }
        }

        return $rules['rules'];
    }

    public function file($name)
    {
        return isset($this->files[$name]) ? $this->files[$name] : false;
    }

    protected function postAttributes()
    {
        foreach ($_POST as $key => $value) {
            $this->$key = htmlentities($value);
            $this->request[$key] = htmlentities($value);
        }
    }

    public function all()
    {
        return $this->request;
    }
}
