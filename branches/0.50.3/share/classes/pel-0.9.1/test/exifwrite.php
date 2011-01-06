
<?php

#!/opt/lampp/bin/php

/*  PEL: PHP Exif Library.  A library with support for reading and
 *  writing all Exif headers in JPEG and TIFF images using PHP.
 *
 *  Copyright (C) 2004, 2005, 2006  Martin Geisler.
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program in the file COPYING; if not, write to the
 *  Free Software Foundation, Inc., 51 Franklin St, Fifth Floor,
 *  Boston, MA 02110-1301 USA
 */

/* $Id: edit-description.php 426 2006-07-11 15:22:20Z mgeisler $ */

/* Error reporting is good.  Having PHP report all errors means that
 * one finds programming errors more quickly. */
error_reporting(E_ALL);

/* a printf() variant that appends a newline to the output. */
function println(/* fmt, args... */) {
    $args = func_get_args();
    $fmt = array_shift($args);
    vprintf($fmt . "\n", $args);
}


/* Make PEL speak the users language, if it is available. */
setlocale(LC_ALL, '');

/* Load the required files.  One would normally just require the
 * PelJpeg.php file for dealing with JPEG images, but because this
 * example can handle both JPEG and TIFF it loads the PelDataWindow
 * class too. */
require_once(dirname(__FILE__) . '/../PelDataWindow.php');
require_once(dirname(__FILE__) . '/../PelJpeg.php');
require_once(dirname(__FILE__) . '/../PelTiff.php');

/* Store the name of the script in $prog and remove this first part of
 * the command line. */

