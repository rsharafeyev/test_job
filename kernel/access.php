<?
$_ACCESS = array();
if ($_SESS['user_id'] > 0)
{
	$db_result = mysql_exec('SELECT `mod_id`,`access` FROM `access` WHERE `user_id` = '.$_SESS['user_id']);
	for ($i=0; $i<count($db_result); $i++)
	{
		$_ACCESS[$db_result[$i]['mod_id']] = $db_result[$i]['access'];
	}
}
?>