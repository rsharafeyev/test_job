<?
?>
<div style="position: absolute; top: 20px; left: 20px;">
	<p>
		<a href="#" id="reg_link"><:2:></a></h3>
		<a href="#" id="login_link"><:1:></a></h3>
	</p>
</div>

<div id="login_dialog" title="<:23:>">
	<p align="right">
		<:41:>:
		<select name="lang_id" id="lang_id" class="text ui-widget-content ui-corner-all">
			<? html_print_select_options($_LANG['list'], $_LANG['selected']); ?>
		</select>
	</p>
	<hr>
	<p>
		<h3><:3:> "<? print $_CONF['SITE_NAME']; ?>"</h3>
	</p>
	<p class="login_validateTips">
		<:4:>
	</p>
	<form method="post" id="login_form">
		<input type="hidden" name="user_action" value="login">
		<p>
			<b><:5:>:</b><br>
			<input type="text" name="login_username" id="login_username" value="<? print htmlspecialchars($_POST['username']); ?>" class="text ui-widget-content ui-corner-all" style="width: 100%;">
		</p>
		<p>
			<b><:6:>:</b><br>
			<input type="password" name="login_password" id="login_password" value="" class="text ui-widget-content ui-corner-all" style="width: 100%" />
		</p>
	</form>
</div>

<div id="reg_dialog" title="<:24:>">
	<p>
		<h3><:7:> "<? print $_CONF['SITE_NAME']; ?>"</h3>
	</p>
	<p class="reg_validateTips">
		<:8:>
	</p>
	<form method="post" id="reg_form" enctype="multipart/form-data">
		<input type="hidden" name="user_action" value="reg_account">
		<p>
			<b><:5:>:</b><br>
			<input type="text" name="reg_username" id="reg_username" value="<? print htmlspecialchars($_POST['reg_username']); ?>" class="text ui-widget-content ui-corner-all" style="width: 100%;">
		</p>
		<p>
			<div style="width: 48%; float: left;">
				<b><:9:>:</b><br>
				<input type="text" name="reg_firstName" id="reg_firstName" value="<? print htmlspecialchars($_POST['reg_firstName']); ?>" class="text ui-widget-content ui-corner-all" style="width: 100%;">
			</div>
			<div style="width: 50%; margin-left: 50%;">
				<b><:10:>:</b><br>
				<input type="text" name="reg_lastName" id="reg_lastName" value="<? print htmlspecialchars($_POST['reg_lastName']); ?>" class="text ui-widget-content ui-corner-all" style="width: 100%;">
			</div>
		</p>
		<b><:11:>:</b>
		<div class="ui-widget-content" style="width: 342px;">
			<div id="reg_photoPrev" style="width: 120px; height: 120px; display: block; margin: 10px auto; border-radius: 120px; background: url(img/no_avatar.png) 100% 100% no-repeat; background-size: cover"></div>
			<input type="file" name="reg_photo" id="reg_photo" accept="image/x-png,image/gif,image/jpeg" class="text ui-widget-content ui-corner-all" style="width: 336px;">
		</div>
		<p>
			<b><:12:>:</b><br>
			<input type="text" name="reg_email" id="reg_email" value="<? print htmlspecialchars($_POST['reg_email']); ?>" class="text ui-widget-content ui-corner-all" style="width: 100%;">
		</p>
		<p>
			<div style="width: 48%; float: left;">
				<b><:6:>:</b><br>
				<input type="password" name="reg_password" id="reg_password" value="" class="text ui-widget-content ui-corner-all" style="width: 100%" />
			</div>
			<div style="width: 50%; margin-left: 50%;">
				<b><:13:>:</b><br>
				<input type="password" name="reg_passwordConfirm" id="reg_passwordConfirm" value="" class="text ui-widget-content ui-corner-all" style="width: 100%" />
			</div>
		</p>
		<p>
			<div style="width: 48%; float: left;">
				<b><:14:>:</b><br>
				<input type="captcha" name="reg_captcha" id="reg_captcha" value="" class="text ui-widget-content ui-corner-all" style="width: 100%" />
			</div>
			<div style="width: 50%; margin-left: 50%; text-align: center;">
				<img src="index.php?get=captcha&rand=<? print rand(); ?>" onclick="$(this).attr('src', 'index.php?get=captcha&rand='+Math.random());" title="<:15:>" style="cursor: pointer;">
			</div>
		</p>
	</form>
</div>

