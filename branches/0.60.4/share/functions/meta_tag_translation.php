<?php

@include 'global_config.php';
include $sr.'/bin/share/db_connect1.php';
//include $sr.'/bin/share/functions/main_functions.php';
//include $sr.'/bin/share/functions/permissions.php';

unset($c_username);
IF ($_COOKIE['login'])
{
	list($c_username) = preg_split('#,#',$_COOKIE['login']);
}

if( array_key_exists('tag',$_REQUEST) )
{
	$tag = $_REQUEST['tag'];
	//echo "Meta-Tag: ".$tag;
}
ELSE
{
	//echo "Tag existiert nicht<BR>";
}

function translateMetaTag($tag,$lang)
{
	SWITCH($lang)
	{
		CASE 'de':
		SWITCH($tag)
		{
			CASE 'FileName':
				$trans_tag = 'interner Dateiname';
			break;
			
			CASE 'ExifToolVersion':
				$trans_tag = 'Exif-Version';
			break;
			
			CASE 'FileSize':
				$trans_tag = 'Datei-Gr&ouml;&szlig;e';
			break;
			
			CASE 'FileModifyDate':
				$trans_tag = 'Bearbeitungs-Datum (FMD)';
			break;
			
			CASE 'FilePermissions':
				$trans_tag = 'Datei-Rechte';
			break;
			
			CASE 'FileType':
				$trans_tag = 'Datei-Typ';
			break;
			
			CASE 'MIMEType':
				$trans_tag = 'MIME-Typ';
			break;
			
			CASE 'ExifByteOrder':
				$trans_tag = 'Exif-Byte-Abfolge';
			break;
			
			CASE 'CurrentIPTCDigest ':
				$trans_tag = '';
			break;
			
			CASE 'Make':
				$trans_tag = 'Kamera-Hersteller';
			break;
			
			CASE 'Model':
				$trans_tag = 'Kamera-Modell';
			break;
			
			CASE 'Software':
				$trans_tag = 'Software-Version';
			break;
			
			CASE 'ModifyDate':
				$trans_tag = 'Modifizierungs-Datum';
			break;
			
			CASE 'JpgFromRawStart':
				$trans_tag = 'Beginn des JPGs';
			break;
			
			CASE 'JpgFromRawLength':
				$trans_tag = 'L&auml;nge des JPGs';
			break;
			
			CASE 'YCbCrPositioning':
				$trans_tag = 'YCbCr-Positionierung';
			break;
			
			CASE 'SubfileType':
				$trans_tag = 'Unterdatei-Typ';
			break;
			
			CASE 'ImageWidth':
				$trans_tag = 'Bild-Breite [px]';
			break;
			
			CASE 'ImageHeight':
				$trans_tag = 'Bild-H&ouml;he [px]';
			break;
			
			CASE 'BitsPerSample':
				$trans_tag = 'Farbtiefe [Bit pro Kanal]';
			break;
			
			CASE 'Compression':
				$trans_tag = 'Kompression';
			break;
			
			CASE 'PhotometricInterpretation':
				$trans_tag = 'Photometrische Interpretation';
			break;
			
			CASE 'StripOffsets':
				$trans_tag = 'Streifen-Versatz';
			break;
			
			CASE 'Orientation':
				$trans_tag = 'Bild-Ausrichtung';
			break;
			
			CASE 'SamplesPerPixel':
				$trans_tag = 'Abtastungen pro Bildpunkt';
			break;
			
			CASE 'RowsPerStrip':
				$trans_tag = 'Spalten pro Streifen';
			break;
			
			CASE 'StripByteCounts':
				$trans_tag = 'Byte pro Streifen';
			break;
			
			CASE 'XResolution':
				$trans_tag = 'X-Aufl&ouml;sung';
			break;
			
			CASE 'YResolution':
				$trans_tag = 'Y-Aufl&ouml;sung';
			break;
			
			CASE 'PlanarConfiguration':
				$trans_tag = 'Speicherung der Pixelkomponenten';
			break;
			
			CASE 'ResolutionUnit':
				$trans_tag = 'Ma&szlig;einheit';
			break;
			
			CASE 'CFARepeatPatternDim':
				$trans_tag = 'Farb-Filter-Muster-Abmessungen';
			break;
			
			CASE 'CFAPattern2':
				$trans_tag = 'Farb-Filter-Muster';
			break;
			
			CASE 'ReferenceBlackWhite':
				$trans_tag = 'SW-Referenz';
			break;
			
			CASE 'ExposureTime':
				$trans_tag = 'Belichtungszeit [s]';
			break;
			
			CASE 'FNumber':
				$trans_tag = 'Blende';
			break;
			
			CASE 'ExposureProgram':
				$trans_tag = 'Belichtungs-Programm';
			break;
			
			CASE 'CreateDate':
				$trans_tag = 'Digitalisierungs-Datum';
			break;
			
			CASE 'ExposureCompensation':
				$trans_tag = 'Belichtungs-Korrektur';
			break;
			
			CASE 'MaxApertureValue':
				$trans_tag = 'max. Blende';
			break;
			
			CASE 'MeteringMode':
				$trans_tag = 'Belichtungs-Messmethode';
			break;
			
			CASE 'Flash':
				$trans_tag = 'Blitz';
			break;
			
			CASE 'FocalLength':
				$trans_tag = 'Brennweite';
			break;
			
			CASE 'UserComment':
				$trans_tag = 'Kommentar';
			break;
			
			CASE 'SubSecTime':
				$trans_tag = 'Genauigkeit der Originalzeit';
			break;
			
			CASE 'SubSecTimeOriginal':
				$trans_tag = 'Genauigkeit der Aufnahmezeit';
			break;
			
			CASE 'SubSecTimeDigitized':
				$trans_tag = 'Genauigkeit der Digitalisierungszeit';
			break;
			
			CASE 'SensingMethod':
				$trans_tag = 'Sensor-Typ';
			break;
			
			CASE 'FileSource':
				$trans_tag = 'Datei-Quelle';
			break;
			
			CASE 'SceneType':
				$trans_tag = 'Szenen-Typ';
			break;
			
			CASE 'CustomRendered':
				$trans_tag = 'Benutzerdefinierte Bildbearbeitung';
			break;
			
			CASE 'ExposureMode':
				$trans_tag = 'Belichtungs-Modus';
			break;
			
			CASE 'DigitalZoomRatio':
				$trans_tag = 'Digital-Zoom (Faktor)';
			break;
			
			CASE 'FocalLengthIn35mmFormat':
				$trans_tag = 'Brennweite (35mm)';
			break;
			
			CASE 'SceneCaptureType':
				$trans_tag = 'Szenen-Aufnahmetyp';
			break;
			
			CASE 'GainControl':
				$trans_tag = 'Verst&auml;rkungs-Regelung';
			break;
			
			CASE 'Contrast':
				$trans_tag = 'Kontrast';
			break;
			
			CASE 'Saturation':
				$trans_tag = 'S&auml;ttigung';
			break;
			
			CASE 'Sharpness':
				$trans_tag = 'Sch&auml;rfe';
			break;
			
			CASE 'SubjectDistanceRange':
				$trans_tag = 'Motiventfernung';
			break;
			
			CASE 'DateTimeOriginal':
				$trans_tag = 'Aufnahme-Datum (DTO)';
			break;
			
			CASE 'TIFF-EPStandardID':
				$trans_tag = 'TIFF-EP-Standard-ID';
			break;
			
			CASE 'Keywords':
				$trans_tag = 'Stichworte';
			break;
			
			CASE 'MakerNotes':
				$trans_tag = 'Hersteller-Angaben';
			break;
			
			CASE 'ApplicationRecordVersion':
				$trans_tag = 'Version der Aufnahme-Software';
			break;
			
			CASE 'MakerNoteVersion':
				$trans_tag = 'Version der Hersteller-Angaben';
			break;
			
			CASE 'ISO':
				$trans_tag = 'Filmempfindlichkeit (ISO)';
			break;
			
			CASE 'Quality':
				$trans_tag = 'Bild-Qualit&auml;t';
			break;
			
			CASE 'WhiteBalance':
				$trans_tag = 'Wei&szlig;abgleich';
			break;
			
			CASE 'FocusMode':
				$trans_tag = 'Fokus-Modus';
			break;
			
			CASE 'FlashSetting':
				$trans_tag = 'Blitz-Einstellung';
			break;
			
			CASE 'FlashType':
				$trans_tag = 'Blitz-Typ';
			break;
			
			CASE 'WhiteBalanceFineTune':
				$trans_tag = 'Wei&szlig;abgleich - Feinkorrektur';
			break;
			
			CASE 'ProgramShift':
				$trans_tag = 'Programm-Abweichung';
			break;
			
			CASE 'ExposureDifference':
				$trans_tag = 'Belichtungs-Abweichung';
			break;
			
			CASE 'PreviewImageStart':
				$trans_tag = 'Vorschaubild - Start';
			break;
			
			CASE 'PreviewImageLength':
				$trans_tag = 'Vorschaubild - L&auml;nge';
			break;
			
			CASE 'ISOSetting':
				$trans_tag = 'ISO-Einstellungen';
			break;
			
			CASE 'FlashExposureComp':
				$trans_tag = 'FlashExposureComp';
			break;
			
			CASE 'FlashExposureBracketValue':
				$trans_tag = 'FlashExposureBracketValue';
			break;
			
			CASE 'ExposureBracketValue':
				$trans_tag = 'Mehrfachbelichtungen';
			break;
			
			CASE 'ToneComp':
				$trans_tag = 'ToneComp';
			break;
			
			CASE 'LensType':
				$trans_tag = 'Objektiv-Typ';
			break;
			
			CASE 'Lens':
				$trans_tag = 'Objektiv';
			break;
			
			CASE 'FlashMode':
				$trans_tag = 'Blitz-Modus';
			break;
			
			CASE 'AFAreaMode':
				$trans_tag = 'AF-Messbereich';
			break;
			
			CASE 'AFPoint':
				$trans_tag = 'AF-Messfeld';
			break;
			
			CASE 'AFPointsInFocus':
				$trans_tag = 'Messfeld mit Fokus';
			break;
			
			CASE 'ShootingMode':
				$trans_tag = 'Aufnahme-Modus';
			break;
			
			CASE 'ContrastCurve':
				$trans_tag = 'Kontrast-Kurve';
			break;
			
			CASE 'ColorHue':
				$trans_tag = 'Farbton';
			break;
			
			CASE 'LightSource':
				$trans_tag = 'Licht-Quelle';
			break;
			
			CASE 'ShotInfoVersion':
				$trans_tag = 'Aufnahme-Info-Version';
			break;
			
			CASE 'HueAdjustment':
				$trans_tag = 'Farbton-Abgleich';
			break;
			
			CASE 'NEFCompression':
				$trans_tag = 'NEF-Kompression';
			break;
			
			CASE 'NoiseReduction':
				$trans_tag = 'Rausch-Unterdr&uuml;ckung';
			break;
			
			CASE 'LinearizationTable':
				$trans_tag = 'Linearisierungs-Tabelle';
			break;
			
			CASE 'WB_RGBGLevels':
				$trans_tag = 'WB-RG/BG-Werte';
			break;
			
			CASE 'LensDataVersion':
				$trans_tag = 'Objektivdaten-Version';
			break;
			
			CASE 'ExitPupilPosition':
				$trans_tag = 'Position der Austrittslinse';
			break;
			
			CASE 'AFAperture':
				$trans_tag = 'AF-Blende';
			break;
			
			CASE 'FocusPosition':
				$trans_tag = 'Fokus-Position';
			break;
			
			CASE 'FocusDistance':
				$trans_tag = 'Fokus-Entfernung';
			break;
			
			CASE 'LensIDNumber':
				$trans_tag = 'Objektiv-ID';
			break;
			
			CASE 'LensFStops':
				$trans_tag = 'Objektiv-Blendenstufen';
			break;
			
			CASE 'MinFocalLength':
				$trans_tag = 'min. Brennweite';
			break;
			
			CASE 'MaxFocalLength':
				$trans_tag = 'max. Brennweite';
			break;
			
			CASE 'MaxApertureAtMinFocal':
				$trans_tag = 'max. Blende bei min. Brennweite';
			break;
			
			CASE 'MaxApertureAtMaxFocal':
				$trans_tag = 'max. Blende bei max. Brennweite';
			break;
			
			CASE 'MCUVersion':
				$trans_tag = 'MCU-Version';
			break;
			
			CASE 'EffectiveMaxAperture':
				$trans_tag = 'effektive max. Blende';
			break;
			
			CASE 'RawImageCenter':
				$trans_tag = 'Zentrum des RAW-Bildes [X / Y]';
			break;
			
			CASE 'SensorPixelSize':
				$trans_tag = 'Pixel-Gr&ouml;&szlig;e des Sensors';
			break;
			
			CASE 'SerialNumber':
				$trans_tag = 'Serien-Nummer';
			break;
			
			CASE 'ShutterCount':
				$trans_tag = 'Ausl&ouml;sungs-Nr.';
			break;
			
			CASE 'FlashInfoVersion':
				$trans_tag = 'Blitz-Info (Version)';
			break;
			
			CASE 'FlashSource':
				$trans_tag = 'Blitz-Quelle';
			break;
			
			CASE 'ExternalFlashFirmware':
				$trans_tag = 'Firmware des ext. Blitzes';
			break;
			
			CASE 'ExternalFlashFlags':
				$trans_tag = 'ext. Blitz-Flags';
			break;
			
			CASE 'FlashCommanderMode':
				$trans_tag = 'Blitz-Befehls-Modus';
			break;
			
			CASE 'FlashControlMode':
				$trans_tag = 'Blitz-Steuerungs-Modus';
			break;
			
			CASE 'FlashGNDistance':
				$trans_tag = 'Blitz GN-Entfernung';
			break;
			
			CASE 'FlashGroupAControlMode':
				$trans_tag = 'Gruppe A Blitzsteuerungs-Modus';
			break;
			
			CASE 'FlashGroupBControlMode':
				$trans_tag = 'Gruppe B Blitzsteuerungs-Modus';
			break;
			
			CASE 'FlashGroupAExposureComp':
				$trans_tag = 'Gruppe A Blitzbelichtungs-Korrektur';
			break;
			
			CASE 'FlashGroupBExposureComp':
				$trans_tag = 'Gruppe B Blitzbelichtungs-Korrektur';
			break;
			
			CASE 'ImageOptimization':
				$trans_tag = 'Bild-Optimierung';
			break;
			
			CASE 'VariProgram':
				$trans_tag = 'Programm-Variation';
			break;
			
			CASE 'Aperture':
				$trans_tag = 'Blende';
			break;
			
			CASE 'BlueBalance':
				$trans_tag = 'Blau-Balance';
			break;
			
			CASE 'CFAPattern':
				$trans_tag = 'Farbfilter-Muster';
			break;
			
			CASE 'ImageSize':
				$trans_tag = 'Bild-Format [px x px]';
			break;
			
			CASE 'JpgFromRaw':
				$trans_tag = 'JPG from RAW';
			break;
			
			CASE 'LensID':
				$trans_tag = 'Objektiv-ID';
			break;
			
			CASE 'LensSpec':
				$trans_tag = 'Objektiv-Spezifikation';
			break;
			
			CASE 'PreviewImage':
				$trans_tag = 'Vorschau-Bild';
			break;
			
			CASE 'RedBalance':
				$trans_tag = 'Rot-Balance';
			break;
			
			CASE 'ScaleFactor35efl':
				$trans_tag = 'Skalierungsfaktor auf 35mm';
			break;
			
			CASE 'ShutterSpeed':
				$trans_tag = 'Belichtungszeit [s]';
			break;
			
			CASE 'SubSecCreateDate':
				$trans_tag = 'Aufnahme-Datum/Zeit (fein)';
			break;
			
			CASE 'SubSecDateTimeOriginal':
				$trans_tag = 'Aufnahme-Datum/Zeit (fein)';
			break;
			
			CASE 'SubSecModifyDate':
				$trans_tag = 'Modifizierungs-Datum/Zeit (fein)';
			break;
			
			CASE 'CircleOfConfusion':
				$trans_tag = 'Unsch&auml;rfekreis';
			break;
			
			CASE 'DOF':
				$trans_tag = 'Sch&auml;rfentiefe';
			break;
			
			CASE 'FOV':
				$trans_tag = 'Sichtfeld';
			break;
			
			CASE 'FocalLength35efl':
				$trans_tag = 'Brennweite (auf 35 mm)';
			break;
			
			CASE 'HyperfocalDistance':
				$trans_tag = 'Bereich der max. Sch&auml;rfe';
			break;
			
			CASE 'LightValue':
				$trans_tag = 'Lichtwert';
			break;
			
			CASE 'CurrentIPTCDigest':
				$trans_tag = '';
			break;
			
			CASE 'GPSLatitude':
				$trans_tag = 'GPS-Breite';
			break;
			
			CASE 'GPSLongitude':
				$trans_tag = 'GPS-L&auml;nge';
			break;
			
			CASE 'GPSAltitude':
				$trans_tag = 'GPS-H&ouml;he [m.&uuml;.NN]';
			break;
			
			CASE 'GPSLatitudeRef':
				$trans_tag = 'GPS-Breite (Referenz)';
			break;
			
			CASE 'GPSLongitudeRef':
				$trans_tag = 'GPS-L&auml;nge (Referenz)';
			break;
			
			CASE 'GPSAltitudeRef':
				$trans_tag = 'GPS-H&ouml;he (Referenz)';
			break;
			
			CASE 'City':
			CASE 'GPSPosition':
				$trans_tag = 'Ort';
			break;
			
			CASE 'GPSVersionID':
				$trans_tag = 'GPS-Versions-ID';
			break;
			
			CASE 'WB_RBLevels':
				$trans_tag = '>>WB_RBLevels';
			break;
			
			CASE 'CropHiSpeed':
				$trans_tag = '>>CropHiSpeed';
			break;
			
			CASE 'ExposureTuning':
				$trans_tag = 'Belichtungs-Korrektur';
			break;
			
			CASE 'VRInfoVersion':
				$trans_tag = 'VR-Info-Version';
			break;
			
			CASE 'VibrationReduction':
				$trans_tag = 'Vibrations-Unterdr&uuml;ckung';
			break;
			
			CASE 'PictureControlVersion':
				$trans_tag = '>>PictureControlVersion';
			break;
			
			CASE 'PictureControlName':
				$trans_tag = '>>PictureControlName';
			break;
			
			CASE 'PictureControlBase':
				$trans_tag = '>>PictureControlBase';
			break;
			
			CASE 'PictureControlAdjust':
				$trans_tag = '>>PictureControlAdjust';
			break;
			
			CASE 'PictureControlQuickAdjust':
				$trans_tag = '>>PictureControlQuickAdjust';
			break;
			
			CASE 'Brightness':
				$trans_tag = 'Helligkeit';
			break;
			
			CASE 'FilterEffect':
				$trans_tag = 'Filter-Effekt';
			break;
			
			CASE 'ToningEffect':
				$trans_tag = 'Farbton-Effekt';
			break;
			
			CASE 'ToningSaturation':
				$trans_tag = 'Farbton-S&auml;ttigung';
			break;
			
			CASE 'Timezone':
				$trans_tag = 'Zeitzone';
			break;
			
			CASE 'DaylightSavings':
				$trans_tag = '>>DaylightSavings';
			break;
			
			CASE 'DateDisplayFormat':
				$trans_tag = 'Datum-Anzeigeformat';
			break;
			
			CASE 'ISOExpansion':
				$trans_tag = 'ISO-Erweiterung';
			break;
			
			CASE 'ISOExpansion2':
				$trans_tag = 'ISO-Erweiterung 2';
			break;
			
			CASE 'FirmwareVersion':
				$trans_tag = 'Firmware-Version';
			break;
			
			CASE 'ISO2':
				$trans_tag = 'ISO 2';
			break;
			
			CASE 'LightSwitch':
				$trans_tag = 'Funktion des Licht-Schalters';
			break;
			
			CASE 'AFAreaModeSetting':
				$trans_tag = 'AF-Bereichs-Einstellungen';
			break;
			
			CASE 'CenterFocusPoint':
				$trans_tag = '>>CenterFocusPoint';
			break;
			
			CASE 'AFAssist':
				$trans_tag = 'AF-Unterst&uuml;tzung';
			break;
			
			CASE 'AFPointIllumination':
				$trans_tag = 'AF-Punkt Beleuchtung';
			break;
			
			CASE 'FocusPointWrap':
				$trans_tag = '>>FocusPointWrap';
			break;
			
			CASE 'AELockForMB-D80':
				$trans_tag = '>>AELockForMB-D80';
			break;
			
			CASE 'MB-D80BatteryType':
				$trans_tag = 'MB-D80 Batterie-Type';
			break;
			
			CASE 'Beep':
				$trans_tag = 'akust. Signal';
			break;
			
			CASE 'GridDisplay':
				$trans_tag = 'Sucher-Gitter';
			break;
			
			CASE 'ISODisplay':
				$trans_tag = 'ISO-Anzeige';
			break;
			
			CASE 'ViewfinderWarning':
				$trans_tag = 'Sucher-Warnung';
			break;
			
			CASE 'NoMemoryCard':
				$trans_tag = 'keine Speicherkarte';
			break;
			
			CASE 'ScreenTips':
				$trans_tag = 'Hilfe-Meldungen';
			break;
			
			CASE 'FileNumberSequence':
				$trans_tag = '>>FileNumberSequence';
			break;
			
			CASE 'ShootingInfoDisplay':
				$trans_tag = '>>ShootingInfoDisplay';
			break;
			
			CASE 'LCDIllumination':
				$trans_tag = 'LCD-Beleuchtung';
			break;
			
			CASE 'EasyExposureComp':
				$trans_tag = '>>EasyExposureComp';
			break;
			
			CASE 'ReverseIndicators':
				$trans_tag = '>>ReverseIndicators';
			break;
			
			CASE 'ExposureControlStepSize':
				$trans_tag = 'Schrittweite der Belichtungs-Korrektur';
			break;
			
			CASE 'CenterWeightedAreaSize':
				$trans_tag = 'mittenbetonter Bereich';
			break;
			
			CASE 'FineTuneOptMatrixMetering':
				$trans_tag = '>>FineTuneOptMatrixMetering';
			break;
			
			CASE 'FineTuneOptCenterWeighted':
				$trans_tag = '>>FineTuneOptCenterWeighted';
			break;
			
			CASE 'FineTuneOptSpotMetering':
				$trans_tag = '>>FineTuneOptSpotMetering';
			break;
			
			CASE 'CLModeShootingSpeed':
				$trans_tag = '>>CLModeShootingSpeed';
			break;
			
			CASE 'ExposureDelayMode':
				$trans_tag = 'Selbstausl&ouml;se-Modus';
			break;
			
			CASE 'AutoBracketSet':
				$trans_tag = 'Auto-Bracket-Einstellung';
			break;
			
			CASE 'AutoBracketOrder':
				$trans_tag = 'Auto-Bracket-Reihenfolge';
			break;
			
			CASE 'FuncButton':
				$trans_tag = 'Funktions-Taste';
			break;
			
			CASE 'OKButton':
				$trans_tag = 'OK-Taste';
			break;
			
			CASE 'AELockButton':
				$trans_tag = 'AE-Feststell-Taste';
			break;
			
			CASE 'CommandDialsReverseRotation':
				$trans_tag = 'R&uuml;ckseitiges Einstellrad';
			break;
			
			CASE 'ShutterReleaseButtonAE-L':
				$trans_tag = 'Aul&ouml;setaste';
			break;
			
			CASE 'MeteringTime':
				$trans_tag = 'Belichtungs-Messzeit';
			break;
			
			CASE 'PrimaryAFPoint':
				$trans_tag = 'prim&auml;rer AF-Punkt';
			break;
			
			CASE 'RemoteOnDuration':
				$trans_tag = '>>RemoteOnDuration';
			break;
			
			CASE 'SelfTimerTime':
				$trans_tag = 'Selbstausl&ouml;ser-Zeit';
			break;
			
			CASE 'SelfTimerShotCount':
				$trans_tag = '>>SelfTimerShotCount';
			break;
			
			CASE 'PlaybackMonitorOffTime':
				$trans_tag = 'Wiedergabe-Monitor-Abschaltung';
			break;
			
			CASE 'ImageReviewTime':
				$trans_tag = 'Bild-Anzeigedauer';
			break;
			
			CASE 'MenuMonitorOffTime':
				$trans_tag = 'Men&uuml;-Anzeigedauer';
			break;
			
			CASE 'ShootingInfoMonitorOffTime':
				$trans_tag = '>>ShootingInfoMonitorOffTime';
			break;
			
			CASE 'FlashShutterSpeed':
				$trans_tag = 'Blitz-Belichtungszeit';
			break;
			
			CASE 'InternalFlash':
				$trans_tag = 'interner Blitz';
			break;
			
			CASE 'ManualFlashOutput':
				$trans_tag = '>>ManualFlashOutput';
			break;
			
			CASE 'RepeatingFlashOutput':
				$trans_tag = '>>RepeatingFlashOutput';
			break;
			
			CASE 'RepeatingFlashCount':
				$trans_tag = 'Anz. Blitz-Wiederholungen';
			break;
			
			CASE 'RepeatingFlashRate':
				$trans_tag = 'Blitz-Wiederholrate';
			break;
			
			CASE 'FlashWarning':
				$trans_tag = 'Blitz-Warnung';
			break;
			
			CASE 'CommanderInternalTTLComp':
				$trans_tag = '>>CommanderInternalTTLComp';
			break;
			
			CASE 'ModelingFlash':
				$trans_tag = '>>ModelingFlash';
			break;
			
			CASE 'AutoFP':
				$trans_tag = '>>AutoFP';
			break;
			
			CASE 'CommanderGroupA_TTLComp':
				$trans_tag = '>>CommanderGroupA_TTLComp';
			break;
			
			CASE 'CommanderGroupB_TTLComp':
				$trans_tag = '>>CommanderGroupB_TTLComp';
			break;
			
			CASE 'LiveViewAF':
				$trans_tag = 'Live-Ansicht AF';
			break;
			
			CASE 'WB_RGGBLevels':
				$trans_tag = '>>WB_RGGBLevels';
			break;
			
			CASE 'RetouchHistory':
				$trans_tag = '>>RetouchHistory';
			break;
			
			CASE 'FlashFocalLength':
				$trans_tag = 'Blitz-Brennweite';
			break;
			
			CASE 'FlashColorFilter':
				$trans_tag = 'Blitz-Farbfilter';
			break;
			
			CASE 'FlashGroupCControlMode':
				$trans_tag = 'Gruppe C Blitzsteuerungs-Modus';
			break;
			
			CASE 'FlashGroupCExposureComp':
				$trans_tag = '';
			break;
			
			CASE 'Gruppe C Blitzbelichtungs-Korrektur':
				$trans_tag = '';
			break;
			
			CASE 'MultiExposureVersion':
				$trans_tag = 'Mehrfach-Belichtungs-Version';
			break;
			
			CASE 'MultiExposureMode':
				$trans_tag = 'Mehfach-Belichtungs-Modus';
			break;
			
			CASE 'MultiExposureShots':
				$trans_tag = 'Mehrfach-Belichtungs-Aufnahmen';
			break;
			
			CASE 'MultiExposureAutoGain':
				$trans_tag = '>>MultiExposureAutoGain';
			break;
			
			CASE 'PowerUpTime':
				$trans_tag = 'Einschalt-Zeit';
			break;
			
			CASE 'AFInfo2Version':
				$trans_tag = '>>AFInfo2Version';
			break;
			
			CASE 'ContrastDetectAF':
				$trans_tag = '>>ContrastDetectAF';
			break;
			
			CASE 'PhaseDetectAF':
				$trans_tag = '>>PhaseDetectAF';
			break;
			
			CASE 'PhaseDetectAF':
				$trans_tag = 'prim&auml;rer AF-Punkt';
			break;
			
			CASE 'AFPointsUsed':
				$trans_tag = 'verwendete AF-Punkte';
			break;
			
			CASE 'ContrastDetectAFInFocus':
				$trans_tag = '>>ContrastDetectAFInFocus';
			break;
			
			CASE 'FileInfoVersion':
				$trans_tag = 'Datei-Info-Version';
			break;
			
			CASE 'DirectoryNumber':
				$trans_tag = 'Verzeichnis-Nummer';
			break;
			
			CASE 'FileNumber':
				$trans_tag = 'Datei-Nummer';
			break;
			
			CASE 'AutoFocus':
				$trans_tag = 'Auto-Fokus';
			break;
			
			CASE 'InterchangeColorSpace':
				$trans_tag = 'IPTC Austausch-Farbraum';
			break;
			/*
			CASE '':
				$trans_tag = '';
			break;
			
			CASE '':
				$trans_tag = '';
			break;
			
			CASE '':
				$trans_tag = '';
			break;
			
			CASE '':
				$trans_tag = '';
			break;
			
			CASE '':
				$trans_tag = '';
			break;
			
			CASE '':
				$trans_tag = '';
			break;
			*/
			//jpg-Infos: ###################################################################################
			CASE 'ExifByteOrder':
				$trans_tag = '';
			break;
			
			CASE 'EncodingProcess':
				$trans_tag = 'Codierung';
			break;
			
			CASE 'ColorComponents':
				$trans_tag = 'Farb-Kanäle';
			break;
			
			CASE 'YCbCrSubSampling':
				$trans_tag = 'YCbCr-Abtastung';
			break;
			
			CASE 'YCbCrPositioning':
				$trans_tag = 'YCbCr-Positionierung';
			break;
			
			CASE 'ExifVersion':
				$trans_tag = 'EXIF-Version';
			break;
			
			CASE 'ComponentsConfiguration':
				$trans_tag = 'Komponenten-Konfiguration';
			break;
			
			CASE 'CompressedBitsPerPixel':
				$trans_tag = 'Komprimierte Bits pro Bildpunkt';
			break;
			
			CASE 'FlashpixVersion':
				$trans_tag = 'Flashpix-Version';
			break;
			
			CASE 'ColorSpace':
				$trans_tag = 'Farbraum';
			break;
			
			CASE 'ExifImageWidth':
				$trans_tag = 'Bild-Breite';
			break;
			
			CASE 'ExifImageHeight':
				$trans_tag = 'Bild-H&ouml;he';
			break;
			
			CASE 'InteropIndex':
				$trans_tag = 'Austausch-Typ';
			break;
			
			CASE 'InteropVersion':
				$trans_tag = 'Austausch-Version';
			break;
			
			CASE 'CustomRendered':
				$trans_tag = 'Benutzerdefinierte Bildbearbeitung';
			break;
			
			CASE 'ThumbnailOffset':
				$trans_tag = 'Vorschau-Offset';
			break;
			
			CASE 'ThumbnailLength':
				$trans_tag = 'Vorschau-L&auml;nge';
			break;
			
			CASE 'ImageBoundary':
				$trans_tag = 'Bild-Begrenzung';
			break;
			
			CASE 'ImageDataSize':
				$trans_tag = 'Datie-Gr&ouml;&szlig;e';
			break;
			
			CASE 'ThumbnailImage':
				$trans_tag = 'Vorschau-Bild';
			break;
			
			CASE 'AFPointPosition':
				$trans_tag = 'AF-Position';
			break;
			
			CASE 'ActiveD_Lighting':
			CASE 'ActiveD-Lighting':
				$trans_tag = 'Aktive Dunkel-Aufhellung';
			break;
			
			CASE 'ApertureValue':
				$trans_tag = 'Blende';
			break;
			
			CASE 'CameraModelName':
				$trans_tag = 'Kamera-Modell';
			break;
			
			CASE 'Caption_Abstract':
			CASE 'Caption-Abstract':
				$trans_tag = 'Beschreibung';
			break;
			
			CASE 'ColorMode':
				$trans_tag = 'Farb-Modus';
			break;
			
			CASE 'Copyright':
				$trans_tag = 'Copyright';
			break;
			
			CASE 'DateInsert':
				$trans_tag = 'Erfassungsdatum (DateInsert)';
			break;
			
			CASE 'FileNameOri':
				$trans_tag = 'Original-Dateiname';
			break;
			
			CASE 'HighISONoiseReduction':
				$trans_tag = 'Rausch-Reduzierung bei hoher Empfindlichkeit';
			break;
			
			CASE 'ImageDescription':
				$trans_tag = 'Bildbeschreibung';
			break;
			
			CASE 'ImageStabilization':
				$trans_tag = 'Bildstabilisierung';
			break;
			
			CASE 'Owner':
				$trans_tag = 'Bild-Eigent&uuml;mer';
			break;
			
			CASE 'SelfTimer':
				$trans_tag = 'Selbstausl&ouml;ser';
			break;
			
			CASE 'ShutterSpeedValue':
				$trans_tag = 'Belichtungszeit';
			break;
			
			CASE 'CurrentIPTCDigest':
				$trans_tag = '>>CurrentIPTCDigest';
			break;
			
			CASE 'XMPToolkit':
				$trans_tag = 'XMP Werkzeug';
			break;
			
			// interne Dateibezeichner: #############################################################
			
			CASE 'note':
				$trans_tag = 'Bewertung [1 - 5]';
			break;
			
			CASE 'pic_id':
				$trans_tag = 'Datensatz-Nummer';
			break;
			
			CASE 'ranking':
				$trans_tag = 'Downloads';
			break;
		}
		break;
		
		CASE 'en':
			$trans_tag = $tag;
		break;
		
		CASE 'ru':
			
		break;
	}
	IF(isset($trans_tag))
	{
		RETURN $trans_tag;
	}
}
?>