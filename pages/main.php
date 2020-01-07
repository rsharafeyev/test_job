<table width="100%" height="100%">
	<tr class="page-header ui-widget-content">
		<td>
			<h2>&nbsp;<? print $_CONF['SITE_NAME']; ?></h2>
		</td>
	</tr>
	<tr class="page-body">
		<td>
			<table width="100%" height="100%">
				<tr>
					<td class="panel-left" valign="top"><? require_once('kernel/menu.php'); ?></td>
					<td style="width: 12px;">&nbsp;</td>
					<td valign="top" id="mod_content"><? require_once('kernel/modules.php'); ?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr class="page-footer ui-widget-content">
		<td align="center">
			<b><? print $_CONF['SITE_NAME']; ?></b> [ <:42:>: <a href="#" id="about" style="color: #1c94c4;"><:43:></a> ]
		</td>
	</tr>
</table>

<div id="dialog-about" title="<:42:>" style="display: none;">
	<p><h3><:43:></h3></p>
	<p>
		<b>email:</b><br>
		r.sharafeyev@gmail.com
	</p>
	<p>
		<b><:44:></b><br>
		+7 7o5 178 o8 o8
	</p>
	<p class="ui-state-highlight">
		<:45:>
	</p>
</div>
<script>
	$(function() {
		$( "#dialog-about" ).dialog({
			autoOpen: false,
			height: 320,
			width: 350,
			modal: true,
			buttons: {
				"<:46:>": function() {
					$( this ).dialog( "close" );
				}
			}
		});

		$( "#about" ).click(function() {
			$( "#dialog-about" ).dialog( "open" );
			return false;
		});
	});
</script>