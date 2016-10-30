<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">

<head>
	<title><?php print $layout_title?></title>
	<?php include("header.php"); ?>
</head>

<body style="width:100%; font-size: <?php print $loguser['fontsize']; ?>%;">
<div id="body">
<div id="body-wrapper">
<div id="header">
	<div id="boardheader">

		<!-- Board header goes here -->
		<table>
			<tr>
				<td style="border: 0px none; text-align: left;">
					<a href="<?php print $boardroot; ?>">
						<img id="theme_banner" src="<?php print htmlspecialchars($layout_logopic); ?>" alt="" title="<?php print htmlspecialchars($layout_title); ?>"  /> 
					</a>
				</td>
				<td style="border: 0px none; text-align: left;">
							<?php if($layout_pora) {
								print $layout_pora;
							 } ?>
				</td>

				<td style="border: 0px none; text-align: right; padding:0px; vertical-align:bottom;" class="smallFonts">
					<div id="userpanel-placeholder"  style="float:right;">					
					<div id="userpanel" class="cell1">
						<?php print $layout_userpanel->build(); ?>
					</div>
					</div>
				</td>
			</tr>
		</table>
	</div> <!--END OF HEADER-->
	
	<div id="boardheader2" class="cell1">
		<table><tr>
		<td style="text-align:left; padding-left:6px;"><?php print $layout_views; ?></td>
		<td><?php print $layout_onlineusers; ?>
		<?php print $layout_birthdays; ?></td>
		<td style="text-align:right; padding-right:6px;"><?php print $layout_time; ?></td>
		</tr></table>
	</div>
</div>
	<div id="sidebar">

		<table id="navigation" class="outline margin" style="width:130px; ">
			<tr class="header1">
				<th>Navigation
				</th>
			</tr>
			<tr class="cell0">
				<td class="sidemenu">
					<?php 
						$layout_navigation->setClass("sidemenu"); 
						print $layout_navigation->build();?>
				</td>
			</tr>
		</table>
	</div>

	<div id="main">
<form action="<?php print actionLink('login'); ?>" method="post" id="logout">
		<input type="hidden" name="action" value="logout" />
	</form>
	
	<?php print $layout_bars; ?>
	<div class="margin">
		<div style="float: right;">
			<?php print $layout_links->build();?>
		</div>
		<?php print $layout_crumbs->build();?>&nbsp;
	</div>
	<?php print $layout_contents;?>
	<div class="margin">
		<div style="float: right;">
			<?php print $layout_links->build();?>
		</div>
		<?php print $layout_crumbs->build();?>&nbsp;
	</div>

	</div>
	<div class="footer" style='clear:both;'>
	<?php print $layout_footer;?>
	</div>
</div>
</div>
</body>
</html>
