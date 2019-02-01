$(function()
{

	if($(window).width() < 600)
	{
		$('#branddt2').toggleClass('hidediv');
		$('#login-logo').attr('src', 'brand-logo.png');
	}
	else
	{
		$('#branddt').toggleClass('hidediv');
	}

	$('#altercrs').click(function(){
		if(!$('#profpage').is(":visible"))
		{
			showProf();
		}
		else
		{
			hideProf();
		}
	});

	$('#campaign-drop').mouseenter(function(){
		$('#campaign-extension').fadeIn(100);
	});
	$('#campaign-drop').mouseleave(function(){
		$('#campaign-extension').fadeOut(100);
	});

	$('#profile-drop').mouseenter(function(){
		$('#profile-extension').fadeIn(100);
	});
	$('#profile-drop').mouseleave(function(){
		$('#profile-extension').fadeOut(100);
	});

	$('.cmpnav').click(function(){
		if(!$('.mktnav').is(":visible"))
		{
			showCamp();
		}
		else
		{
			hideCamp();
		}
	});

	$('#whitey').click(function(){
		hideCamp();
	});

	$('.cmpbrf').click(function(){
		if(!$('#pullcmp').is(":visible"))
		{
			showBrief();
		}
		else
		{
			hideBrief();
		}

	});

	$('.pcmpbrf').click(function(){
		if(!$('#pullcmp').is(":visible"))
		{
			showBrief();
		}
		else
		{
			hideBrief();
		}

	});
	
	$('.brandclick').click(function(){
		if(!$('.branddet').is(":visible"))
		{
			showBrand();
		}
		else
		{
			hideBrand();
		}
	});

	$('.brandpnl').click(function(){
		if(!$('.branddet').is(":visible"))
		{
			showBrand();
		}
		else
		{
			hideBrand();
		}
	});	

	$('#counter-btn').click(showNeg);
	$('#close-neg').click(hideNeg);

	$('#contact-brand').click(showApply);
	$('#close-contact').click(hideApply);

	$('.reqclick').click(function(){
		if(!$('.reqpnl').is(":visible"))
		{
			showReq();
		}
		else
		{
			hideReq();
		}
	})

	$('.invclick').click(function(){
		if(!$('.invpnl').is(":visible"))
		{
			showInv();
		}
		else
		{
			hideInv();
		}
	})

	$('.admin-neg').click(function(){
		var id = this.id;
		$(".message-panel").hide();
		$('#admin-btns-pending-' + id).hide();
		$('#neg-panel-admin-' + id).css("display", "inline-block");
	});

	$('.crsneg-pending-admin').click(function(){
		var id = this.id;
		$('#neg-panel-admin-' + id).hide();
		$('#admin-btns-pending-' + id).show();
	});

	$('.reject-draft').click(function(){
		var id = this.id;
		$('#admin-btns-approved-' + id).hide();
		$('#reject-draft-panel-' + id).css('display', 'inline-block');
	});

	$('.crsneg-approved-admin').click(function(){
		console.log('#reject-draft-panel-' + id);
		var id = this.id;
		$('#reject-draft-panel-' + id).hide();
		$('#admin-btns-approved-' + id).show();
	});

	$(".fa-envelope-o").mouseover(function(){
		var id = this.id;
		$("#tooltip-" + id).show();
	}).mouseleave(function(){
		var id = this.id;
		$("#tooltip-" + id).hide();
	});

	$(".show-message").click(function(){
		var id = this.id;
		if(!$("#msg-" + id).is(":visible"))
		{
			console.log(".message-panel #" + id);
			$("#msg-" + id).show();
			$("#" + id).css("color", "#FFAB00");
		}
		else
		{
			$("#msg-" + id).hide();
			$("#" + id).css("color", "#555");
		}
	});
});

