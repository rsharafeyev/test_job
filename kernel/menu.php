<?
	$MOD_DATA = mysql_exec('SELECT * FROM `modules` WHERE 1=1 ORDER BY `order_id`');
	parse_str($_SERVER['QUERY_STRING'], $get_data);
	
	$photoData = @getimagesize($_CONF["PHOTO_DIR"].$_USER['photo']);
	if ($photoData[0] > $photoData[1]){$photoSide = 'width';} else {$photoSide = 'height';}
	
?>
<ul id="mainmenu">
	<li align="right">
		<:41:>:
		<select name="lang_id" id="lang_id" class="text ui-widget-content ui-corner-all">
			<? html_print_select_options($_LANG['list'], $_LANG['selected']); ?>
		</select>
	</li>
	<li>&nbsp;</li>
	<?
	for ($i=0; $i<count($MOD_DATA); $i++)
	{
		if (($MOD_DATA[$i]['public'] > 0) OR ($_ACCESS[$MOD_DATA[$i]['id']] > 0) OR ($_USER['global_admin'] > 0))
		{
		?>
		<li <? if($_GET['mod_id']==$MOD_DATA[$i]['id']){print ' class="ui-corner-all ui-state-active"';} ?>><a href="index.php?mod_id=<? print $MOD_DATA[$i]['id']; ?>"><? print $MOD_DATA[$i]['name']; ?></a></li>
		<?
		}
	}
	?>
	<li>&nbsp;</li>
	<?
	if ($_SESS['user_id'] > 0)
	{
		?>
		<li><a style="font-weight: bolder;" href="index.php?user_action=logout"><:37:></a></li>
		<?
	}
	?>
</ul>
<br>
<div class="ui-widget ui-widget-content ui-corner-all" style="padding: 5px;">
	<a href="index.php?mod_id=2#profile" title="<:38:>: <? print $_USER['firstName'].' '.$_USER['lastName']; ?>" style="display:block"><div style="width: 120px; height: 120px; display: block; margin: 10px auto; border-radius: 120px; background: url(<? if ($_USER['photo'] == '0'){print 'img/no_avatar.png';} else {print $_CONF["PHOTO_DIR"].$_USER['photo'];} ?>) 100% 100% no-repeat; background-size: cover"></div></a>
	<:39:><br>
	<:40:>: <b><? print $_USER['firstName'].' '.$_USER['lastName']; ?></b>
</div>

<script>
	$(function() {
		$( "#mainmenu" ).menu();
	});

	$("#lang_id").change(function() {
		<?
			$url_param = array_merge($_GET, array('lang_id' => ''))
		?>
		location.href = 'index.php?<? print url_make($url_param); ?>' + $(this).val();
	});
</script>
