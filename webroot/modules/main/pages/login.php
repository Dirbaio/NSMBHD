<?php
//page /login

function request($username='', $password='', $session=false)
{
	Url::setCanonicalUrl('/login');

	$error = '';
	if($username) {
		$salt = Config::get('salt');

		$user = Sql::querySingle("SELECT * FROM users WHERE name=?", $username);

		if(!$user || $user["password"] !== Util::hash($password.$salt.$user['pss']))
			$error = "Wrong username or password";
		else
		{
			Session::start($user["id"]);
			Url::redirect('/');
		}
	}


	$breadcrumbs = array(
		array('url' => '/login', 'title' => __('Log in')),
	);

	renderPage('login.html', array(
		'username' => $username,
		'error' => $error,
		'breadcrumbs' => $breadcrumbs, 
		'actionlinks' => array(),
		'title' => __('Log in'),
	));
}