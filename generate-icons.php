<?php
/**
 * PWA Icon Generator for TaRL System
 * Creates all required icon sizes for PWA manifest
 */

// Create images directory if it doesn't exist
$imageDir = __DIR__ . '/public/images';
if (!file_exists($imageDir)) {
    mkdir($imageDir, 0755, true);
}

// Icon sizes needed for PWA
$iconSizes = [72, 96, 128, 144, 152, 192, 384, 512];

// SVG template for TaRL logo
function createIconSVG($size) {
    return '<?xml version="1.0" encoding="UTF-8"?>
<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 ' . $size . ' ' . $size . '" xmlns="http://www.w3.org/2000/svg">
  <defs>
    <linearGradient id="bg" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" style="stop-color:#4f46e5;stop-opacity:1" />
      <stop offset="100%" style="stop-color:#7c3aed;stop-opacity:1" />
    </linearGradient>
  </defs>
  
  <!-- Background Circle -->
  <circle cx="' . ($size/2) . '" cy="' . ($size/2) . '" r="' . ($size/2) . '" fill="url(#bg)"/>
  
  <!-- TaRL Letter "T" -->
  <text x="' . ($size/2) . '" y="' . ($size/2 + $size/8) . '" 
        text-anchor="middle" 
        fill="white" 
        font-family="Arial, sans-serif" 
        font-weight="bold" 
        font-size="' . ($size * 0.6) . '"
        dominant-baseline="middle">T</text>
        
  <!-- Education Icon -->
  <g transform="translate(' . ($size * 0.15) . ',' . ($size * 0.15) . ') scale(' . ($size * 0.0015) . ')">
    <path d="M12 3L1 9L12 15L21 12.09V17H23V9M5 13.18V17.18L12 21L19 17.18V13.18L12 17L5 13.18Z" 
          fill="white" opacity="0.3"/>
  </g>
</svg>';
}

// Generate icons for each required size
foreach ($iconSizes as $size) {
    $svg = createIconSVG($size);
    $svgFile = $imageDir . "/icon-{$size}x{$size}.svg";
    
    // Save SVG file
    file_put_contents($svgFile, $svg);
    
    // Convert SVG to PNG using ImageMagick (if available)
    $pngFile = $imageDir . "/icon-{$size}x{$size}.png";
    
    // Try to convert SVG to PNG
    if (extension_loaded('imagick')) {
        try {
            $imagick = new Imagick();
            $imagick->readImageBlob($svg);
            $imagick->setImageFormat('png');
            $imagick->setImageBackgroundColor('transparent');
            $imagick->writeImage($pngFile);
            $imagick->clear();
            echo "✅ Created PNG icon: icon-{$size}x{$size}.png\n";
        } catch (Exception $e) {
            echo "⚠️  ImageMagick failed for {$size}x{$size}: " . $e->getMessage() . "\n";
        }
    } else {
        // Create a simple colored square as fallback
        $image = imagecreate($size, $size);
        $bg = imagecolorallocate($image, 79, 70, 229); // Indigo color
        $white = imagecolorallocate($image, 255, 255, 255);
        
        // Fill background
        imagefill($image, 0, 0, $bg);
        
        // Add "T" text
        $fontSize = $size * 0.4;
        $fontFile = null; // Use default font
        
        // Calculate text position
        $textX = $size / 2 - ($fontSize * 0.3);
        $textY = $size / 2 + ($fontSize * 0.3);
        
        imagestring($image, 5, $textX, $textY - 20, 'T', $white);
        
        // Save PNG
        imagepng($image, $pngFile);
        imagedestroy($image);
        
        echo "✅ Created fallback PNG icon: icon-{$size}x{$size}.png\n";
    }
    
    echo "✅ Created SVG icon: icon-{$size}x{$size}.svg\n";
}

// Create favicon.ico (32x32)
$faviconSvg = createIconSVG(32);
$faviconFile = __DIR__ . '/public/favicon.svg';
file_put_contents($faviconFile, $faviconSvg);
echo "✅ Created favicon.svg\n";

// Create apple-touch-icon (180x180 is standard)
$appleTouchSvg = createIconSVG(180);
$appleTouchFile = $imageDir . '/apple-touch-icon.svg';
file_put_contents($appleTouchFile, $appleTouchSvg);

// Try to create PNG version
if (extension_loaded('gd')) {
    $image = imagecreate(180, 180);
    $bg = imagecolorallocate($image, 79, 70, 229);
    $white = imagecolorallocate($image, 255, 255, 255);
    imagefill($image, 0, 0, $bg);
    imagestring($image, 5, 75, 80, 'T', $white);
    imagepng($image, $imageDir . '/apple-touch-icon.png');
    imagedestroy($image);
    echo "✅ Created apple-touch-icon.png\n";
}

// Create speedtest image for bandwidth monitoring
$speedTestSvg = '<?xml version="1.0" encoding="UTF-8"?>
<svg width="100" height="100" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
  <rect width="100" height="100" fill="#f3f4f6"/>
  <text x="50" y="55" text-anchor="middle" fill="#6b7280" font-family="Arial" font-size="12">Test</text>
</svg>';

file_put_contents($imageDir . '/speedtest.svg', $speedTestSvg);

if (extension_loaded('gd')) {
    $testImage = imagecreate(100, 100);
    $testBg = imagecolorallocate($testImage, 243, 244, 246);
    imagepng($testImage, $imageDir . '/speedtest.jpg');
    imagedestroy($testImage);
    echo "✅ Created speedtest.jpg for bandwidth monitoring\n";
}

echo "\n🎉 ALL PWA ICONS GENERATED SUCCESSFULLY!\n";
echo "\n📋 FILES TO UPLOAD VIA FTP:\n";
echo "Upload the entire /public/images/ directory to your server's public_html/images/\n";
echo "Also upload /public/favicon.svg to public_html/favicon.svg\n";
echo "\n✨ This will eliminate all 404 icon errors!\n";
?>