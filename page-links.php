<?php
/**
 * TamdBlog is a theme built on Typecho. It's vibrant and stunning, clear and elegant, with a minimalist white color scheme that makes your site the very definition of elegance
 * 
 * @package Tamd Blog
 * @author KAg Design <3150675236@qq.com,me@gsav.cn>
 * @version 1.0.4
 * @link http://gsav.cn/
 *
 * This file is part of Tamdblog.
 * Tamdblog is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version. 
 */

/**
 * 友情链接 (Links)
 *
 * @package custom
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$this->need('header.php');
?>
<link rel="stylesheet" href="<?php $this->options->themeUrl('static/css/page-links.css'); ?>">
<div class="apply-modal-overlay" id="applyModal">
    <div class="apply-modal">
        <div class="apply-modal-header">
            <h3 class="apply-modal-title">APPLY FOR LINKS</h3>
            <button class="btn-close-apply" id="closeApplyBtn">&times;</button>
        </div>
        
        <form id="linkApplyForm" class="apply-form">
            <div class="input-group">
                <label>站点名称 *</label>
                <input type="text" name="name" placeholder="TamdBlog" required>
            </div>
            <div class="input-group">
                <label>站点链接 *</label>
                <input type="url" name="url" placeholder="https://gsav.cn" required>
            </div>
            <div class="input-group">
                <label>头像 URL</label>
                <input type="url" name="avatar" placeholder="https://.../avatar.png">
            </div>
            <div class="input-group">
                <label>简介</label>
                <textarea name="desc" rows="2" placeholder="写点什么..."></textarea>
            </div>
            
            <button type="submit" class="btn-submit-apply" id="submitBtn">提交申请</button>
        </form>
    </div>
</div>

<main class="links-container">
    <header class="links-header">
        <h1 class="links-title"><?php $this->title() ?></h1>
        <div class="links-content-area">
            <?php $this->content(); ?>
        </div>
    </header>

    <ul class="links-grid">
    <?php
    $db = Typecho_Db::get();
    $select = $db->select()->from('table.links')->where('sort = ?', 'approved')->order('lid', Typecho_Db::SORT_ASC);
    $dynamicLinks = $db->fetchAll($select);

    if (!empty($dynamicLinks)):
        foreach ($dynamicLinks as $item):
            // 如果对方没填头像，使用轻量默认占位图
            $avatarUrl = !empty($item['image']) ? $item['image'] : "data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0OCIgaGVpZ2h0PSI0OCI+PHJlY3Qgd2lkdGg9IjQ4IiBoZWlnaHQ9IjQ4IiBmaWxsPSIjZjFmNWY5Ii8+PC9zdmc+";
    ?>
        <li>
            <a href=" <?php echo htmlspecialchars($item['url']); ?>" target="_blank" class="link-card">
                <img 
                    src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0OCIgaGVpZ2h0PSI0OCI+PHJlY3Qgd2lkdGg9IjQ4IiBoZWlnaHQ9IjQ4IiBmaWxsPSIjZjFmNWY5Ii8+PC9zdmc+" 
                    data-src="<?php echo htmlspecialchars($avatarUrl); ?>" 
                    class="link-avatar lazyload" 
                    alt="<?php echo htmlspecialchars($item['name']); ?>"
                >
                <div class="link-info">
                    <div class="link-name"><?php echo htmlspecialchars($item['name']); ?></div>
                    <div class="link-desc"><?php echo htmlspecialchars($item['description'] ? $item['description'] : '这个站长很懒，什么都没写'); ?></div>
                </div>
                <div class="link-dot"></div>
            </a >
        </li>
    <?php 
        endforeach;
    else:
    ?>
        
        <p style="color: #64748b; font-size: 13px; grid-column: 1/-1; text-align: center; padding: 40px 0;">
            暂无链接，点击下方按钮申请加入。
        </p >
    <?php endif; ?>
</ul>
    <div class="apply-trigger-wrap">
    <button class="btn-apply-trigger" id="openApplyBtn">申请交换友链 (Join Us)</button>
    <?php if ($this->user->hasLogin()): ?>
        <a href="/?action=manage_links" class="btn-apply-trigger" >友情链接后台，链接管理</a>
    <?php else: ?>
    
    <?php endif; ?>
</div>

</main>
<script src="<?php $this->options->themeUrl('static/js/page-links.js'); ?>"></script>

<?php $this->need('footer.php'); ?>