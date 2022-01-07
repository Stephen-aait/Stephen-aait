/*************************************************************** 
 * Application Name: DM FileManager									
 * Application URI: http://www.dutchmonkey.com/
 * Description: AJAX Web Based File Management System.
 * Author: Frank D. Strack
 * Author Email: development@dutchmonkey.com
 * Author URI: http://www.dutchmokney.com
 ***************************************************************/

function Browser()
{
	this.ns40 			= ((navigator.appName == "Netscape") && (parseInt(navigator.appVersion) == 4));
	this.ns50 			= ((navigator.appName == "Netscape") && (parseInt(navigator.appVersion) >= 5));
	this.ie50 			= (navigator.appVersion.indexOf("MSIE 5") != -1);
	this.ie60 			= (navigator.appVersion.indexOf("MSIE 6") != -1);
	this.ie70 			= (navigator.appVersion.indexOf("MSIE 7") != -1);
	this.Exploiter		= ((navigator.appName == "Microsoft Internet Explorer") && (parseInt(navigator.appVersion) >= 4));
	this.platform 		= navigator.platform;
	this.UserAgent  	= navigator.userAgent;
	
	this.browser 		= (this.ns40 || this.ns50 || this.ie50 || this.Exploiter);
	
	this.Mozilla 		= (navigator.appName == "Netscape" ? true : false);
	this.netscape 		= this.ns40 || this.ns50;
	this.explorer 		= this.ie05 || this.Exploiter;
	this.Safari 		= (navigator.appVersion.indexOf("Safari") != -1);
	this.Chrome 		= (navigator.appVersion.indexOf("Chrome") != -1);
	this.Opera 			= (navigator.appName.indexOf("Opera") != -1);
	
	this.name 			= (navigator.appName == "Netscape" ? "Mozilla" : "Microsoft Internet Explorer");
	this.version 		= navigator.appVersion.indexOf("MSIE 7") != -1 ? 7 : parseInt(navigator.appVersion);

	this.macie5 		= ((this.platform == "MacPPC") && (this.version == 5) && (this.explorer));
	this.MacPPC 		= ((this.userAgent == "PPC Mac") && (this.version == 5) && (this.explorer));
	
	this.dhtml 			= (this.ns40 || this.macie5) ? false : (this.ns50 || this.ie50 || this.Exploiter || this.Safari);
	this.rollovers 		= this.browser || this.version >= 3;
	
	this.Width 			= function() { return ((navigator.appName == "Microsoft Internet Explorer" ? document.body.offsetWidth : window.innerWidth));}
	this.Height 		= function() { return ((navigator.appName == "Microsoft Internet Explorer" ? document.body.offsetHeight : window.innerHeight));}
	
	this.toString 		= _toString;
	
	this.setCookie 		= _WriteCookie;
	this.getCookie 		= _ReadCookie;
	
	this.Encode 		= _Encode;
	this.Decode 		= _Decode;
	
	this.GetPosition 	= _GetAbsolutePosition
	
	this.DOM			= _DOM;
	
	this.getCommonName 	= _GetCommonName;
	
	this.Report			= _Report;
	
	this.Clean			= _Clean;
	
	if(navigator.userAgent.indexOf("Safari") != -1)	
	{
		if(navigator.userAgent.indexOf("Version/") != -1)	this.version = 3;	// Safari 3 contains this string
		else								 				this.version = 2;	// Safari 2 does not.
	}
	
	/*var parts = this.UserAgent.split("/");
	
	for(var i = 0; i < parts.length; i++)
	{
		if(parts[i].indexOf("Safari") != -1)
		{
			info = parts[i].split(" ");
			
			this.version = parseInt(info[0]);
		}
	}*/
	
	return true;
}

function _Decode(html)
{
	html = html.replace(/&amp;/g, "&");
	html = html.replace(/&quot;/g, "\"");
	html = html.replace(/&apos;/g, "\'");
	html = html.replace(/&lt;/g, "<");
	html = html.replace(/&gt;/g, ">");
	
	return html;
}

