<?
if ($_GET["get"]=="captcha")
{
	$letters = 'ABCDEFGKIJKLMNOPQRSTUVWXYZ';
	$caplen = 6;
	$width = 120; $height = 40;
	$font = 'fonts/UnrealEngine.ttf';
	$fontsize = 18;
	$fake_fontsize = 24;
	$pixels = 500;

	header('Content-type: image/png');

	$im = imagecreatefrompng('img/bg_capcha.png');

	$fake_captcha = '';
	for ($i = 0; $i < $caplen; $i++)
	{
		$fake_captcha .= $letters[ rand(0, strlen($letters)-1) ];
		$x = ($width - 20) / $caplen * $i + 10;
		$x = rand($x, $x+4);
		$y = $height - ( ($height - $fontsize) / 2 );
		$R=rand(0, 127);
		$G=rand(0, 127);
		$B=rand(0, 255);
		$curcolor = imagecolorallocatealpha( $im, $R, $G, $B, 100 );
		$angle = rand(-25, 25);
		imagettftext($im, $fake_fontsize+2, $angle, $x, $y, $curcolor, $font, $fake_captcha[$i]);
	}

	$captcha = '';
	for ($i = 0; $i < $caplen; $i++)
	{
		$captcha .= $letters[ rand(0, strlen($letters)-1) ];
		$x = ($width - 20) / $caplen * $i + 10;
		$x = rand($x, $x+4);
		$y = $height - ( ($height - $fontsize) / 2 );
		$R=rand(0, 127);
		$G=rand(0, 127);
		$B=rand(0, 255);
		$curcolor = imagecolorallocatealpha( $im, $R, $G, $B, 16 );
		$angle = rand(-25, 25);
		imagettftext($im, $fontsize, $angle, $x, $y, $curcolor, $font, $captcha[$i]);
	}

	$data = array();
	$_SESS['captcha'] = strtolower($captcha);
	$data['captcha'] = $_SESS['captcha'];
	mysql_update('sessions',$data,'`sess_id` = \''.mysql_safeval($_SESS['sess_id']).'\'');

	imagepng($im);
	imagedestroy($im);
	die;
}
?>