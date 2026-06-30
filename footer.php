<?php
/**
 * TamdBlog is a theme built on Typecho. It's vibrant and stunning, clear and elegant, with a minimalist white color scheme that makes your site the very definition of elegance
 * 
 * @package Tamd Blog
 * @author KAgDesign <3150675236@qq.com,me@gsav.cn>
 * @version 1.0.2
 * @link http://gsav.cn/
 *
 * This file is part of Tamdblog.
 * Tamdblog is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version. 
 */

if (!defined('__TYPECHO_ROOT_DIR__')) exit; 
?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
<footer class="site-footer" role="contentinfo">
    <div class="footer-container">
        <div class="footer-left">
            <p class="copyright">
                &copy; <?php echo date('Y'); ?> <a href="<?php $this->options->siteUrl(); ?>"><?php $this->options->title(); ?></a>. 
                All Rights Reserved.
            </p>
            <p class="site-tech">
                Built with <a href="https://typecho.org" target="_blank" rel="nofollow">Typecho</a> · 
                Designed by <a href="https://www.gsav.cn/" target="_blank">TamdBlog</a>
            </p>
            <!-- 创作不易，修改请保留版权，谢谢 -->
        </div>
        <div class="footer-right">
            <ul class="footer-links">
                <li><a href="<?php $this->options->feedUrl(); ?>">RSS</a></li>
                <li><a href="<?php $this->options->siteUrl(); ?>sitemap.xml">Sitemap</a></li>
                <?php if ($this->options->miitbeian): ?>
                    <li><img class="" style="width: 16px;height: 16px;" src="<?php $this->options->themeUrl('static/img/icp.png'); ?>">&nbsp<a href="https://beian.miit.gov.cn/" target="_blank" rel="nofollow"><?php $this->options->miitbeian(); ?></a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</footer><!-- end #footer -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const navbar = document.querySelector('.main-navbar');
    const slider = document.getElementById('topSlide');

    function checkScroll() {
        // 如果首页有幻灯片，就根据幻灯片高度判断
        const triggerHeight = slider ? slider.offsetHeight - 20 : -999;
        
        if (window.scrollY > triggerHeight) {
            navbar.classList.add('is-scrolled');
        } else {
            navbar.classList.remove('is-scrolled');
        }
    }
    checkScroll();
    window.addEventListener('scroll', checkScroll);
});
</script>
<?php $this->footer(); ?>
</body>
</html>
