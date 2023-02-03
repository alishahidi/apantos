<?php

use Ramsey\Uuid\Nonstandard\Uuid;
use System\Config\Config;
use System\Dot\Dot;
use System\Security\Security;

if (! function_exists('view')) {
    function view($dir, $vars = [])
    {
        $viewBuilder = new \System\View\ViewBuilder();
        $viewBuilder->run($dir);
        $viewVars = $viewBuilder->vars;
        $content = $viewBuilder->content;
        extract(array_merge($viewVars, $vars));
        $prefix = ' ?> ';
        $suffix = ' <?php ';
        $content = html_entity_decode($content);
        eval("$prefix{$content}$suffix");
    }
}

if (! function_exists('dot')) {
    function dot($items)
    {
        return new Dot($items);
    }
}

if (! function_exists('dd')) {
    function dd($data, $exit = true)
    {
        dump($data);
        if ($exit) {
            exit;
        }
    }
}

if (! function_exists('html')) {
    function html($text)
    {
        return html_entity_decode($text);
    }
}

if (! function_exists('old')) {
    function old($name)
    {
        return isset($_SESSION['tmp_old'][$name]) ? $_SESSION['tmp_old'][$name] : null;
    }
}

if (! function_exists('oldEqualValue')) {
    function oldEqualValue($oldName, $value)
    {
        return ! empty($_SESSION['tmp_old'][$oldName]) && $_SESSION['tmp_old'][$oldName] === $value ? true : false;
    }
}

if (! function_exists('oldOrEqualValue')) {
    function oldOrEqualValue($oldName, $value, $mainValue)
    {
        if (isset($_SESSION['tmp_old'][$oldName])) {
            return $_SESSION['tmp_old'][$oldName] === $value ? true : false;
        }

        return $value === $mainValue ? true : false;
    }
}

if (! function_exists('oldOr')) {
    function oldOr($name, $value)
    {
        return isset($_SESSION['tmp_old'][$name]) ? $_SESSION['tmp_old'][$name] : $value;
    }
}

if (! function_exists('flash')) {
    function flash($name, $message = null)
    {
        if (empty($message)) {
            if (! isset($_SESSION['tmp_flash'][$name])) {
                return false;
            }
            $tmp = $_SESSION['tmp_flash'][$name];
            unset($_SESSION['tmp_flash'][$name]);

            return $tmp;
        } else {
            $_SESSION['flash'][$name] = $message;
        }
    }
}

if (! function_exists('flashExists')) {
    function flashExists($name = null)
    {
        if ($name) {
            return isset($_SESSION['tmp_flash'][$name]);
        }

        return isset($_SESSION['tmp_flash']) ? count($_SESSION['tmp_flash']) : false;
    }
}

if (! function_exists('getFlash')) {
    function getFlash($name)
    {
        return isset($_SESSION['tmp_flash'][$name]) ? $_SESSION['tmp_flash'][$name] : null;
    }
}

if (! function_exists('allFlashes')) {
    function allFlashes()
    {
        if (! isset($_SESSION['tmp_flash'])) {
            return false;
        }
        $tmp = $_SESSION['tmp_flash'];
        unset($_SESSION['tmp_flash']);

        return $tmp;
    }
}

if (! function_exists('error')) {
    function error($name, $message = null)
    {
        if (empty($message)) {
            if (! isset($_SESSION['tmp_error'][$name])) {
                return false;
            }
            $tmp = $_SESSION['tmp_error'][$name];
            unset($_SESSION['tmp_error'][$name]);

            return $tmp;
        } else {
            $_SESSION['error'][$name] = $message;
        }
    }
}

if (! function_exists('errorExists')) {
    function errorExists($name = null)
    {
        if ($name) {
            return isset($_SESSION['tmp_error'][$name]);
        }

        return isset($_SESSION['tmp_error']) ? count($_SESSION['tmp_error']) : false;
    }
}

if (! function_exists('allErrors')) {
    function allErrors()
    {
        if (! isset($_SESSION['tmp_error'])) {
            return false;
        }
        $tmp = $_SESSION['tmp_error'];
        unset($_SESSION['tmp_error']);

        return $tmp;
    }
}

if (! function_exists('currentDomain')) {
    function currentDomain()
    {
        return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https://'.$_SERVER['HTTP_HOST'] : 'http://'.$_SERVER['HTTP_HOST'];
    }
}

if (! function_exists('safeHeader')) {
    function safeHeader($header, $message = null)
    {
        header($header);
        if ($message) {
            echo $message;
        }
        exit;
    }
}

if (! function_exists('redirect')) {
    function redirect($url)
    {
        $url = trim($url, '/ ');
        $url = strpos($url, '://') !== null ? $url : "{currentDomain()}/{$url}";

        return safeHeader("Location: {$url}");
    }
}

if (! function_exists('back')) {
    function back($routeName = null)
    {
        $route = isset($routeName) ? route($routeName) : null;
        $url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : $route;

        return safeHeader("Location: {$url}");
    }
}

