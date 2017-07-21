<?php
//  AcmlmBoard XD - User account registration page
//  Access: any, but meant for guests.

$crumbs = new PipeMenu();
$crumbs->add(new PipeMenuLinkEntry(__("Register"), "register"));
makeBreadcrumbs($crumbs);

$haveSecurimage = is_file("securimage/securimage.php");
if($haveSecurimage)
	session_start();

$title = __("Register");

class StopForumSpam
{
    /**
    * The API key.
    *
    * @var string
    */
    private $api_key;
    /**
    * The base url, for tha API/
    *
    * @var string
    */
    private $endpoint = 'http://www.stopforumspam.com/';
    /**
    * Constructor.
    *
    * @param string $api_key Your API Key, optional (unless adding to database).
    */
    public function __construct( $api_key = null ) {
        // store variables
        $this->api_key = $api_key;
    }
    /**
    * Add to the database
    *
    * @param array $args associative array containing email, ip, username and optionally, evidence
    * e.g. $args = array('email' => 'user@example.com', 'ip_addr' => '8.8.8.8', 'username' => 'Spammer?', 'evidence' => 'My favourite website http://www.example.com' );
    * @return boolean Was the update succesfull or not.
    */
    public function add( $args )
    {
        // check for mandatory arguments
        if (empty($args['username']) || empty($args['ip_addr']) || empty($args['email']) ) {
            return false;
        }
        // known?
        $is_spammer = $this->is_spammer($args);
        if (!$is_spammer || $is_spammer['known']) {
            return false;
        }
        // add api key
        $args['api_key'] = $this->api_key;
        // url to poll
        $url = $this->endpoint.'add.php?'.http_build_query($args, '', '&');
        // execute
        $response = file_get_contents($url);
        return (false == $response ? false : true);
    }
    /**
    * Get record from spammers database.
    *
    * @param array $args associative array containing either one (or all) of these: username / email / ip.
    * e.g. $args = array('email' => 'user@example.com', 'ip' => '8.8.8.8', 'username' => 'Spammer?' );
    * @return object Response.
    */
    public function get( $args )
    {
        // should check first if not already in database
        // url to poll
        $url = $this->endpoint.'api?f=json&'.http_build_query($args, '', '&');
        //
        return $this->poll_json( $url );
    }
    /**
    * Check if either details correspond to a known spammer. Checking for username is discouraged.
    *
    * @param array $args associative array containing either one (or all) of these: username / email / ip
    * e.g. $args = array('email' => 'user@example.com', 'ip' => '8.8.8.8', 'username' => 'Spammer?' );
    * @return boolean
    */
    public function is_spammer( $args )
    {
        // poll database
        $record = $this->get( $args );
        if ( !isset($record->success) ) {
            return false;
        }
        // give the benefit of the doubt
        $spammer = false;
        // are all datapoints on SFS?
        $known = true;
        // parse database record
        $datapoint_count = 0;
        $known_datapoints = 0;
        foreach( $record as $datapoint )
        {
            // not 'success'
            if ( isset($datapoint->appears) && $datapoint->appears ) {
                $datapoint_count++;
                // are ANY of the datapoints on SFS?
                if ( $datapoint->appears == true)
                {
                    $known_datapoints++;
                    $spammer = true;
                }
            }
        }
        // are ANY of the datapoints not on SFS
        if ( $datapoint_count > $known_datapoints) {
            $known = false;
        }
return $spammer;
        return array(
            'spammer' => $spammer,
            'known' => $known
        );
    }
    /**
    * Get json and decode. Currently used for polling the database, but hoping for future
    * json response support, when adding.
    *
    * @param string $url The url to get
    * @return object Response.
    */
    protected static function poll_json( $url )
    {
        $json = file_get_contents( $url );
        $object = json_decode($json);
        return $object;
    }
}

function IsTorExitPoint(){
    if (gethostbyname(ReverseIPOctets($_SERVER['REMOTE_ADDR']).".".$_SERVER['SERVER_PORT'].".".ReverseIPOctets($_SERVER['SERVER_ADDR']).".ip-port.exitlist.torproject.org")=="127.0.0.2") {
        return true;
    } else {
       return false;
    }
}

function ReverseIPOctets($inputip){
    $ipoc = explode(".",$inputip);
    return $ipoc[3].".".$ipoc[2].".".$ipoc[1].".".$ipoc[0];
}

function validateSex($sex)
{
	if($sex == 0) return 0;
	if($sex == 1) return 1;
	if($sex == 2) return 2;

	return 2;
}

