<?php

$ajaxPage = true;

$id = (int)$_GET["id"];
echo FetchResult("select email from {users} where id={0} and showemail=1", $id);

