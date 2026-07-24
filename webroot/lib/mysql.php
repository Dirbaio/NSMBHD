<?php
// AcmlmBoard XD support - MySQL database wrapper functions

$queries = 0;
$dberror = "";
function sqlConnect()
{
	global $dbserv, $dbuser, $dbpass, $dbname, $dblink, $dberror;

	// PHP 8.1+ makes mysqli throw on its own, which would bypass the error
	// handling below and lose the offending query. Report through return values
	// instead, the way the rest of this file expects.
	mysqli_report(MYSQLI_REPORT_OFF);

	$dblink = new mysqli($dbserv, $dbuser, $dbpass);
	if($dblink->connect_error)
	{
		$dberror = $dblink->connect_error;
		return false;
	}
	if(!$dblink->select_db($dbname))
	{
		$dberror = "Database does not exist";
		return false;
	}

	if (!$dblink->set_charset("utf8mb4"))
	{
        $dberror = "Error setting UTF8 charset";
		return false;
	}

	unset($dbpass);

	return true;
}

function SqlEscape($text)
{
	global $dblink;
	return $dblink->real_escape_string($text);
}

function Query_ExpandFieldLists($match)
{
	$ret = array();
	$prefix = $match[1];
	$fields = preg_split('@\s*,\s*@', $match[2]);

	foreach ($fields as $f)
		$ret[] = $prefix.'.'.$f.' AS '.$prefix.'_'.$f;

	return implode(',', $ret);
}

function Query_MangleTables($match)
{
	global $dbpref, $tableLists;
	$tablename = $match[1];
	if($tableLists[$tablename])
		return $tableLists[$tablename];

	return $dbpref.$tablename;
}

function Query_AddUserInput($match)
{
	global $args;
	$match = $match[1];
	$format = 's';
	if(preg_match("/^\d+\D$/", $match))
	{
		$format = substr($match, strlen($match)-1, 1);
		$match = substr($match, 0, strlen($match)-1);
	}

	$var = $args[$match+1];

	if ($var === NULL) return 'NULL';

	if ($format == 'c')
	{
		$final = '';
		foreach ($var as $v) $final .= '\''.SqlEscape($v).'\',';
		return substr($final,0,-1);
	}

	if($format == "i") return (string)((int)$var);
	if($format == "u") return (string)max((int)$var, 0);
	if($format == "l")
	{
		//This is used for storing integers using the full 32bit range.
		//TODO: add code to emulate the 32bit overflow on 64bit.
		return (string)((int)$var);
	}
	return '\''.SqlEscape($var).'\'';
}

/*
 * Function for prepared queries
 *
 * Example usage: Query("SELECT t1.(foo,bar), t2.(*) FROM {table1} t1 LEFT JOIN {table2} t2 ON t2.id=t1.crapo WHERE t1.id={0} AND t1.crapo={1}", 1337, "Robert'; DROP TABLE students; --");
 * assuming a database prefix of 'abxd_', final query is:
 * SELECT t1.foo AS t1_foo,t1.bar AS t1_bar, t2.* FROM abxd_table1 t1 LEFT JOIN abxd_table2 t2 ON t2.id=t1.crapo WHERE t1.id='1337' AND t1.crapo='Robert\'; DROP TABLE students; --'
 *
 * compacted fieldlists allow for defining certain widely-used field lists as global variables or defines (namely, the fields for usernames)
 * {table} syntax allows for flexible manipulation of table names (namely, adding a DB prefix)
 *
 */

function parseQuery()
{
	global $dbpref, $args, $fieldLists;
	$args = func_get_args();
	if (is_array($args[0])) $args = $args[0];

	$query = $args[0];

	// expand compacted field lists
	$query = preg_replace("@(\w+)\.\(\*\)@s", '$1.*', $query);
	$query = str_replace(".(_userfields)", ".(".$fieldLists["userfields"].")", $query);
	$query = preg_replace_callback("@(\w+)\.\(([\w,\s]+)\)@s", 'Query_ExpandFieldLists', $query);

	// add table prefixes
	$query = preg_replace_callback("@\{([a-z]\w*)\}@si", "Query_MangleTables", $query);

	// add the user input
	$query = preg_replace_callback("@\{(\d+\w?)\}@s", 'Query_AddUserInput', $query);

	return $query;
}

function query()
{
	$args = func_get_args();
	if (is_array($args[0])) $args = $args[0];
	return rawQuery(parseQuery($args));
}

function rawQuery($query)
{
	global $queries, $querytext, $dblink, $mysqlCellClass, $debugQueries;

	$res = @$dblink->query($query);

	// Let it crash: the uncaught exception lands in the PHP error log like any
	// other fatal, stack trace included.
	if(!$res)
		throw new Exception("MySQL error: ".$dblink->error." -- Query was: ".$query);

	$queries++;

	if($debugQueries)
	{
		$mysqlCellClass = ($mysqlCellClass+1)%2;
		$querytext .= "<tr class=\"cell$mysqlCellClass\"><td><pre style=\"white-space:pre-wrap;\">".htmlspecialchars(preg_replace('/^\s*/m', "", $query))."</pre></td><td>";
		if(function_exists("backTrace"))
			$querytext .= backTrace();
	}

	return $res;
}

function fetch($result)
{
	return $result->fetch_assoc();
}

function fetchRow($result)
{
	return $result->fetch_row();
}

function fetchResult()
{
	$res = Query(func_get_args());
	if($res->num_rows == 0) return -1;
	return Result($res, 0, 0);
}

// based on http://stackoverflow.com/a/3779460/736054
function result($res, $row = 0, $field = 0)
{
	$res->data_seek($row);
	$ceva = array_values($res->fetch_assoc());
	$rasp = $ceva[$field];
	return $rasp;
}

function numRows($result)
{
	return $result->num_rows;
}

function insertId()
{
	global $dblink;
	return $dblink->insert_id;
}

function affectedRows()
{
	global $dblink;
	return $dblink->affected_rows;
}

function getDataPrefix($data, $pref)
{
	$res = array();

	foreach($data as $key=>$val)
		if(substr($key, 0, strlen($pref)) == $pref)
			$res[substr($key, strlen($pref))] = $val;

	return $res;
}


$fieldLists = array(
	"userfields" => "id,name,displayname,powerlevel,sex,minipic,karma"
);

$tableLists = array(
);

function loadFieldLists()
{
	global $fieldLists, $tableLists;

	//Allow plugins to add their own!
	$bucket = "fieldLists"; include('lib/pluginloader.php');
}

?>
