<script language='javascript'>

var divLargeImageElement;
var divFullscreenImageElement;
var currentPreviewImageIndex;
var imagePath;
var user_id = 0;

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

self.loadCurrentPreviewImage = function loadCurrentPreviewImage(imagePath)
{
  $('imgPreview').src = imagePath + "vorschau/hq-preview/" + imageArray[currentPreviewImageIndex].fileName + "_hq.jpg";
  $('labelPreviewFileName').innerHTML = imageArray[currentPreviewImageIndex].fileName + ".jpg";
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
  divFullscreenImageElement = document.createElement("divFullscreenImage");
  var h = 100;
  var w = 100;
	if (window.innerHeight)
	{
	  h = window.innerHeight;
	  w = window.innerWidth;
	} else if (document.documentElement && document.documentElement.clientHeight)
	{
	  h = document.documentElement.clientHeight;
	  w = document.documentElement.clientWidth;
	} else if (document.body)
	{
	  h = document.body.clientHeight;
	  w = document.body.clientWidth;
	}
	if ((w / h) < (imageArray[currentPreviewImageIndex].ratio))
	{
		h = w / imageArray[currentPreviewImageIndex].ratio; 
	}
	//alert(h);
  divFullscreenImageElement.innerHTML = 
	'<div id="divImageLarge" style="position:fixed; left:0px; top:0px; width:100%; height:100%; background-color:#333333; z-index:600; overflow: hidden;">'+
    //'<div id="divImageLarge" style="position:fixed; left:0px; top:0px; width:100%; height:100%; background-image:url(../../share/images/bg_trans_70.png); z-index:600;">'+
    '<center>'+
    //'<table border=0 width=100% height=100% cellspacing=0 cellpadding=0><tr><td>'+
    '<a href="javascript:hideFullscreenImage();">'+
    '<img id="imgFullscreen" src="' + imagePath + 'originale/' + imageArray[currentPreviewImageIndex].fileName + '.jpg" height='+h+' style="max-width:100%;" border=0>'+
    '</a>'+
    //'</td></tr></table>'+
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
  divLargeImageElement.innerHTML = 
	  '<div id="divImageLarge" style="position:fixed; left:0px; top:0px; width:100%; height:100%; background-color:#333333; z-index:500;">'+
    //'<div id="divImageLarge" style="position:fixed; left:0px; top:0px; width:100%; height:100%; background-image:url(../../share/images/bg_trans_70.png); z-index:500;">'+
      //'<div id="divInner1" style="position:absolute; width:100%; height:100%">'+
      '<table border=0 width="100%" height="100%">'+
      '<tr><td align=center valign=middle>'+
      '<table border=0><tr><td>'+
      
      '<table border=0 cellspacing=0>'+
      //'<tr><td colspan=2 style="background-color:#ffffff; margin:0px;" align=right>'+
    //'<div id="divInner2" style="z-index:1;">'+
      //'<div style="top:10px; right:0px; width:23px; height:23px; z-index:1;"><a href="javascript:hideFullscreenOverlay();"><img src="img/button_close.png" border=0></a></div>'+
      //'</td></tr>'+
      '<tr><td colspan=2 style="margin:0px;" align=center>'+
      '<div id="divOverlayContent" align=left style="margin:0px">' +
      '<img id="imgPreview" src="' + imagePath + 'vorschau/hq-preview/' + imageArray[currentPreviewImageIndex].fileName + '_hq.jpg">' +
      '' +
      '</div>'+
      //'</div>'+
      '</td></tr>'+
      '</table>'+
      
      '</td></tr></table>'+
      '</td></tr>'+
      '</table>'+
      '<div style="position:absolute; top:0px; width:100%; z-index:1000;"><center>'+
      '<table border=0 width=270px height=27px style="background-image:url(../../share/images/navtop.png)" cellpadding=0 cellspacing=0><tr>'+
      '<td width=27 valign=top>&nbsp;</td>'+
      '<td align=center valign=middle><font style="color:#ffffff; font-family:arial, Helvetica,sans-serif; font-size:12px;"><label id="labelPreviewFileName">' + imageArray[currentPreviewImageIndex].fileName + '.jpg</label></font></td>'+
      //'<td align=center valign=middle><font style="color:#ffffff; font-family:arial, Helvetica,sans-serif; font-size:12px;"><label id="labelPreviewFileName">' + imageArray[currentPreviewImageIndex].fileName + '.jpg</label></font></td>'+
      '<td width=27 valign=top><div id="divPreviewDownloadButton"></div></td>'+
      '</tr></table>'+
      '</center></div>'+
      '<div style="position:absolute; bottom:0px; width:100%; z-index:1000;"><center>'+
      '<table border=0 width=548px height=68px style="background-image:url(../../share/images/navbg.png)" cellpadding=0 cellspacing=0><tr>'+
      '<td align=center valign=middle width=70><a href="javascript:showFullscreenImage(' + "'" + imagePath + "'" + ');"><img src="../../share/images/fullscreen.png" border=0></a></td>'+
      '<td align=center valign=middle></td>'+
      '<td align=center valign=middle width=40><a href="javascript:firstImage(' + "'" + imagePath + "'" + ');"><img id="imgNavFirst" src="../../share/images/first.png" border=0></a></td>'+
      '<td align=center valign=middle width=40><a href="javascript:prevStepImage(' + "'" + imagePath + "'" + ');"><img id="imgNavPrevStep" src="../../share/images/prevstep.png" border=0></a></td>'+
      '<td align=center valign=middle width=20></td>'+
      '<td align=center valign=middle width=60><a href="javascript:prevImage(' + "'" + imagePath + "'" + ');"><img id="imgNavPrev" src="../../share/images/prev.png" border=0></a></td>'+
      '<td align=center valign=middle width=10></td>'+
      '<td align=center valign=middle width=60><a href="javascript:nextImage(' + "'" + imagePath + "'" + ');"><img id="imgNavNext" src="../../share/images/next.png" border=0></a></td>'+
      '<td align=center valign=middle width=20></td>'+
      '<td align=center valign=middle width=40><a href="javascript:nextStepImage(' + "'" + imagePath + "'" + ');"><img id="imgNavNextStep" src="../../share/images/nextstep.png" border=0></a></td>'+
      '<td align=center valign=middle width=40><a href="javascript:lastImage(' + "'" + imagePath + "'" + ');"><img id="imgNavLast" src="../../share/images/last.png" border=0></a></td>'+
      '<td align=center valign=middle></td>'+
      '<td align=center valign=middle width=70><a href="javascript:hideFullscreenOverlay(gotoFilmstreifenPosition);"><img src="../../share/images/close.png" border=0></a></td>'+
      '</tr></table>'+
      '</center></div>'+
      //'</div>'+
    '</div>';
  document.body.appendChild(divLargeImageElement);
  //$("divOverlayContent").innerHTML = '<img src=../img/symbols/ajax-loader.gif>';
  //new Ajax.Updater($("divOverlayContent"), filename, {method: "get"});
}

self.openPreview = function openPreview(newImagePath, getImageArrayCallback, initialFileName)
{
  //user_id = newUser_id;
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

</script>