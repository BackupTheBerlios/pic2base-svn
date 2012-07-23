<HTML>
<HEAD>
<TITLE>Ajax-Testseite</TITLE>
<script type="text/javascript" src="../../ajax/inc/prototype.js"></script>
</HEAD>

<script>
	var fileList = null;
	function fileListReceived( responseText )
	{
		//alert( responseText );
		fileList = JSON.parse( responseText, null );
		processFile( fileList );
	}

	function processFile( fileList )
	{
		//alert( 1 );
		var client = new XMLHttpRequest();
		//alert( fileList.file_array.length );
		client.open("GET", "http://192.168.2.30/pic2base/bin/html/erfassung/stapel2_action.php?file=" + fileList.file_array[0], true);
		alert( "Zeile 22: processing " + fileList.file_array[0] );
		fileList.file_array.splice( 0, 1 );
		alert( "Zeile 24: naechstes Element in der Dateiliste: " + fileList.file_array[0] );
		client.onreadystatechange = function(){
			//if( client.status == 200)
			if( client.readyState == 4 )
			{
				alert( "ClientReadyState: " + client.readyState );
				alert( "ResponseText: " + client.responseText );
				var result = JSON.parse( client.responseText );
				alert( "geparste Client-Response: " + result );
				
				if( result.errorCode != 0 )
				{
					alert( "Fehler" );
				}
				else
				{
					alert( "Erfolg" );
				}
						
				if( fileList.file_array.length > 0 )
				{
					alert("Daeilistenlaenge: " + fileList.file_array.lenght);
					processFile( fileList );
				}
				else
				{
					alert("shit");
				}
			}
			else
			{
				alert( "Status: " + client.readyState );
			}
		};
		alert( "Zeile 57: Bisher keine Reaktion auf client.onreadystatechange..." );
		client.send( null );
	}
</script>

<BODY onLoad='showFiles()'>

<?php
include '../../share/global_config.php';
include $sr.'/bin/share/functions/ajax_functions.php';
//echo "<div id='text'></div>";

?>

<script type="text/javascript">

</script>

<!--<input type='button' value='Datei-Liste anzeigen' onclick='alert(document.getElementById("new_value").value)'><br><br>-->
<!--<input type='button' value='Datei-Liste anzeigen' onclick='showFiles()'>-->

</BODY>
</HTML>