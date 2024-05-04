<?php
session_start();
define("BASE_PATH", __DIR__);
define('CURRENT_DOMAIN', currentDomain() . '/khabary/');
define('DISPLAY_ERROR', true);
define('DB_HOST', 'localhost');
define('DB_NAME', 'khabary');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '123');


//helpers
function protocol()
{
    return stripos($_SERVER['SERVER_PROTOCOL'], "https") === true ? "https://" : "http://";
}
function currentDomain()
{
    return protocol() . $_SERVER['SERVER_NAME'];
}
function assets($src)
{
    $domain = trim(CURRENT_DOMAIN, '/');
    $src = $domain . trim($src, '/');
    return $src;
}
function url($src)
{
    $domain = trim(CURRENT_DOMAIN, '/');
    $src = $domain . trim($src, '/');
    return $src;
}
function dd($data)
{
    echo '<pre>';
    var_dump($data);
    exit;
}
function currentUrl()
{
    return currentDomain() . $_SERVER['REQUEST_URI'];
}
function methodField()
{
    return $_SERVER['REQUEST_METHOD'];
}
function displayError($displayError)
{
    if ($displayError) {
        ini_set("display_errors", 1);
        ini_set("display_startup_errors", 1);
        error_reporting(E_ALL);
    } else {
        ini_set("display_errors", 0);
        ini_set("display_startup_errors", 0);
        error_reporting(0);
    }
}
displayError(DISPLAY_ERROR);
global $flashMessage;
if (isset($_SESSION['flashMessage'])) {
    $flashMessage = $_SESSION['flashMessage'];
    unset($_SESSION['flashMessage']);
}
function flash($name, $value = null)
{
    if ($value === null) {
        global $flashMessage;
        $message = isset($flashMessage[$name]) ? $flashMessage[$name] : '';
        return $message;
    } else {
        $_SESSION['flashMessage'][$name] = $value;
    }
}
function uri($reservedUrl, $class, $method, $requestMethod = 'GET')
{
    $currentUrl = explode('?', currentUrl())[0];
    $currentUrl = str_replace(CURRENT_DOMAIN, '', $currentUrl);
    $currentUrl = trim($currentUrl, '/');
    $currentUrlArray = explode('/', $currentUrl);
    $currentUrlArray = array_filter($currentUrlArray);

    $reservedUrl = trim($reservedUrl, '/');
    $reservedUrlArray = explode('/', $reservedUrl);
    $reservedUrlArray = array_filter($reservedUrlArray);

    if (sizeof($currentUrlArray) != sizeof($reservedUrlArray) || methodField() != $requestMethod) {
        return false;
    }

    $parameters = [];
    for ($key = 0; $key < sizeof($currentUrlArray); $key++) {
        if ($reservedUrlArray[$key][0] == "{" && $reservedUrlArray[$key][strlen($reservedUrlArray[$key]) - 1] == "}") {
            array_push($parameters, $currentUrlArray[$key]);
        } elseif ($currentUrlArray[$key] !== $reservedUrlArray[$key]) {
            return false;
        }
    }

    if (methodField() == 'POST') {
        $request = isset($_FILES) ? array_merge($_POST, $_FILES) : $_POST;
        $parameters = array_merge([$request], $parameters);
    }

    $object = new $class;
    call_user_func_array(array($object, $method), $parameters);
    exit();
}
// admin/category/edit/{id} reserved url
// admin/category/delete/{id} reserved url
// admin/category/edit/5 current url 
// admin/category/edit/5 current url 
uri('admin/category', 'Category', 'index');