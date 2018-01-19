<?php
//  AcmlmBoard XD - Administration hub page
//  Access: administrators


if($loguser['powerlevel'] < 3)
	Kill(__("You're not an administrator. There is nothing for you here."));
$lastUrlMinPower = 3;

$title = __("PHP Info");

$crumbs = new PipeMenu();
$crumbs->add(new PipeMenuLinkEntry(__("Admin"), "admin"));
$crumbs->add(new PipeMenuLinkEntry(__("PHP info"), "phpinfo"));
makeBreadcrumbs($crumbs);

unset($_SERVER['ABXD_SALT']);
unset($_SERVER['ABXD_SFS_KEY']);
unset($_SERVER['MYSQL_PASSWORD']);

ob_start();
phpinfo(INFO_ALL ^ INFO_ENVIRONMENT);
$pinfo = ob_get_contents();
ob_end_clean();

$pinfo = preg_replace( '%^.*<body>(.*)</body>.*$%ms','$1',$pinfo);
echo '<div id="phpinfo">'.$pinfo.'</div>';

?>

<style type="text/css">
	#phpinfo {}
	#phpinfo .center { text-align: inherit; }
	#phpinfo pre {}
	#phpinfo a:link {}
	#phpinfo a:hover {}
	#phpinfo table {}
	#phpinfo .center {}
	#phpinfo .center table {}
	#phpinfo .center th {}
	#phpinfo td, th {}
	#phpinfo h1 {}
	#phpinfo h2 {}
	#phpinfo .p {}
	#phpinfo .e { width: 300px; }
	#phpinfo .h {}
	#phpinfo .v {}
	#phpinfo .vr {}
	#phpinfo img {}
	#phpinfo hr {}
</style>
