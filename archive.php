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

<main class="archive-container" role="main">
    <header class="archive-header">
        <div class="archive-meta-tag">CATEGORY</div>
        <h1 class="archive-title">
            <?php $this->archiveTitle(array(
                'category'  =>  _t('%s'),
                'search'    =>  _t('搜索: %s'),
                'tag'       =>  _t('标签: %s'),
                'author'    =>  _t('作者: %s')
            ), '', ''); ?>
        </h1>
        <p class="archive-description">
            目前共计 <?php echo $this->getTotal(); ?> 篇文章
        </p>
    </header>
    <div class="archive-list">
        <?php if ($this->have()): ?>
            <?php while($this->next()): ?>
                <article class="archive-item" itemscope itemtype="http://schema.org/BlogPosting">
                    <time class="item-date" datetime="<?php $this->date('c'); ?>">
                        <?php $this->date('m . d'); ?>
                    </time>
                    <div class="item-main">
                        <h2 class="item-title" itemprop="name headline">
                            <a itemprop="url" href="<?php $this->permalink() ?>"><?php $this->title() ?></a>
                        </h2>
                    </div>
                </article>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="archive-empty">
                <p>该分类下暂无内容</p>
            </div>
        <?php endif; ?>
    </div>
    <?php if ($this->thePageNav()): ?>
        <nav class="archive-navigator">
            <?php $this->pageNav('&larr;', '&arr;'); ?>
        </nav>
    <?php endif; ?>
</main>
<?php $this->need('footer.php'); ?>
