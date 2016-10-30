<?php

chdir("../");
include("install/installer.php");
if($_POST["action"] == "install")
{
	install();
	echo "Success!";
}

