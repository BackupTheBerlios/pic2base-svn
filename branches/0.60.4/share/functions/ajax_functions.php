<?php
//var_dump($_GET);
function createPreviewAjax($pic_id, $max_size, $quality)
{
	unset($username);
	IF ($_COOKIE['login'])
	{
		list($c_username) = preg_split('#,#',$_COOKIE['login']);
	}
	
	//Erzeugung einer Bildvorschau unter optimaler Nutzung des Bildschirmes;
	//Qualitaeten: 1 - Vorschaubild; 2 - HQ-Bild; 3 - Original-Bild
	include '../share/global_config.php';
	include $sr.'/bin/share/db_connect1.php';
	
	$exiftool = buildExiftoolCommand($sr);
	
	$res0 = mysql_query( "SELECT * FROM $table2 WHERE pic_id='$pic_id'");
	$row = mysql_fetch_array($res0);
	$FileNameV = $row['FileNameV'];
	$FileNameHQ = $row['FileNameHQ'];
	$FileName = $row['FileName'];
	$FileNameOri = $row['FileNameOri'];
	$Width = $row['ExifImageWidth'];
	$Height = $row['ExifImageHeight'];
	$Orientation = $row['Orientation'];
	
	$FileNameOri_ext = explode('.', $FileNameOri);
	$ext = strtolower(isset($FileNameOri_ext[1]));//Extension des Original-Bildes	
	
	//abgeleitete Groessen:
	$parameter_v=getimagesize($sr.'/images/vorschau/thumbs/'.$FileNameV);
	$parameter_hq=getimagesize($sr.'/images/vorschau/hq-preview/'.$FileNameHQ);
	$parameter_o=getimagesize($sr.'/images/originale/'.$FileName);
	
	SWITCH($quality)
	{
		CASE '1':
		$breite = $parameter_v[0];
		$hoehe = $parameter_v[1];
		$bildname = 'http://'.$sr.'/images/vorschau/thumbs/'.$FileNameV;
		break;
		
		CASE '2':
		$breite = $parameter_hq[0];
		$hoehe = $parameter_hq[1];
		$bildname = 'http://'.$sr.'/images/vorschau/hq-preview/'.$FileNameHQ;
		break;
		
		CASE '3':
		$breite = $parameter_o[0];
		$hoehe = $parameter_o[1];
		SWITCH($Orientation)
		{
			CASE '':
			CASE 0:
			CASE 1:
			$bildname = 'http://'.$_SERVER['SERVER_NAME'].$inst_path.'/pic2base/images/originale/'.$FileName;
			break;
			
			default:
			//wenn das bild nicht landscape ist wird geprueft, ob es dieses Bild schon in gedrehter Form gibt:
			$verz=opendir($sr.'/images/originale/rotated');
			$n = 0;
			while($bilddatei=readdir($verz))
			{
				if($bilddatei != "." && $bilddatei != "..")
				{
					//$bildd=$bilder_verzeichnis."/".$bilddatei;
					//echo "Bild: ".$bilddatei."; Datei: ".$file_name."<BR>";
					IF ($bilddatei == $FileName)
					{
						$n++;
					}
				}
			}
//			echo "N: ".$n."<BR>";
			IF ($n > '0')
			{
				//wenn ein bereits gedrehtes Bild gefunden wurde, verwende dies:
				$bildname = 'http://'.$_SERVER['SERVER_NAME'].$inst_path.'/pic2base/images/originale/rotated/'.$FileName;
			}
			ELSE
			{
				//wenn keins gefunden wurde wird es gedreht und dann angezeigt:
				$bildname = 'http://'.$_SERVER['SERVER_NAME'].$inst_path.'/pic2base/images/originale/'.$FileName;
				
				IF($ext == 'nef')
				{
					copy("$pic_path/$FileName", "$pic_rot_path/$FileName");
					clearstatcache();
					chmod ($pic_rot_path."/".$FileName, 0700);
					clearstatcache();
				}
				ELSE
				{
					SWITCH($Orientation)
					{
						case 3:
						//Das Vorschaubild muss 180 gedreht werden:
						$command = "/usr/bin/convert ".$pic_path."/".$FileName." -rotate 180 ".$pic_rot_path."/".$FileName."";
						$output = shell_exec($command);
						break;
						
						case 6:
						//Das Vorschaubild muss 90 im Uhrzeigersinn gedreht werden:
						$command = "/usr/bin/convert ".$pic_path."/".$FileName." -rotate 90 ".$pic_rot_path."/".$FileName."";
						//echo $command;
						$output = shell_exec($command);
						break;
						
						case 8:
						//Das Vorschaubild muss 90 entgegen dem Uhrzeigersinn gedreht werden:
						$command = "/usr/bin/convert ".$pic_path."/".$FileName." -rotate 270 ".$pic_rot_path."/".$FileName."";
						$output = shell_exec($command);
						break;
					}
				}
			}
			$parameter_o_r=getimagesize($sr.'/images/originale/rotated/'.$FileName);
			$breite = $parameter_o_r[0];
			$hoehe = $parameter_o_r[1];
			break;
		}
		//echo $bildname;
		break;
	}
	
	$ratio_pic = $breite / $hoehe;
	
	SWITCH($Orientation)
	{
		CASE '':
		CASE '0':
		CASE '1':
		CASE '3':
		$Height = $row['ExifImageHeight'];
		$Width = $row['ExifImageWidth'];
		break;
		
		CASE '6':
		CASE '8':
		$Height = $row['ExifImageWidth'];
		$Width = $row['ExifImageHeight'];
		break;
	}
	
	$breite_v = $parameter_v[0];
	$hoehe_v = $parameter_v[1];
	IF ($breite_v >= $hoehe_v)
      	{
      		$Breite = $max_size;
      		$Hoehe = number_format(($Breite * $hoehe_v / $breite_v),0,',','.');
      	}
      	ELSE
      	{
      		$Hoehe = $max_size;
      		$Breite = number_format(($Hoehe * $breite_v / $hoehe_v),0,',','.');
      	}
	echo "<a href='' onclick=\"ZeigeBild('$bildname', '$Width', '$Height', '$ratio_pic', 'ori', '');return false\"  title='Ansicht in optimaler Qualit&auml;t'><div class='shadow_1'><img src='$inst_path/pic2base/images/vorschau/thumbs/$FileNameV' alt='Vorschaubild' width=$Breite height=$Hoehe z='5' border='0'></div></a>";
}

