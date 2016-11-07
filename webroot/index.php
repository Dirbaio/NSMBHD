<?php

// Error handling
//==============================

error_reporting(E_ALL ^ E_NOTICE | E_STRICT);

function fail($why) {
	throw new Exception($why);
}

function my_error_handler()
{
	$last_error = error_get_last();
	if ($last_error && ($last_error['type']==E_ERROR || $last_error['type']==E_USER_ERROR))
		header('HTTP/1.1 500 Internal Server Error');
}
register_shutdown_function('my_error_handler');


// Load main module
//============================

require(__DIR__.'/ModuleHandler.php');
require(__DIR__.'/vendor/autoload.php');
ModuleHandler::init();
ModuleHandler::loadModule('/modules/main');
ModuleHandler::loadModule('/url_styles/rewrite');
ModuleHandler::loadModule('/themes/cheese');

if(isset($_COOKIE['mobileversion']) && $_COOKIE['mobileversion'] && $_COOKIE['mobileversion'] != 'false')
	ModuleHandler::loadModule('/layouts/mobile');
else
	ModuleHandler::loadModule('/layouts/nsmbhd');

// Run the page
//============================


function getPages()
{
	$prefix = '//page ';
	
	$pages = array();
	foreach(ModuleHandler::getFilesMatching('/pages/**.php') as $file) 
	{
		$handle = @fopen($file, 'r');
		if (!$handle)  continue;

		while (($line = fgets($handle, 4096)) !== false)
			if(startsWith($line, $prefix))
			{
				$page = substr($line, strlen($prefix));
				$page = trim($page);
				$pages[$page] = $file;
			}
		
		fclose($handle);
	}

	return $pages;
}

function getBase() {
	$base = $_SERVER["SCRIPT_NAME"];
	$idx = strrpos($base, '/');
	if($idx !== false)
		$base = substr($base, 0, $idx+1);
	return $base;
}

function renderPage($template, $vars)
{
	$navigation = array(
		array('url' => Url::format('/'), 'title' => __('Main')),
		array('url' => Url::format('/members'), 'title' => __('Members')),
		array('url' => Url::format('/online'), 'title' => __('Online users')),
		array('url' => Url::format('/search'), 'title' => __('Search')),
		array('url' => Url::format('/lastposts'), 'title' => __('Last posts')),
		array('url' => Url::format('/faq'), 'title' => __('FAQ/Rules')),
	);

	$user = Session::get();

	if($user)
		$userpanel = array(
			array('user' => $user),
			array('url' => Url::format('/u/#-:/edit', $user['id'], $user['name']), 'title' => __('Edit profile')),
			array('url' => Url::format('/u/#-:/pm', $user['id'], $user['name']), 'title' => __('Messages')),
			array('url' => Url::format('/logout'), 'title' => __('Log out')),
		);
	else
		$userpanel = array(
			array('url' => Url::format('/register'), 'title' => __('Register')),
			array('url' => Url::format('/login'), 'title' => __('Log in')),
		);
 
 	$onlineFid = 0;
 	if(isset($vars['forum']))
 		$onlineFid = $vars['forum']['id'];

 	global $is404;
 	if($is404) {
	    header('HTTP/1.0 404 Not Found');
	    header('Status: 404 Not Found');
 		$onlineFid = -1;
 	}

	$layout = array(
		'template' => $template,
		'css' => ModuleHandler::toWebPath(ModuleHandler::getFilesMatching('/css/**.css')),
		'js' => ModuleHandler::toWebPath(ModuleHandler::getFilesMatching('/js/**.js')),
		'title' => 'RandomTests',
		'pora' => true,
		'poratext' => 'Hello World',
		'poratitle' => 'ASDF',
		'views' => Records::getViewCounter(),
		'user' => $user,
		'navigation' => $navigation,
		'userpanel' => $userpanel,
		'onlineUsers' => OnlineUsers::update($onlineFid),
		'base' => getBase(),
	);
	$vars['layout'] = $layout;
	$vars['loguser'] = Session::get();

	if(!isset($vars['breadcrumbs']) || !is_array($vars['breadcrumbs']))
		throw new Exception('breadcrumbs not found in vars, must be there and be an array');
	if(!isset($vars['actionlinks']) || !is_array($vars['actionlinks']))
		throw new Exception('actionlinks not found in vars, must be there and be an array');

	array_unshift($vars['breadcrumbs'], 
		array('url' => Url::format('/'), 'title' => __('Main')));

	Template::render('layout/main.html', $vars);

}

function runPage($path)
{
	$pages = getPages();

	//Kill trailing and extra slashes.
	$origpath = $path;
	$path = preg_replace('#/+$#', '', $path);
	$path = preg_replace('#//+#', '/', $path);
	if($path == '') $path = '/';
	if($path != $origpath)
		Url::redirect($path);


	if ($_SERVER['REQUEST_METHOD'] === 'POST') 
	{
		$input = json_decode(file_get_contents('php://input'), true);
		if(!is_array($input) || json_last_error() !== JSON_ERROR_NONE)
			$input = $_POST;

		foreach($_GET as $key => $value)
			$input[$key] = $value;
	}
	else
		$input = $_GET;

	$foundPagefile = null;
	foreach($pages as $page=>$pagefile)
	{
		//match $path against $page
		$names = array();
		$pattern = preg_replace_callback('/(:|#|\$)([a-zA-Z][a-zA-Z0-9]*|)/', 
			function($matches) use (&$names) 
			{
				if($matches[1] == '#')
					$regex = '-?[0-9]+';
				else if($matches[1] == '$')
					$regex = '[^/]+';
				else
					$regex = '[a-zA-Z0-9-_]+';
				if($matches[2])
				{
					$names[] = $matches[2];
					return '('.$regex.')';
				}
				else
					return $regex;
			}, $page);

		if (preg_match('#^' . $pattern . '$#', $path, $matches)) 
		{
			foreach($names as $idx => $name)
				$input[$name] = $matches[$idx+1];

			$foundPagefile = $pagefile;
			break;
		}
	}

	if(!$foundPagefile) {
		$foundPagefile = __DIR__.'/modules/main/pages/404.php';
		global $is404;
		$is404 = true;
	}

	$input['input'] = $input;

	require($foundPagefile);

	//Calculate parameters
	$params = array();
	$refFunc = new ReflectionFunction('request');
	foreach($refFunc->getParameters() as $param) 
	{
		if(isset($input[$param->name]))
			$params[] = $input[$param->name];
		else if($param->isDefaultValueAvailable())
			$params[] = $param->getDefaultValue();
		else
			fail('Missing parameter: '.$param->name);
	}

	//Call the thing
	call_user_func_array('request', $params);
}


runPage(UrlStyle::getPath());
