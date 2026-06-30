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
<main class="post-container" role="main">
    <div class="post-meta-top mb-3">
        <a href="<?php $this->options->siteUrl(); ?>" class="post-meta-item" style="">首页</a>
        <span class="post-meta-divider">/</span>
        <span class="post-meta-item">
            <?php $this->category(','); ?>
        </span>
        <span class="post-meta-divider">/</span>
        <span class="post-meta-item">
            此处
        </span>
    </div>
    <div class="post-warp">
    <article class="post-core" itemscope itemtype="http://schema.org/BlogPosting">
        <header class="post-header">
            
            <h1 class="post-title text-center" itemprop="name headline">
                <?php $this->title() ?>
            </h1>
            <div class="post-meta-bottom w-100 text-center">
            <div style="text-align:center;">
                <time class="post-meta-item" datetime="<?php $this->date('c'); ?>" itemprop="datePublished">
                    <?php $this->date('Y 年 m 月 d 日'); ?>
                </time>
                <span class="post-author-wrapper">
                    <a href="<?php $this->author->permalink(); ?>" rel="author"><?php $this->author(); ?></a>
                </span>
                <span class="post-mate-item">
                    共<?php echo art_count($this->cid); ?> 字
                </span>
                <!-- 登录状态提示 -->
                <?php if($this->user->hasLogin()): ?>
                    <?php
                    $editFile = $this->is('post') ? 'write-post.php' : 'write-page.php';
                    $editUrl = Typecho_Common::url($editFile, $this->options->adminUrl) . '?cid=' . intval($this->cid);
                    ?>
                    <a href="<?php echo $editUrl; ?>" class="post-meta-item" style="">编辑</a>
                <?php endif ?>
            </div>
               
            </div>
        </header>

        <div class="post-content text-content" itemprop="articleBody">
            <?php $this->content(); ?>
        </div>

        <?php if ($this->tags): ?>
            <footer class="post-footer">
                <div class="post-tags">
                    <?php $this->tags(' ', true, ''); ?>
                </div>
            </footer>
        <?php endif; ?>

    </article>

    <nav class="post-navigation">
        <div class="nav-card prev-card">
            <?php $this->thePrev('
                <span class="nav-label">
                    PREV
                </span>
                <span class="nav-title">
                    %s
                </span>
                ', '
                <span class="nav-label">
                    PREV
                </span>
                <span class="nav-title">
                    已经是第一篇了
                </span>
            '); 
            ?>
        </div>
        <div class="nav-card next-card">
            <?php $this->theNext('
                <span class="nav-label">
                    NEXT
                </span>
                <span class="nav-title">
                    %s
                </span>
                ', '
                <span class="nav-label">
                    NEXT
                </span>
                <span class="nav-title">
                    没有更多文章了
                </span>
            ');
            ?>
        </div>
    </nav>
        
    <section class="post-comments-section">
        <?php $this->need('comments.php'); ?>
    </section>

    </div>
    
</main>

<?php $this->need('footer.php'); ?>