function getShortFS($FileSize)
{
	SWITCH ($FileSize)
	{
		CASE $FileSize < 1024:
		$fs = $FileSize;
		echo $fs." Byte";
		break;
		
		case $FileSize < 1048576:
		$fs = number_format(($FileSize / 1024),1,',','.');
		echo $fs." kB";
		break;
		
		case $FileSize >= 1048576:
		$fs = number_format(($FileSize / 1048576),1,',','.');
		echo $fs." MB";
		break;
	}
}
?>

<SCRIPT language="javascript">
<!--

ImageState = function( picId, baseFile, mod )
{
	this.picId = picId;
	this.baseFile = baseFile;
	this.mod = mod;
};

currImageState = new ImageState( 0, "", "" );
getDetailsAjax = null;

function getPreview(kat_id, ID, mod, pic_id, modus, base_file, bewertung, auswahl, position, jump, treestatus)
{
	//confirm("kat_id: " + kat_id + ", ID: "+ID+", mod: "+mod+", pic_id: "+pic_id+", modus: "+modus+", base_file: "+base_file +", bewertung: "+bewertung +", Auswahl: "+auswahl +", Position: "+position +", Sprung-Richtung: "+jump +", Treestatus: "+treestatus);
	
	var url = '../../share/get_preview.php';
	var params ='kat_id=' + kat_id + '&ID=' + ID + '&mod=' + mod + '&pic_id=' + pic_id + '&modus=' + modus + '&base_file=' + base_file + '&bewertung=' + bewertung + '&auswahl=' + auswahl + '&position=' + position + '&jump=' + jump + '&treestatus=' + treestatus;
	var target = 'filmstreifen';
	var myAjax = new Ajax.Updater(target,url,{method:'get', parameters: params, onCreate: blende_ein, onComplete: filmstreifen_geladen});
	
}

