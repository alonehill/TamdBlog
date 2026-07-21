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

    <link rel="stylesheet" href="<?php $this->options->themeUrl('static/css/normalize.css'); ?>">
    <link rel="stylesheet" href="<?php $this->options->themeUrl('static/css/grid.css'); ?>">
    <link rel="stylesheet" href="<?php $this->options->themeUrl('static/css/style.main.css'); ?>">
    <link rel="stylesheet" href="<?php $this->options->themeUrl('static/css/sidebar.css'); ?>">
    <link rel="stylesheet" href="<?php $this->options->themeUrl('static/FontAwesome/css/all.min.css'); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet" 
          integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" 
          crossorigin="anonymous">
    
    <!-- 思源宋体 -->
<?php if ($this->options->fontsCdnLink == 'a') { ?>
    <link href="https://fonts.font.im/css2?family=Noto+Serif+SC:wght@700&display=swap" rel="stylesheet">
    <link href="https://fonts.font.im/css2?family=Noto+Serif+SC:wght@400&display=swap" rel="stylesheet">
<?php } elseif ($this->options->fontsCdnLink == 'b') { ?>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif+SC:wght@700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif+SC:wght@400&display=swap" rel="stylesheet">
    </div>
<?php } ?>

    <?php $this->need('components/header/TopNavbar.php'); ?>

    <?php $this->need('components/slide/TopSlideStyle.php'); ?>
    <!-- 通过自有函数输出HTML头部信息 -->
    <?php $this->header(); ?>
<!-- 自定义头部 HTML -->
<?php if ($this->options->customHeaderHtml): ?>
    <?php $this->options->customHeaderHtml(); ?>
<?php endif; ?>

<!-- 自定义 CSS -->
<?php if ($this->options->customCss): ?>
    <style type="text/css">
        <?php $this->options->customCss(); ?>
    </style>
<?php endif; ?>
</head>
<body>