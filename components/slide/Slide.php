<?php if ($this->options->sliderStatus == 'on'): ?>
    <?php 
    $slides = !empty($this->options->slideData) ? json_decode($this->options->slideData, true) : [];
    
    if (!empty($slides)): 
    ?>
    <div id="topSlide" class="carousel slide carousel-fade containerxx" data-bs-ride="carousel">
        <!-- 指示器 -->
        <div class="carousel-indicators">
            <?php foreach ($slides as $i => $slide): ?>
                <button type="button" data-bs-target="#topSlide" data-bs-slide-to="<?php echo $i; ?>" <?php echo $i == 0 ? 'class="active" aria-current="true"' : ''; ?> aria-label="Slide <?php echo $i + 1; ?>"></button>
            <?php endforeach; ?>
        </div>
        
        <!-- 幻灯片主体 -->
        <div id="topSlideImg" class="carousel-inner">
            <?php foreach ($slides as $index => $slide): ?>
                <div class="carousel-item topslideimg <?php echo $index == 0 ? 'active' : ''; ?>" style="background-image: url('<?php echo htmlspecialchars($slide['img']); ?>');">
                    <!-- 如果有跳转链接，将内容包裹在 A 标签中 -->
                    <?php if (!empty($slide['link'])): ?>
                        <a href="<?php echo htmlspecialchars($slide['link']); ?>" class="stretched-link"></a >
                    <?php endif; ?>
                    
                    <div class="topslidecontent p-2">
                        <div class="text-center">
                            <h1 class="fw-bold text-shadow carousel-text-shadow"><?php echo htmlspecialchars($slide['title']); ?></h1>
                            <p class="mt-md-4 carousel-text-shadow"><?php echo htmlspecialchars($slide['desc']); ?></p >
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <?php else: ?>
    <!-- 无数据时的兜底逻辑 -->
    <div id="topSlide" class="carousel slide carousel-fade containerxx" data-bs-ride="carousel">
        <div id="topSlideImg" class="carousel-inner">
            <div class="carousel-item topslideimg active" style="background-image: url('<?php $this->options->themeUrl("static/img/404-bg.jpg");?>');">
                <div class="topslidecontent">
                    <div class="text-center">
                        <h1 class="fw-bold text-shadow carousel-text-shadow">未配置幻灯片</h1>
                        <p class="mt-md-4 carousel-text-shadow">请在后台“<a href="<?php echo $this->options->adminUrl;?>options-theme.php#typecho-option-item-slideData-9">主题设置</a>”中添加幻灯片图片及相关信息。</p >
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

<?php else: ?>
    <div class="" style="height: 64px!important;"></div>
<?php endif; ?>