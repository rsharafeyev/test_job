<?php
//DEFAULT TIME ZONE
	date_default_timezone_set('Asia/Almaty');

//SITE CONFIG
	$_CONF['SITE_ID'] = 1;
	$_CONF['SITE_BASEURL'] = 'http://'.$_SERVER['HTTP_HOST'].'/test_job';
	$_CONF['SITE_NAME'] = 'TestJob';

	$_CONF['META_KEYWORDS'] = 'TestJob';
	$_CONF['META_DESCRIPTION'] = 'My Test Job';
	$_CONF['META_GENERATOR'] = 'Rinat Sharafeyev';

//DB_CONFIG
	$_CONF['DB_CONF'] = array('HOST'=>'localhost','USER'=>'test_job','PASS'=>'Te$tJ0b8602','BASE'=>'test_job');

//SECURITY
	$_CONF['PASS_SECRETKEY'] = 'fYVn8daRPdQwPmV';

//SESSIONS
	$_CONF['SESS_SECRETKEY'] = 'AdgI5gvAgCsMkDy';
	$_CONF['SESS_LIFETIME'] = 24 * 60 * 60; //Время жизни сессии (в секундах)
	$_CONF['SESS_LIFETIME_A'] = 30 * 60; //Время жизни сессии без действий (в секундах)
	$_CONF['SESS_SHIELD'] = false; //Защита от DoS'a
	$_CONF['SESS_SHIELD_INTERVAL'] = 5; //Интервал (в секундах)
	$_CONF['SESS_SHIELD_MAXACT'] = 10; //Максимальное количество обращений за SESS_SHIELD_INTERVAL
	$_CONF['SESS_SHIELD_BLOCKTIME'] = 5 *60; //Время блокировки (в секундах)

//VISIBILITY
	$_CONF['ITEMS_PER_PAGE'] = 50;

//PHOTO
	$_CONF['PHOTO_DIR'] = 'photo/';
	$_CONF['PHOTO_SIZE'] = 5 * 1024 * 1024; //Максимальный размер фото (5 MB)
	$_CONF['PHOTO_RESIZE'] = 600; //Размер наибольшей стороны (ширины/высоты) в пикселях
?>
