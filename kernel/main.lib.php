<?
function access_add($user_id, $mod_id, $access)
{
	$data = array();
	$data['user_id'] = intval($user_id);
	$data['mod_id'] = intval($mod_id);
	$data['access'] = intval($access);
	mysql_insert('access', $data);
}

function access_auto_add($user_id)
{
	$db_data = mysql_exec('SELECT `id`, `access_auto_add` FROM `modules` WHERE `access_auto_add` > 0');
	foreach ($db_data as $key => $val)
	{
		access_add($user_id, $val['id'], $val['access_auto_add']);
	}
}

function ArrToStr($arr)
{
	$result = '';
	foreach ($arr as $key => $val)
	{
		$result .= "$key='$val'\r\n";
	}
	return($result);
}

function get_var($var)
{
	$result = false;
	if(isset($_GET[$var]))
	{
		$result = $_GET[$var];
	} else
	if(isset($_POST[$var]))
	{
		$result = $_POST[$var];
	}
	return($result);
}

function saved_var($var)
{
	$result = false;
	if(isset($_GET[$var]))
	{
		$result = $_GET[$var];
		setcookie($var, $result);
	} else
	if(isset($_POST[$var]))
	{
		$result = $_POST[$var];
		setcookie($var, $result);
	} else
	if(isset($_COOKIE[$var]))
	{
		$result = $_COOKIE[$var];
	}
	return($result);
}

function str_var($str1, $str2)
{
	if ($str1) {
		return($str1);
	} else
	if ($str2) {
		return($str2);
	} else {
		return(false);
	}
}

function url_make($a)
{
	$vals = array();
	foreach ($a as $key => $val) {
		$vals[] = $key.'='.$val;
	}
	return(implode('&', $vals));
}

function datetime_to_timestamp($datetime, $offset_d = 0, $offset_m = 0, $offset_Y = 0)
{
	$datetime = trim($datetime);
	$tmp = explode(' ',$datetime);
	$data = array();

	$tmp1 = explode('.',$tmp[0]);
	$data['d'] = $tmp1[0] + $offset_d;
	$data['m'] = $tmp1[1] + $offset_m;
	$data['Y'] = $tmp1[2] + $offset_Y;

	if (count($tmp) > 1)
	{
		$tmp2 = explode(':',$tmp[1]);
		$data['h'] = $tmp2[0];
		$data['i'] = $tmp2[1];
		$data['s'] = $tmp2[2];
	} else
	{
		$data['h'] = 0;
		$data['i'] = 0;
		$data['s'] = 0;
	}

	return(mktime($data['h'],$data['i'],$data['s'],$data['m'],$data['d'],$data['Y']));
}

function is_even($i)
{
	return(($i%2)===0);
}

function mod_exists($mod_id)
{
	return(count(mysql_exec('SELECT 1 FROM `modules` WHERE `id` = '.$mod_id))>0);
}

function user_get_passhash($password)
{
	global $_CONF;
	return(md5(md5($_CONF['PASS_SECRETKEY']).md5($password)));
}

function html_print_select_options($data, $selected = false)
{
	foreach($data as $id => $val)
	{
		?>
		<option value="<? print $id; ?>"<? if ($selected && $selected == $id){print ' selected';} ?>><? print $val; ?></option>
		<?
	}
}

function kill_captcha()
{
	global $_SESS;
	$data = array();
	$data['captcha'] = '0';
	$_SESS['captcha'] = '0';
	mysql_update('sessions',$data,'`sess_id` = \''.mysql_safeval($_SESS['sess_id']).'\'');
}
?>