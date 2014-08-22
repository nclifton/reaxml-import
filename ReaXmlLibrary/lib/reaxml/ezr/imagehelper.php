<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @copyright	Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 **/ 
class ReaxmlEzrImagehelper {
	public static function createThumbs($fname, $pathToThumbs, $thumbWidth) {
		$info = pathinfo ( $fname );
		if (strtolower ( $info ['extension'] ) == 'jpg') {
			
			// load image and get image size
			$img = imagecreatefromjpeg ( "{$fname}" );
			
			$width = imagesx ( $img );
			$height = imagesy ( $img );
			
			// calculate thumbnail size
			$new_width = $thumbWidth;
			$new_height = floor ( $height * ($thumbWidth / $width) );
			
			// create a new temporary image
			$tmp_img = imagecreatetruecolor ( $new_width, $new_height );
			
			// copy and resize old image into new image
			imagecopyresized ( $tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height );
			
			// save thumbnail into a file
			imagejpeg ( $tmp_img, $pathToThumbs . basename($fname));
		} else if (strtolower ( $info ['extension'] ) == 'gif') {
			$img = imagecreatefromgif ( "{$fname}" );
			
			$width = imagesx ( $img );
			$height = imagesy ( $img );
			
			// calculate thumbnail size
			$new_width = $thumbWidth;
			$new_height = floor ( $height * ($thumbWidth / $width) );
			
			// create a new temporary image
			$tmp_img = imagecreatetruecolor ( $new_width, $new_height );
			
			// copy and resize old image into new image
			imagecopyresized ( $tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height );
			
			// save thumbnail into a file
			imagegif ( $tmp_img, $pathToThumbs . basename($fname) );
		}
	}
}
