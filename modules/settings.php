<div class="ui-widget ui-widget-content ui-corner-all">
	<div style="padding-left: 10px; border-bottom: 1px solid #dddddd;"><h2><? print $_MOD['name']; ?></h2></div>
	<div id="table-scroll" style="display: block; height: 300px; overflow-y: scroll">
		<div id="tabs">
			<ul>
				<li><a href="#tabs-1"><:48:></a></li>
				<li><a href="#tabs-2"><:6:></a></li>
			</ul>
			<div id="tabs-1">
				<div style="width: 360px;">
				<form method="post" id="profile_form" enctype="multipart/form-data">
					<input type="hidden" name="user_action" value="upd_account">
					<p>
						<h3><:48:></h3>
					</p>
					<p class="validateTips" id="profile_validateTips">
						<:49:>
					</p>
					<p>
						<b><:5:> (<:50:>):</b><br>
						<input type="text" name="profile_username" id="profile_username" value="<? print htmlspecialchars($_USER['username']); ?>" class="text ui-widget-content ui-corner-all" style="width: 100%;" disabled="disable">
					</p>
					<p>
						<div style="width: 48%; float: left;">
							<b><:9:>:</b><br>
							<input type="text" name="profile_firstName" id="profile_firstName" value="<? print htmlspecialchars($_USER['firstName']); ?>" class="text ui-widget-content ui-corner-all" style="width: 100%;">
						</div>
						<div style="width: 50%; margin-left: 50%;">
							<b><:10:>:</b><br>
							<input type="text" name="profile_lastName" id="profile_lastName" value="<? print htmlspecialchars($_USER['lastName']); ?>" class="text ui-widget-content ui-corner-all" style="width: 100%;">
						</div>
					</p>
					<b><:11:>:</b>
					<div class="ui-widget-content" style="width: 360px;">
						<a href="index.php?mod_id=2#photoFull" id="open_PhotoFull" title="<:58:>" style="display:block">
							<div id="profile_photoPrev" style="width: 120px; height: 120px; display: block; margin: 10px auto; border-radius: 120px; background: url(<? if ($_USER['photo'] == '0'){print 'img/no_avatar.png';} else {print $_CONF["PHOTO_DIR"].$_USER['photo'];} ?>) 100% 100% no-repeat; background-size: cover"></div>
						</a>
						<input type="file" name="profile_photo" id="profile_photo" accept="image/x-png,image/gif,image/jpeg" class="text ui-widget-content ui-corner-all" style="width: 360px;">
					</div>
					<p>
						<b><:12:>:</b><br>
						<input type="text" name="profile_email" id="profile_email" value="<? print htmlspecialchars($_USER['email']); ?>" class="text ui-widget-content ui-corner-all" style="width: 100%;">
					</p>
					<p>
						<input type="submit" id="profile_submit" value="<:51:>">
					</p>
				</form>
			</div>
			</div>
			<div id="tabs-2">
				<form method="post">
					<input type="hidden" name="user_action" value="pass_change">
					<p>
						<h3><:6:></h3>
					</p>
					<p class="validateTips" id="pass_validateTips"><:52:></p>
					<p>
						<:53:>:<br>
						<input type="password" name="pass_old" id="pass_old">
					</p>
					<p>
						<:54:>:<br>
						<input type="password" name="pass_new" id="pass_new"><br>
						<:13:>:<br>
						<input type="password" name="pass_chk" id="pass_chk">
					</p>
					<p>
						<input type="submit" id="pass_submit" value="<:51:>">
					</p>
				</form>
			</div>
		</div>
	</div>
</div>

<div id="photo_dialog" title="<:11:>">
	<div id="profile_photoFull" style="width: 600px; height: 600px; display: block; margin: auto auto; background: url(<? if ($_USER['photo'] == '0'){print 'img/no_avatar.png';} else {print $_CONF["PHOTO_DIR"].$_USER['photo'];} ?>) 100% 100% no-repeat; background-size: cover"></div>
</div>

<script>
	$(function(){
		var profile_username = $( "#profile_username" ),
			profile_firstName = $( "#profile_firstName" ),
			profile_lastName = $( "#profile_lastName" ),
			profile_email = $( "#profile_email" ),
			profile_allFields = $( [] ).add( profile_username ).add( profile_firstName ).add( profile_lastName ).add( profile_email ),
			profile_tips = $( "#profile_validateTips" );

		var pass_old = $( "#pass_old" ),
			pass_new = $( "#pass_new" ),
			pass_chk = $( "#pass_chk" ),
			pass_allFields = $( [] ).add( pass_old ).add( pass_new ).add( pass_chk ),
			pass_tips = $( "#pass_validateTips" );

		$( "#table-scroll" ).height( $("#mod_content").height()-60 );
		$( "#tabs" ).tabs();
		$( "#photo_dialog" ).dialog({
			autoOpen: false,
			width: <? print ($_CONF['PHOTO_RESIZE'] + 40); ?>,
			modal: true,
			close: function() {
				//alert("close");
			}
		});
		<?
		if (isset($MSG))
		{
			print "updateTips( '".$MSG."' );";
		}
		?>

		$("#profile_photo").change(function() {
			updatePhotoPrev(profile_tips, this, <? print $_CONF['PHOTO_SIZE']; ?>, $('#profile_photoPrev'), '<:31:>', '<:30:>');
		});

		$("#open_PhotoFull").click(function(){
			$('#photo_dialog').dialog("open");
			return false;
		})

		$( "#pass_submit" ).click(function(){
			var bValid = true;
				pass_allFields.removeClass( "ui-state-error" );
				bValid = bValid && checkRegexp( pass_tips, pass_old, /^[a-zA-Z0-9_\-\!\@\#\$\%\^\&\*]{6,32}$/, '<:55:>');
				bValid = bValid && checkRegexp( pass_tips, pass_new, /^[a-zA-Z0-9_\-\!\@\#\$\%\^\&\*]{6,32}$/, '<:56:>');
				bValid = bValid && strCompare( pass_tips, pass_new, "<:54:>", pass_chk, "<:13:>", "<:57:>");

			return bValid;
		});

		$( "#profile_submit" ).click(function(){
			var bValid = true;
			profile_allFields.removeClass( "ui-state-error" );
			bValid = bValid && checkRegexp( profile_tips, profile_firstName, /^[a-zA-Zа-яА-Я0-9_\-]{2,32}$/, '<:17:>');
			bValid = bValid && checkRegexp( profile_tips, profile_lastName, /^[a-zA-Zа-яА-Я0-9_\-]{2,32}$/, '<:18:>');
			bValid = bValid && checkRegexp( profile_tips, profile_email, /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/, '<:19:>');

			return bValid;
		});

		<?
		if ($_POST['user_action']=='upd_account')
		{
			?>
			updateTips(profile_tips, "<? print $_RESULT['text']; ?>");
			<?
		}
		if ($_POST['user_action']=='pass_change')
		{
			?>
			updateTips(pass_tips, "<? print $_RESULT['text']; ?>");
			$( "#tabs" ).tabs('select', 1);
			<?
		}
		?>
	});
</script>