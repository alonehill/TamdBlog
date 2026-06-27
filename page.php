<?php
/**
 * TamdBlog is a theme built on Typecho. It's vibrant and stunning, clear and elegant, with a minimalist white color scheme that makes your site the very definition of elegance
 * 
 * @package Tamd Blog
 * @author KAgDesign <3150675236@qq.com,me@gsav.cn>
 * @version 1.0.0
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

<main class="page-main-container">
    <article class="page-content-box">
        <header class="page-header">
            <h1 class="page-title"><?php $this->title() ?></h1>
        </header>

        <div class="post-content">
            <?php $this->content(); ?>
        </div>

        <?php $this->need('comments.php'); ?>
    </article>
</main>
<?php $this->need('footer.php'); ?>
