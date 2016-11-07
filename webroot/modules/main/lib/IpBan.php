<?php

class IpBan
{
	public static function isIpBanned($ip)
	{
		$bans = Sql::query('SELECT * FROM {ipbans} WHERE instr(?, ip)=1', $ip);
		
		$result = false;
		while($ipban = Sql::fetch($bans))
		{
			if (self::ipMatches($ip, $ipban['ip']))
				if ($ipban['whitelisted'])
					return false;
				else
					$result = $ipban;
		}
		return $result;
	}

	public static function ipMatches($ip, $mask) {
		return $ip === $mask || $mask[strlen($mask) - 1] === '.';
	}

	public static function check() {
		if(self::isIpBanned($_SERVER['REMOTE_ADDR']))
			fail('You\'re banned.');
	}
}