<html><head><title>Test</title>
<script type="text/javascript">
function TasteGedrueckt (Ereignis) 
{
  if (!Ereignis)
    Ereignis = window.event;
  if (Ereignis.which) 
  {
    Tastencode = Ereignis.which;
    if(Ereignis.which == 37)
    {
    	alert("eins zurueck");
    }
    else if(Ereignis.which == 39)
    {
    	alert("eins vor");
    }
    else if(Ereignis.which == 38)
    {
    	alert("10 vor");
    }
    else if(Ereignis.which == 40)
    {
    	alert("10 zurueck");
    }
  } 
  else if (Ereignis.keyCode) 
  {
    Tastencode = Ereignis.keyCode;
    if(Ereignis.keyCode == 37)
    {
    	alert("eins zurueck");
    }
    else if(Ereignis.keyCode == 39)
    {
    	alert("eins vor");
    }
    else if(Ereignis.keyCode == 38)
    {
    	alert("10 vor");
    }
    else if(Ereignis.keyCode == 40)
    {
    	alert("10 zurueck");
    }
  }
  document.formular.ausgabe.value = "Taste mit Dezimalwert " + Tastencode + " gedrueckt";
}
<!--
function TasteLosgelassen (Ereignis) 
{
  if (!Ereignis)
    Ereignis = window.event;
  if (Ereignis.which) 
  {
    Tastencode = Ereignis.which;
  } 
  else if (Ereignis.keyCode) 
  {
    Tastencode = Ereignis.keyCode;
  }
  //document.formular.ausgabe.value = "Taste mit Dezimalwert " + Tastencode + " losgelassen";
}
-->

document.onkeydown = TasteGedrueckt;
//document.onkeyup = TasteLosgelassen;
</script>
</head><body>

<form action="" name="formular">
<p><input type="text" name="ausgabe" readonly="readonly" size="50"></p>
</form>


</body></html>