if (! function_exists('backUrl')) {
    function backUrl($routeName = null)
    {
        $route = isset($routeName) ? route($routeName) : null;

        return isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : $route;
    }
}

if (! function_exists('asset')) {
    function asset($src)
    {
        return currentDomain().'/'.trim($src, '/');
    }
}

if (! function_exists('asset_ftp')) {
    function asset_ftp($src)
    {
        return trim(Config::get('FTP_URL'), "\/").'/'.trim($src, "\/");
    }
}

if (! function_exists('url')) {
    function url($url)
    {
        return currentDomain().'/'.trim($url, '/');
    }
}

if (! function_exists('findRouteByName')) {
    function findRouteByName($name)
    {
        global $routes;
        $allRoutes = array_merge($routes['get'], $routes['post'], $routes['put'], $routes['delete']);
        $route = null;
        foreach ($allRoutes as $routeElem) {
            if ($routeElem['name'] == $name && $routeElem['name'] != null) {
                $route = $routeElem['url'];
                break;
            }
        }

        return $route;
    }
}

if (! function_exists('route')) {
    function route($name, $params = [], $https = false)
    {
        if (! is_array($params)) {
            throw new \Exception('route params must be array.');
        }
        $route = findRouteByName($name);
        if ($route === null) {
            throw new \Exception("route $name not found.");
        }
        $params = array_reverse($params);
        $routeParamsMatch = [];
        preg_match_all('/{[^}.]*}/', $route, $routeParamsMatch);
        if (! empty($params) && $params[0] && count($routeParamsMatch[0]) > count($params)) {
            throw new \Exception('route params not enough.');
        }
        foreach ($routeParamsMatch[0] as $key => $routeMatch) {
            $route = str_replace($routeMatch, array_pop($params), $route);
        }

        $currentDomain = $https ? str_replace(['http'], 'https', str_replace(['https'], 'http', currentDomain())) : currentDomain();

        return $currentDomain.'/'.trim($route, ' /');
    }
}

if (! function_exists('safeRoute')) {
    function safeRoute($name)
    {
        $route = findRouteByName($name);
        if ($route == null) {
            throw new \Exception("route $name not found.");
        }

        return currentDomain().'/'.trim($route, ' /');
    }
}

if (! function_exists('methodField')) {
    function methodField()
    {
        $method_field = strtolower($_SERVER['REQUEST_METHOD']);
        if ($method_field == 'post') {
            if (isset($_POST['_method'])) {
                if ($_POST['_method'] == 'put') {
                    $method_field = 'put';
                } elseif ($_POST['_method'] == 'delete') {
                    $method_field = 'delete';
                }
            }
        }

        return $method_field;
    }
}

if (! function_exists('getMethod')) {
    function getMethod()
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }
}

if (! function_exists('array_dot')) {
    function array_dot($array, $return_array = [], $return_key = '')
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $return_array = array_merge($return_array, array_dot($value, $return_array, "{$return_key}{$key}."));
            } else {
                $return_array["{$return_key}{$key}"] = $value;
            }
        }

        return $return_array;
    }
}

if (! function_exists('currentUrl')) {
    function currentUrl()
    {
        return currentDomain().$_SERVER['REQUEST_URI'];
    }
}

if (! function_exists('equalUrl')) {
    function equalUrl($url, $contain = false, $containUrl = null)
    {
        if (! $contain) {
            return currentUrl() == $url;
        }
        if ($containUrl) {
            return strpos(currentUrl(), $containUrl) === 0;
        } else {
            return strpos(currentUrl(), $url) === 0;
        }
    }
}

if (! function_exists('e')) {
    function e($value)
    {
        return htmlentities($value);
    }
}

if (! function_exists('d')) {
    function d($value)
    {
        return html_entity_decode($value);
    }
}

if (! function_exists('hp')) {
    function hp($value)
    {
        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);

        return $purifier->purify($value);
    }
}

if (! function_exists('hpd')) {
    function hpd($value)
    {
        return d(hp(d($value)));
    }
}

if (! function_exists('setOr')) {
    function setOr($issetValue, $replaceValue)
    {
        return $issetValue ? $issetValue : $replaceValue;
    }
}

if (! function_exists('dash_space')) {
    function dash_space($string)
    {
        return str_replace(' ', '-', strtolower($string));
    }
}

if (! function_exists('publicPath')) {
    function publicPath()
    {
        return Config::get('app.BASE_DIR').DIRECTORY_SEPARATOR.'public';
    }
}

if (! function_exists('env')) {
    function env($name, $default = null)
    {
        if ($default) {
            return $_ENV[$name] ? $_ENV[$name] : $default;
        }

        return $_ENV[$name];
    }
}

if (! function_exists('get_rand_key')) {
    function get_rand_key()
    {
        return md5(Uuid::uuid4()->toString());
    }
}

if (! function_exists('get_token')) {
    function get_token()
    {
        return Security::getToken();
    }
}

if (! function_exists('get_csrf')) {
    function get_csrf()
    {
        return Security::getCsrf();
    }
}

