<?php
//page /api/markasread

function request($fid=0)
{
	Session::checkLoggedIn();
	
	if($fid)
		Sql::query(
			'REPLACE INTO {threadsread} (id,thread,date) 
			SELECT ?, t.id, ? FROM {threads} t WHERE t.forum=?',
			Session::id(), time(), $fid);
	else
		Sql::query(
			'REPLACE INTO {threadsread} (id,thread,date) 
			SELECT ?, t.id, ? FROM {threads} t',
			Session::id(), time());

	json(Url::format('/'));
}