function getTimePreview(j, m, t, pic_id, mod, modus, base_file, bewertung, position, jump)
{
	//position: Welcher Ausschnitt des Filmstreifens dargestellt wird
	//jump: in welche Richtung navigiert wird: 1: 6 Bilder weiter; 2: zum Ende; -1: 6 Bilder zurueck; -2: zum Anfang
	var url = '../../share/get_preview.php';
	var params ='j=' + j + '&m=' + m + '&t=' + t + '&pic_id=' + pic_id + '&mod=' + mod + '&modus=' + modus + '&base_file=' + base_file + '&bewertung=' + bewertung + '&position=' + position + '&jump=' + jump;
	//alert("Parameter: "+params);
	var target = 'filmstreifen';
	var myAjax = new Ajax.Updater(target,url,{method:'get', parameters: params, onCreate: blende_ein, onComplete: filmstreifen_geladen});
}

function getTimePreview2(j, m, t, pic_id, mod, modus, base_file, sr)
{
	//confirm("Jahr: " + j + ", Monat: "+ m +", Tag: "+ t +", mod: " + mod + ", pic_id: " + pic_id+", modus: "+modus+", base_file: "+base_file+", Software-root: "+sr);
	var url = '../../share/get_preview.php';
	var params ='j=' + j + '&m=' + m + '&t=' + t + '&pic_id=' + pic_id + '&mod=' + mod + '&modus=' + modus + '&base_file=' + base_file;
	//alert("Parameter: "+params);
	var target = 'filmstreifen';
	var myAjax = new Ajax.Updater(target,url,{method:'get', parameters: params, onCreate: blende_ein, onComplete: filmstreifen_geladen});
	
}

function getFormElements(formular)
{
	var form = $(formular);
	//alert("Formular-Elemente aus "+form);
	form.getInputs();
	form.reset('formular');
}

function magnifyPic(pic_id)
{
	//alert("Pic-ID: " + pic_id);
	var url = '../../share/magnify_pic.php';
	var params = 'pic_id=' + pic_id;
	var target = 'spalte2F';
	var myAjax = new Ajax.Updater(target,url,{method:'get', parameters: params});
}

var divMarked = null;
function getDetails(pic_id, base_file, mod, form_name)
{
	if( ( getDetailsAjax != null ) && ( !getDetailsAjax._complete ) )
	{
		getDetailsAjax.transport.abort();
	}
	getDetailsAjax = new Ajax.Updater('spalte2F','../../share/get_details.php',{method:'get', parameters: 'pic_id=' + pic_id + '&base_file=' + base_file + '&mod=' + mod + '&form_name=' + form_name, onComplete: setPreviewMark(document.getElementById( "picture_" + pic_id ))});
}

function unsetPreviewMark()
{
    if(divMarked != null )
    {
        // Div verstecken
       divMarked.style.display = "none";
    }
}

function setPreviewMark(newDivMark)
{
	//alert(newDivMark.innerHTML);
	unsetPreviewMark();
	divMarked = newDivMark;
    // Div anzeigen
    divMarked.style.display = "";
}


function getInfo(id, zeit)
{
	//alert("ID: "+id+", Zeit: "+zeit);
	var url = '../../share/get_info.php';
	var params = 'id=' + id + '&zeit=' + zeit;
	var target = 'spalte2F';
	var myAjax = new Ajax.Updater(target,url,{method:'get', parameters: params});
}

function getGeoPreview1(long, lat, alt, radius1, einheit1, mod, modus, base_file, form_name, bewertung, position, jump)
{
	var url = '../../share/get_preview.php';
	var params ='long=' + long + '&lat=' + lat + '&alt=' + alt + '&radius1=' + radius1 + '&einheit1=' + einheit1 + '&mod=' + mod + '&modus=' + modus + '&base_file=' + base_file + '&form_name=' + form_name + '&bewertung=' + bewertung + '&position=' + position + '&jump=' + jump;
	//alert("Parameter: "+params);
	var target = 'filmstreifen';
	var myAjax = new Ajax.Updater(target,url,{method:'get', parameters: params, onCreate: blende_ein, onComplete: filmstreifen_geladen});
}