if(isset($_POST['name']))
{
	$name = trim($_POST['name']);
	$cname = str_replace(" ","", strtolower($name));

	$rUsers = Query("select name, displayname from {users}");
	while($user = Fetch($rUsers))
	{
		$uname = trim(str_replace(" ", "", strtolower($user['name'])));
		if($uname == $cname)
			break;
		$uname = trim(str_replace(" ", "", strtolower($user['displayname'])));
		if($uname == $cname)
			break;
	}

	$ipKnown = FetchResult("select COUNT(*) from {users} where lastip={0}", $_SERVER['REMOTE_ADDR']);

	//This makes testing faster.
	if($_SERVER['REMOTE_ADDR'] == "127.0.0.1")
		$ipKnown = 0;

	if($uname == $cname)
		$err = __("This user name is already taken. Please choose another.");
	else if($name == "" || $cname == "")
		$err = __("The user name must not be empty. Please choose one.");
	else if(strpos($name, ";") !== false)
		$err = __("The user name cannot contain semicolons.");
	elseif($ipKnown >= 3)
		$err = __("Another user is already using this IP address.");
	else if (strlen($_POST['pass']) < 4)
		$err = __("Your password should atleast be 4 characters.");
	else if ($_POST['pass'] !== $_POST['pass2'])
		$err = __("The passwords you entered don't match.");
	else if($haveSecurimage)
	{
		include("securimage/securimage.php");
		$securimage = new Securimage();
		if($securimage->check($_POST['captcha_code']) == false)
			$err = __("You got the CAPTCHA wrong.");
	}

	if(!$err)
	{
		$reasons = array();
		if(IsTorExitPoint()) {
			$reasons[] = 'tor';
		}

		$s = new StopForumSpam($stopForumSpamKey);

		if($s->is_spammer(array('email' => $_POST['email'], 'ip' => $_SERVER['REMOTE_ADDR'] ))) {
			$reasons[] = 'sfs';
		}

		if(count($reasons)) {
			$reason = implode(',', $reasons);
			$bucket = "regfail"; include("lib/pluginloader.php");

			$err = 'An unknown error occured, please try again.';
		}
	}

	if($err)
	{
		Alert($err);
	}
	else
	{
		$newsalt = Shake();
		$sha = doHash($_POST['pass'].$salt.$newsalt);

		$sex = validateSex($_POST["sex"]);
		$rUsers = Query("insert into {users} (name, password, pss, regdate, lastactivity, lastip, email, sex, theme) values ({0}, {1}, {2}, {3}, {3}, {4}, {5}, {6}, {7})", $_POST['name'], $sha, $newsalt, time(), $_SERVER['REMOTE_ADDR'], $_POST['email'], $sex, Settings::get("defaultTheme"));

		$uid = insertId();

		if($uid == 1)
			Query("update {users} set powerlevel = 4 where id = 1");

		recalculateKarma($uid);

		logAction('register', array('user' => $uid));

		$user = Fetch(Query("select * from {users} where id={0}", $uid));
		$user["rawpass"] = $_POST["pass"];

		$bucket = "newuser"; include("lib/pluginloader.php");

		$sessionID = Shake();
		setcookie("logsession", $sessionID, 0, $boardroot, "", isHttps(), true);
		Query("INSERT INTO {sessions} (id, user, autoexpire) VALUES ({0}, {1}, {2})", doHash($sessionID.$salt), $user["id"], 0);
		redirectAction("board");
	}
}


$sexes = array(__("Male"), __("Female"), __("N/A"));

$name = "";
if(isset($_POST["name"]))
	$name = htmlspecialchars($_POST["name"]);
$email = "";
if(isset($_POST["email"]))
	$email = htmlspecialchars($_POST["email"]);
$sex = 2;
if(isset($_POST["sex"]))
	$sex = validateSex($_POST["sex"]);

echo "
<form action=\"".actionLink("register")."\" method=\"post\">
	<table class=\"outline margin width50\">
		<tr class=\"header0\">
			<th colspan=\"2\">
				".__("Register")."
			</th>
		</tr>
		<tr>
			<td class=\"cell2\">
				<label for=\"un\">".__("User name")."</label>
			</td>
			<td class=\"cell0\">
				<input type=\"text\" id=\"un\" name=\"name\" value=\"$name\" maxlength=\"20\" style=\"width: 98%;\"  class=\"required\" />
			</td>
		</tr>
		<tr>
			<td class=\"cell2\">
				<label for=\"pw\">".__("Password")."</label>
			</td>
			<td class=\"cell1\">
				<input type=\"password\" id=\"pw\" name=\"pass\" size=\"13\" maxlength=\"32\" class=\"required\" /> / ".__("Repeat:")." <input type=\"password\" id=\"pw2\" name=\"pass2\" size=\"13\" maxlength=\"32\" class=\"required\" />
			</td>
		</tr>
		<tr>
			<td class=\"cell2\">
				<label for=\"email\">".__("Email address")."</label>
			</td>
			<td class=\"cell0\">
				<input type=\"email\" id=\"email\" name=\"email\" value=\"$email\" style=\"width: 98%;\" maxlength=\"60\" />
			</td>
		</tr>
		<tr>
			<td class=\"cell2\">
				".__("Sex")."
			</td>
			<td class=\"cell1\">
				".MakeOptions("sex",$sex,$sexes)."
			</td>
		</tr>";

if($haveSecurimage)
{
	echo "
		<tr>
			<td class=\"cell2\">
				".__("Security")."
			</td>
			<td class=\"cell1\">
				<img width=\"200\" height=\"80\" id=\"captcha\" src=\"".actionLink("captcha", shake())."\" alt=\"CAPTCHA Image\" />
				<button onclick=\"document.getElementById('captcha').src = '".actionLink("captcha", shake())."?' + Math.random(); return false;\">".__("New")."</button><br />
				<input type=\"text\" name=\"captcha_code\" size=\"10\" maxlength=\"6\" class=\"required\" />
			</td>
		</tr>";
}

echo "
		<tr class=\"cell2\">
			<td></td>
			<td>
				<input type=\"submit\" name=\"action\" value=\"".__("Register")."\"/>
			</td>
		</tr>
		<tr>
			<td colspan=\"2\" class=\"cell0 smallFonts\">
				".__("Specifying an email address is not exactly a hard requirement, but it will allow you to reset your password should you forget it. By default, your email is not shown.")."
			</td>
		</tr>
	</table>
</form>";

function MakeOptions($fieldName, $checkedIndex, $choicesList)
{
	$checks[$checkedIndex] = " checked=\"checked\"";
	foreach($choicesList as $key=>$val)
		$result .= format("
					<label>
						<input type=\"radio\" name=\"{1}\" value=\"{0}\"{2} />
						{3}
					</label>", $key, $fieldName, $checks[$key], $val);
	return $result;
}
