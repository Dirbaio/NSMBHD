<?php


class Sql
{
	private static $db = null;
	private static $config = null;

	private static $fieldLists;
	public static function connect($config)
	{
		self::$config = $config;
		$dbstring = 'mysql:host='.$config['host'].';dbname='.$config['database'].';charset='.$config['charset'];
		self::$db = new PDO($dbstring, $config['user'], $config['pass']);
	    self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	    //This is needed to workaround this bug;
	    // https://stackoverflow.com/questions/11738451/error-while-using-pdo-prepared-statements-and-limit-in-query
		self::$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

		//Field lists
		self::$fieldLists = array(
			'userfields' => array('id','name','displayname','powerlevel','sex','minipic','karma')
		);
	}

	public static function getDatabase()
	{
		return self::$config['database'];
	}
	public static function getUser()
	{
		return self::$config['name'];
	}
	public static function getPrefix()
	{
		return self::$config['prefix'];
	}

	public static function query()
	{
		//Get the query and the args
		$args = func_get_args();
		if (is_array($args[0])) $args = $args[0];

		$query = $args[0];
		array_shift($args); //Remove first element of args, so args only contains the actual arguments
		if ($args !== NULL && isset($args[0]) && is_array($args[0])) $args = $args[0];
		
		if($args !== NULL && count(array_filter(array_keys($args), 'is_string')) != 0) //Associative array!
		{
			$newArgs = array();
			foreach($args as $key => $val)
				$newArgs[':$key'] = $val;
			$args = $newArgs;
		}

		$query = preg_replace('@\{([a-z]\w*)\}@si', self::$config['prefix'].'$1', $query);
		$query = preg_replace_callback('@(\w+)\.\(([\w,\s]+)\)@s', function ($match)
		{
			$ret = array();
			$prefix = $match[1];
			$fields = preg_split('@\s*,\s*@', $match[2]);

			$fileds2 = array();
			foreach ($fields as $f)
				if($f[0] == '_') {
					$f = substr($f, 1);
					if(!isset(self::$fieldLists[$f]))
						throw new Exception('field list not found: '.$f);
					foreach(self::$fieldLists[$f] as $ff)
						$fields2[] = $ff;
				}
				else
					$fields2[] = $f;

			foreach ($fields2 as $f)
				$ret[] = $prefix.'.'.$f.' AS '.$prefix.'__'.$f;

			return implode(',', $ret);
		}, $query);

		//Prepare statement
		try {
			$stmt = self::$db->prepare($query);
			$stmt->execute($args);
		}
		catch(PDOException $e) {
			echo 'SQL QUERY:', $query;
			throw $e;
		}
		return $stmt;
	}

	public static function queryValue()
	{
		$res = self::query(func_get_args());
		$res = $res->fetchAll(PDO::FETCH_NUM);
		return $res[0][0];
	}

	public static function queryAffected()
	{
		$res = self::query(func_get_args());
		return $res->rowCount();
	}

	public static function querySingle()
	{
		$stmt = self::query(func_get_args());
		return self::fetch($stmt);
	}

	public static function queryAll()
	{
		$stmt = self::query(func_get_args());
		return self::fetchAll($stmt);
	}

	public static function fetch($result)
	{
		$res = $result->fetch(PDO::FETCH_ASSOC);
		if($res)
			$res = self::postProcess($res);
		return $res;
	}

	public static function fetchAll($result)
	{
		$res = $result->fetchAll(PDO::FETCH_ASSOC);
		$res = array_map(array('Sql', 'postProcess'), $res);
		return $res;
	}

	private static function postProcess($in)
	{
		$res = array();
		foreach($in as $key => $val)
		{
	        $parts  = explode('__', $key);
	        $lastPart = array_pop($parts);

	        $dest = &$res;
	        foreach ($parts as $part) 
	        {
	            if (!isset($dest[$part]) || !is_array($dest[$part]))
	                $dest[$part] = array();

	            $dest = &$dest[$part];
	        }

            $dest[$lastPart] = $val;
		}
		return $res;
	}

	public static function insertId()
	{
		return self::$db->lastInsertId();
	}
}