function getGeoPreview2(ort, radius2, einheit2, mod, modus, base_file, form_name, bewertung, position, jump)
{
	var url = '../../share/get_preview.php';
	var params = 'ort=' + ort + '&radius2=' + radius2 + '&einheit2=' + einheit2 + '&mod=' + mod + '&modus=' + modus + '&base_file=' + base_file + '&form_name=' + form_name + '&bewertung=' + bewertung + '&position=' + position + '&jump=' + jump;
	//alert("Parameter in GetGeoPreview2: "+params);
	var target = 'filmstreifen';
	var myAjax = new Ajax.Updater(target,url,{method:'get', parameters: params, onCreate: blende_ein, onComplete: filmstreifen_geladen});
}

function getDescPreview1(desc1, bed1, desc2, bed2, desc3, bed3, desc4, bed4, desc5, mod, modus, base_file, bewertung, position, jump)
{
	var url = '../../share/get_preview.php';
	var params ='desc1=' + desc1 + '&bed1=' + bed1 + '&desc2=' + desc2 + '&bed2=' + bed2 + '&desc3=' + desc3 + '&bed3=' + bed3 + '&desc4=' + desc4 + '&bed4=' + bed4 + '&desc5=' + desc5 + '&mod=' + mod + '&modus=' + modus + '&base_file=' + base_file + '&bewertung=' + bewertung + '&position=' + position + '&jump=' + jump;
	//alert("Parameter: "+params);
	var target = 'filmstreifen';
	var myAjax = new Ajax.Updater(target,url,{method:'get', parameters: params, onCreate: blende_ein, onComplete: filmstreifen_geladen});
}

function getExifPreview(zusatz1, bedingung1, zw1, mod, modus, base_file, bewertung, position, jump)
{
	var url = '../../share/get_preview.php';
	var params ='zusatz1=' + zusatz1 + '&bedingung1=' + bedingung1 + '&zw1=' + zw1 + '&mod=' + mod + '&modus=' + modus + '&base_file=' + base_file + '&bewertung=' + bewertung + '&position=' + position + '&jump=' + jump;
	//alert("Parameter: "+params);
	var target = 'filmstreifen';
	var myAjax = new Ajax.Updater(target,url,{method:'get', parameters: params, onCreate: blende_ein, onComplete: filmstreifen_geladen});
}

function getExpSearchPreview(kat, op, kat1, op1, kat2, op2, kat3, op3, kat4, op4, mod, modus, base_file, bewertung, position, jump)
{
	var url = '../../share/get_preview.php';
	var params ='kat=' + kat + '&op=' + op + '&kat1=' + kat1 + '&op1=' + op1 + '&kat2=' + kat2 + '&op2=' + op2 + '&kat3=' + kat3 + '&op3=' + op3 + '&kat4=' + kat4 + '&op4=' + op4 + '&mod=' + mod + '&modus=' + modus + '&base_file=' + base_file + '&bewertung=' + bewertung + '&position=' + position + '&jump=' + jump;
	//alert("Parameter: "+params);
	var target = 'filmstreifen';
	var myAjax = new Ajax.Updater(target,url,{method:'get', parameters: params, onCreate: blende_ein, onComplete: filmstreifen_geladen});
}

function delPicture(FileName, c_username, pic_id, waitUntilDeleted)
{
	//loescht Bild-Datei aus dem DOWNLOAD-Ordner
	var url = '../../share/del_picture.php';
	var params = 'FileName=' + FileName + '&c_username=' + c_username + '&pic_id=' + pic_id;
	//alert("Parameter: "+params);
	var target = 'box' + pic_id;
	if( waitUntilDeleted == null )
	{
		waitUntilDeleted = false;
	}
	var myAjax = new Ajax.Updater(target,url,{method:'get', parameters: params, asynchronous: !waitUntilDeleted});
}

