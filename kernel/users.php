<?
if ($_POST['user_action'] == 'login')
{
	$_RESULT['done'] = true;
	$user_data = mysql_exec('SELECT `id` FROM `users` WHERE `username` = \''.mysql_safeval($_POST['login_username']).'\' AND `password` = \''.user_get_passhash($_POST['login_password']).'\'');
	if (count($user_data) > 0)
	{
		sess_set_user_id($user_data[0]['id']);
		header('location: index.php');
		exit;
	} else
	{
		$_RESULT['done'] = false;
		$_RESULT['text'] = '<:25:>';
	}
} else
if ($_POST['user_action'] == 'logout' || $_GET['user_action'] == 'logout')
{
	$_RESULT['done'] = true;
	sess_set_user_id(0);
	header('location: index.php');
	exit;
} else
if ($_POST['user_action'] == 'reg_account')
{
	$_RESULT['done'] = true;
	$data = array();
	$data['global_admin'] = 0;


	if ( !preg_match('/^[a-zA-Z0-9_\-]{3,16}$/', $_POST['reg_username']) )
	{
		$_RESULT['done'] = false;
		$_RESULT['text'] .= '<:16:><br>';
	}

	$db_data = mysql_exec('SELECT `id` FROM `users` WHERE `username` = \''.mysql_safeval($_POST['reg_username']).'\'');
	if (count($db_data) > 0)
	{
		$_RESULT['done'] = false;
		$_RESULT['text'] .= '<:26:><br>';		
	}

	if ( !preg_match('/^[a-zA-Zа-яА-Я0-9_\-]{2,32}$/', $_POST['reg_firstName']) && preg_match('/^[a-zA-Zа-яА-Я0-9_\-]{2,32}$/', $_POST['reg_lastName']) )
	{
		$_RESULT['done'] = false;
		$_RESULT['text'] .= '<:27:><br>';
	}

	if (!preg_match('/^(?:[a-z0-9]+(?:[-_.]?[a-z0-9]+)?@[a-z0-9_.-]+(?:\.?[a-z0-9]+)?\.[a-z]{2,5})$/i', $_POST['reg_email']))
	{
		$_RESULT['done'] = false;
		$_RESULT['text'] .= '<:19:><br>';
	}

	if ( !preg_match('/^[a-zA-Z0-9_\-\!\@\#\$\%\^\&\*]{6,32}$/', $_POST['reg_password']))
	{
		$_RESULT['done'] = false;
		$_RESULT['text'] .= '<:20:><br>';
	}

	if ( $_POST['reg_password'] != $_POST['reg_passwordConfirm'] )
	{
		$_RESULT['done'] = false;
		$_RESULT['text'] .= '<:28:><br>';
	}

	if ($_SESS['captcha'] == '0' || strtolower($_POST['reg_captcha']) != $_SESS['captcha'])
	{
		$_RESULT['done'] = false;
		$_RESULT['text'] .= '<:29:><br>';
	}

	$photoName = '0';
	print_r($_FILES['reg_photo']);
	if (strlen($_FILES['reg_photo']['name']) > 0 && $_FILES['reg_photo']['size'] > 0)
	{
		if ($_FILES["reg_photo"]["size"] > $_CONF['PHOTO_SIZE'])
		{
			$_RESULT['done'] = false;
			$_RESULT['text'] .= '<:30:> '.round($_CONF['PHOTO_SIZE']/(1024*1024), 2).'MB.<br>';
		}

		$photoData = @getimagesize($_FILES["reg_photo"]["tmp_name"]);
		
		if($photoData === FALSE || !($photoData[2] == IMAGETYPE_GIF || $photoData[2] == IMAGETYPE_JPEG || $photoData[2] == IMAGETYPE_PNG))
		{
			$_RESULT['done'] = false;
			$_RESULT['text'] .= '<:31:><br>';
		}
		
		if ($_RESULT['done'])
		{
			$photoName = md5($_POST['reg_username'].'|'.time().'|'.rand(100000,999999)).'.jpg';
			$photoImage = new imageTool();
			$photoImage -> load($_FILES['reg_photo']['tmp_name']);
			$photoImage -> resizeToSquare($_CONF['PHOTO_RESIZE']);
			$photoImage -> save($_CONF['PHOTO_DIR'].$photoName, IMAGETYPE_JPEG, 80);
		}
	}
	
	if ( $_RESULT['done'] )
	{
		$data['username'] = $_POST['reg_username'];
		$data['firstName'] = $_POST['reg_firstName'];
		$data['lastName'] = $_POST['reg_lastName'];
		$data['photo'] = $photoName;
		$data['email'] = $_POST['reg_email'];
		$data['password'] = user_get_passhash($_POST['reg_password']);

		mysql_insert('users', $data);
		$new_user_id = mysql_insert_id($_CONF['DB_CONN']);
		if ($new_user_id > 0)
		{
			$_RESULT['text'] .= '<:32:><br>';
			access_auto_add($new_user_id);
		} else
		{
			$_RESULT['text'] .= '<:33:><br>';
		}
	}
	kill_captcha();
} else
if ($_POST['user_action'] == 'upd_account')
{
	$_RESULT['done'] = true;
	$data = array();

	if ( !preg_match('/^[a-zA-Zа-яА-Я0-9_\-]{2,32}$/', $_POST['profile_firstName']) && preg_match('/^[a-zA-Zа-яА-Я0-9_\-]{2,32}$/', $_POST['profile_lastName']) )
	{
		$_RESULT['done'] = false;
		$_RESULT['text'] .= '<:27:><br>';
	}

	if (!preg_match('/^(?:[a-z0-9]+(?:[-_.]?[a-z0-9]+)?@[a-z0-9_.-]+(?:\.?[a-z0-9]+)?\.[a-z]{2,5})$/i', $_POST['profile_email']))
	{
		$_RESULT['done'] = false;
		$_RESULT['text'] .= '<:19:><br>';
	}

	$photoName = '0';
	if (strlen($_FILES['profile_photo']['name']) > 0 && $_FILES['profile_photo']['size'] > 0)
	{
		if ($_FILES["profile_photo"]["size"] > $_CONF['PHOTO_SIZE'])
		{
			$_RESULT['done'] = false;
			$_RESULT['text'] .= '<:30:> '.round($_CONF['PHOTO_SIZE']/(1024*1024), 2).'MB.<br>';
		}

		$photoData = @getimagesize($_FILES["profile_photo"]["tmp_name"]);
		
		if($photoData === FALSE || !($photoData[2] == IMAGETYPE_GIF || $photoData[2] == IMAGETYPE_JPEG || $photoData[2] == IMAGETYPE_PNG))
		{
			$_RESULT['done'] = false;
			$_RESULT['text'] .= '<:31:><br>';
		}
		
		if ($_RESULT['done'])
		{
			$photoName = md5($_USER['username'].'|'.time().'|'.rand(100000,999999)).'.jpg';
			$photoImage = new imageTool();
			$photoImage -> load($_FILES['profile_photo']['tmp_name']);
			$photoImage -> resizeToSquare($_CONF['PHOTO_RESIZE']);
			$photoImage -> save($_CONF['PHOTO_DIR'].$photoName, IMAGETYPE_JPEG, 80);
		}
	}
	
	if ( $_RESULT['done'] )
	{
		$data['firstName'] = $_POST['profile_firstName'];
		$data['lastName'] = $_POST['profile_lastName'];
		if ($photoName != '0')
		{
			$data['photo'] = $photoName;
		}
		$data['email'] = $_POST['profile_email'];

		$_USER = array_merge($_USER, $data);
		mysql_update('users', $data, '`id` = '.$_USER['id']);
		$_RESULT['text'] .= '<:34:><br>';
	}
} else
if ($_POST['user_action'] == 'pass_change')
{
	$_RESULT['done'] = true;
	if (count(mysql_exec("SELECT 1 FROM `users` WHERE `id` = ".intval($_USER["id"])." AND `password` = '".user_get_passhash($_POST['pass_old'])."'")) == 0)
	{
		$_RESULT['done'] = false;
		$_RESULT['text'] .= '<:35:><br>';
	}

	if ( !preg_match('/^[a-zA-Z0-9_\-\!\@\#\$\%\^\&\*]{6,32}$/', $_POST['pass_new']))
	{
		$_RESULT['done'] = false;
		$_RESULT['text'] .= '<:20:><br>';
	}

	if ( $_POST['pass_new'] != $_POST['pass_chk'] )
	{
		$_RESULT['done'] = false;
		$_RESULT['text'] .= '<:28:><br>';
	}
	
	if ( $_RESULT['done'] )
	{
		$data = array();
		$data['password'] = user_get_passhash($_POST['pass_new']);
		mysql_update('users', $data, '`id` = '.intval($_USER['id']));
		$_RESULT['text'] = '<:36:>';
	}
}


if ($_USER['id'] > 0)
{
	//GET USER PARAMS
	if ($_USER['params'] != '')
	{
		$tmp_line = explode('|', $_USER['params']);
		$_USER['params'] = array();

		for ($i=0; $i<count($tmp_line); $i++)
		{
			$tmp_param = explode('=', $tmp_line[$i]);
			if (count($tmp_param) == 2)
			{
				$_USER['params'][$tmp_param[0]] = $tmp_param[1];
			}
		}
	}


	
}
?>