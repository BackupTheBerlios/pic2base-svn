<p style="margin:120px 0px; text-align:center">Willkommen im Admin-Bereich!<BR><BR></BR>
W&auml;hlen Sie bitte aus der linken Leiste die gew&uuml;nschte Aktion aus.</p>
<?php
include '../../share/global_config.php';
$install_file = '../../share/db_check1.php';
@$fh = fopen($install_file, 'r');
IF($fh)
{
	IF(fclose($fh) AND @unlink($install_file))
	{
		echo "<center>Installations-Hilfsskript wurde entfernt.</center>";
	}
}

?>