function copyPicture(FileName, c_username, pic_id, waitUntilCopyed)
{
	var url = '../../share/copy_picture.php';
	var params = 'FileName=' + FileName + '&c_username=' + c_username + '&pic_id=' + pic_id;
	//alert("Parameter: "+params);
	var target = 'box' + pic_id;
	if( waitUntilCopyed == null )
	{
		waitUntilCopyed = false;
	}
	var myAjax = new Ajax.Updater(target,url,{method:'get', parameters: params, asynchronous: !waitUntilCopyed});
}

function rotPrevPic(Orientation, FileNameV, pic_id, fs_hoehe)
{
	if(confirm("Wollen Sie das Vorschaubild wirklich drehen?"))
	{
		var url = '../../share/rot_prev_pic.php';
		var params = 'FileNameV=' + FileNameV + '&Orientation=' + Orientation + '&pic_id=' + pic_id + '&fs_hoehe=' + fs_hoehe;
		//alert("Parameter: "+params);
		var target = 'box' + pic_id;
		var myAjax = new Ajax.Updater(target,url,{method:'get', parameters: params});
	}
}

function deletePictureGeo1(FileName, c_username, pic_id, long, lat, alt, radius1, einheit1, mod, modus, base_file, form_name, bewertung, position, jump, aktion)
{
	//LOESCHT BILD AUS DEM ARCHIV!!!!!!
	var url = '../../share/delete_picture.php';
	var params = 'FileName=' + FileName + '&c_username=' + c_username + '&pic_id=' + pic_id + '&long=' + long + '&lat=' + lat + '&alt=' + alt + '&radius1=' + radius1 + '&einheit1=' + einheit1 + '&mod=' + mod + '&modus=' + modus + '&base_file=' + base_file + '&form_name=' + form_name + '&bewertung=' + bewertung + '&position=' + position + '&jump=' + jump + '&aktion=' + aktion;
	//alert("Parameter: "+params);
	var target = 'filmstreifen';
	var myAjax = new Ajax.Updater(target,url,{method:'get', parameters: params, onCreate: blende_ein, onComplete: blende_aus});
}

function deletePictureGeo2(FileName, c_username, pic_id, ort, radius2, einheit2, mod, modus, base_file, form_name, bewertung, position, jump, aktion)
{
	//LOESCHT BILD AUS DEM ARCHIV!!!!!!
	var url = '../../share/delete_picture.php';
	var params = 'FileName=' + FileName + '&c_username=' + c_username + '&pic_id=' + pic_id + '&ort=' + ort + '&radius2=' + radius2 + '&einheit2=' + einheit2 + '&mod=' + mod + '&modus=' + modus + '&base_file=' + base_file + '&form_name=' + form_name + '&bewertung=' + bewertung + '&position=' + position + '&jump=' + jump + '&aktion=' + aktion;
	//alert("Parameter in DeletePictureGeo2: "+params);
	var target = 'filmstreifen';
	var myAjax = new Ajax.Updater(target,url,{method:'get', parameters: params, onCreate: blende_ein, onComplete: blende_aus});
}

function deletePictureZeit(FileName, c_username, pic_id, j, m, mod, modus, base_file, bewertung, position, jump, aktion)
{
	//LOESCHT BILD AUS DEM ARCHIV!!!!!!
	var url = '../../share/delete_picture.php';
	var params = 'FileName=' + FileName + '&c_username=' + c_username + '&pic_id=' + pic_id + '&j=' + j + '&m=' + m + '&mod=' + mod + '&modus=' + modus + '&base_file=' + base_file + '&bewertung=' + bewertung + '&position=' + position + '&jump=' + jump + '&aktion=' + aktion;
	//alert("Parameter: "+params);
	var target = 'filmstreifen';
	var myAjax = new Ajax.Updater(target,url,{method:'get', parameters: params, onCreate: blende_ein, onComplete: blende_aus});
}

