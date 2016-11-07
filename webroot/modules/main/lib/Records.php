<?php

class Records
{
	public static function update()
	{
		//Check the amount of users right now for the records
		$misc = Sql::querySingle('SELECT * FROM {misc}');

		$onlineUsers = Sql::queryAll(
				'SELECT id FROM {users} WHERE lastactivity > ? or lastposttime > ? ORDER BY name',
				time()-300, time()-300);

		// Max users record.
		if(count($onlineUsers) > $misc['maxusers'])
		{
			$onlineUsersList = '';
			foreach($onlineUsers as $onlineUser)
				$onlineUsersList .= ':'.$onlineUser['id'];

			Sql::query(
				'UPDATE misc SET maxusers = ?, maxusersdate = ?, maxuserstext = ?',
				count($onlineUsers), time(), $onlineUsersList);
		}

		// Max posts record, in 1 hour and 1 day.
		$new = Sql::querySingle(
			'SELECT 
				(SELECT count(*) FROM {posts} WHERE date > ?) AS hour,
				(SELECT count(*) FROM {posts} WHERE date > ?) AS day',
			time() - 3600, time() - 86400);

		if($records['hour'] > $misc['maxpostsday'])
			Sql::query(
				'UPDATE misc SET maxpostshour = ?, maxpostshourdate = ?',
				$records['hour'], time());

		if($records['day'] > $misc['maxpostsday'])
			Sql::query(
				'UPDATE misc SET maxpostsday = ?, maxpostsdaydate = ?',
				$records['day'], time());

		if(!Browsers::isBot())
			Sql::query('UPDATE misc SET views=views+1');
	}

	public static function getViewCounter()
	{
		return Sql::queryValue("SELECT views FROM {misc}");
	}
}