if (! function_exists('verify_password')) {
    function verify_password($password)
    {
        return Security::verifyPassword($password);
    }
}

if (! function_exists('get_gravatar')) {
    function get_gravatar($email, $size = 50)
    {
        $url = 'https://www.gravatar.com/avatar/';
        $url .= md5(strtolower(trim($email)));
        $url .= "?s=$size";

        return $url;
    }
}

if (! function_exists('limitDotPrint')) {
    function limitDotPrint($string, $limtLen)
    {
        if (strlen($string) <= $limtLen) {
            $string = $string;
        } else {
            $string = $string.' ....';
        }

        return $string;
    }
}

if (! function_exists('estimateReadingTime')) {
    function estimateReadingTime($text, $wpm = 200)
    {
        $totalWords = str_word_count(strip_tags($text));
        $minutes = floor($totalWords / $wpm);
        $seconds = floor($totalWords % $wpm / ($wpm / 60));

        return ['m' => $minutes, 's' => $seconds];
    }
}

if (! function_exists('estimateReadingTimePrint')) {
    function estimateReadingTimePrint($ms)
    {
        $minutes = $ms['m'];
        $seconds = $ms['s'];

        return "Minute: $minutes Seconds: $seconds";
    }
}

if (! function_exists('estimateReadingTimePrintPersian')) {
    function estimateReadingTimePrintPersian($ms)
    {
        $minutes = $ms['m'];
        $seconds = $ms['s'];

        return "دقیقه: $minutes ثانیه: $seconds";
    }
}

if (! function_exists('objectToArray')) {
    function objectToArray($object, $name)
    {
        $returnArray = [];
        if (is_array($object)) {
            foreach ($object as $obj) {
                if (! is_array($name)) {
                    array_push($returnArray, $obj->{$name});

                    continue;
                }
                $tmpArr = [];
                foreach ($name as $item) {
                    $tmpArr[$item] = $obj->{$item};
                }
                array_push($returnArray, $tmpArr);
                unset($tmpArr);
            }
        } else {
            if (! is_array($name)) {
                array_push($returnArray, $object->{$name});
            }
            foreach ($name as $item) {
                $returnArray[$item] = $object->{$item};
            }
        }

        return $returnArray;
    }
}

function paginateViewRouteGenerator($routeUrl, $pageCount)
{
    if (isset($_GET[0])) {
        return currentUrl();
    }
    $_GET['_pageid'] = $pageCount;
    $getVariables = array_map(fn ($value, $key) => $key.'='.$value, $_GET, array_keys($_GET));

    return $routeUrl.'?'.implode('&', $getVariables);
}

function paginateView($count, $perPage, $beforeCount, $afterCount, $routeUrl, $view, $activeView, $linkName, $counterName, $beforeStaticValue = '&lt;', $afterStaticValue = '&gt;')
{
    $totalRows = $count;
    $currentPage = isset($_GET['_pageid']) ? (int) $_GET['_pageid'] : 1;
    $totalPages = ceil($totalRows / $perPage);
    $currentPage = max(min($currentPage, $totalPages), 1);
    $paginateView = '';
    $paginateView .= $currentPage != 1 ? str_replace('{'.$counterName.'}', $afterStaticValue, str_replace('{'.$linkName.'}', paginateViewRouteGenerator($routeUrl, $currentPage - 1), $view)) : '';
    for ($i = $currentPage - $beforeCount; $i <= $currentPage - 1; $i++) {
        $paginateView .= $i >= 1 ? str_replace('{'.$counterName.'}', $i, str_replace('{'.$linkName.'}', paginateViewRouteGenerator($routeUrl, $i), $view)) : '';
    }
    $paginateView .= str_replace('{'.$counterName.'}', $currentPage, str_replace('{'.$linkName.'}', paginateViewRouteGenerator($routeUrl, $currentPage), $activeView));
    for ($i = $currentPage; $i <= $currentPage + $afterCount - 1; $i++) {
        $paginateView .= ($i + 1 <= $totalPages) ? str_replace('{'.$counterName.'}', $i + 1, str_replace('{'.$linkName.'}', paginateViewRouteGenerator($routeUrl, $i + 1), $view)) : '';
    }
    $paginateView .= $currentPage != $totalPages ? str_replace('{'.$counterName.'}', $beforeStaticValue, str_replace('{'.$linkName.'}', paginateViewRouteGenerator($routeUrl, $currentPage + 1), $view)) : '';

    return $paginateView;
}

if (! function_exists('error_400')) {
    function error_400()
    {
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
}

if (! function_exists('error_401')) {
    function error_401()
    {
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
}

if (! function_exists('error_404')) {
    function error_404()
    {
        http_response_code(404);
        header($_SERVER['SERVER_PROTOCOL'].' 404 Not Found');
        $view404 = Config::get('app.ERRORS.404');
        if ($view404) {
            view($view404);
        } else {
            view('errors.404');
        }
        exit;
    }
}
