<script language='javascript'>

var divLargeImageElement;
var divFullscreenImageElement;
var currentPreviewImageIndex;
var imagePath;
var user_id = 0;
var statusFlag = 0; // 0 - F wechselt von klein zu Vollbild, 1 - F wechselt von Vollbild zurueck zu klein
var n = 0;

self.updateNavImages = function updateNavImages()
{
  if (currentPreviewImageIndex <= 0)
  {
    $('imgNavFirst').src = '../../share/images/first_disabled.png';
    $('imgNavPrevStep').src = '../../share/images/prevstep_disabled.png';
    $('imgNavPrev').src = '../../share/images/prev_disabled.png';
  }
  else
  {
    $('imgNavFirst').src = '../../share/images/first.png';
    $('imgNavPrevStep').src = '../../share/images/prevstep.png';
    $('imgNavPrev').src = '../../share/images/prev.png';
  }
  
  if (currentPreviewImageIndex >= imageArray.length - 1)
  {
    $('imgNavLast').src = '../../share/images/last_disabled.png';
    $('imgNavNextStep').src = '../../share/images/nextstep_disabled.png';
    $('imgNavNext').src = '../../share/images/next_disabled.png';
  }
  else
  {
    $('imgNavLast').src = '../../share/images/last.png';
    $('imgNavNextStep').src = '../../share/images/nextstep.png';
    $('imgNavNext').src = '../../share/images/next.png';
  }
}
//alert(currentPreviewImageIndex);
self.loadCurrentPreviewImage = function loadCurrentPreviewImage(imagePath)
{
  $('imgPreview').src = imagePath + "vorschau/hq-preview/" + imageArray[currentPreviewImageIndex].fileName + "_hq.jpg";
  $('labelPreviewFileName').innerHTML = imageArray[currentPreviewImageIndex].fileName + ".jpg";
  $('labelPreviewFileNumber').innerHTML = currentPreviewImageIndex + 1;
  /*switch (imageArray[currentPreviewImageIndex].downloadStatus)
  {
    case 0:
	{
	  $('imagePreviewDownload').src = '../../share/images/download_disabled.png';
    }
    break;
    case 1:
	{
	  $('imagePreviewDownload').src = '../../share/images/download.png';
    }
    break;
    case 2:
	{
	  $('imagePreviewDownload').src = '../../share/images/download.png';
    }
    break;
    case 100:
	{
	  $('imagePreviewDownload').src = '../../share/images/downloaded.png';
    }
    break;
  }*/
  //$('divPreviewDownloadButton');
  var url = '../../share/picture_info.php';
  var params = 'FileName=' + imageArray[currentPreviewImageIndex].fileName + '.jpg' + '&c_username=' + c_username + '&pic_id=' + imageArray[currentPreviewImageIndex].id + '&inforequest=DownloadStatusIconPreview&Owner=' + imageArray[currentPreviewImageIndex].Owner + '&user_id=' + user_id;
  var myAjax = new Ajax.Updater('divPreviewDownloadButton', url,{method:'get', parameters: params});
}

self.downloadButtonPressed = function downloadButtonPressed(FileName, c_username, pic_id, downloadStatus)
{
	switch (downloadStatus)
	{
		case 1:
		{
			copyPicture(FileName, c_username, pic_id, true);
		}
		break;
		case 100:
		{
			delPicture(FileName, c_username, pic_id, true);
		}
		break;
	}
	loadCurrentPreviewImage(imagePath);
}

self.firstImage = function firstImage(imagePath)
{
  currentPreviewImageIndex = 0;
  updateNavImages();
  loadCurrentPreviewImage(imagePath);
}

self.lastImage = function lastImage(imagePath)
{
  currentPreviewImageIndex = imageArray.length - 1;
  updateNavImages();
  loadCurrentPreviewImage(imagePath);
}

self.nextImage = function nextImage(imagePath)
{
  currentPreviewImageIndex++;
  if (currentPreviewImageIndex > imageArray.length - 1)
  {
    currentPreviewImageIndex = imageArray.length - 1;
  }
  updateNavImages();
  loadCurrentPreviewImage(imagePath);
  n = currentPreviewImageIndex;
}

self.nextStepImage = function nextStepImage(imagePath)
{
  currentPreviewImageIndex += 10;
  if (currentPreviewImageIndex > imageArray.length - 1)
  {
    currentPreviewImageIndex = imageArray.length - 1;
  }
  updateNavImages();
  loadCurrentPreviewImage(imagePath);
}

