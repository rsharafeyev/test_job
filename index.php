<?php
//error_reporting(E_ERROR | E_WARNING | E_PARSE);
ob_start("ob_parse");

require_once('init.php');

if ($_GET['mod_only'] == 1)
{
	require_once('kernel/modules.php');
	exit;
}

?>
<!doctype html>
<html lang="ru">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<meta name="robots" content="index, follow">
	<meta name="keywords" content="<:META_KEYWORDS:>">
	<meta name="description" content="<:META_DESCRIPTION:>">
	<meta name="generator" content="<:META_GENERATOR:>">
	<title><:TITLE:></title>
	<link href="css/main.css" rel="stylesheet">
	<link href="css/custom-theme/jquery-ui-1.9.2.custom.css" rel="stylesheet">
	<link href="css/jquery.datetimepicker.css" rel="stylesheet">
	<script src="js/jquery-1.9.1.js"></script>
	<script src="js/jquery-ui-1.9.2.custom.js"></script>
	<script src="js/jquery.datetimepicker.js"></script>
	<script src="js/custom.js"></script>
	<script>
	$(function() {
		OnLoadPage();
	 });
	</script>
</head>
<body>
<?
if ($_SESS['user_id'] > 0)
{
	if ($_GET['mod_only'] == 2)
	{
		require_once('kernel/modules.php');
	} else
	{
		require_once('pages/main.php');
	}
} else
{
	require_once('pages/login.php');
}
?>
</body>
</html>
<?
/*
$tmp = "asd asdflgk jasodf gnajksdfg <:1:> asd sadgs dfsdf <:2:> asdfasdf !<br>";
print $tmp;
preg_match_all('/<:(\d+):>/', $tmp, $matches, PREG_PATTERN_ORDER);
print_r($matches);
*/

ob_end_flush();

function ob_parse($html)
{
	global $_CONF, $_SESS, $MOD_DATA, $_LANG;

	if ($_SESS['user_id'] > 0)
	{
		$html = str_replace('<:TITLE:>', $_CONF['SITE_NAME'].' - '.$MOD_DATA[0]['name'], $html);
	} else
	if ($_SESS['user_id'] == 0)
	{
		$html = str_replace('<:TITLE:>', $_CONF['SITE_NAME'].' - '.'Вход в систему',$html);
	}

	$html = str_replace('<:META_KEYWORDS:>', $_CONF['META_KEYWORDS'], $html);
	$html = str_replace('<:META_DESCRIPTION:>', $_CONF['META_DESCRIPTION'], $html);
	$html = str_replace('<:META_GENERATOR:>', $_CONF['META_GENERATOR'], $html);


	$db_data = mysql_exec('SELECT `str_id`, `string` FROM `strings` WHERE `lang_id` = '.$_LANG['selected'].' ORDER BY `str_id`');
	$_STR = array();
	foreach ($db_data as $key => $val)
	{
		$_STR[$val['str_id']] = $val['string'];
	}

	preg_match_all('/<:(\d+):>/', $html, $matches, PREG_PATTERN_ORDER);

	foreach ($matches[0] as $key => $val)
	{
		if (isset($_STR[$matches[1][$key]]))
		{
			$html = str_replace($val, $_STR[$matches[1][$key]], $html);
		}
	}

	return($html);
}
?>