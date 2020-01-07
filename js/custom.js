function OnLoadPage()
{
	$( ".btn_add" ).button({
		icons: {
			primary: "ui-icon-plus"
		}
	});
	$( ".btn_edt" ).button({
		icons: {
			primary: "ui-icon-pencil"
		}
	});
	$( ".btn_del" ).button({
		icons: {
			primary: "ui-icon-trash"
		}
	});
	$( ".btn_print" ).button({
		icons: {
			primary: "ui-icon-print"
		}
	});
	$( ".btn_svc" ).button({
		icons: {
			primary: "ui-icon-suitcase"
		}
	});
	$( ".btn_transfer" ).button({
		icons: {
			primary: "ui-icon-transferthick-e-w"
		}
	});
	$( ".btn_ping" ).button({
		icons: {
			primary: "ui-icon-gear"
		}
	});
	$( ".btn_clear" ).button({
		icons: {
			primary: "ui-icon-circle-close"
		}
	});
}

function updateTips( obj, msg ) {
	obj
		.html( '<span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span> ' + msg )
		.addClass( "ui-state-highlight" );
	setTimeout(function() {
		obj.removeClass( "ui-state-highlight", 1500 );
	}, 5000 );
}

function strCompare( tips, field, name, field2, name2, msg ) {
	if ( field.val() != field2.val()) {
		field.addClass( "ui-state-error" );
		field2.addClass( "ui-state-error" );
		msg = msg.replace('${name}', name);
		msg = msg.replace('${name2}', name2);
		updateTips( tips, msg );
		return false;
	} else {
		return true;	
	}
}

function checkRegexp( tips, field, regexp, msg ) {
	if ( !( regexp.test( field.val() ) ) )
	{
		field.addClass( "ui-state-error" );
		updateTips( tips, msg );
		return false;
	} else 
	{
		return true;
	}
}

function updatePhotoPrev(tips, field, size, prev, msg1, msg2) {
	if (field.files && field.files[0])
	{
		var photoPath = field.value;
		var photoExt = photoPath.substring(photoPath.lastIndexOf('.') + 1).toLowerCase();
		var photoSize = field.files[0].size;
		if (!(photoExt == "gif" || photoExt == "png" || photoExt == "jpeg" || photoExt == "jpg"))
		{
			updateTips( tips, msg1 );
			return;
		}
		if(photoSize > size) //max photo size
		{
			updateTips( tips, msg2 + ' ' + Math.round(size / (1024*1024)) + ' MB' );
			return;
		}

		var reader = new FileReader();
		reader.onload = function(e) {
			$( prev ).css('background', 'url('+e.target.result+') 100% 100% no-repeat');
			$( prev ).css('background-size', 'cover');
		}
		reader.readAsDataURL(field.files[0]);
	}
}