function deletePictureKat(FileName, c_username, pic_id, KAT_ID, kat_id, mod, modus, base_file, bewertung, position, jump, aktion)
{
	//LOESCHT BILD AUS DEM ARCHIV!!!!!!
	var url = '../../share/delete_picture.php';
	var params = 'FileName=' + FileName + '&c_username=' + c_username + '&pic_id=' + pic_id + '&KAT_ID=' + KAT_ID + '&kat_id=' + kat_id + '&mod=' + mod + '&modus=' + modus + '&base_file=' + base_file + '&bewertung=' + bewertung + '&position=' + position + '&jump=' + jump + '&aktion=' + aktion;
	//alert("Parameter: "+params);
	var target = 'filmstreifen';
	var myAjax = new Ajax.Updater(target,url,{method:'get', parameters: params, onCreate: blende_ein, onComplete: blende_aus});
}

function deletePictureDesc(FileName, c_username, pic_id, desc1, bed1, desc2, bed2, desc3, bed3, desc4, bed4, desc5, mod, modus, base_file, bewertung, position, jump, aktion)
{
	//LOESCHT BILD AUS DEM ARCHIV!!!!!!
	var url = '../../share/delete_picture.php';
	var params = 'FileName=' + FileName + '&c_username=' + c_username + '&pic_id=' + pic_id + '&desc1=' + desc1 + '&bed1=' + bed1 + '&desc2=' + desc2 + '&bed2=' + bed2 +'&desc3=' + desc3 + '&bed3=' + bed3 +'&desc4=' + desc4 + '&bed4=' + bed4 +'&desc5=' + desc5 +'&mod=' + mod + '&modus=' + modus + '&base_file=' + base_file + '&bewertung=' + bewertung + '&position=' + position + '&jump=' + jump + '&aktion=' + aktion;
	//alert("Parameter: "+params);
	var target = 'filmstreifen';
	var myAjax = new Ajax.Updater(target,url,{method:'get', parameters: params, onCreate: blende_ein, onComplete: blende_aus});
}

function deletePictureExif(FileName, c_username, pic_id, zusatz1, bed1, zusatzwert1, mod, modus, base_file, bewertung, position, jump, aktion)
{
	//LOESCHT BILD AUS DEM ARCHIV!!!!!!
	var url = '../../share/delete_picture.php';
	var params = 'FileName=' + FileName + '&c_username=' + c_username + '&pic_id=' + pic_id + '&zusatz1=' + zusatz1 + '&bed1=' + bed1 + '&zw1=' + zusatzwert1 +'&mod=' + mod + '&modus=' + modus + '&base_file=' + base_file + '&bewertung=' + bewertung + '&position=' + position + '&jump=' + jump + '&aktion=' + aktion;
	//alert("Parameter: "+params);
	var target = 'filmstreifen';
	var myAjax = new Ajax.Updater(target,url,{method:'get', parameters: params, onCreate: blende_ein, onComplete: blende_aus});
}

function getZusatzwert(zusatz1, bewertung)
{
	var url = '../../share/get_zusatzwert1.php';
	var params = 'field=' + zusatz1 + '&bewertung=' + bewertung;
	//alert("Parameter: "+params);
	var target = 'zw1';
	var myAjax = new Ajax.Updater(target,url,{method:'get', parameters: params, onCreate: blende_ein, onComplete: blende_aus});
}

function reloadDestTree(kat_id_d, kat_id_s)
{
	var url = '../../share/reload_dest_tree.php';
	var params = 'kat_id_d=' + kat_id_d +'&kat_id_s=' + kat_id_s;
	//alert("Parameter: "+params);
	var target = 'spalte2';
	var myAjax = new Ajax.Updater(target,url,{method:'get', parameters: params, onCreate: blende_ein, onComplete: blende_aus});
}

function reloadSourceTree(kat_id_s)
{
	var url = '../../share/reload_source_tree.php';
	var params = 'kat_id_s=' + kat_id_s;
	//alert("Parameter: "+params);
	var target = 'spalte1';
	var myAjax = new Ajax.Updater(target,url,{method:'get', parameters: params, onCreate: blende_ein, onComplete: blende_aus});
}

