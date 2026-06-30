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
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="<?php $this->options->charset(); ?>">
    <meta name="renderer" content="webkit">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title><?php $this->archiveTitle([
            'category' => _t('分类 %s 下的文章'),
            'search'   => _t('包含关键字 %s 的文章'),
            'tag'      => _t('标签 %s 下的文章'),
            'author'   => _t('%s 发布的文章')
        ], '', ' - '); ?><?php $this->options->title(); ?></title>

    <!-- 使用url函数转换相关路径 -->
    <link rel="stylesheet" href="<?php $this->options->themeUrl('static/css/normalize.css'); ?>">
    <link rel="stylesheet" href="<?php $this->options->themeUrl('static/css/grid.css'); ?>">
    <link rel="stylesheet" href="<?php $this->options->themeUrl('static/css/style.main.css'); ?>">
    <link rel="stylesheet" href="<?php $this->options->themeUrl('static/css/sidebar.css'); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet" 
          integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" 
          crossorigin="anonymous">
    <!-- 思源宋体 -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif+SC:wght@700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif+SC:wght@400&display=swap" rel="stylesheet">
    <!-- 通过自有函数输出HTML头部信息 -->
    <?php $this->header(); ?>
</head>
<body>
<header class="main-navbar">
    <div class="navbar-container">
        <input type="checkbox" id="search-toggle" class="search-toggle-cb">
        <input type="checkbox" id="nav-toggle" class="nav-toggle-cb">
        <div class="nav-functional-left">
            <label for="search-toggle" class="nav-icon-link search-trigger" aria-label="Open Search">
                <svg xmlns="http://www.w3.org/2000/svg" 
                     width="18" 
                     height="18" 
                     viewBox="0 0 24 24" 
                     fill="none" 
                     stroke="currentColor" 
                     stroke-width="2.5" 
                     stroke-linecap="round" 
                     stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </label>
        </div>
        <a class="navbar-logo" href="<?php $this->options->siteUrl(); ?>">
            <?php $this->options->title() ?>
        </a>
        <label for="nav-toggle" class="nav-toggle-btn">
            <span class="hamburger-line"></span>
        </label>

        <div class="navbar-collapse-zone">
            <nav class="navbar-menu">
                <?php $this->widget('Widget_Contents_Page_List')->to($pages); ?>
                <?php while($pages->next()): ?>
                    <a href="<?php $pages->permalink(); ?>"><?php $pages->title(); ?></a>
                <?php endwhile; ?>
            </nav>
            <div class="nav-functional-right">
                <label for="search-toggle" class="nav-icon-link desktop-only-search" aria-label="Open Search">
                    <svg xmlns="http://www.w3.org/2000/svg" 
                         width="18"
                         height="18" 
                         viewBox="0 0 24 24" 
                         fill="none" 
                         stroke="currentColor" 
                         stroke-width="2.2" 
                         stroke-linecap="round" 
                         stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </label>
                <!--
                <a href="<?php $this->options->feedUrl(); ?>" class="nav-icon-link" target="_blank" aria-label="RSS">
                    <svg xmlns="http://www.w3.org/2000/svg" 
                         width="18" 
                         height="18" 
                         viewBox="0 0 24 24" 
                         fill="none" 
                         stroke="currentColor" 
                         stroke-width="2.2" 
                         stroke-linecap="round" 
                         stroke-linejoin="round">
                        <path d="M4 11a9 9 0 0 1 9 9"></path>
                        <path d="M4 4a16 16 0 0 1 16 16"></path>
                        <circle cx="5" cy="19" r="1"></circle>
                    </svg>
                </a>
                -->
            </div>
        </div>
        
        <div class="fullscreen-search-overlay">
            <label for="search-toggle" class="search-overlay-bg-closer"></label>
            <div class="search-card-box">
                <div class="search-card-header">
                    <span class="search-card-title">全站搜索</span>
                    <label for="search-toggle" class="search-card-close">✕</label>
                </div>
                <form id="search" method="post" action="<?php $this->options->siteUrl(); ?>" role="search" class="search-card-form">
                    <div class="search-card-input-wrapper">
                        <span class="search-card-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        </span>
                        <input type="text" id="s" name="s" class="search-card-input" placeholder="输入搜索内容..." autocomplete="off" required />
                    </div>
                </form>
            </div>
        </div>
    </div>
</header>
<!-- end #header -->
 
