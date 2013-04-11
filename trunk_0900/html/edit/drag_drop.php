<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
  <title>Drag&Drop-Test</title>
  <meta name="GENERATOR" content="eclipse">
  <meta name="AUTHOR" content="k. henneberg">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <!-- <link rel=stylesheet type='text/css' href='../../css/format2.css'>-->
  
  	<style>
/*	#draggable { border:solid; width: 150px; height: 150px; padding: 0.5em; }*/
	#container { border:solid; width: 600px; height: 600px; padding: 0.5em; }
	
	#draggable { border:solid; width: 150px; height: 150px; padding: 0.5em; float: left; margin: 10px 10px 10px 0; background-color: green;}
	.droppable { border:solid; width: 150px; height: 150px; padding: 0.5em; float: left; margin: 10px; background-color: grey;}
	
	</style>
	
  <link rel="shortcut icon" href="../../share/images/favicon.ico">
  <script language="JavaScript" src="../../share/functions/resize_elements.js"></script>
  <script language="JavaScript" src="../../share/functions/jquery-1.8.2.min.js"></script>
  <script language="JavaScript" src="../../share/functions/jquery-ui.min.js"></script>

   
   	<script>
//		$(function() {
//		$( "#draggable" ).draggable({
		//	grid: [20,20],
//			cursor: "move",
//			snap: "#container",
//			containment: "#container"
//			});
//		});


		$( drag );
		$( drop );
		
		 function drag() 
		 {
		 	$( "#draggable" ).draggable
			 	({
					revert: "invalid",
					opacity: 0.7,
					//stack: "#draggable",
					cursor: "move",
					snap:".droppable",
					snapMode: "inner"
				});
		 }
		 //Dropzone erstellen
		 function drop() 
		 {
		 	$( ".droppable" ).droppable
		 	({
				accept: "#draggable",
			 	hoverClass: "hovered",
			 	drop: positioning
		 	});
		 }

		// Positionieren wenn erfolgreich gedroppt
		 function positioning( event, ui ) 
		 {
		 	position = $(this).position();
		 	ui.draggable.animate(
				{
				   opacity: 1,
				   top: position.top,
				   left: position.left
			   	}, 200
		   	);
		 }
	</script>
  
  
</head>

<body>

	<div id="container">

		<div id="draggable">
		<p>Drag me rum!</p>
		</div>
		
		<div class="droppable">
		<p>Drop here</p>
		</div>
		
		<div class="droppable">
		<p>Drop here</p>
		</div>
		
	</div>

</body>
</html>