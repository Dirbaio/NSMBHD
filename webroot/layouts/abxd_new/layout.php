<!doctype html>
<html>
<head>
	<title><?php print $layout_title?></title>
	<?php include("header.php"); ?>
</head>
<body style="width:100%; font-size: <?php print $loguser['fontsize']; ?>%;">
<div id="body">
<div id="body-wrapper">
	<div id="main" style="padding:8px;">
		<div class="outline margin" id="header">
			<table class="outline margin">
				<tr>
					<td colspan="3" class="cell0">
						<!-- Board header goes here -->
						<table>
							<tr>
								<td style="border: 0px none; text-align: left;">
									<a href="<?php echo $boardroot;?>">
										<img id="theme_banner" src="<?php print htmlspecialchars($layout_logopic); ?>" alt="" title="<?php print htmlspecialchars($layout_logotitle); ?>" style="padding: 8px;" />
									</a>
								</td>
								<?php if($layout_pora) { ?>
								<td style="border: 0px none;">
									<?php print $layout_pora; ?>
								</td>
								<?php } ?>
							</tr>
						</table>
					</td>
				</tr>
				<tr class="cell1 mainMenuContainer">
					<td>
						<div class="userDropdownContainer">
						<?php print userLink($loguser, true);
							if ($loguserid) {
								$layout_userpanel->shift();
								$layout_userpanel->setClass("userMenu");
							}
							print $layout_userpanel->build();
							$layout_navigation->setClass("mainMenu");
							?>
						</div>
						<?php print $layout_navigation->build(); ?>
					</td>
				</tr>
			</table>
		</div>
	<div style="text-align: right;" class="nOnlineUsers">
		<?php print $layout_onlineusers; ?>
	</div>
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
