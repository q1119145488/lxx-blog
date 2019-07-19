<?php
namespace Captcha;
use think\Session;

  	class Captcha{


  		public static function getCaptcha( $w='120',$h='35'){
  			header( 'Content-Type: image/png' );
  			$im = imagecreatetruecolor( $w, $h );

  			// Create some colors
			$white = imagecolorallocate($im, 255, 255, 255);
			$grey = imagecolorallocate($im, 128, 128, 128);
			$black = imagecolorallocate($im, 0, 0, 0);
			imagefilledrectangle($im, 0, 0, 399, 29, $white);
			$addFirst = mt_rand(0,99);
			$addSecond = mt_rand(0,99);
			$text = "$addFirst + $addSecond=";
			$addResult = $addFirst + $addSecond;

			Session::set('addResult',$addResult);


			imagettftext($im, 20, 0, 11, 25, $grey, 'arial.ttf', $text);// Add some shadow to the text

			imagettftext($im, 20, 0, 10, 24, $black, 'arial.ttf', $text);// Add the text


			// Using imagepng() results in clearer text compared with imagejpeg()
			imagepng($im);
			imagedestroy($im);

  		}



  	}
?>