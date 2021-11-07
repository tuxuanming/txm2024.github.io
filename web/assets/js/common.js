function htmlEncode(str) {
	var s = "";
	if (str.length == 0) return "";
	s = str.replace(/&/g, "&amp;");
	s = s.replace(/ /g, "&nbsp;");
	s = s.replace(/</g, "&lt;");
	s = s.replace(/>/g, "&gt;");  
	s = s.replace(/\'/g, "&#39;");
	s = s.replace(/\"/g, "&quot;");
	return s;
}
function encode_space(str) {
	var s="";
	if(str.length == 0) return "";
	s=str.replace(/\r?\n/g, "<br>");
	s=s.replace(/ /g, "&nbsp;");
	s=s.replace(/\t/g, "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");
	return s;
}
function LoadCSS(url) {
   var head = document.getElementsByTagName('head')[0];
   var link = document.createElement('link');
   link.type = 'text/css';
   link.rel = 'stylesheet';
   link.href = url;
   head.appendChild(link);
   return link;
 }
function InsertString(tb, str){
    //var tb = document.getElementById(tbid);
    tb.focus();
    if (document.all){
        var r = document.selection.createRange();
        document.selection.empty();
        r.text = str;
        r.collapse();
        r.select();
    }
    else{
        var newstart = tb.selectionStart+str.length;
        tb.value=tb.value.substr(0,tb.selectionStart)+str+tb.value.substring(tb.selectionEnd);
        tb.selectionStart = newstart;
        tb.selectionEnd = newstart;
    }
}
function GetSelection(tb){

    var sel = '';
    if (document.all){
        var r = document.selection.createRange();
        document.selection.empty();
        sel = r.text;
    }
    else{
    	//var tb = document.getElementById(tbid);
    	// tb.focus();
        var start = tb.selectionStart;
        var end = tb.selectionEnd;
        sel = tb.value.substring(start, end);
    }
    return sel;
}
function GetUrlParms()    
{
    var args=new Object();
    var query=location.search.substring(1);
    var pairs=query.split("&");
    for(var i=0;i<pairs.length;i++)
    {
        var pos=pairs[i].indexOf('=');
        if(pos==-1) 
        	continue;
        var argname=pairs[i].substring(0,pos);
        var value=pairs[i].substring(pos+1);
        args[argname]=decodeURIComponent(value);
    }
    return args;
}
function BuildUrlParms(obj) {
	var arr = [];
	for (var name in obj){
		arr.push(name+'='+encodeURIComponent(obj[name]));
	}
	return '?'+arr.join('&');
}
shortcuts={
	"65": function(){
			try{
				$('ul.pager>li>a.pager-pre-link').get(0).click();
			}catch(exp){}
		} , //alt+A
	"68": function(){
			try{
				$('ul.pager>li>a.pager-next-link').get(0).click();
			}catch(exp){}
		} , //alt+D
	"66": function(){location.href=$('#nav_bbs').attr('href');} , //alt+B
	"80": function(){location.href=$('#nav_prob').attr('href');} , //alt+P
	"82": function(){location.href=$('#nav_record').attr('href');} , //alt+R
	"73": function(){$("#search_input").focus();} , //alt+I
	"76": function(){$("#login_btn").click();} , //alt+L
	"77": function(){
			var obj=$('#nav_mail');
			if(obj.length) //if logged in
				location.href=obj.attr('href');
		}   //Alt+M

};
shortcuts[49]=shortcuts[66]; //Alt+1
shortcuts[50]=function(){location.href=$('#nav_set').attr('href');} //Alt+2
shortcuts[51]=shortcuts[80]; //Alt+3
shortcuts[52]=shortcuts[82]; //Alt+4
shortcuts[53]=function(){location.href=$('#nav_rank').attr('href');} //Alt+5
shortcuts[54]=function(){location.href=$('#nav_about').attr('href');} //Alt+6
shortcuts[55]=shortcuts[73]; //Alt+7

function hotkey_hint_show () {
	$('.shortcut-hint').addClass('shortcut-hint-active');
}
function hotkey_hint_dismiss (E) {
	if(E.keyCode==18){ //alt key
		$('.shortcut-hint').removeClass('shortcut-hint-active');
	}
}
function reg_hotkey (key, fun) {
	shortcuts[key] = fun; }
$(document).ready(function(){
	var $nav=$('#navbar_top'),navFixed=false,$win=$(window),$container=$('body>.container-fluid'),$notifier=$('#notifier');
	function processScroll () {
		var now = $win.scrollTop(),
			navTop = $('header').height(),
			$navbar_pseude = $('#navbar_pseude');
		if(now>navTop && !navFixed){
			navFixed = true;
			$navbar_pseude.removeClass('hide');
			$nav.addClass('navbar-fixed-top');
		}else if(now<=navTop && navFixed){
			navFixed = false;
			//use class instead of style, so it can be overridden by @media{...}
			$navbar_pseude.addClass('hide');
			$nav.removeClass('navbar-fixed-top');
		}
	}
	if($nav.length && $('header').length){
		processScroll();
		$win.on('scroll', processScroll);
	}else if($nav.length){
		navFixed = true;
	}
	$('#LoginModal').on('shown',function(){
		$('#uid').focus();
	});
	$('#logoff_btn').click(function(){$.ajax({url:"logoff.php",dataType:"html",success:function(){location.reload();}});});
	var $search_input=$('#search_input');
	if($search_input.length)
	{
		$search_input.typeahead({
			source:function(query, update){
				var typeahead = this;
				$.getJSON("ajax_search.php?q="+encodeURIComponent(query),function (r){
					  update(r.arr);
					  typeahead.$menu.find('.active').removeClass('active');
					}
				);
			}
		});
		$search_input.keydown(function(E){
			if(E.keyCode == 13){
				var selected = $search_input.parent().find('.typeahead:visible>.active');
				if(!selected.length)
					$('#search_form').submit();
			}
		})
	}
	$('#form_login').submit(function(E){
		var b=false;
		if($('#uid').val()==''){
			$('#uid_ctl').addClass('error');
			b=true;
		}else{
			$('#uid_ctl').removeClass('error');
		}
		if($('#pwd').val()==''){
			b=true;
			$('#pwd_ctl').addClass('error');
		}else
			$('#pwd_ctl').removeClass('error');
		if(b){
			return false;
		}
	});
	$('#search_form').submit(function(){
		if($.trim($('#search_input').val()).length==0)
			return false;
		return true;
	});
	function checkMail()
	{
		$.get("ajax_mailfunc.php?op=check",function(data){
			if(isNaN(data)||data=='0')
				return;
			$notifier.html('&nbsp;('+data+')');
			var $alert=$('<div class="alert alert-success center alert-popup">You have unread mails.</div>').appendTo('body');
			setTimeout(function(){$alert.fadeOut(400);},1000);
		});
	}
	if($notifier.length) {
		setTimeout(checkMail,3000);
	}
}).keydown(function(E){
	if(window.hidehotkey)
		return;
	if(E.altKey && !E.metaKey){
		var key=E.keyCode;
		if(key>=97 && key<=122)
			key-=32;
		else if(key==18){ //alt key
			hotkey_hint_show(E);
			return;
		}
		if(shortcuts.hasOwnProperty(key))
			(shortcuts[key])(E);
		return false;
	}
}).keyup(hotkey_hint_dismiss);
$('#search_input').keyup(hotkey_hint_dismiss);
