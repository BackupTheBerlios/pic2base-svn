<?php
IF (!$_COOKIE['login'])
{
	include '../../share/global_config.php';
	//var_dump($sr);
	header('Location: ../../../index.php');
}

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


require_once '../share/classes/pel-0.9.1/PelDataWindow.php';
require_once '../share/classes/pel-0.9.1/PelJpeg.php';
require_once '../share/classes/pel-0.9.1/PelTiff.php';

$data = new PelDataWindow(file_get_contents($input));

if (PelJpeg::isValid($data))
{
	$jpeg = $file = new PelJpeg();
	$jpeg->load($data);
	$exif = $jpeg->getExif();

	if ($exif == null)
	{
		$exif = new PelExif();
		$jpeg->setExif($exif);

		$tiff = new PelTiff();
		$exif->setTiff($tiff);
	}
	ELSE
	{
		$tiff = $exif->getTiff();
	}
}
elseif
(PelTiff::isValid($data))
{
	$tiff = $file = new PelTiff();
	/* Now load the data. */
	$tiff->load($data);
}
else
{
	PelConvert::bytesToDump($data->getBytes(0, 16));
	exit(1);
}

$ifd0 = $tiff->getIfd();

if ($ifd0 == null)
{
	$ifd0 = new PelIfd(PelIfd::IFD0);
	$tiff->setIfd($ifd0);
}

$desc = $ifd0->getEntry(PelTag::IMAGE_DESCRIPTION);

if ($desc == null)
{
	$desc = new PelEntryAscii(PelTag::IMAGE_DESCRIPTION, $description);
	$ifd0->addEntry($desc);
}
else
{
	$desc->setValue($description);
}

file_put_contents($output, $file->getBytes());
?>