//jQuery-Version:
function checkWindowSize() 
{   
    var preview_height;
    var preview_width;
	//alert(jQuery("#tt").css({width})));
	if ( jQuery(window).width() > 1000 ) 
    { 
        jQuery("#page").css({"width":(jQuery(window).width() - 40) + "px"});
        jQuery("#head").css({"width":(jQuery(window).width() - 46) + "px"});
        jQuery("#content").css({"width":(jQuery(window).width() - 168) + "px"});
        jQuery("#spalte1").css({"width":((jQuery(window).width() - 162) / 2) + "px"});
        jQuery("#spalte2").css({"width":((jQuery(window).width() - 161) / 2) + "px"});
        jQuery("#spalte1F").css({"width":((jQuery(window).width() - 162) / 2) + "px"});
        jQuery("#spalte2F").css({"width":((jQuery(window).width() - 161) / 2) + "px"});
        jQuery("#foot").css({"width":(jQuery(window).width() - 46) + "px"});
        jQuery("#log_frame").css({"width":(jQuery(window).width() - 200) + "px"});
        
        //Cookie für Anzahl der Spalten in protect_metadata0.php und kompactview_metadata0.php sowie Bildanzahl im Filmstreifen setzen:
    	if( jQuery(window).width() >1000 && jQuery(window).width() <=1100)
    	{
    		document.cookie="columns=4; path=/";
    		document.cookie="step=6; path=/";
    	}
    	else if( jQuery(window).width() >1100 && jQuery(window).width() <=1420)
    	{
    		document.cookie="columns=5; path=/";
    		document.cookie="step=7; path=/";
    	}
    	else if( jQuery(window).width() >1420 && jQuery(window).width() <=1600)
    	{
    		document.cookie="columns=6; path=/";
    		document.cookie="step=10; path=/";
    	}
    	else if( jQuery(window).width() >1600)
    	{
    		document.cookie="columns=7; path=/";
    		document.cookie="step=12; path=/";
    	}
    	
    	/*Breite der Textfelder in get_details.php*/
    	jQuery("#kats").css({"width":((jQuery(window).width() -246) / 2) + "px"});//210
        jQuery("#kat_textarea").css({"width":((jQuery(window).width() - 260) / 2) + "px"});//230
        jQuery("#desc").css({"width":((jQuery(window).width() - 246) / 2) + "px"});//210
        jQuery("#desc_textarea").css({"width":((jQuery(window).width() - 260) / 2) + "px"});//220
        document.cookie="kat_textarea_width=" + ((jQuery(window).width() - 260) / 2) + "; path=/";//220
        document.cookie="desc_textarea_width=" + ((jQuery(window).width() - 260) / 2) + "; path=/";//220
        
        /*Breite der Tabelle zur Kategorie-/Datum-Auswahl bei der Suche; Spalte1*/
        /*jQuery("#kat").css({"width":((jQuery(window).width() - 0) / 3) + "px"}); //290
        jQuery("#kat1").css({"width":((jQuery(window).width() - 0) / 3) + "px"}); //350*/
        
        /*Breite der Texteingabefelder bei der Suche nach Beschreibungstext*/
        jQuery(".Feld250").css({"width":(((jQuery(window).width() - 1000) / 5) + 250) + "px"});
        
        /*Breite des Vorschaubildes bei der Bild-Bewertung (in Spalte2)*/
        preview_width = ((jQuery(window).width() - 161) / 2) -30; //Breite spalte2 - 20 px
        
        /*Breite des Filmstreifens*/
        jQuery("#filmstreifen").css({"width":(jQuery(window).width() - 168) + "px"});
        
        /*Breite des Texteingabefeldes im Bereich Bearbeitung - Beschreibung bearbeiten*/
        jQuery("#desc_textarea2").css({"width":((jQuery(window).width() - 235) / 2) + "px"});
        
        /*Zellenbreite des vollstaendigen Kategorie-Baumes; Spalte2*/
        jQuery("#kat_b").css({"width":((jQuery(window).width() - 0) / 3) + "px"}); //290
        jQuery("#kat1_b").css({"width":((jQuery(window).width() - 0) / 3) + "px"}); //350
        
        /*Breite des Vorschaustreifens im Kollektion-Bearbeiten-Fenster (edit_selected_collection.php)*/
        jQuery("#coll").css({"width":(jQuery(window).width() - 250) + "px"});
        
        /*Breite der Button-Zeile (und damit der oberen Tabelle) im Kollektion-Bearbeiten-Fenster (edit_selected_collection.php)*/
        jQuery("#button_bar").css({"width":(jQuery(window).width() - 250) + "px"});
        
        /*Breite des Bild-Infobereichs rechts neben dem vergroesserten Vorschaubild (tooltip4) im Bearbeiten-Fenster (edit_selected_collection.php)*/
        jQuery(".ttinfo").css({"width":(jQuery(window).width() - 756) + "px"});
        /*jQuery(".ttinfo").css({"width":"90%"});*/
        
        /*Breite des Suchfeldes (Kollektion-Beschreibung) in recherche2.php*/
        jQuery("#coll_description").css({"width":((jQuery(window).width() - 750) / 2) + "px"});
        
    	
        //alert( ((jQuery(window).width() - 162) / 2) );
    } 
    else 
    { 
    	jQuery("#page").css({"width":"950px"});
    	jQuery("#head").css({"width":"944px"});
    	jQuery("#content").css({"width":"822px"});
    	jQuery("#spalte1").css({"width":"414px"});
    	jQuery("#spalte2").css({"width":"415px"});
    	jQuery("#spalte1F").css({"width":"414px"});
    	jQuery("#spalte2F").css({"width":"415px"});
    	jQuery("#foot").css({"width":"944px"});
    	jQuery("#log_frame").css({"width":"780px"});
    	
    	/*Breite der Textfelder in get_details.php*/
    	jQuery("#kats").css({"width":"360px"});//390
        jQuery("#kat_textarea").css({"width":"370px"});//380
        jQuery("#desc").css({"width":"360px"});//390
        jQuery("#desc_textarea").css({"width":"370px"});//380
        document.cookie="kat_textarea_width=370; path=/";//380
        document.cookie="desc_textarea_width=370; path=/";//380
        
        /*Breite der Tabelle zur Kategorie-/Datum-Auswahl bei der Suche*/
        jQuery("#kat").css({"width":"355px"});
        
        /*Breite der Texteingabefelder bei der Suche nach Beschreibungstext*/
        jQuery(".Feld250").css({"width":"250px"});
        
        /*Breite des Vorschaubildes bei der Bild-Bewertung (in Spalte2)*/
        preview_width = 350;
        
        /*Breite des Filmstreifens*/
        jQuery("#filmstreifen").css({"width":"820px"});
        
        /*Breite des Texteingabefeldes im Bereich Bearbeitung - Beschreibung bearbeiten*/
        jQuery("#desc_textarea2").css({"width":"380px"});
        
        /*Zellenbreite des vollstaendigen Kategorie-Baumes; Spalte2*/
        jQuery("#kat_b").css({"width":"350px"}); 
        jQuery("#kat1_b").css({"width":"340px"});
        
        /*Breite des Vorschaubereichs im Kollektion-Bearbeiten-Fenster (edit_selected_collection.php)*/
        jQuery("#coll").css({"width":"740px"});
        
        /*Breite der Button-Zeile (und damit der oberen Tabelle) im Kollektion-Bearbeiten-Fenster (edit_selected_collection.php)*/
        jQuery("#button_bar").css({"width":"740px"});
        
        /*Breite des Bild-Infobereichs rechts neben dem vergroesserten Vorschaubild (tooltip4) im Bearbeiten-Fenster (edit_selected_collection.php)*/
        jQuery(".ttinfo").css({"width":"274px"});
        
        /*Breite des Suchfeldes (Kollektion-Beschreibung) in recherche2.php*/
        jQuery("#coll_description").css({"width":"130px"});
    	
    	//Cookie für Anzahl der Spalten in protect_metadata0.php und kompactview_metadata0.php sowie Bildanzahl im Filmstreifen setzen:
    	document.cookie="columns=3; path=/";
    	document.cookie="step=6; path=/";
    } 
    
    if ( jQuery(window).height() > 743 ) 
    { 
        /*allgemeine Layoutelemente*/
    	jQuery("#page").css({"height":(jQuery(window).height() - 40) + "px"});
        jQuery("#navi").css({"height":(jQuery(window).height() - 107) + "px"});
        jQuery("#content").css({"height":(jQuery(window).height() - 116) + "px"});
        jQuery("#spalte1").css({"height":(jQuery(window).height() - 110) + "px"});
        jQuery("#spalte2").css({"height":(jQuery(window).height() - 110) + "px"});
        jQuery("#spalte1F").css({"height":(jQuery(window).height() - 266) + "px"});
        jQuery("#spalte2F").css({"height":(jQuery(window).height() - 266) + "px"});
        jQuery("#log_frame").css({"height":(jQuery(window).height() - 146) + "px"});
        
        /*Hoehe der Textfelder in get_details.php*/
        jQuery("#kats").css({"height":((jQuery(window).height() - 590) / 2) + "px"});
        jQuery("#kat_textarea").css({"height":((jQuery(window).height() - 630) / 2) + "px"});
        jQuery("#desc").css({"height":((jQuery(window).height() - 590) / 2) + "px"});//545
        jQuery("#desc_textarea").css({"height":((jQuery(window).height() - 630) / 2) + "px"});//585
        document.cookie="kat_textarea_height=" + ((jQuery(window).height() - 630) / 2) + "; path=/";
        document.cookie="desc_textarea_height=" + ((jQuery(window).height() - 630) / 2) + "; path=/";//585
        
        /*Hoehe des fieldsets zur Aufnahme des Kategoriebaumes bei der Suche*/
        jQuery("#kat_tree_fieldset").css({"height":(jQuery(window).height() - 297) + "px"});
        
        /*Hoehe des fieldsets in der rechten Spalte (allgemeine Verwendung)*/
        jQuery("#fieldset_spalte2").css({"height":(jQuery(window).height() - 297) + "px"});//298
        
        /*Hoehe des Vorschaubildes bei der Bild-Bewertung (in Spalte2)*/
        preview_height = jQuery(window).height() - 320; //hoehe spalte2 - 54 px
        
        /*Hoehe des Texteingabefeldes im Bereich Bearbeitung - Beschreibung bearbeiten*/
        jQuery("#desc_textarea2").css({"height":(jQuery(window).height() - 470) + "px"});
        
        /*vertkale Positionierung der Speichern- und Abbrechen Buttons in der Navigationsleiste*/
        jQuery("#button3").css({"margin-top":(jQuery(window).height() - 405) + "px"});
        
        /*Hoehe der Scrollbox um den vollst. Datumbaum; Bearbeitung, Kat. zuweisen nach Datum, spalte1*/
        jQuery("#scrollbox0").css({"height":(jQuery(window).height() - 311) + "px"});
        
        /*Hoehe der Scrollbox um den vollst. Kategoriebaum; Bearbeitung, Kat. zuweisen nach Kat.*/
        jQuery("#scrollbox1").css({"height":(jQuery(window).height() - 311) + "px"});
        //alert( jQuery(window).height() + "px" );
        
        /*Hoehe der Scrollbox um die vollst. Kollektions-Auflistung; edit_collection.php*/
        jQuery("#scrollbox2").css({"height":(jQuery(window).height() - 171) + "px"});
        
        /*Hoehe der Scrollbox in der Kollektions-Ansicht; recherche/view_collection.php*/
        jQuery("#scrollbox3").css({"height":(jQuery(window).height() - 211) + "px"});
        
        /*Hoehe einer umfangreichen Hilfe-Box; recherche2.php*/
        jQuery("#help").css({"height":(jQuery(window).height() - 310) + "px"});
        
        /*vertikaler Abstand zwischen den beiden tabellen in edit_selected_collection.php*/
        jQuery("#picture_list").css({"margin-top":((jQuery(window).height() - 700) / 3) + "px"});
    } 
    else 
    { 
    	/*allgemeine Layoutelemente*/
    	jQuery("#page").css({"height":"703px"});
    	jQuery("#navi").css({"height":"637px"});
    	jQuery("#content").css({"height":"627px"});
    	jQuery("#spalte1").css({"height":"633px"});
    	jQuery("#spalte2").css({"height":"633px"});
    	jQuery("#spalte1F").css({"height":"477px"});
    	jQuery("#spalte2F").css({"height":"477px"});
    	jQuery("#log_frame").css({"height":"600px"});
    	
    	/*Hoehe der Textfelder in get_details.php*/
    	jQuery("#kats").css({"height":"76px"});//84
        jQuery("#kat_textarea").css({"height":"56px"});//64
        jQuery("#desc").css({"height":"76px"});//90
        jQuery("#desc_textarea").css({"height":"56px"});//70
        document.cookie="kat_textarea_height=56; path=/";//64
        document.cookie="desc_textarea_height=56; path=/";//70
        
        /*Hoehe des fieldsets zur Aufnahme des Kategoriebaumes bei der Suche*/
        jQuery("#kat_tree_fieldset").css({"height":"446px"});
        
        /*Hoehe des fieldsets in der rechten Spalte (allgemeine Verwendung)*/
        jQuery("#fieldset_spalte2").css({"height":"445px"});//445
        
        /*Hoehe des Vorschaubildes bei der Bild-Bewertung (in Spalte2)*/
        preview_height = 350;
        
        /*Hoehe des Texteingabefeldes im Bereich Bearbeitung - Beschreibung bearbeiten*/
        jQuery("#desc_textarea2").css({"height":"273px"});
        
        /*vertkale Positionierung der Speichern- und Abbrechen Buttons in der Navigationsleiste*/
        jQuery("#button3").css({"margin-top":"335px"});
        
        /*Hoehe der Scrollbox um den vollst. Datumbaum; Bearbeitung, Kat. zuweisen nach Datum, spalte1*/
        jQuery("#scrollbox0").css({"height":"432px"});
        
        /*Hoehe der Scrollbox um den vollst. Kategoriebaum; Bearbeitung, Kat. zuweisen nach Kat.*/
        jQuery("#scrollbox1").css({"height":"432px"});
        
        /*Hoehe der Scrollbox um die vollst. Kollektions-Auflistung; edit_collection.php*/
        jQuery("#scrollbox2").css({"height":"572px"});
        
        /*Hoehe der Scrollbox in der Kollektions-Ansicht; recherche/view_collection.php*/
        jQuery("#scrollbox3").css({"height":"532px"});
        
        /*Hoehe einer umfangreichen Hilfe-Box; recherche2.php*/
        jQuery("#help").css({"height":"430px"});
        
        /*vertikaler Abstand zwischen den beiden tabellen in edit_selected_collection.php*/
        jQuery("#picture_list").css({"margin-top":"15px"});
        
        //alert( jQuery(scrollbox3).height() + "px" );
    }
    
    //alert( jQuery(window).height() );
    document.cookie="window_width=" + jQuery(window).width() + "; path=/";
    document.cookie="window_height=" + jQuery(window).height() + "; path=/";
    
    /*Cookies fuer die max. hor./vert. Ausdehnung des Vorschaubildes bei der Bewertung von Bildern*/
	document.cookie="vert_preview_size=" + preview_height + "; path=/";
	document.cookie="hor_preview_size=" + preview_width + "; path=/";   
}