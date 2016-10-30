<?php
	if(isAllowed("viewCalendar") && !$isBot)
		$navigation->add(new PipeMenuLinkEntry(__("Calendar"), "calendar", "", "", "calendar"));
?>