self.prevImage = function prevImage(imagePath)
{
  currentPreviewImageIndex--;
  if (currentPreviewImageIndex < 0)
  {
    currentPreviewImageIndex = 0;
  }
  updateNavImages();
  loadCurrentPreviewImage(imagePath);
}

self.prevStepImage = function prevStepImage(imagePath)
{
  currentPreviewImageIndex -= 10;
  if (currentPreviewImageIndex < 0)
  {
    currentPreviewImageIndex = 0;
  }
  updateNavImages();
  loadCurrentPreviewImage(imagePath);
}

self.hideFullscreenImage = function hideFullscreenImage()
{
  document.body.removeChild(divFullscreenImageElement);
}

self.showFullscreenImage = function showFullscreenImage(imagePath)
{
	
	var image = imageArray[currentPreviewImageIndex].fileName + ".jpg";
//	alert(image);
	
	  divFullscreenImageElement = document.createElement("divFullscreenImage");
  var h = 100;
  var w = 100;
	if (window.innerHeight)
	{
	  h = window.innerHeight;
	  w = window.innerWidth;
	} 
	else if (document.documentElement && document.documentElement.clientHeight)
	{
	  h = document.documentElement.clientHeight;
	  w = document.documentElement.clientWidth;
	} 
	else if (document.body)
	{
	  h = document.body.clientHeight;
	  w = document.body.clientWidth;
	}
	if ((w / h) < (imageArray[currentPreviewImageIndex].ratio))
	{
		h = w / imageArray[currentPreviewImageIndex].ratio; 
	}
	//alert(h);
	
	//Kontrolle, ob gedrehtes Vorschabild verwendet werden kann:
	var result = "default";
	result = jQuery.ajax({
	type: 'POST',
	async: false,   // WICHTIG!
	url: '../../share/check_rotated_preview.php',
	data: ({
		filename: image,
	})
	}).responseText;
//	alert(result);
	
  divFullscreenImageElement.innerHTML = 
	'<div id="divImageLarge" style="position:fixed; left:0px; top:0px; width:100%; height:100%; background-color:#333333; z-index:600; overflow: hidden;">'+
    '<center>'+
    '<a href="javascript:hideFullscreenImage()" title=\"[Mausklick schlie&szlig;t Fenster!]\";>'+
    '<img id="imgFullscreen" src="' + imagePath + result + imageArray[currentPreviewImageIndex].fileName + '.jpg" height='+h+' style="max-width:100%; border-radius:15px;" border=0>'+
    '</a>'+
    '</center>'+
    '</div>';
  document.body.appendChild(divFullscreenImageElement);
}

self.hideFullscreenOverlay = function hideFullscreenOverlay(updateFilmstreifenCallback)
{
  updateFilmstreifenCallback(currentPreviewImageIndex, imageArray[currentPreviewImageIndex].id);
  document.body.removeChild(divLargeImageElement);
}