function getTimeTreeview(pic_id, mod, s_m, bewertung)
{
	var url = '../../share/time_treeview.php';
	var params = 'pic_id=' + pic_id + '&mod=' + mod + '&show_mod=' + s_m + '&bewertung=' + bewertung;
	//alert("Parameter: "+params);
	var target = 'spalte1F';
	var myAjax = new Ajax.Updater(target,url,{method:'get', parameters: params, onCreate: blende_ein, onComplete: blende_aus});
}

function getTimeTreeview2(pic_id, mod, s_m)
{
	//wird verwendet, wenn Bilder bearbeitet werden sollen und die Auswahl nach Aufnahmedatum erfolgt
	var url = '../../share/time_treeview2.php';
	var params = 'pic_id=' + pic_id + '&mod=' + mod + '&show_mod=' + s_m;
	//alert("Parameter: "+params);
	var target = 'spalte1F';
	var myAjax = new Ajax.Updater(target,url,{method:'get', parameters: params, onCreate: blende_ein, onComplete: blende_aus});
}

function createNewPreview(pic_id, c_username, hl, gamma, targ_color, col_inter, rota, wb, hsi, contrast, FileNameRaw, modus, Orientation)
{
	var url = '../../share/create_new_previews.php';
	var params = 'pic_id=' + pic_id + '&c_username=' + c_username + '&hl=' + hl + '&gamma=' + gamma + '&targ_color=' + targ_color + '&col_inter=' + col_inter + '&rota=' + rota + '&wb=' + wb + '&hsi=' + hsi + '&contrast=' + contrast + '&file_name_raw=' + FileNameRaw + '&modus=' + modus + '&Orientation=' + Orientation;
	//alert("Parameter: "+params);
	var target = 'new_preview';
	var myAjax = new Ajax.Updater(target,url,{method:'get', parameters: params, onCreate: blende_ein, onComplete: blende_aus});
}

function saveNewNote(pic_id,note)
{
	var url = '../../share/save_note.php';
	var params = 'pic_id=' + pic_id + '&note=' + note;
	//alert("Parameter: "+params);
	var target = 'star_set'+pic_id;
	//alert(target);
	var myAjax = new Ajax.Updater(target,url,{method:'get', parameters: params});
}

function saveNewParam(lokation, ort, loc_id, pic_id)
{
	var url = '../../share/save_new_param.php';
	var params = 'pic_id=' + pic_id + '&location=' + lokation + '&ort=' + ort + '&loc_id=' + loc_id;
	//alert("Parameter: "+params);
	var target = '#';
	//alert(target);
	var myAjax = new Ajax.Updater(target,url,{method:'get', parameters: params});
}

function saveChanges(pic_id,desc,dat)
{
	var url = '../../share/save_changes.php';
	var desc = desc.replace("?", ".");
	var params = 'pic_id=' + pic_id + '&description=' + desc + '&aufn_dat=' + dat;
	//alert("Parameter: "+params);
	var target = 'description';
	//alert(target);
	var myAjax = new Ajax.Updater(target,url,{method:'get', parameters: params});
}

//Fkt. zum Vorblenden der Fortschritts-Anzeige
function blende_aus()
{
	//alert("fertig");
	document.getElementById("blend").style.display="none";
}

function filmstreifen_geladen(response)
{
	$("filmstreifen").innerHTML = response.responseText;
	var ob = $("filmstreifen").getElementsByTagName("script");
	//alert(ob.length);
	for (var i = 0; i < ob.length; i++)
	{
		if (ob[i].innerHTML != null)
		{
			//alert(ob[i].innerHTML);
			eval(ob[i].innerHTML);
		}
	}
	blende_aus();
	getDetails( currImageState.picId, currImageState.baseFile, currImageState.mod, currImageState.formName );
}

function blende_ein()
{
	//alert("nicht fertig");
	document.getElementById("blend").style.display="block";
}

function changeWritable(lfdnr, checked, sr)
{
	var url = '../../share/change_writable.php';
	var params = 'lfdnr=' + lfdnr + '&checked=' + checked + '&sr=' + sr;
	//alert("Parameter: "+params);
	var target = lfdnr;
	//alert(target);
	var myAjax = new Ajax.Updater(target,url,{method:'get', parameters: params});
}

