<?php
//  AcmlmBoard XD support - Database settings

function mygetenv($name, $default=null) {
    $res = getenv($name);
    if($res)
        return $res;
    if($default !== null)
        return $default;
    // exit(1), not die(): die() exits 0, so a deploy that forgot an env var
    // would pass the init container running `launch.sh upgrade` and roll
    // forward. error_log() rather than printing, because it reaches both the
    // FPM log and the CLI's stderr, and the page doesn't need the var's name.
    error_log('Missing envvar ' . $name);
    exit(1);
}

$dbserv = mygetenv("MYSQL_HOST");
$dbuser = mygetenv("MYSQL_USER");
$dbpass = mygetenv("MYSQL_PASSWORD");
$dbname = mygetenv("MYSQL_DATABASE");

$urlRewriting = true;

$stopForumSpamKey = mygetenv("ABXD_SFS_KEY", '');

// Cloudflare Turnstile, the bot check on the registration form. Deliberately
// has no default: a deploy that forgets these should fail loudly at startup,
// not quietly serve an unprotected registration form. `./d start` passes
// Cloudflare's published always-passes test pair.
$turnstileSiteKey = mygetenv("ABXD_TURNSTILE_SITE_KEY");
$turnstileSecretKey = mygetenv("ABXD_TURNSTILE_SECRET_KEY");

$salt = mygetenv("ABXD_SALT");
