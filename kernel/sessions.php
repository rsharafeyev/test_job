<?
sess_fix();

if ($_COOKIE['sess_id'] && $_COOKIE['sess_key'])
{
	$sess_id = $_COOKIE['sess_id'];
 	$sess_key = $_COOKIE['sess_key'];
	if (sess_valid($sess_id,$sess_key))
	{
		$DB_DATA = mysql_exec('SELECT * FROM `sessions` WHERE `sess_id` = \''.$sess_id.'\'');
		if (count($DB_DATA) > 0)
		{
			$_SESS = $DB_DATA[0];
			if ($_SESS['user_id'] > 0)
			{
				$tmp_user_data = mysql_exec('SELECT * FROM `users` WHERE `id` = '.$_SESS['user_id'].' LIMIT 1');
				if (count($tmp_user_data) > 0)
				{
					$_USER = array();
					$_USER = array_merge($_USER, $tmp_user_data[0]);
				} else
				{
					$_SESS['user_id'] = 0;
					user_init();
				}
			} else
			{
				user_init();
			}

			if ($_CONF['SESS_SHIELD'])
			{
				if ($_SESS['shield_blocked'] <= time())
				{
					if (time() - $_SESS['shield_act'] < $_CONF['SESS_SHIELD_INTERVAL'])
					{
						$_SESS['shield_counter']++;
						if ($_SESS['shield_counter'] >= $_CONF['SESS_SHIELD_MAXACT'])
						{
							$_SESS['shield_counter'] = 0;
							$_SESS['shield_blocked'] = time() + $_CONF['SESS_SHIELD_BLOCKTIME'];
						}
					} else
					{
						$_SESS['shield_act'] = time();
						$_SESS['shield_counter'] = 0;
						mysql_update('sessions',$_SESS,'`sess_id` = \''.$sess_id.'\'');
					}
				}
			}

			$_SESS['sess_act'] = time();
            mysql_update('sessions',$_SESS,'`sess_id` = \''.$sess_id.'\'');
            if (($_CONF['SESS_SHIELD']) && ($_SESS['shield_blocked'] > time()))
            {
				print 'Session blocked on: '.($_SESS['shield_blocked'] - time()).' sec.';
				exit;
            }
		} else
		{
			$_SESS = array();
			$_SESS['sess_id'] = $sess_id;
			$_SESS['sess_add'] = time();
			$_SESS['sess_act'] = time();
			$_SESS['user_id'] = 0;
			$_SESS['shield_act'] = time();
			$_SESS['shield_counter'] = 1;
			$_SESS['shield_blocked'] = time();
			$_SESS['captcha'] = '0';
			mysql_insert('sessions',$_SESS);

			user_init();
		}
		if ($_GET['check'] == 'cookie')
		{
			$url = base64_decode($_GET['url']);
			header('Location: '.$url);
			exit;
		}
	} else
	{
		sess_init();
	}
} else
{
	if ($_GET['check'] == 'cookie')
	{
		print "Your browser does not support cookies or cookies are disabled!";
		exit;
	} else
	{
		sess_init();
	}
}


function user_init()
{
	global $_USER, $_SESS;
	$_SESS['user_id'] =0;
	$_USER['id'] = 0;
	$_USER['username'] = '';
	$_USER['email'] = '';
	$_USER['firstName'] = '';
	$_USER['lastName'] = '';
	$_USER['captcha'] = 0;
	$_USER['global_admin'] = 0;
}

function sess_init()
{
	global $_CONF;
	$sess_id = md5(getenv('remote_addr').':'.time().':'.rand(1000,9999));
	$sess_key = md5($sess_id.':'.$_CONF['SESS_SECRETKEY']);
	setcookie('sess_id', $sess_id);
	setcookie('sess_key', $sess_key);
	header('Location: '.(isset($_SERVER['HTTPS']) ? "https" : "http").'://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'].'?check=cookie&url='.base64_encode((isset($_SERVER['HTTPS']) ? "https" : "http").'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']));
	exit;
}

function sess_valid($sess_id,$sess_key)
{
	global $_CONF;
	return($sess_key == md5($sess_id.':'.$_CONF['SESS_SECRETKEY']));
}

function sess_fix()
{
	global $_CONF;
	$db_data = mysql_exec('SELECT `sess_id`,`user_id` FROM `sessions` WHERE `sess_act` < '.intval(time() - $_CONF['SESS_LIFETIME_A']).' OR `sess_add` < '.intval(time() - $_CONF['SESS_LIFETIME']));
	foreach ($db_data as $key => $val)
	{
		mysql_exec('DELETE FROM `sessions` WHERE `sess_id` = \''.$val['sess_id'].'\'');
		if ($val['user_id'] > 0)
		{
			//user session has expired
		}
	}
	
}

function sess_set_user_id($user_id)
{
	global $_SESS;
	global $_USER;
	if ($user_id > 0)
	{
		$db_data = mysql_exec('SELECT * FROM `users` WHERE `id` = '.intval($user_id));
		if (count($db_data) > 0)
		{
			$_SESS['user_id'] = $user_id;
			$_USER = array();
			$_USER = array_merge($_USER, $db_data[0]);
			setcookie('last_username', $_USER['username']);
		} else
		{
			user_init();
		}
	} else
	{
		user_init();
	}
	mysql_exec('UPDATE `sessions` SET `user_id` = '.$_SESS['user_id'].' WHERE `sess_id` = \''.$_SESS['sess_id'].'\'');
}
?>