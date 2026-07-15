<?php

// 设置缓存控制，减少重复处理
$cacheTime = 86400; // 缓存1天
header('Cache-Control: public, max-age=' . $cacheTime);
header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $cacheTime) . ' GMT');

// 获取当前目录下所有支持的图片文件
$imageFiles = glob(__DIR__ . '/*.{jpg,jpeg,png,gif,webp}', GLOB_BRACE);
if (empty($imageFiles)) {
    // 没有图片时返回 404
    header('HTTP/1.1 404 Not Found');
    exit('No images available');
}

// 随机选择一张图片
$chosen = $imageFiles[array_rand($imageFiles)];

// 获取请求参数（宽/高）
$width = isset($_GET['w']) ? intval($_GET['w']) : 0;
$height = isset($_GET['h']) ? intval($_GET['h']) : 0;

// 如果传入了尺寸，则进行缩放处理
if ($width > 0 || $height > 0) {
    // 获取原图信息
    $imageInfo = getimagesize($chosen);
    $origWidth = $imageInfo[0];
    $origHeight = $imageInfo[1];
    $mime = $imageInfo['mime'];

    // 计算缩放后的尺寸（保持比例）
    if ($width > 0 && $height > 0) {
        // 如果宽高都指定，则按比例缩放到完全填充（裁剪）或适应，这里采用“适应”模式（保持比例，一边可能不足）
        // 也可以改为“裁剪”模式，下面给出两种选择，这里默认使用适应模式（居中留白），更通用
        $ratio = min($width / $origWidth, $height / $origHeight);
        $newWidth = intval($origWidth * $ratio);
        $newHeight = intval($origHeight * $ratio);
    } elseif ($width > 0) {
        // 只指定宽度，高度按比例
        $ratio = $width / $origWidth;
        $newWidth = $width;
        $newHeight = intval($origHeight * $ratio);
    } else { // 只指定高度
        $ratio = $height / $origHeight;
        $newHeight = $height;
        $newWidth = intval($origWidth * $ratio);
    }

    // 创建目标画布
    $thumb = imagecreatetruecolor($newWidth, $newHeight);

    // 如果是透明图片（PNG/WebP）保留透明度
    $isTransparent = in_array($mime, ['image/png', 'image/webp']);
    if ($isTransparent) {
        imagealphablending($thumb, false);
        imagesavealpha($thumb, true);
        $transparent = imagecolorallocatealpha($thumb, 255, 255, 255, 127);
        imagefilledrectangle($thumb, 0, 0, $newWidth, $newHeight, $transparent);
    } else {
        // 非透明填充白色背景
        $white = imagecolorallocate($thumb, 255, 255, 255);
        imagefilledrectangle($thumb, 0, 0, $newWidth, $newHeight, $white);
    }

    // 根据原图类型创建图像资源
    switch ($mime) {
        case 'image/jpeg':
            $src = imagecreatefromjpeg($chosen);
            break;
        case 'image/png':
            $src = imagecreatefrompng($chosen);
            break;
        case 'image/gif':
            $src = imagecreatefromgif($chosen);
            break;
        case 'image/webp':
            $src = imagecreatefromwebp($chosen);
            break;
        default:
            header('HTTP/1.1 415 Unsupported Media Type');
            exit('Unsupported image type');
    }

    // 重采样缩放
    imagecopyresampled($thumb, $src, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);
    imagedestroy($src);

    // 输出处理后的图片
    header('Content-Type: ' . $mime);
    switch ($mime) {
        case 'image/jpeg':
            imagejpeg($thumb, null, 85); // 压缩质量 85%
            break;
        case 'image/png':
            imagepng($thumb, null, 8); // 压缩级别 8
            break;
        case 'image/gif':
            imagegif($thumb);
            break;
        case 'image/webp':
            imagewebp($thumb, null, 85);
            break;
    }
    imagedestroy($thumb);
} else {
    // 未传尺寸，直接输出原图
    $imageInfo = getimagesize($chosen);
    header('Content-Type: ' . $imageInfo['mime']);
    readfile($chosen);
}
exit;