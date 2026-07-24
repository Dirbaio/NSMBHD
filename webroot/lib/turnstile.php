<?php
//  AcmlmBoard XD - Cloudflare Turnstile, the bot check on the registration form.
//
//  This replaces the Securimage image captcha. That was a 3.2RC2 (April 2012)
//  build, and it had stopped being a speed bump: its distortion is solved by
//  off-the-shelf models and by human farms at well under a cent a go, and
//  Securimage::validate() only cleared the stored code on a *correct* answer,
//  so a single image could be guessed at until its 15-minute expiry.
//
//  https://developers.cloudflare.com/turnstile/

//  The widget writes its token into a hidden `cf-turnstile-response` field in
//  the surrounding form. Tokens are single-use and expire after 300 seconds;
//  the whole page re-renders on a rejected POST, so each attempt gets a fresh
//  widget and a fresh token without any explicit reset.
function turnstileWidget()
{
	global $turnstileSiteKey;

	return "
					<div class=\"cf-turnstile\" data-sitekey=\"".htmlspecialchars($turnstileSiteKey)."\"></div>
					<script src=\"https://challenges.cloudflare.com/turnstile/v0/api.js\" async defer></script>";
}

//  Check the submitted token against Cloudflare's siteverify API.
//
//  Returns true only on an explicit success. A missing token, a network
//  failure, a timeout, an HTTP error or an unparseable reply all count as a
//  failed check: an outage at Cloudflare then blocks registration until it
//  clears, which is the cheaper of the two mistakes. Failing open would leave
//  the door wide with nothing in the logs pointing at it.
function turnstileCheck()
{
	global $turnstileSecretKey;

	if(empty($_POST['cf-turnstile-response']))
		return false;

	$ch = curl_init('https://challenges.cloudflare.com/turnstile/v0/siteverify');
	curl_setopt_array($ch, array(
		CURLOPT_POST           => true,
		CURLOPT_POSTFIELDS     => http_build_query(array(
			'secret'   => $turnstileSecretKey,
			'response' => $_POST['cf-turnstile-response'],
			// A hint only -- Cloudflare does not require it to match. Safe to
			// pass because the gateway overwrites X-Forwarded-For, so this is
			// the real client; see CLAUDE.md.
			'remoteip' => $_SERVER['REMOTE_ADDR'],
		)),
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_CONNECTTIMEOUT => 5,
		CURLOPT_TIMEOUT        => 10,
	));

	$body   = curl_exec($ch);
	$error  = curl_error($ch);
	$status = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
	curl_close($ch);

	if($body === false)
	{
		error_log("turnstile: siteverify request failed: $error");
		return false;
	}
	if($status != 200)
	{
		error_log("turnstile: siteverify returned HTTP $status");
		return false;
	}

	$result = json_decode($body, true);
	if(!is_array($result) || !isset($result['success']))
	{
		error_log("turnstile: siteverify returned an unparseable body: $body");
		return false;
	}

	if($result['success'] !== true)
	{
		// invalid-input-response and timeout-or-duplicate are ordinary: a stale
		// tab, a resubmitted form, or a bot. invalid-input-secret is a
		// misconfiguration and worth spotting in the log.
		$codes = isset($result['error-codes']) ? implode(',', (array)$result['error-codes']) : '';
		error_log("turnstile: verification failed ($codes)");
		return false;
	}

	return true;
}
