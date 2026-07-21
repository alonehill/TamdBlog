<?php
/**
 * 优雅、醉美、大道至简
 * 
 * @package Tamd Blog
 * @author KAg Design <3150675236@qq.com,me@gsav.cn>
 * @version 1.1.0
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
<?php $this->need('components/header/Head.php'); ?>
<?php $this->need('components/header/Navbar.php'); ?>
<div id="body">
    <?php $this->need('components/slide/Slide.php'); ?>
    <div class="container">
        <div class="row">
            <main class="main-content <?php if ($this->options->sidebarStatus == 'on'): ?> col-lg-8 <?php else: ?> <?php endif; ?>">
                <?php $this->need('modules/index/ArticleList.php'); ?>
                <?php $this->need('components/pagination.php'); ?>
            </main>
            <?php if ($this->options->sidebarStatus == 'on'): ?>
            <div class="col-lg-4 sidebar-wrapper">
                <?php $this->need('components/sidebar/Sidebar.php'); ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $this->need('components/footer/Footer.php'); ?>
<?php $this->need('components/footer/Foot.php'); ?>