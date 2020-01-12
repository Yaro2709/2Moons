<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html lang="de">
<head>
<link rel="stylesheet" type="text/css" href="./styles/css/ingame.css?v={$REV}">
<link rel="stylesheet" type="text/css" href="./styles/theme/gow/formate.css?v={$REV}">
<link rel="stylesheet" type="text/css" href="./styles/css/admin.css?v={$REV}">
<link rel="stylesheet" type="text/css" href="./styles/css/jquery.css?v={$REV}">
<link rel="icon" href="./favicon.ico">
<title>{$title}</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta http-equiv="content-script-type" content="text/javascript">
<meta http-equiv="content-style-type" content="text/css">
<link rel="shortcut icon" href="./favicon.ico" type="image/x-icon">
{if $goto}
<meta http-equiv="refresh" content="{$gotoinsec};URL={$goto}">
{/if}
<script type="text/javascript" src="./scripts/base/jquery.js?v={$REV}"></script>
<script type="text/javascript" src="./scripts/base/jquery.ui.js?v={$REV}"></script>
<script type="text/javascript" src="./scripts/game/base.js?v={$REV}"></script>
<script type="text/javascript" src="./scripts/base/tooltip.js?v={$REV}"></script>
<script type="text/javascript" src="./scripts/base/filterlist.js?v={$REV}"></script>
{foreach item=scriptname from=$scripts}
<script type="text/javascript" src="./scripts/game/{$scriptname}.js?v={$REV}"></script>
{/foreach}
<script type="text/javascript">
var xsize 	= screen.width;
var ysize 	= screen.height;
var breite	= 720;
var hoehe	= 300;
var xpos	= (xsize-breite) / 2;
var ypos	= (ysize-hoehe) / 2;
var head_info	= "{$fcm_info}";

function useropen(target_url) {
	var userlist = window.open("UserListPage.php?action=edit&id="+ target_url, "info", "scrollbars=yes,statusbar=no,toolbar=no,location=no,directories=no,resizable=no,menubar=no,width=850,height=600,screenX="+((xsize-600)/2)+",screenY="+((ysize-850)/2)+",top="+((ysize-600)/2)+",left="+((xsize-850)/2));
	userlist.focus();
}

function openEdit(id, type) {
	var editlist = window.open("?page=qeditor&edit="+type+"&id="+id, "edit", "scrollbars=yes,statusbar=no,toolbar=no,location=no,directories=no,resizable=no,menubar=no,width=850,height=600,screenX="+((xsize-600)/2)+",screenY="+((ysize-850)/2)+",top="+((ysize-600)/2)+",left="+((xsize-850)/2));
	editlist.focus();
}
</script> 
</head>
<body>
<div id="tooltip" class="tip"></div>