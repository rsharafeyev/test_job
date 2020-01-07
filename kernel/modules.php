<?
$DEFAULT_MOD_ID = 1;


if ($_GET['mod_id'] && count(mysql_exec('SELECT 1 FROM `modules` WHERE `id` = '.$MOD_ID.' LIMIT 1')) > 0)
{
	$MOD_ID = intval($_GET['mod_id']);
} else
{
	$MOD_ID = $DEFAULT_MOD_ID;
}

if ($_SESS['err_msg'])
{
	?>
	<div class="alert"><? print $_SESS['err_msg']; ?></div>
	<?
}

$MOD_DATA = mysql_exec('SELECT * FROM `modules` WHERE `id` = '.$MOD_ID.' LIMIT 1');
if (count($MOD_DATA) > 0)
{
	$filename = 'modules/'.$MOD_DATA[0]['filename'];
	if (file_exists($filename) && is_file($filename))
	{
		$_MOD = $MOD_DATA[0];
		if (($MOD_DATA[0]['public'] > 0) OR ($_ACCESS[$MOD_ID] > 0) OR ($_USER['global_admin'] > 0))
		{
			include($filename);
		} else
		{
			?>
			<div id="dialog" title="Basic dialog">
				<p>Отказано в доступе.</p>
			</div>
			<div class="alert"></div>
			<?
		}
	} else
	{
		print "error";
	}
} else
{
	print "error";
}


?>