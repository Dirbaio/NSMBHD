<?php

class SchemaUpdater
{
	private static $dbpref;
	private static $dbname;

	private static function typeOk($type) 
	{
		if(!is_array($type)) return false;
		if(!$type['type']) return false;
		return true;
	}

	private static function sqlToType($sql)
	{
		$res = array(
			"type" => $sql["Type"],
			"notNull" => $sql["Null"] == "NO",
			"autoIncrement" => $sql["Extra"] == "auto_increment",
			"default" => $sql["Default"],
		);
		return $res;
	}

	private static function typeToSql($type)
	{
		$res = $type["type"];
		if($type["notNull"])
			$res .= " NOT NULL";

		//Auto increment columns don't have a default.
		if($type["autoIncrement"])
			$res .= " AUTO_INCREMENT";
		else if($type["default"] === null)
			$res .= " DEFAULT NULL";
		else
			$res .= " DEFAULT '".$type["default"]."'";

		return $res;
	}

	private static function equalTypes($a, $b)
	{
		return 
			$a["type"] == $b["type"] && 
			$a["notNull"] == $b["notNull"] && 
			$a["autoIncrement"] == $b["autoIncrement"] && 
			($a["autoIncrement"] || $a["default"] == $b["default"]);
	}

	private static function keyToSql($key)
	{
		return $key["type"]." KEY (".implode(",", $key["fields"]).")";
	}

	private static function equalKeys($a, $b)
	{
		return
			$a["type"] == $b["type"] &&
			$a["fields"] === $b["fields"];
	}

	private static function createTable($table, $tableSchema)
	{

		echo " creating&hellip;";
		$creates = array();

		foreach($tableSchema['fields'] as $field => $type)
			$creates[] = "`".$field."` ".self::typeToSql($type);

		foreach($tableSchema["keys"] as $key)
			$creates[] = self::keyToSql($key);

		$create = "create table `".self::$dbpref.$table."` (\n".implode(",\n", $creates)."\n) ENGINE=MyISAM;";

		Sql::query($create);
	}

	private static function getKeysForTable($table)
	{
		$keys = array();

		foreach(Sql::query("show keys from `".self::$dbpref.$table."`") as $entry)
		{
			if(!array_key_exists($entry["Key_name"], $keys))
			{
				$type = "";
				if($entry["Index_type"] == "FULLTEXT") $type = "fulltext";
				else if($entry["Key_name"] == "PRIMARY") $type = "primary";
				else if($entry["Non_unique"] == 0) $type = "unique";

				$keys[$entry["Key_name"]] = array(
					"fields" => array(),
					"type" => $type
				);
			}
			$keys[$entry["Key_name"]]["fields"][] = $entry["Column_name"];
		}
		return $keys;
	}

	private static function upgradeTable($table, $tableSchema)
	{

		$alters = array();


		//======= FIELDS

		$foundFields = array();
		$currFields = Sql::queryAll("show columns from `".self::$dbpref.$table."`");
		$fields = $tableSchema['fields'];
		if(!is_array($fields))
			fail('Table '.$table.' is missing the fields array');

		foreach($fields as $fieldName => $type)
			if(!self::typeOk($type))
				fail("Type for table $table, field $fieldName is not OK");

		foreach($currFields as $field)
		{
			$fieldName = $field['Field'];
			$foundFields[] = $fieldName;
			$type = self::sqlToType($field);
			if(array_key_exists($fieldName, $tableSchema['fields']))
			{
				$wantedType = $fields[$fieldName];
				if(!self::equalTypes($type, $wantedType))
				{
					$wantedType = self::typeToSql($wantedType);
					$alters[] = "CHANGE `$fieldName` `$fieldName` $wantedType";
					$changes++;
				}
			}
		}

		foreach($fields as $fieldName => $type)
		{
			if(!in_array($fieldName, $foundFields))
			{
				$alters[] = "ADD `$fieldName` ".self::typeToSql($type);
				$changes++;
			}
		}

		//======= KEYS
		$currKeys = self::getKeysForTable($table);
		$keys = $tableSchema["keys"];

		foreach($currKeys as $keyName => $key)
		{
			$found = false;
			foreach($keys as $match)
				if(self::equalKeys($key, $match))
					$found = true;

			if(!$found)
				if($keyName == 'PRIMARY')
					$alters[] = "DROP PRIMARY KEY";
				else
					$alters[] = "DROP KEY $keyName";
		}

		if(!is_array($keys))
			fail('Table '.$table.' is missing the keys array');

		foreach($keys as $key)
		{
			$found = false;
			foreach($currKeys as $match)
				if(self::equalKeys($key, $match))
					$found = true;

			if(!$found)
				$alters[] = "ADD ".self::keyToSql($key);
		}

		if(count($alters) == 0)
			echo " OK.\n";
		else
		{
			echo "\n";
			Sql::query("ALTER TABLE ".self::$dbpref.$table." ".implode(",", $alters));
			foreach($alters as $alter)
				echo "$alter\n";
		}
	}

	private static function checkTable($table, $tableSchema)
	{
		echo $table."...";
		$tableStatus = Sql::querySingle("SHOW TABLE STATUS FROM ".self::$dbname." LIKE '".self::$dbpref.$table."'");
		
		if($tableStatus == null) //Table doesn't exist
			self::createTable($table, $tableSchema);
		else
			self::upgradeTable($table, $tableSchema);
	}

	
	public static function run()
	{
		global $config;
		self::$dbpref = Sql::getPrefix();
		self::$dbname = Sql::getDatabase();

		$tables = Schema::get();

		foreach($tables as $name => $table)
			self::checkTable($name, $table);
	}
}