function _Encode(html)
{
	html = html.replace(/&/g, "&amp;");
	html = html.replace(/\"/g, "&quot;");
	html = html.replace(/\'/g, "&apos;");
	html = html.replace(/</g, "&lt;");
	html = html.replace(/>/g, "&gt;");
	
	return html;
}

function _Clean(s)
{
	if(s)	s = s.replace(/[^\w\s\(\)\.-]+/g, "");
	
	return s;
}

function _GetAbsolutePosition(obj) 
{ 
	var curleft = curtop = 0; 
	
	if (obj.offsetParent) 
	{ 
		curleft = obj.offsetLeft; 
		curtop = obj.offsetTop; 
		
		while (obj = obj.offsetParent) 
		{ 
			curleft += obj.offsetLeft;
			curtop += obj.offsetTop;
		}
	} 
	
	return [curleft,curtop]; 
}

function _toString()
{
	var browser = String("");
	browser		+= "this.UserAgent = " + this.UserAgent + "\n";
	browser		+= "this.CommonName = " + this.getCommonName() + "\n";
	browser 	+= "this.Mozilla = " + this.Mozilla + "\n";
	browser 	+= "this.Explorer = " + this.explorer + "\n";
	browser 	+= "this.Safari = " + this.Safari + "\n";
	browser 	+= "this.Version = " + this.version + "\n";
	browser     += "this.Platform = " + this.platform + "\n";
	browser 	+= "this.ns40 = " + this.ns40 + "\n";
	browser 	+= "this.ns50 = " + this.ns50 + "\n";
	browser 	+= "this.ie60 = " + this.ie60 + "\n";
	browser 	+= "this.ie70 = " + this.ie70 + "\n";
	browser 	+= "this.macie5 = " + this.macie5 + "\n";
	browser 	+= "this.MacPPC = " + this.MacPPC + "\n";
	browser 	+= "this.Exploiter = " + this.Exploiter + "\n";
	browser 	+= "this.DOM() = " + this.DOM() + "\n";
	browser 	+= "this.dhtml = " + this.dhtml + "\n";
	browser 	+= "this.rollovers = " + this.rollovers + "\n";
	
	return browser;
}	

function _GetCommonName()
{
	if(navigator.userAgent.indexOf("MSIE 7.0") != -1)
		return "Microsoft Internet Explorer 7.0 (full support)";
	else if(navigator.userAgent.indexOf("MSIE 8.0") != -1)
		return "Microsoft Internet Explorer 8.0 (full support)";
	else if(navigator.userAgent.indexOf("MSIE 6.0") != -1)
		return "Microsoft Internet Explorer 6.0 (full support)";
	else if(navigator.userAgent.indexOf("Firefox/3") != -1)
		return "Mozilla Firefox " + this.version + " (full support)";
	else if(navigator.userAgent.indexOf("Firefox/2") != -1)
		return "Mozilla Firefox " + this.version + " (full support)";
	else if(navigator.userAgent.indexOf("Opera/9") != -1)
		return "Opera " + this.version + " (full support)";
	else if((navigator.userAgent.indexOf("Chrome/") != -1))
		return "Google Chrome (full support)";
	else if(navigator.userAgent.indexOf("Safari") != -1)	
		return "Apple Safari " + this.version + (this.version > 2 ? " (full support)" : " (limited supported)");
	else if(navigator.userAgent.indexOf("Firefox") != -1)
		return "Mozilla Firefox " + this.version + " (limited support)";
	else
		return navigator.appName + " version " + parseInt(navigator.appVersion) + " (limited support)";
}

function _DOM()
{	
	/*var parts = navigator.userAgent.split("/");
	
	var browser = "";
	var version = 0;
	
	for(var i = 0; i < parts.length; i++)
	{
		if(parts[i].indexOf("Safari") != -1)
		{
			info = parts[i].split(" ");
			
			version = parseInt(info[0]);
			browser = info[1];
		}
	}

	if(browser == "Safari" &&  version < 3)	
		return false;
	else
		return true;*/
		
	if(this.getCommonName().indexOf("(full support)") != -1)	return true;
	else														return false;
}

function _Report(id)
{
	//var browser = navigator.appName + " version " + parseInt(navigator.appVersion);
	document.getElementById(id).innerHTML = "You are using " + _GetCommonName();
}

function _WriteCookie(key, value, expires)
{
	if(!expires)	expires = new Date();
	
	document.cookie  = key + "=" + value + "; expires=" + expires.toGMTString() + "; path=/";
}

function _ReadCookie(key) 
{
	 var theCookie = "" + document.cookie;
	 
	 var ind = theCookie.indexOf(key);
	 
	 if(ind == -1 || key == "") return ""; 
	 
	 var ind1=theCookie.indexOf(';',ind);
	 
	 if(ind1 == -1) ind1=theCookie.length;
	  
	 return unescape(theCookie.substring(ind + key.length + 1,ind1));
}