function writeExif()
{
	//$prog = array_shift($argv);
	$error = false;

	$input = array_shift($argv);
	$output = array_shift($argv);
	/*if (isset($input))
	{
	  echo "Input file: ".$input."<br>";
	} else
	{
	  $error = true;
	}

	if (isset($output)) {
	  echo "Output file: ".$output."<br>";
	} else {
	  $error = true;
	}*/

	/*if ($error) {
	  echo "Error: Input or Output file not set.";
	  exit(1);
	}*/

	/* We typically need lots of RAM to parse TIFF images since they tend
	 * to be big and uncompressed. */
	ini_set('memory_limit', '32M');

	/* The input file is now read into a PelDataWindow object.  At this
	 * point we do not know if the file stores JPEG or TIFF data, so
	 * instead of using one of the loadFile methods on PelJpeg or PelTiff
	 * we store the data in a PelDataWindow. */
	echo 'Reading file "'.$input.'"<br>';
	$data = new PelDataWindow(file_get_contents($input));

	/* The static isValid methods in PelJpeg and PelTiff will tell us in
	 * an efficient maner which kind of data we are dealing with. */
	if (PelJpeg::isValid($data))
	{
		/* The data was recognized as JPEG data, so we create a new empty
		 * PelJpeg object which will hold it.  When we want to save the
		 * image again, we need to know which object to same (using the
		 * getBytes method), so we store $jpeg as $file too. */
		$jpeg = $file = new PelJpeg();

		/* We then load the data from the PelDataWindow into our PelJpeg
		 * object.  No copying of data will be done, the PelJpeg object will
		 * simply remember that it is to ask the PelDataWindow for data when
		 * required. */
		$jpeg->load($data);

		/* The PelJpeg object contains a number of sections, one of which
		 * might be our Exif data. The getExif() method is a convenient way
		 * of getting the right section with a minimum of fuzz. */
		$exif = $jpeg->getExif();

		if ($exif == null)
		{
			/* Ups, there is no APP1 section in the JPEG file.  This is where
			 * the Exif data should be. */
			echo 'No APP1 section found, added new.<br>';

			/* In this case we simply create a new APP1 section (a PelExif
			 * object) and adds it to the PelJpeg object. */
			$exif = new PelExif();
			$jpeg->setExif($exif);

			/* We then create an empty TIFF structure in the APP1 section. */
			$tiff = new PelTiff();
			$exif->setTiff($tiff);
		} else
		{
			/* Surprice, surprice: Exif data is really just TIFF data!  So we
			 * extract the PelTiff object for later use. */
			println('Found existing APP1 section.<br>');
			$tiff = $exif->getTiff();
		}
	} elseif (PelTiff::isValid($data))
	{
		/* The data was recognized as TIFF data.  We prepare a PelTiff
		 * object to hold it, and record in $file that the PelTiff object is
		 * the top-most object (the one on which we will call getBytes). */
		$tiff = $file = new PelTiff();
		/* Now load the data. */
		$tiff->load($data);
	} else
	{
		/* The data was not recognized as either JPEG or TIFF data.
		 * Complain loudly, dump the first 16 bytes, and exit. */
		println('Unrecognized image format! The first 16 bytes follow:');
		PelConvert::bytesToDump($data->getBytes(0, 16)); 
		exit(1);
	}

	/* TIFF data has a tree structure much like a file system.  There is a
	 * root IFD (Image File Directory) which contains a number of entries
	 * and maybe a link to the next IFD.  The IFDs are chained together
	 * like this, but some of them can also contain what is known as
	 * sub-IFDs.  For our purpose we only need the first IFD, for this is
	 * where the image description should be stored. */
	$ifd0 = $tiff->getIfd();
	//echo $ifd0."<br>";

	if ($ifd0 == null)
	{
		/* No IFD in the TIFF data?  This probably means that the image
		 * didn't have any Exif information to start with, and so an empty
		 * PelTiff object was inserted by the code above.  But this is no
		 * problem, we just create and inserts an empty PelIfd object. */
		println('No IFD found, adding new.<br>');
		$ifd0 = new PelIfd(PelIfd::IFD0);
		$tiff->setIfd($ifd0);
	}

	/* Each entry in an IFD is identified with a tag.  This will load the
	 * ImageDescription entry if it is present.  If the IFD does not
	 * contain such an entry, null will be returned. */
	$desc = $ifd0->getEntry(PelTag::IMAGE_DESCRIPTION);

	if (false)
	{
		/* We need to check if the image already had a description stored. */
		if ($desc == null)
		{
			/* The was no description in the image. */
			println('Added new IMAGE_DESCRIPTION entry with "%s<br>".', $description);

			/* In this case we simply create a new PelEntryAscii object to hold
			 * the description.  The constructor for PelEntryAscii needs to know
			 * the tag and contents of the new entry. */
			$desc = new PelEntryAscii(PelTag::IMAGE_DESCRIPTION, $description);
			/* This will insert the newly created entry with the description
			 * into the IFD. */
			$ifd0->addEntry($desc);
		} else
		{
			/* An old description was found in the image. */
			println('Updating IMAGE_DESCRIPTION entry from "%s" to "%s<br>".',
				$desc->getValue(), $description);

			/* The description is simply updated with the new description. */
			$desc->setValue($description);
		}
	}

//$ifd1 = new PelIfd(PelIfd::IFD0);
$ifd1 = $ifd0->getSubIfd(PelIfd::GPS);
if ($ifd1 == null) {
  /* No IFD in the TIFF data?  This probably means that the image
   * didn't have any Exif information to start with, and so an empty
   * PelTiff object was inserted by the code above.  But this is no
   * problem, we just create and inserts an empty PelIfd object. */
  echo 'No GPS found, adding new.<br>';
  $ifd1 = new PelIfd(PelIfd::GPS);
  //getEntries
  $ifd0->addSubIfd($ifd1);
  //$ifd0->setNextIfd($ifd1);
}
//echo "ifd1: ".$ifd1."<br>";
$lat = $ifd1->getEntry(PelTag::GPS_LATITUDE);
//echo $lat."<br>";
$gps_arr1[0] = 10;
$gps_arr1[1] = 1;
$gps_arr2[0] = 5;
$gps_arr2[1] = 1;
$gps_arr3[0] = 12;
$gps_arr3[1] = 1;
//$gps_arr[3] = 1;
//$gps_arr[4] = 1;
//$gps_arr[5] = 1;
if ($lat == null) {
  $lat = new PelEntryRational(PelTag::GPS_LATITUDE, $gps_arr1, $gps_arr2, $gps_arr3);
  //$lat = new PelEntryRational(PelTag::GPS_LATITUDE, 10, 5, 12);
  echo "Creating latitude entry... ";
  $ifd1->addEntry($lat);
  echo "Created.<br>";
} else {
  $lat->setValue($gps_arr1, $gps_arr2, $gps_arr3);
  //$lat->setValue(10, 5, 12);
}

/* At this point the image on disk has not been changed, it is only
 * the object structure in memory which represent the image which has
 * been altered.  This structure can be converted into a string of
 * bytes with the getBytes method, and saving this in the output file
 * completes the script. */
println('Writing file "%s".<br>', $output);
file_put_contents($output, $file->getBytes());

$exifdata=exif_read_data($output,"",true,false);
echo "Latitude: ".$exifdata["GPS"]["GPSLatitude"][0]." ".$exifdata["GPS"]["GPSLatitude"][1]." ".$exifdata["GPS"]["GPSLatitude"][2]."<br>";
}
/* The End. */
?>