self.showFullscreenOverlay = function showFullscreenOverlay(imagePath, imageArray)
{
  divLargeImageElement = document.createElement("divLargeImage");
  //alert("<?php echo $_SERVER['SCRIPT_FILENAME']; ?>");
  //alert(currentPreviewImageIndex);
  divLargeImageElement.innerHTML = 
	  '<div id="divImageLarge" style="position:fixed; left:0px; top:0px; width:100%; height:100%; background-color:#333333; z-index:500;">'+
      '<table border=0 width="100%" height="100%">'+
      '<tr><td align=center valign=middle>'+
      '<table border=0><tr><td>'+
      
      '<table border=0 cellspacing=0>'+
      '<tr><td colspan=2 style="margin:0px;" align=center>'+
      '<div id="divOverlayContent" align=left style="margin:0px">' +
      '<img id="imgPreview" src="' + imagePath + 'vorschau/hq-preview/' + imageArray[currentPreviewImageIndex].fileName + '_hq.jpg" style=\'border-radius:12px;\'>' +
      '' +
      '</div>'+
      '</td></tr>'+
      '</table>'+
      
      '</td></tr></table>'+
      '</td></tr>'+
      '</table>'+
      '<div style="position:absolute; top:0px; width:100%; z-index:1000;"><center>'+
      '<table border=0 width=270px height=27px style="background-image:url(../../share/images/navtop.png)" cellpadding=0 cellspacing=0><tr>'+
      '<td width=27 valign=top>&nbsp;</td>'+
      '<td align=center valign=middle><font style="color:#ffffff; font-family:arial, Helvetica,sans-serif; font-size:12px;">Datei <label id="labelPreviewFileName">' + imageArray[currentPreviewImageIndex].fileName + '.jpg</label> - Bild <label id="labelPreviewFileNumber">' + currentPreviewImageIndex + '</label> von ' + imageArray.length + '</font></td>'+
      '<td width=27 valign=top><!--<div id="divPreviewDownloadButton"></div>--></td>'+
      '</tr></table>'+
      '</center></div>'+
      '<div style="position:absolute; bottom:0px; width:100%; z-index:1000;"><center>'+
      '<table border=0 width=548px height=68px style="background-image:url(../../share/images/navbg.png)" cellpadding=0 cellspacing=0><tr>'+
      '<td align=center valign=middle width=70><a href="javascript:showFullscreenImage(' + "'" + imagePath + "'" + ');" title="zur Vollbild-Ansicht (F)"><img src="../../share/images/fullscreen.png" border=0></a></td>'+
      '<td align=center valign=middle></td>'+
      '<td align=center valign=middle width=40><a href="javascript:firstImage(' + "'" + imagePath + "'" + ');" title="zum ersten Bild (Pos1-Taste)"><img id="imgNavFirst" src="../../share/images/first.png" border=0></a></td>'+
      '<td align=center valign=middle width=40><a href="javascript:prevStepImage(' + "'" + imagePath + "'" + ');" title="zehn Bilder zur&uuml;ck (Pfeil-Unten-Taste)"><img id="imgNavPrevStep" src="../../share/images/prevstep.png" border=0></a></td>'+
      '<td align=center valign=middle width=20></td>'+
      '<td align=center valign=middle width=60><a href="javascript:prevImage(' + "'" + imagePath + "'" + ');" title="zum vorigen Bild (Pfeil-Links-Taste)"><img id="imgNavPrev" src="../../share/images/prev.png" border=0></a></td>'+
      '<td align=center valign=middle width=10></td>'+
      '<td align=center valign=middle width=60><a href="javascript:nextImage(' + "'" + imagePath + "'" + ');" title="zum n&auml;chsten Bild (Leertaste oder Pfeil-Rechts-Taste)"><img id="imgNavNext" src="../../share/images/next.png" border=0></a></td>'+
      '<td align=center valign=middle width=20></td>'+
      '<td align=center valign=middle width=40><a href="javascript:nextStepImage(' + "'" + imagePath + "'" + ');" title="zehn Bilder vor (Pfeil-Oben-Taste)"><img id="imgNavNextStep" src="../../share/images/nextstep.png" border=0></a></td>'+
      '<td align=center valign=middle width=40><a href="javascript:lastImage(' + "'" + imagePath + "'" + ');" title="zum letzten Bild (Ende-Taste)"><img id="imgNavLast" src="../../share/images/last.png" border=0></a></td>'+
      '<td align=center valign=middle></td>'+
      '<td align=center valign=middle width=70><a href="javascript:hideFullscreenOverlay(gotoFilmstreifenPosition);" title="Vorschau schlie&szlig;en (F10)"><img src="../../share/images/close.png" border=0></a></td>'+
      '</tr></table>'+
      '</center></div>'+
    '</div>';
  document.body.appendChild(divLargeImageElement);
}

self.openPreview = function openPreview(newImagePath, getImageArrayCallback, initialFileName)
{
  imagePath = newImagePath;
  imageArray = getImageArrayCallback();
  if (imageArray.length > 0)
  {
    currentPreviewImageIndex = 0;
    if (initialFileName != '')
    {
        for (var i = 0; i < imageArray.length; i++)
        {
            if (imageArray[i].fileName + '.jpg' == initialFileName)
            {
                currentPreviewImageIndex = i;
                break;
            }
        }
    }
    showFullscreenOverlay(imagePath, imageArray);
    loadCurrentPreviewImage(imagePath);
    updateNavImages();
	}
}

