<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>phatfusion : slider</title>

<link rel="stylesheet" href="../_common/css/main.css" type="text/css"
	media="all">
<link href="slider.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="../_common/js/mootools.js"></script>
<script type="text/javascript" src="slider.js"></script>

</head>
<body>
<div id="container">

<h3 class="example">example</h3>
<div id="example">
<div id="slideContainer1">
<div id="slideHandle1"></div>
</div>
<div id="pos1"></div>

<script type="text/javascript">
			
			window.addEvent('domready', function()
			{
				var slider1 = new Slider
				('slideContainer1', 'slideHandle1',
					{
						onComplete: function(val)
						{
							$('pos1').setHTML(val);
						}
					}
				);
			}
			);
		</script></div>
</div>
</body>
</html>