<script>
	$(function() {
		var login_username = $( "#login_username" ),
			login_firstName = $( "#login_password" ),
			login_allFields = $( [] ).add( login_username ).add( login_password ),
			login_tips = $( ".login_validateTips" );

		var reg_username = $( "#reg_username" ),
			reg_firstName = $( "#reg_firstName" ),
			reg_lastName = $( "#reg_lastName" ),
			reg_email = $( "#reg_email" ),
			reg_password = $( "#reg_password" ),
			reg_passwordConfirm = $( "#reg_passwordConfirm" ),
			reg_captcha = $( "#reg_captcha" ),
			reg_allFields = $( [] ).add( reg_username ).add( reg_firstName ).add( reg_lastName ).add( reg_email ).add( reg_password ).add( reg_passwordConfirm ).add( reg_captcha ),
			reg_tips = $( ".reg_validateTips" );

		$("#reg_photo").change(function() {
			updatePhotoPrev(reg_tips, this, <? print $_CONF['PHOTO_SIZE'] ; ?>, $('#reg_photoPrev'), '<:31:>', '<:30:>');
		});

		$("#login_dialog").dialog({
			autoOpen: false,
			width: 360,
			modal: true,
			buttons: {
				"<:2:>": function() {
					$('#login_dialog').dialog("close");
					$('#reg_dialog').dialog("open");
				},
				"<:1:>": function() {
					$("#login_form").submit();
				}
			},
			close: function() {
				//alert("close");
			}
		});

		$("#reg_dialog").dialog({
			autoOpen: false,
			width: 360,
			modal: true,
			buttons: {
				"<:2:>": function() {
					var bValid = true;
						reg_allFields.removeClass( "ui-state-error" );
						bValid = bValid && checkRegexp( reg_tips, reg_username, /^[a-zA-Z0-9_\-]{6,32}$/, '<:16:>');
						bValid = bValid && checkRegexp( reg_tips, reg_firstName, /^[a-zA-Zа-яА-Я0-9_\-]{2,32}$/, '<:17:>');
						bValid = bValid && checkRegexp( reg_tips, reg_lastName, /^[a-zA-Zа-яА-Я0-9_\-]{2,32}$/, '<:18:>');
						bValid = bValid && checkRegexp( reg_tips, reg_email, /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/, '<:19:>');
						bValid = bValid && checkRegexp( reg_tips, reg_password, /^[a-zA-Z0-9_\-\!\@\#\$\%\^\&\*]{6,32}$/, '<:20:>');
						bValid = bValid && strCompare( reg_tips, reg_password, "<:6:>", reg_passwordConfirm, "<:13:>", "<:57:>");
						bValid = bValid && checkRegexp( reg_tips, reg_captcha, /^[a-zA-Z0-9]{6}$/, '<:21:>');

					if (bValid) { $("#reg_form").submit(); }
				},
				"<:22:>": function() {
					$('#reg_dialog').dialog("close");
					$('#login_dialog').dialog("open");
				}
			},
			close: function() {
				//alert("close");
			}
		});


		$("#login_link").button().bind("click", function() {
			$('#login_dialog').dialog("open");
			return false;
		});

		$("#reg_link").button().bind("click", function() {
			$('#reg_dialog').dialog("open");
			return false;
		});

		$( "#login_form" ).keypress(function( event ) {
			if ( event.which == 13 ) {
				$("#login_form").submit();
			}
		});

		$( "#reg_form" ).keypress(function( event ) {
			if ( event.which == 13 ) {
				$("#reg_form").submit();
			}
		});

		$("#lang_id").change(function() {
			<?
				$url_param = array_merge($_GET, array('lang_id' => ''))
			?>
			location.href = 'index.php?<? print url_make($url_param); ?>' + $(this).val();
		});

		<?
		if ($_POST['user_action'] == 'reg_account')
		{
			?>
			$('#reg_dialog').dialog("open");
			<?
		} else
		{
			?>
			$('#login_dialog').dialog("open");
			<?
		}
		
		if ($_POST['user_action']=='login' && !$_RESULT['done'])
		{
			print 'updateTips( login_tips, "'.$_RESULT['text'].'");';
		}

		if ($_POST['user_action']=='reg_account')
		{
			if ($_RESULT['done'])
			{
			?>
			$('#login_username').val( $('#reg_username').val() );
			$('#login_password').val( '' );
			$('#reg_dialog').dialog("close");
			$('#login_dialog').dialog("open");
			updateTips(login_tips, "<? print $_RESULT['text']; ?>");
			<?
			} else
			{
			print 'updateTips( reg_tips, "'.$_RESULT['text'].'");';				
			}
		}
		?>
	});
</script>