function changeViewable(lfdnr, checked, sr)
{
	var url = '../../share/change_viewable.php';
	var params = 'lfdnr=' + lfdnr + '&checked=' + checked + '&sr=' + sr;
	//alert("Parameter: "+params);
	var target = lfdnr;
	//alert(target);
	var myAjax = new Ajax.Updater(target,url,{method:'get', parameters: params});
}

function changeUserpermission(user_id, perm_id, checked, sr)
{
	var url = '../../share/change_userpermission.php';
	var params = 'user_id=' + user_id + '&perm_id=' + perm_id + '&checked=' + checked + '&sr=' + sr;
	//alert("Parameter: "+params);
	var target = perm_id;
	//alert(target);
	var myAjax = new Ajax.Updater(target,url,{method:'get', parameters: params});
}

function changeGrouppermission(group_id, perm_id, checked, sr)
{
	var url = '../../share/change_grouppermission.php';
	var params = 'group_id=' + group_id + '&perm_id=' + perm_id + '&checked=' + checked + '&sr=' + sr;
	//alert("Parameter: "+params);
	var target = perm_id;
	//alert(target);
	var myAjax = new Ajax.Updater(target,url,{method:'get', parameters: params});
}

function createKmlFile(kml_cod_statement,sr,mod,long_mittel,lat_mittel,radius)
{
	var url = '../../share/createKmlFile.php';
	var params = 'kml_cod_statement=' + kml_cod_statement + '&sr=' + sr + '&mod=' + mod + '&long_mittel=' + long_mittel + '&lat_mittel=' + lat_mittel + '&radius=' + radius;
	//alert("Parameter: "+params);
	var target = 'ge_icon';
	//alert(target);
	var myAjax = new Ajax.Updater(target,url,{method:'get', parameters: params, onCreate: blende_ein, onComplete: blende_aus});
}

function createPdfFile(pdf_statement,sr,mod,num6_1)
{
	var url = '../../share/createPdfFile.php';
	var params = 'pdf_statement=' + pdf_statement + '&sr=' + sr + '&mod=' + mod + '&num6_1=' + num6_1;
	//alert("Parameter: "+params);
	var target = 'pdf_icon';
	//alert(target);
	var myAjax = new Ajax.Updater(target,url,{method:'get', parameters: params, onCreate: blende_ein, onComplete: blende_aus});
}

function createNextKrit(number,kat,op,number1,kat1,op1,number2,kat2,op2,number3,kat3,op3,number4,kat4,op4,bewertung)
{
	var url = '../../share/createNextKrit.php';
	var params = 'number=' + number + '&kat=' + kat +'&op=' + op + '&number1=' + number1 + '&kat1=' + kat1 +'&op1=' + op1 + '&number2=' + number2 + '&kat2=' + kat2 +'&op2=' + op2 + '&number3=' + number3 + '&kat3=' + kat3 +'&op3=' + op3 + '&number4=' + number4 + '&kat4=' + kat4 +'&op4=' + op4 + '&bewertung=' + bewertung;
	//alert("Parameter: "+params);
	var target = 'parameter';
	//alert(target);
	var myAjax = new Ajax.Updater(target,url,{method:'get', parameters: params, onCreate: blende_ein, onComplete: blende_aus});
}

function manage_picture(pic_id, modus)
{
	//loescht oder behaelt vorgemerktes Bild im Archiv
	var url = '../../share/manage_picture.php';
	var params = 'pic_id=' + pic_id + '&modus=' + modus;
	//alert("Parameter: "+params);
	var target = 'picture'+pic_id;
	//alert(target);
	var myAjax = new Ajax.Updater(target,url,{method:'get', parameters: params});
}

function getUploadFiles(value)
{
	var url = '../../share/get_upload_files.php';	
	var client = new XMLHttpRequest();
	//client.open("GET", url, false);	//false erzwingt synchrone Datenuebertragung!!!
	client.open("GET", url, true);
	client.onreadystatechange = function()
								{
									if( client.readyState == 4 )
									{
										fileListReceived( client.responseText );
									}
								};

	client.send( null );
}
-->
</SCRIPT>