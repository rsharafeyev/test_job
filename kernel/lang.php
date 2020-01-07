<?
$_LANG = array();
$_LANG['list'] = array();
$_LANG['default'] = 0;
$_LANG['selected'] = 0;

$db_data = mysql_exec('SELECT * FROM `languages` WHERE 1 ORDER BY `id`');
if (count($db_data) == 0){die('Error. Table "languages" is empty.');}

foreach ($db_data as $key => $val)
{
	if ($val['default'] > 0){$_LANG['default'] = $val['id'];}
	$_LANG['list'][$val['id']] = $val['name_short'];
}
if($_LANG['default'] == 0)
{
	$_LANG['default'] = $db_data[0]['id'];
}

$_LANG['selected'] = saved_var('lang_id');
if (!isset($_LANG['list'][$_LANG['selected']]))
{
	$_LANG['selected'] = $_LANG['default'];
}
?>