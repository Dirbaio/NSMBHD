<?php
//page /logout

function request()
{
	Session::end();

	Url::redirect('/');
}

