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
 
<?php function threadedComments($comments, $options) {
    $commentClass = '';
    if ($comments->authorId) {
        if ($comments->authorId == $comments->ownerId) {
            $commentClass .= ' comment-by-author';
        } else {
            $commentClass .= ' comment-by-user';
        }
    }
?>
 
<li id="li-<?php $comments->theId(); ?>" class="comment-item<?php 
if ($comments->levels > 0) {
    echo ' comment-child';
    $comments->levelsAlt(' comment-level-odd', ' comment-level-even');
} else {
    echo ' comment-parent';
}
$comments->alt(' comment-odd', ' comment-even');
echo $commentClass;
?>">
    <div id="<?php $comments->theId(); ?>" class="comment-body">
        <!-- 头像区 -->
        <div class="comment-avatar">
            <?php $comments->gravatar('45', ''); ?>
        </div>
        <!-- 内容主区 -->
        <div class="comment-main">
            <div class="comment-header">
                <div class="comment-meta">
                    <span class="comment-author"><?php $comments->author(); ?></span>
                    <?php if ($comments->authorId == $comments->ownerId): ?>
                        <span class="comment-badge">作者</span>
                    <?php endif; ?>
                    <span class="comment-date">
                        <a href="<?php $comments->permalink(); ?>"><?php $comments->date('Y-m-d H:i'); ?></a>
                    </span>
                </div>
                <div class="comment-actions">
                    <span class="comment-reply"><?php $comments->reply(); ?></span>
                </div>
            </div>
            <div class="comment-content text-content">
                <?php $comments->content(); ?>
            </div>
        </div>
    </div>
    <!-- 子评论嵌套 -->
    <?php if ($comments->children) { ?>
        <div class="comment-children">
            <?php $comments->threadedComments($options); ?>
        </div>
    <?php } ?>
</li>
<?php } ?>
<div id="comments" class="comments-area">
    <?php $this->comments()->to($comments); ?>
    <?php if ($comments->have()): ?>
        <div class="comments-list-header">
            <h3 class="comments-title">
                <?php $this->commentsNum(_t('暂无评论'), _t('1 条评论'), _t('%d 条评论')); ?>
            </h3>
        </div>
        <ol class="comment-list">
            <?php $comments->listComments(); ?>
        </ol>
        <?php if ($comments->thePageNav()): ?>
            <nav class="comments-navigator">
                <?php $comments->pageNav('&larr;', '&arr;'); ?>
            </nav>
        <?php endif; ?>
    <?php endif; ?>
    <?php if($this->allow('comment')): ?>
        <div id="<?php $this->respondId(); ?>" class="respond">
            <div class="respond-header">
                <h3 id="response" class="respond-title"><?php _e('参与讨论'); ?></h3>
                <span class="cancel-comment-reply">
                    <?php $comments->cancelReply(_t('取消回复')); ?>
                </span>
            </div>
            <form method="post" action="<?php $this->commentUrl() ?>" id="comment-form" class="comment-form" role="form">
                <!-- 登录状态提示 -->
                <?php if($this->user->hasLogin()): ?>
                    <p class="comment-user-status">
                        <?php _e('以'); ?> <a href="<?php $this->options->profileUrl(); ?>"><?php $this->user->screenName(); ?></a> <?php _e('身份登录'); ?> · 
                        <a href="<?php $this->options->logoutUrl(); ?>" title="Logout" class="logout-link"><?php _e('退出 &raquo;'); ?></a>
                    </p>
                <?php else: ?>
                    <!-- 未登录时 -->
                    <div class="comment-inputs-grid">
                        <div class="input-group">
                            <input type="text" name="author" id="author" class="form-input" placeholder="昵称 *" value="<?php $this->remember('author'); ?>" required />
                        </div>
                        <div class="input-group">
                            <input type="email" name="mail" id="mail" class="form-input" placeholder="邮箱 *" value="<?php $this->remember('mail'); ?>" <?php if ($this->options->commentsRequireMail): ?>required<?php endif; ?> />
                        </div>
                        <div class="input-group">
                            <input type="url" name="url" id="url" class="form-input" placeholder="网址 (选填)" value="<?php $this->remember('url'); ?>" />
                        </div>
                    </div>
                <?php endif; ?>
                <!-- 文本输入框 -->
                <div class="comment-textarea-wrapper">
                    <textarea rows="4" name="text" id="textarea" class="form-textarea" placeholder="请和气发言..." required><?php $this->remember('text'); ?></textarea>
                </div>
                <!-- 表单底部提交动作 -->
                <div class="comment-form-footer">
                    <button type="submit" class="submit-btn"><?php _e('发送评论'); ?></button>
                </div>
            </form>
        </div>
    <?php else: ?>
        <p class="comments-closed-notice"><?php _e('评论功能已关闭。'); ?></p>
    <?php endif; ?>
