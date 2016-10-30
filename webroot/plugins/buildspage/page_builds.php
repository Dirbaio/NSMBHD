<?php
header("HTTP/1.1 301 Moved Permanently");
header("Status: 301 Moved Permanently");
die(header("Location: /download/"));
