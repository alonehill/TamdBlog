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
<?php $this->need('header.php'); ?>

<main class="error-container">
    <div class="error-box">
        <h1 class="error-code">404</h1>
        <h2 class="error-title">页面迷路了</h2>
        <p class="error-desc">你所寻找的页面可能已经移动，或者在创作的宇宙中从未存在过。</p>
        
        <div class="error-actions">
            <a href="<?php $this->options->siteUrl(); ?>" class="btn-back-home">
                回到首页
            </a>
        </div>
    </div>
</main>

<?php $this->need('footer.php'); ?>
