<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
  <title>Layout-Test</title>
  <meta name="GENERATOR" content="Quanta Plus">
  <meta name="AUTHOR" content="k. henneberg">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <link rel=stylesheet type='text/css' href='../css/format2.css'>
  <link rel="shortcut icon" href="../share/images/favicon.ico">
  <script type="text/javascript">
	function Fensterweite () 
	{
		if (window.innerWidth) 
		{
	    	return window.innerWidth;
		}
		else 
		if (document.body && document.body.offsetWidth)
		{
	    	return document.body.offsetWidth;
	  	}
	  	else
		{
	    	return 0;
	  	}
	}
	
	function Fensterhoehe () 
	{
		if (window.innerHeight)
		{
	    	return window.innerHeight;
		}
		else
		if (document.body && document.body.offsetHeight)
		{
	    	return document.body.offsetHeight;
		}
		else
		{
	    	return 0;
		}
	}
	
	function neuAufbau ()
	{
	  if (Weite != Fensterweite() || Hoehe != Fensterhoehe())
	    location.href = location.href;
	}
	
	/* Überwachung von Netscape initialisieren */
	if (!window.Weite && window.innerWidth) 
	{
	  window.onresize = neuAufbau;
	  Weite = Fensterweite();
	  Hoehe = Fensterhoehe();
	}
</script>
</head>

<body>

<?php

/*
 * Project: pic2base
 * File: layout_test1.php
 *
 * Copyright (c) 2005 - 2012 Klaus Henneberg
 *
 * Project owner:
 * Klaus Henneberg
 * Finkenweg 18
 * 38889 Blankenburg, BRD
 *
 * All files of this project are licensed under the terms of the Open Software License
 * http://www.opensource.org/licenses/osl-2.1.php
 */

include '../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';

echo "
<script type='text/javascript'>
	var Weite;
	var Hoehe;
	var breite = Weite/10;
	var hoehe = Hoehe/10;
	var contentbreite;
	var pagebreite;
	
	if(Weite>1000)
	{
		contentbreite = Weite - 180;
		pagebreite = Weite - 100;
		//alert(contentbreite);
	}
	else
	{
		contentbreite=823;
		pagebreite = 950;
	}
	
	if(Hoehe>730)
	{
		contenthoehe = Hoehe - 100;
	}
	
		document.write('<div class=\"page\" style=\"width:\"+pagebreite+\"px;\">');
			document.write('<p id=\"kopf\">pic2base :: Layout-Test</p>');
		
			document.write('<div class=\"navi\" style=\"clear:right;\">');
			
				document.write('<div class=\"menucontainer\">');
				document.write('Text');
				document.write('</div>');
			
			document.write('</div>');
		
			document.write('<div class=\"content\" style=\"width:\" + contentbreite + \"px;\">');
			//document.write('<div class=\"content\" style=\"width:823px\">');
			document.write('Breite:' + Weite + ', Höhe: ' + Hoehe + ', Contentbreite: ' + contentbreite + ', Pagebreite: ' + pagebreite);
			document.write('</div>');
		
			document.write('<br style=\"clear:both;\" />');
		
			document.write('<p id=\"fuss\"><br></p>');
		//document.write('');
		
		document.write('</div>');
		
	document.write('</DIV>');

	//document.write('<div class=\"test\" style=\"margin-left: 0px; padding: 5px; background-color:#00ffaa; border:1px solid #ff0000; float:left; height:'+hoehe+'px; width: ' +breite+ 'px; overflow:auto;\">Breite: '+breite+', Höhe: '+hoehe+'</div>');
</script>";

/*
echo "
<DIV Class='klein'>
	<div class='page'>
	
		<p id='kopf'>pic2base :: Layout-Test</p>
		
		<div class='navi' style='clear:right;'>
			<div class='menucontainer'>
			Text
			</div>
		</div>
		
		<div class='content'>
			<p style='margin:70px 0px; text-align:center'>
				<script type='text/javascript'>
				document.write('Weite: ' + Weite + ' Höhe: ' + Hoehe);
				</script>
			</p>
			
			<script type='text/javascript'>
			var breite = Weite/10;
			var hoehe = Hoehe/10;
			//document.write('Breite:' + breite + ', Höhe: ' + hoehe);
			document.write('<div class=\"test\" style=\"margin-left: 0px; padding: 5px; background-color:#00ffaa; border:1px solid #ff0000; float:left; height:'+hoehe+'px; width: ' +breite+ 'px; overflow:auto;\">Breite: '+breite+', Höhe: '+hoehe+'</div>');
			</script>
		</div>
		<br style='clear:both;' />
	
		<p id='fuss'><A style='margin-right:745px; color:#eeeeee;' HREF='http://www.pic2base.de' target='blank'>www.pic2base.de</A>".$cr."</p>
	
	</div>
</DIV>";
*/
?>

</body>
</html>
