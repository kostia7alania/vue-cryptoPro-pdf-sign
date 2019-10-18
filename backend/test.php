<?php
//$cmd = 'convert -v';
//$f= __DIR__.'/processing/example.pdf';
// $f = 'C:\\ImageMagick\\example.pdf';
// $img = new Imagick($f);
//

$cmd = 'C:\ImageMagick-7.0.8-Q16\convert C:\ImageMagick-7.0.8-Q16\example.pdf[0] C:\ImageMagick-7.0.8-Q16\example.jpg 2>&1';
//echo $cmd;die;
exec( $cmd, $command_output, $return_val );
print_r($command_output);
if($return_val) {
  //echo $return_val;
  $t = $command_output[0];
  echo $t;
  //echo mb_convert_encoding($t, 'ISO-8859-15', 'UTF-8');
  //echo 'error exec. CMD='.$cmd;
} else echo 'SUC';
 
/*
  $antialiasing = "4";
  $preview_page = "1";
  $resolution = "300";
  $exec_command  = "gswin64c -dSAFER -dBATCH -dNOPAUSE -sDEVICE=" . $output_format . " ";
  $exec_command .= "-dTextAlphaBits=". $antialiasing . " -dGraphicsAlphaBits=" . $antialiasing . " ";
  $exec_command .= "-dFirstPage=" . $preview_page . " -dLastPage=" . $preview_page . " ";
  $exec_command .= "-r" . $resolution . " ";
  //$exec_command .= "-sOutputFile=" . $output_file . " '" . $path . "'";
  $exec_command .= "-sOutputFile=" . $output_file . " " . $path . "";
  exec( $exec_command, $command_output, $return_val );
  echo "Executing command...<hr> $exec_command <hr> $return_val <hr>";
  var_dump($command_output);
  die;
  */
?>
