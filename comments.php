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
            <img class="avatar" src="<?php echo getCustomAvatar($comments->mail, 45); ?>" alt="" width="45" height="45"/>
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
                <?php 
                $db = Typecho_Db::get();
                $row = $db->fetchRow(
                    $db->select('text')->from('table.comments')->where('coid = ?', $comments->coid)
                );
                if ($row) {
                    $content = parseEmoji($row['text']);
                    $content = strip_tags($content, '<img>');
                    echo $content;
                }
                ?>
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
                <div class="comment-text area-wrapper">
                    <textarea rows="4" name="text" id="textarea" class="form-textarea" placeholder="请和气发言..." required><?php $this->remember('text'); ?></textarea>
                </div>
                <!-- ========== 表情面板 ========== -->
                <div class="emoji-wrap">
                    <button type="button" class="emoji-btn" id="emoji-btn">
                        <svg class="emoji-btn-icon" viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm3.5-9c.83 0 1.5-.67 1.5-1.5S16.33 8 15.5 8 14 8.67 14 9.5s.67 1.5 1.5 1.5zm-7 0c.83 0 1.5-.67 1.5-1.5S9.33 8 8.5 8 7 8.67 7 9.5 7.67 11 8.5 11zm3.5 6.5c2.33 0 4.31-1.46 5.11-3.5H6.89c.8 2.04 2.78 3.5 5.11 3.5z"/>
                        </svg>
                        表情
                    </button>
                    <div class="emoji-panel" id="emoji-panel">
                        <!-- 由 JS 动态生成 -->
                    </div>
                </div>
                <!-- ========== 表情面板结束 ========== -->
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
<style>
  /* ========== 表情面板样式 ========== */
.emoji-wrap {
    position: relative;
    display: inline-block;
    margin: 10px 0;
}

.emoji-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #f5f5f5;
    border: 1px solid #ddd;
    padding: 8px 14px;
    border-radius: 20px;
    cursor: pointer;
    font-size: 14px;
    color: #555;
    transition: all 0.2s;
    user-select: none;
}

.emoji-btn:hover {
    background: #eee;
    border-color: #ccc;
    color: #333;
}

.emoji-btn-icon {
    flex-shrink: 0;
}

/* 表情选择面板 */
.emoji-panel {
    display: none;
    position: absolute;
    bottom: 100%;
    left: 0;
    margin-bottom: 8px;
    background: #fff;
    border: 1px solid #e0e0e0;
    border-radius: 12px;
    padding: 10px;
    width: 320px;
    max-height: 260px;
    overflow-y: auto;
    box-shadow: 0 8px 24px rgba(0,0,0,0.12);
    z-index: 9999;
    display: none;
    flex-wrap: wrap;
    gap: 4px;
}

.emoji-panel.show {
    display: flex;
}

/* 单个表情项 */
.emoji-item {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 6px;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s;
    width: 44px;
    height: 44px;
}

.emoji-item:hover {
    background: #f0f0f0;
    transform: scale(1.15);
}

.emoji-item:active {
    transform: scale(0.95);
}

.emoji-item img {
    width: 28px;
    height: 28px;
    display: block;
}

/* 滚动条 */
.emoji-panel::-webkit-scrollbar {
    width: 6px;
}

.emoji-panel::-webkit-scrollbar-track {
    background: transparent;
    border-radius: 3px;
}

.emoji-panel::-webkit-scrollbar-thumb {
    background: #ccc;
    border-radius: 3px;
}

.emoji-panel::-webkit-scrollbar-thumb:hover {
    background: #aaa;
}

/* 评论内容中的表情 */
.emoji-img {
    display: inline-block;
    vertical-align: middle;
    margin: 0 1px;
}

