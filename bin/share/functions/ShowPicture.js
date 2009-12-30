function ZeigeBild(bildname,breite,hoehe,ratio_pic,modus)
{
	//alert("Name: " + bildname + ", Breite: " + breite + ", Hoehe: " + hoehe + ", Ratio: " + ratio_pic + ", Modus: " + modus)
	anotherWindow = window.open("", "bildfenster", "");
	// Wird bereits ein Bild in der "Grossansicht" angezeigt? - dann wird es geschlossen:
	if (anotherWindow != null)
	{
		anotherWindow.close();
	}

	if(modus=='HQ')
	{
		if (hoehe > (screen.height -100))
		{
			diff = hoehe / (screen.height -100);
			hoehe = hoehe / diff;
			breite = breite / diff;
		}
		
		//alert("Breite: "+breite+", Hoehe: "+hoehe+", Modus: "+modus+", Format: "+size);
		var ref,parameter,dateiname,htmlcode,b=breite,h=hoehe,woerter,location;

		//dateiname=bildname.substring(bildname.length-17,bildname.length);
		woerter = bildname.split("/");
		dateiname = woerter[woerter.length - 1];
		htmlcode="<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n";
		htmlcode+="<html style=\"height: 100%\">\n<head>\n<title>"+dateiname+"<\/title>\n";
		htmlcode+="<\/head>\n<body style=\"margin: 0; padding: 0; height: 100%\"><center>\n";
		
		htmlcode+="<div style=\"position: absolute; top: "+(Math.random()*(h-50))+"px; left: "+(Math.random()*(b-200))+"px; z-index: 1;\">\n";
		htmlcode+="<img src=\"../../share/images/p2b.gif\" height=50px>\n";
		htmlcode+="<\/div>\n";		

		htmlcode+="<div style=\"position: absolute; top: 0px; left: 0px; z-index: 2;\">\n";
		htmlcode+="<img src=\"../../share/images/no_pic.gif\" height="+h+" width="+b+" alt=\"no_pic.gif\" title=\"[Mausklick schlie&szlig;t Fenster!]\" onclick=\"window.close()\">\n";
		htmlcode+="<\/div>\n";
		
		htmlcode+="<div style=\"position: absolute; top: 0px; left: 0px; z-index: 0;\">\n";
		htmlcode+="<img src=\""+bildname+"\" height="+h+">\n";
		htmlcode+="<\/div>\n</center>\n<\/body>\n<\/html>\n";
		
		parameter="width="+b+",height="+h+",screenX="+(screen.width-b)/2+",screenY="+(screen.height-h)/2+",left="+(screen.width-b)/2+",top="+(screen.height-h)/2;
	}
	
	else if(modus=='ori')
	{
		screen_ratio = screen.width / screen.height;
		//alert("Screen-Ratio: "+screen_ratio+", Pic-Ratio: "+ratio_pic);
		if (screen_ratio > ratio_pic)
		{
			hoehe = screen.height - 100;
			breite = Math.floor(hoehe * ratio_pic);
		}
		else if(ratio_pic >= screen_ratio)
		{
			breite = screen.width;
			hoehe = Math.floor(breite / ratio_pic);
		}
		//alert("Breite: "+breite+", Hoehe: "+hoehe+", Modus: "+modus);
		var ref,parameter,dateiname,htmlcode,b=breite,h=hoehe,woerter;
		
		woerter = bildname.split("/");
		dateiname = woerter[woerter.length - 1];

		htmlcode="<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n";
		htmlcode+="<html style=\"height: 100%\">\n<head>\n<title>"+dateiname+"<\/title>\n";
		htmlcode+="<\/head><center>\n<body style=\"margin: 0; padding: 0; height: 100%\">\n";

		htmlcode+="<div style=\"position: absolute; top: "+(Math.random()*(h-50))+"px; left: "+(Math.random()*(b-200))+"px; z-index: 1;\">\n";
		htmlcode+="<img src=\"../../share/images/p2b.gif\" height=50px>\n";
		htmlcode+="<\/div>\n";	
		
		htmlcode+="<div style=\"position: absolute; top: 0px; left: 0px; z-index: 2;\">\n";
		htmlcode+="<img src=\"../../share/images/no_pic.gif\" height="+h+" width="+b+" alt=\"no_pic.gif\" title=\"[Mausklick schlie&szlig;t Fenster!]\" onclick=\"window.close()\">\n";
		htmlcode+="<\/div>\n";
		
		htmlcode+="<div style=\"position: absolute; top: 0px; left: 0px; z-index: 0;\">\n";
		htmlcode+="<img src=\""+bildname+"\" height="+h+">\n";
		htmlcode+="<\/div>\n</center>\n<\/body>\n<\/html>\n";
		
		parameter="width="+b+",height="+h+",screenX="+(screen.width-b)/2+",screenY="+(screen.height-h)/2+",left="+(screen.width-b)/2+",top="+(screen.height-h)/2;
	}
	
	ref=window.open("","bildfenster",parameter);
	ref.document.open("text/html");
	ref.document.write(htmlcode);
	ref.document.close();
	ref.focus();
}
