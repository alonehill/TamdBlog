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
    
    <!-- 侧边栏总外框 -->
    <aside id="secondary" class="widget-area" role="complementary">

        <!-- 名片 -->
        <section class="widget widget-profile modern-sidebar-widget">
            <div class="profile-avatar">
                <?php if ($this->options->logoUrl): ?>
                    <img src="<?php $this->options->logoUrl(); ?>" />
                <?php else: ?>
                    <?php $this->options->gravatar('60', ''); ?>
                <?php endif; ?>
            </div>
            <div class="profile-info">
                <h3 class="profile-name"><?php $this->options->title(); ?></h3>
                <p class="profile-bio"><?php $this->options->description(); ?></p>
            </div>
        </section>

        <!-- 分类 -->
        <?php if (!empty($this->options->sidebarBlock) && in_array('ShowCategory', $this->options->sidebarBlock)): ?>
        <section class="widget modern-sidebar-widget">
            <h3 class="widget-title">
                <svg xmlns="http://www.w3.org/2000/svg" 
                     width="16" height="16" 
                     viewBox="0 0 24 24" 
                     fill="none" 
                     stroke="currentColor" 
                     stroke-width="2.2" 
                     class="widget-title-icon">
                    <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z">
                    </path>
                </svg>
                <?php _e('分类'); ?>
            </h3>
            <div class="widget-tags-cloud">
                <?php $this->widget('Widget_Metas_Category_List')->to($category); ?>
                <?php while($category->next()): ?>
                    <a href="<?php $category->permalink(); ?>"><?php $category->name(); ?></a>
                <?php endwhile; ?>
            </div>
        </section>
        <?php endif; ?>

        <!-- 最新文章 -->
        <?php if (!empty($this->options->sidebarBlock) && in_array('ShowRecentPosts', $this->options->sidebarBlock)): ?>
        <section class="widget modern-sidebar-widget">
            <h3 class="widget-title">
                <svg xmlns="http://www.w3.org/2000/svg" 
                     width="16" 
                     height="16" 
                     viewBox="0 0 24 24" 
                     fill="none" 
                     stroke="currentColor" 
                     stroke-width="2.2" 
                     class="widget-title-icon">
                    <path d="M12 20h9"></path>
                    <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                </svg>
                <?php _e('最新文章'); ?>
            </h3>
            <ul class="widget-list post-list-wrapper">
                <?php $this->widget('Widget_Contents_Post_Recent')->to($recent); ?>
                    <?php while($recent->next()): ?>
                    <li class="post-item">
                        <a class="post-link" href="<?php $recent->permalink(); ?>">
                            <span class="post-title"><?php $recent->title(); ?></span>
                        </a>
                    </li>
                <?php endwhile; ?>
            </ul>
        </section>
        <?php endif; ?>

        <?php if (!empty($this->options->sidebarBlock) && in_array('ShowRecentComments', $this->options->sidebarBlock)): ?>
        <!-- 最近回复 -->
        <section class="widget modern-sidebar-widget mt-4xx">
            <h3 class="widget-title">
                <svg xmlns="http://www.w3.org/2000/svg" 
                     width="16" 
                     height="16" 
                     viewBox="0 0 24 24" 
                     fill="none" 
                     stroke="currentColor" 
                     stroke-width="2.2" 
                     class="widget-title-icon">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                </svg>
                <?php _e('最近回复'); ?>
            </h3>
            <ul class="widget-list comment-list-wrapper">
                <?php \Widget\Comments\Recent::alloc()->to($comments); ?>
                <?php while ($comments->next()): ?>
                    <li class="comment-item">
                        <span class="comment-author"><?php $comments->author(false); ?></span>
                        <span class="comment-divider">:</span>
                        <a class="comment-link" href="<?php $comments->permalink(); ?>"><?php $comments->excerpt(35, '...'); ?></a>
                    </li>
                <?php endwhile; ?>
            </ul>
        </section>
        <?php endif; ?>

        <!-- 文章归档 -->
        <?php if (!empty($this->options->sidebarBlock) && in_array('ShowArchive', $this->options->sidebarBlock)): ?>
        <section class="widget modern-sidebar-widget">
            <h3 class="widget-title">
                <svg xmlns="http://www.w3.org/2000/svg" 
                     width="16" 
                     height="16" 
                     viewBox="0 0 24 24" 
                     fill="none" 
                     stroke="currentColor" 
                     stroke-width="2.2" 
                     class="widget-title-icon">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
                <?php _e('文章归档'); ?>
            </h3>
            <ul class="widget-list archive-list-wrapper">
                <?php \Widget\Contents\Post\Date::alloc('type=month&format=F Y')
                    ->parse('
                        <li class="archive-item">
                            <a class="archive-link" href="{permalink}">
                                <span class="archive-date">{date}</span>
                                <span class="archive-arrow">→</span>
                            </a>
                        </li>'
                    ); 
                ?>
            </ul>
        </section>
        <?php endif; ?>

        <!-- 控制中心 -->
        <?php if (!empty($this->options->sidebarBlock) && in_array('ShowOther', $this->options->sidebarBlock)): ?>
        <section class="widget modern-sidebar-widget">
            <h3 class="widget-title">
                <svg xmlns="http://www.w3.org/2000/svg" 
                     width="16" 
                     height="16" 
                     viewBox="0 0 24 24" 
                     fill="none" 
                     stroke="currentColor" 
                     stroke-width="2.2" 
                     class="widget-title-icon">
                    <circle cx="12" cy="12" r="3"></circle>
                    <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                </svg>
                <?php _e('控制中心'); ?>
            </h3>
            <ul class="widget-list misc-list-wrapper">
            <?php if ($this->user->hasLogin()): ?>
                <li class="misc-item admin-login">
                    <a href="<?php $this->options->adminUrl(); ?>">
                        <?php _e('进入后台'); ?> 
                        <span class="user-badge">
                        <?php $this->user->screenName(); ?>
                        </span>
                    </a>
                </li>
                <li class="misc-item">
                    <a href="<?php $this->options->logoutUrl(); ?>">
                        <?php _e('安全退出'); ?>
                    </a>
                </li>
            <?php else: ?>
                <!--
                    <li class="misc-item admin-login">
                        <a href="<?php $this->options->adminUrl('login.php'); ?>">
                            <?php _e('管理登录'); ?>
                        </a>
                    </li>
                -->
            <?php endif; ?>
                <li class="misc-item"><a href="<?php $this->options->feedUrl(); ?>"><?php _e('文章 Feed RSS'); ?></a></li>
                <li class="misc-item"><a href="<?php $this->options->commentsFeedUrl(); ?>"><?php _e('评论 Feed RSS'); ?></a></li>
            </ul>
        </section>
        <?php endif; ?>

    </aside>