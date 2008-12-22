<?php
//サムネイル用に一律に幅 80px にリサイズ
include_once('Net/UserAgent/Mobile.php'); 

if (isset($_GET['img'])  && !empty($_GET['img'])) {
    $image_name = $_GET['img'];
} else {
    header('HTTP/1.0 204 No Content');
    exit;
}

$image_file = $_GET['img'];

$path_parts = pathinfo($image_file);
$image_extension = $path_parts['extension'];

$image_type = '';
if (preg_match('/jp[g|eg]/i', $image_extension)) {
    $image_type = 'jpg';
} elseif (preg_match('/gif/i', $image_extension)) {
    $image_type = 'gif';
} elseif (preg_match('/png/i', $image_extension)) {
    $image_type = 'png';
} else  {
    header('HTTP/1.0 204 No Content');
    exit;
}

$agent = Net_UserAgent_Mobile::singleton();

$output_image_type = $image_type;

if ($agent->isDoCoMo()) {
    if (!$agent->isFOMA()) {
        $output_image_type = 'gif';
    } else {
        if (strcmp($image_type, 'png') == 0) {
            $output_image_type = 'gif';
        }
    }
} elseif ($agent->isEZWeb()) {
    if (!$agent->isWAP2()) {
        $output_image_type = 'png';
    }
} elseif ($agent->isSoftBank()) {
    if (!$agent->isTypeW && !$agent->isType3GC()) {
        $output_image_type = 'png';
    }
}

$display = $agent->getDisplay();

$view_x = $display->getWidth();
if (empty($view_x)) {
    $view_x = '240';
}

$view_y = $display->getHeight();
if (empty($view_y)) {
    $view_y = '320';
}

// 拡張子 gif なのに jpeg ってケースがある...
// 拡張子じゃなく判別できる？
if (strcmp($image_type, 'jpg') == 0) {
    $image_data = @imagecreatefromjpeg($image_file);
} elseif (strcmp($image_type, 'gif') == 0) {
    $image_data = @imagecreatefromgif($image_file);
} else {
    $image_data = @imagecreatefrompng($image_file);
}

$image_x =@imagesx($image_data);
$image_y =@imagesy($image_data);

/*
$output_image_x = $image_x;
$output_image_y = $image_y;

if ($image_x > $image_y) {
    if ($image_x > $view_x) {
        $output_image_x = $view_x;
        $output_image_y = $image_y*($view_x/$image_x);
    }
} else {
    if ($image_y > $view_y) {
        $output_image_x = $image_x*($view_y/$image_y);
        $output_image_y = $view_y;
    }
}
*/

//サムネイル用に一律に幅 80px にリサイズ
$output_image_x = 80;
$output_image_y = 80*$image_y/$image_x;

if (strcmp($output_image_type, 'jpg') == 0) {
    
    $output_image_data = @imagecreatetruecolor($output_image_x, $output_image_y);
    @imagecopyresampled($output_image_data, $image_data, 0, 0, 0, 0, $output_image_x, $output_image_y, $image_x, $image_y);
    
    header('Content-Type: image/jpeg');
    @imagejpeg($output_image_data);
    
} if (strcmp($output_image_type, 'gif') == 0) {
    
    $output_image_data = @imagecreate($output_image_x, $output_image_y);
    @imagecopyresized($output_image_data, $image_data, 0, 0, 0, 0, $output_image_x, $output_image_y, $image_x, $image_y);
    
    header('Content-Type: image/gif');
    @imagegif($output_image_data);
    
} else {
    
    $output_image_data = @imagecreatetruecolor($output_image_x, $output_image_y);
    @imagecopyresampled($output_image_data, $image_data, 0, 0, 0, 0, $output_image_x, $output_image_y, $image_x, $image_y);
    
    header('Content-Type: image/png');
    @imagepng($output_image_data);
    
}

@imagedestroy($image_data);
@imagedestroy($output_image_data);
?>