@media (max-width: 480px) {
    .emoji-panel {
        bottom: 100%;
        margin-bottom: 8px;
    }
    
    .emoji-item {
        width: 38px;
        height: 38px;
        padding: 4px;
    }
    
    .emoji-item img {
        width: 24px;
        height: 24px;
    }
}
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 表情映射：码点 => [短代码, SVG文件名]
    const emojiMap = {
        '1f479': [':1f479:', '1f479.svg'],
        '1f47a': [':1f47a:', '1f47a.svg'],
        '1f47b': [':1f47b:', '1f47b.svg'],
        '1f47d': [':1f47d:', '1f47d.svg'],
        '1f47e': [':1f47e:', '1f47e.svg'],
        '1f47f': [':1f47f:', '1f47f.svg'],
        '1f480': [':1f480:', '1f480.svg'],
        '1f494': [':1f494:', '1f494.svg'],
        '1f4a2': [':1f4a2:', '1f4a2.svg'],
        '1f4a6': [':1f4a6:', '1f4a6.svg'],
        '1f4a9': [':1f4a9:', '1f4a9.svg'],
        '1f600': [':1f600:', '1f600.svg'],
        '1f601': [':1f601:', '1f601.svg'],
        '1f602': [':1f602:', '1f602.svg'],
        '1f603': [':1f603:', '1f603.svg'],
        '1f604': [':1f604:', '1f604.svg'],
        '1f605': [':1f605:', '1f605.svg'],
        '1f606': [':1f606:', '1f606.svg'],
        '1f607': [':1f607:', '1f607.svg'],
        '1f608': [':1f608:', '1f608.svg'],
        '1f609': [':1f609:', '1f609.svg'],
        '1f60a': [':1f60a:', '1f60a.svg'],
        '1f60b': [':1f60b:', '1f60b.svg'],
        '1f60c': [':1f60c:', '1f60c.svg'],
        '1f60d': [':1f60d:', '1f60d.svg'],
        '1f60e': [':1f60e:', '1f60e.svg'],
        '1f60f': [':1f60f:', '1f60f.svg'],
        '1f610': [':1f610:', '1f610.svg'],
        '1f611': [':1f611:', '1f611.svg'],
        '1f612': [':1f612:', '1f612.svg'],
        '1f613': [':1f613:', '1f613.svg'],
        '1f614': [':1f614:', '1f614.svg'],
        '1f615': [':1f615:', '1f615.svg'],
        '1f616': [':1f616:', '1f616.svg'],
        '1f617': [':1f617:', '1f617.svg'],
        '1f618': [':1f618:', '1f618.svg'],
        '1f619': [':1f619:', '1f619.svg'],
        '1f61a': [':1f61a:', '1f61a.svg'],
        '1f61b': [':1f61b:', '1f61b.svg'],
        '1f61c': [':1f61c:', '1f61c.svg'],
        '1f61d': [':1f61d:', '1f61d.svg'],
        '1f61e': [':1f61e:', '1f61e.svg'],
        '1f61f': [':1f61f:', '1f61f.svg'],
        '1f620': [':1f620:', '1f620.svg'],
        '1f621': [':1f621:', '1f621.svg'],
        '1f622': [':1f622:', '1f622.svg'],
        '1f623': [':1f623:', '1f623.svg'],
        '1f624': [':1f624:', '1f624.svg'],
        '1f625': [':1f625:', '1f625.svg'],
        '1f626': [':1f626:', '1f626.svg'],
        '1f627': [':1f627:', '1f627.svg'],
        '1f628': [':1f628:', '1f628.svg'],
        '1f629': [':1f629:', '1f629.svg'],
        '1f62a': [':1f62a:', '1f62a.svg'],
        '1f62b': [':1f62b:', '1f62b.svg'],
        '1f62c': [':1f62c:', '1f62c.svg'],
        '1f62d': [':1f62d:', '1f62d.svg'],
        '1f62e': [':1f62e:', '1f62e.svg'],
        '1f62f': [':1f62f:', '1f62f.svg'],
        '1f630': [':1f630:', '1f630.svg'],
        '1f631': [':1f631:', '1f631.svg'],
        '1f632': [':1f632:', '1f632.svg'],
        '1f633': [':1f633:', '1f633.svg'],
        '1f634': [':1f634:', '1f634.svg'],
        '1f635': [':1f635:', '1f635.svg'],
        '1f636': [':1f636:', '1f636.svg'],
        '1f637': [':1f637:', '1f637.svg'],
        '1f641': [':1f641:', '1f641.svg'],
        '1f642': [':1f642:', '1f642.svg'],
        '1f643': [':1f643:', '1f643.svg'],
        '1f644': [':1f644:', '1f644.svg'],
        '1f648': [':1f648:', '1f648.svg'],
        '1f649': [':1f649:', '1f649.svg'],
        '1f64a': [':1f64a:', '1f64a.svg'],
        '1f910': [':1f910:', '1f910.svg'],
        '1f911': [':1f911:', '1f911.svg'],
        '1f912': [':1f912:', '1f912.svg'],
        '1f913': [':1f913:', '1f913.svg'],
        '1f914': [':1f914:', '1f914.svg'],
        '1f915': [':1f915:', '1f915.svg'],
        '1f916': [':1f916:', '1f916.svg'],
        '1f917': [':1f917:', '1f917.svg'],
        '1f920': [':1f920:', '1f920.svg'],
        '1f921': [':1f921:', '1f921.svg'],
        '1f922': [':1f922:', '1f922.svg'],
        '1f923': [':1f923:', '1f923.svg'],
        '1f924': [':1f924:', '1f924.svg'],
        '1f925': [':1f925:', '1f925.svg'],
        '1f927': [':1f927:', '1f927.svg'],
        '1f928': [':1f928:', '1f928.svg'],
        '1f929': [':1f929:', '1f929.svg'],
        '1f92a': [':1f92a:', '1f92a.svg'],
        '1f92b': [':1f92b:', '1f92b.svg'],
        '1f92c': [':1f92c:', '1f92c.svg'],
        '1f92d': [':1f92d:', '1f92d.svg'],
        '1f92e': [':1f92e:', '1f92e.svg'],
        '1f92f': [':1f92f:', '1f92f.svg'],
        '1f970': [':1f970:', '1f970.svg'],
        '1f971': [':1f971:', '1f971.svg'],
        '1f972': [':1f972:', '1f972.svg'],
        '1f973': [':1f973:', '1f973.svg'],
        '1f974': [':1f974:', '1f974.svg'],
        '1f975': [':1f975:', '1f975.svg'],
        '1f976': [':1f976:', '1f976.svg'],
        '1f978': [':1f978:', '1f978.svg'],
        '1f97a': [':1f97a:', '1f97a.svg'],
        '1f9d0': [':1f9d0:', '1f9d0.svg'],
        '2620-fe0f': [':2620-fe0f:', '2620-fe0f.svg'],
        '2639-fe0f': [':2639-fe0f:', '2639-fe0f.svg'],
        '263a-fe0f': [':263a-fe0f:', '263a-fe0f.svg'],
        '2764-fe0f': [':2764-fe0f:', '2764-fe0f.svg'],
    };

    const panel = document.getElementById('emoji-panel');
    const textarea = document.querySelector('textarea[name="text"]');
    const btn = document.getElementById('emoji-btn');
    const themeUrl = '<?php echo rtrim($this->options->themeUrl, '/'); ?>';

    if (!panel || !textarea || !btn) return;

    // 生成面板
    for (const [codepoint, [shortcode, filename]] of Object.entries(emojiMap)) {
        const span = document.createElement('span');
        span.className = 'emoji-item';
        span.title = shortcode; // 悬停提示短代码
        
        // 创建 SVG 图片
        const img = document.createElement('img');
        img.src = themeUrl + '/emojis/' + filename;
        img.alt = shortcode;
        img.width = 32;
        img.height = 32;
        img.loading = 'lazy';
        
        span.appendChild(img);
        span.setAttribute('data-code', shortcode); // 存储短代码
        panel.appendChild(span);
    }

    // 切换面板
    btn.addEventListener('click', function(e) {
        e.stopPropagation();
        panel.classList.toggle('show');
    });

    // 点击外部关闭面板
    document.addEventListener('click', function(e) {
        if (!panel.contains(e.target) && e.target !== btn && !btn.contains(e.target)) {
            panel.classList.remove('show');
        }
    });

    // 点击表情插入短代码
    panel.addEventListener('click', function(e) {
        const item = e.target.closest('.emoji-item');
        if (!item || !textarea) return;
        
        const code = item.getAttribute('data-code');
        const start = textarea.selectionStart;
        const end = textarea.selectionEnd;
        const text = textarea.value;
        
        textarea.value = text.substring(0, start) + code + text.substring(end);
        textarea.selectionStart = textarea.selectionEnd = start + code.length;
        textarea.focus();
        panel.classList.remove('show');
    });
});
</script>