</div>


<style>
/*
 * 评论表单与外框
 */
.comments-area {
    margin-top: 40px;
    font-family: -apple-system, BlinkMacSystemFont, sans-serif;
}

/* 评论区头部 */
.comments-list-header {
    margin-bottom: 20px;
}

.comments-title {
    font-size: 16px;
    font-weight: 600;
    color: var(--c-title, #0f172a);
    margin: 0;
}

/* 评论输入框表单（Respond）*/
.respond {
    margin-top: 32px;
    padding-top: 24px;
    border-top: 1px id var(--c-border-light, #f1f5f9);
}

.respond-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
}

.respond-title {
    font-size: 16px;
    font-weight: 600;
    color: var(--c-title, #0f172a);
    margin: 0;
}

.cancel-comment-reply a {
    font-size: 12px;
    color: #ef4444;
    text-decoration: none;
    padding: 4px 10px;
    background: #fef2f2;
    border-radius: 6px;
}

/* 登录状态提示 */
.comment-user-status {
    font-size: 13px;
    color: var(--c-muted, #94a3b8);
    margin-bottom: 14px;
}

.comment-user-status a {
    color: var(--c-title, #0f172a);
    text-decoration: none;
    font-weight: 500;
}

.comment-user-status .logout-link {
    color: #ef4444;
}

.comment-inputs-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
    margin-bottom: 12px;
}

.form-input, .form-textarea {
    width: 100%;
    box-sizing: border-box;
    font-size: 14px;
    color: var(--c-body, #334155);
    background-color: #f8fafc;
    border: 1px solid transparent;
    border-radius: 10px;
    padding: 10px 14px;
    outline: none;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

.comment-children .form-input, .form-textarea {
    color: var(--c-body, #334155);
    background-color: #fff;

}

.form-textarea {
    resize: vertical;
    min-height: 90px;
}

.form-input:focus, .form-textarea:focus {
    background-color: #ffffff;
    border-color: #cbd5e1;
    box-shadow: 0 4px 12px rgba(15, 23, 42, 0.04);
}

.comment-form-footer {
    display: flex;
    justify-content: flex-end;
    margin-top: 12px;
}

.submit-btn {
    font-size: 13px;
    font-weight: 500;
    color: #ffffff;
    background-color: var(--c-title, #0f172a);
    border: none;
    border-radius: 8px;
    padding: 10px 20px;
    cursor: pointer;
    transition: all 0.2s;
}

.submit-btn:hover {
    background-color: #334155;
    transform: translateY(-1px);
}

.submit-btn:active {
    transform: translateY(0);
}

.comments-navigator {
    margin: 24px 0;
    text-align: center;
}

.comments-navigator .page-navigator {
    display: inline-flex;
    gap: 6px;
    list-style: none;
    padding: 0;
    margin: 0;
}

.comments-navigator .page-navigator a, 
.comments-navigator .page-navigator span {
    display: inline-block;
    padding: 6px 12px;
    font-size: 13px;
    text-decoration: none;
    color: var(--c-body, #334155);
    border-radius: 6px;
}

.comments-navigator .page-navigator a {
    background: #f1f5f9;
}

.comments-navigator .page-navigator .current {
    background: var(--c-title, #0f172a);
    color: #fff;
    font-weight: 600;
}

/* 关闭提示 */
.comments-closed-notice {
    font-size: 13px;
    color: var(--c-muted, #94a3b8);
    text-align: center;
    margin-top: 24px;
}

@media (max-width: 640px) {
    .comment-inputs-grid {
        grid-template-columns: 1fr;
        gap: 8px;
    }
    .submit-btn {
        width: 100%;
        text-align: center;
    }
}

/*
 * 当评论框在回复状态时强制竖直排列防止挤压空间
 */

.comment-list .comment-inputs-grid {
    grid-template-columns: 1fr!important;
    gap: 8px!important;
}
</style>