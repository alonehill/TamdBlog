<?php 
$style = isset($this->options->sliderStyle) ? $this->options->sliderStyle : '0';

if (in_array($style, ['a', 'b', 'c'])): 
    $constName = 'TEST_WIDGET_CSS_' . strtoupper($style);
    if (!defined($constName)): 
        define($constName, 1);
        $baseUrl = \Helper::options()->themeUrl . '/static/TopSlideStyle/';
?>
        <link rel="stylesheet" href="<?php echo $baseUrl; ?>TopSlide<?php echo strtoupper($style); ?>.css">
        <script src="<?php echo $baseUrl; ?>TopSlide<?php echo strtoupper($style); ?>.js" defer></script>
<?php 
    endif; 
endif; 
?>