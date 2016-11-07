<?php

class OnlineUsers
{
	public static function update($fid)
	{
		$fid = (int) $fid;

		// Delete old visitors from the guest list.
		Sql::query('DELETE FROM {guests} WHERE lastactivity < ?', time()-300);

		if($fid != -1) {
			// Add user/guest, update lastforum/lasturl/stuff
			if(Session::id()) {
				Sql::query(
					"UPDATE {users} SET
						lastip=?,
						lastactivity=?,
						lasturl=?,
						lastforum=?,
						lastuseragent=?
					WHERE id=?",
					$_SERVER['REMOTE_ADDR'], time(), Url::getPath(), $fid, $_SERVER['HTTP_USER_AGENT'], Session::id());
			}
			else {
				Sql::query(
					"INSERT INTO {guests}
						(lastip, lastactivity, lasturl, lastforum, lastuseragent) VALUES (?,?,?,?,?) 
					ON DUPLICATE KEY UPDATE
						lastip=?,
						lastactivity=?,
						lasturl=?,
						lastforum=?,
						lastuseragent=?",
					$_SERVER['REMOTE_ADDR'], time(), Url::getPath(), $fid, $_SERVER['HTTP_USER_AGENT'],
					$_SERVER['REMOTE_ADDR'], time(), Url::getPath(), $fid, $_SERVER['HTTP_USER_AGENT']);
			}
		}

		$users = Sql::queryAll(
			"SELECT user.(_userfields)
			FROM {users} user
			WHERE (? = 0 OR lastforum = ?) AND (lastactivity > ? or lastposttime > ?) AND loggedin = 1
			ORDER BY name",
			$fid, $fid, time()-300, time()-300);
		
		$guestCount = Sql::queryValue(
			"SELECT COUNT(*)
			FROM {guests}
			WHERE (? = 0 OR lastforum = ?) AND bot=0",
			$fid, $fid);

		$botCount = Sql::queryValue(
			"SELECT COUNT(*)
			FROM {guests}
			WHERE (? = 0 OR lastforum = ?) AND bot=1",
			$fid, $fid);

		if($fid != 0 && $fid != -1)
			$forum = Fetch::forum($fid);
		else
			$forum = null;

		return array(
			'forum' => $forum,
			'users' => $users,
			'guestCount' => $guestCount,
			'botCount' => $botCount);
	}
}