self.keyPressed = function keyPressed(Ereignis, currentPreviewImageIndex) 
{
  if (!Ereignis)
    Ereignis = window.event;
  if (Ereignis.which) 
  {
    Tastencode = Ereignis.which;
    if(Ereignis.which == 35)
    {
    	//alert("Ende");
    	if(statusFlag == 0)
    	{
    		lastImage(imagePath);
    	}
    	else if(statusFlag == 1)
    	{
    		hideFullscreenImage();
    		statusFlag = 0;
    		lastImage(imagePath);
    	}
    }
    else if(Ereignis.which == 36)
    {
    	//alert("Pos 1");
    	if(statusFlag == 0)
    	{
    		firstImage(imagePath);
    	}
    	else if(statusFlag == 1)
    	{
    		hideFullscreenImage();
    		statusFlag = 0;
    		firstImage(imagePath);
    	}
    }
    else if(Ereignis.which == 37)
    {
    	//alert("eins zurueck");
    	if(statusFlag == 0)
    	{
    		prevImage(imagePath);
    	}
    	else if(statusFlag == 1)
    	{
    		hideFullscreenImage();
    		statusFlag = 0;
    		prevImage(imagePath);
    	}
    }
    else if(Ereignis.which == 39 || Ereignis.which == 32)
    {
    	//alert("eins vor" + imagePath);
    	if(statusFlag == 0)
    	{	
    		nextImage(imagePath);
    	}
    	else if(statusFlag == 1)
    	{
    		hideFullscreenImage();
    		statusFlag = 0;
    		nextImage(imagePath);
    	}
    }
    else if(Ereignis.which == 38)
    {
    	//alert("10 vor");
    	if(statusFlag == 0)
    	{
    		nextStepImage(imagePath);
    	}
    	else if(statusFlag == 1)
    	{
    		hideFullscreenImage();
    		statusFlag = 0;
    		nextStepImage(imagePath);
    	}
    }
    else if(Ereignis.which == 40)
    {
    	//alert("10 zurueck");
    	if(statusFlag == 0)
    	{
    		prevStepImage(imagePath);
    	}
    	else if(statusFlag == 1)
    	{
    		hideFullscreenImage();
    		statusFlag = 0;
    		prevStepImage(imagePath);
    	}
    }
    else if(Ereignis.which == 121)
    {
    	//alert("abbrechen");
    	if(statusFlag == 0)
    	{
    		hideFullscreenOverlay(gotoFilmstreifenPosition);
    	}
    	else if(statusFlag == 1)
    	{
    		hideFullscreenImage();
    		statusFlag = 0;
    		hideFullscreenOverlay(gotoFilmstreifenPosition);
    	}
    }
    else if(Ereignis.which == 70)
    {
    	//alert("fullscreen");
    	if(statusFlag == 0)
    	{
    		showFullscreenImage(imagePath);
    		statusFlag = 1;
    	}
    	else if(statusFlag == 1)
    	{
    		hideFullscreenImage();
    		statusFlag = 0;
    	}
    }
  } 
  else if (Ereignis.keyCode) 
  {
    Tastencode = Ereignis.keyCode;
    if(Ereignis.keyCode == 35)
    {
    	//alert("Ende");
    	lastImage(imagePath);
    }
    else if(Ereignis.keyCode == 36)
    {
    	//alert("Pos 1");
    	firstImage(imagePath);
    }
    else if(Ereignis.keyCode == 37)
    {
    	//alert("eins zurueck");
    	prevImage(imagePath);
    }
    else if(Ereignis.keyCode == 39 || Ereignis.keyCode == 32)
    {
    	//alert("eins vor");
    	nextImage(imagePath);
    }
    else if(Ereignis.keyCode == 38)
    {
    	//alert("10 vor");
    	nextStepImage(imagePath);
    }
    else if(Ereignis.keyCode == 40)
    {
    	//alert("10 zurueck");
    	prevStepImage(imagePath);
    }
    else if(Ereignis.keyCode == 27)
    {
    	//alert("abbrechen");
    	hideFullscreenOverlay(gotoFilmstreifenPosition);
    }
    else if(Ereignis.keyCode == 70)
    {
    	//alert("fullscreen");
    	if(statusFlag == 0)
    	{
    		showFullscreenImage(imagePath);
    		statusFlag = 1;
    	}
    	else if(statusFlag == 1)
    	{
    		hideFullscreenImage();
    		statusFlag = 0;
    	}
    }
  }
}
document.onkeydown = keyPressed;

</script>