function showProf()
{
	$('#profpage').fadeIn(150);
	$('.mob-nav-image').hide();
	$('.mob-nav-crs').show();
}
function hideProf()
{
	console.log('DP clicked');
	$('.mob-nav-crs').hide();
	$('.mob-nav-image').show();
	$('#profpage').fadeOut(150);
}
function showBrief()
{
	$('#pullcmp').slideDown('fast');
	$('#briefdown').removeClass('fa-chevron-down');
	$('#briefdown').addClass('fa-chevron-up');
}
function hideBrief()
{
	$('#pullcmp').slideUp('fast');
	$('#briefdown').removeClass('fa-chevron-up');
	$('#briefdown').addClass('fa-chevron-down');
}
function showBrand()
{
	console.log('Brand clicked')
	$('.branddet').slideDown('fast');
	$('.branddown').removeClass('fa-chevron-down');
	$('.branddown').addClass('fa-chevron-up');
}
function hideBrand()
{
	console.log('Brand clicked');
	$('.branddet').slideUp('fast');
	$('.branddown').removeClass('fa-chevron-up');
	$('.branddown').addClass('fa-chevron-down');
}
function showCamp()
{
	$('#cmpnav').css('border', '1px solid #ffbc00');
	$('.campdown').removeClass('fa-chevron-up').addClass('fa-chevron-down').css('color', '#ffbc00');
	$('#whitey').fadeIn(150);
	$('.mktnav').fadeIn(150);
}
function hideCamp()
{
	$('#cmpnav').css('border', 'none');
	$('#whitey').fadeOut(150);
	$('.mktnav').fadeOut(150);
	$('.campdown').removeClass('fa-chevron-down').addClass('fa-chevron-up').css('color', '#cecece');
}
function showNeg()
{
	console.log('Neg Opened');
	$('#respond-buttons').addClass('hidediv');
	$('#negotiate-form').fadeIn(150);
}
function hideNeg()
{
	console.log('Close Clicked');
	$('#negotiate-form').fadeOut(150, function(){
		$('#respond-buttons').removeClass('hidediv');
	});
}
function showApply()
{
	console.log('hello');
	$('.apply-panel').addClass('hidediv');
	$('#contact-panel').fadeIn(150);
}
function hideApply()
{
	$('#contact-panel').fadeOut(150, function(){
		$('.apply-panel').removeClass('hidediv');
	});
}
function showReq()
{
	$('.reqpnl').slideDown('fast');
	$('.reqdown').removeClass('fa-chevron-down');
	$('.reqdown').addClass('fa-chevron-up');
}
function hideReq()
{
	$('.reqpnl').slideUp('fast');
	$('.reqdown').removeClass('fa-chevron-up');
	$('.reqdown').addClass('fa-chevron-down');
}
function showInv()
{
	$('.invpnl').slideDown('fast');
	$('.invdown').removeClass('fa-chevron-down');
	$('.invdown').addClass('fa-chevron-up');
}
function hideInv()
{
	$('.invpnl').slideUp('fast');
	$('.invdown').removeClass('fa-chevron-up');
	$('.invdown').addClass('fa-chevron-down');
}
function pullInstaDp(igId, index)
{
	$.post
	(	
		"admin_controller.php",
		{
			function: 'getInfluencerIgPic',
			id: igId,
		},
		function(responseText){
			if(responseText != 0)
			{	
				$('#img-'+index).attr("src", responseText);
			}	
		}
	);
}

function adminPanelControl()
{
	if($( ".influencer-type-select" ).val() == "pending")
	{
		$(".approved-panel").hide();
		$(".complete-panel").hide();
		$(".pending-panel").show();
	}
	else if($( ".influencer-type-select" ).val() == "approved")
	{
		$(".pending-panel").hide();
		$(".complete-panel").hide();
		$(".approved-panel").show();
	}
	else if($( ".influencer-type-select" ).val() == "complete")
	{
		$(".pending-panel").hide();
		$(".approved-panel").hide();
		$(".complete-panel").show();
	}
}
