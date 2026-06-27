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
$this->need('header.php');
?>
<div id="body">
<?php if ($this->options->sliderStatus == 'on') { ?>

    <?php 
    $slug = $this->options->sliderSlug ? $this->options->sliderSlug : 'slider';
    $num = $this->options->sliderNum ? $this->options->sliderNum : 3;
    
    $this->widget('Widget_Archive@index', 'pageSize=' . $num . '&type=category', 'slug=' . $slug)->to($sliderCategory); 
    if ($sliderCategory->have()) {
    ?>

    <div id="topSlide" class="carousel slide carousel-fade containerxx" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <?php for ($i = 0; $i < $num; $i++): ?>
                <button type="button" data-bs-target="#topSlide" data-bs-slide-to="<?php echo $i; ?>" <?php echo $i == 0 ? 'class="active" aria-current="true"' : ''; ?> aria-label="Slide <?php echo $i + 1; ?>"></button>
            <?php endfor; ?>
        </div>
        <div id="topSlideImg" class="carousel-inner">
        <?php $index = 0; ?>
        <?php while($sliderCategory->next()): ?>
            <div class="carousel-item topslideimg <?php echo $index == 0 ? 'active' : ''; ?>" style="background-image: url(<?php echo get_post_img($sliderCategory); ?>);">
                <div class="topslidecontent">
                    <div class="text-center">
                        <h1 class="fw-bold text-shadow carousel-text-shadow"><?php $sliderCategory->title(); ?></h1>
                        <p class="mt-md-4 carousel-text-shadow"><?php $sliderCategory->excerpt(60, '...'); ?></p>
                    </div>
                </div>
            </div>
        <?php $index++; ?>
        <?php endwhile; ?>
        </div>
    </div>
    <?php
    } else {
    ?>
    <div id="topSlide" class="carousel slide carousel-fade containerxx" data-bs-ride="carousel">
        <div id="topSlideImg" class="carousel-inner">
            <div class="carousel-item topslideimg active" style="background-image: url('<?php $this->options->themeUrl("static/img/404-bg.jpg");?>');">
                <div class="topslidecontent">
                    <div class="text-center">
                        <h1 class="fw-bold text-shadow carousel-text-shadow"></h1>
                        <p class="mt-md-4 carousel-text-shadow">未在幻灯片分类下设置文字，先去写一篇带有图片或者图片附件的文章吧</p >
                    </div>
                </div>
            </div>
       
        </div>
    </div>
    
    <?php
    }
    ?>
<?php } else { ?>
    <div class="" style="height: 64px!important;">
        
    </div>
<?php } ?>
    <div class="container">
        <div class="row">
            <main class="main-content <?php if ($this->options->sidebarStatus == 'on'): ?> col-lg-8 <?php else: ?> <?php endif; ?>">
                <section class="article-list">
                    <?php while($this->next()): ?>
                    <a href="<?php $this->permalink() ?>" class="index-post-card">
                        <?php 
                            $thumb = '';
                            preg_match_all("/<img class=\"\" src=\"(.*?)\"/", $this->content, $matches);
                            if(isset($matches[1][0])){
                                $thumb = $matches[1][0];
                            } else {
                                $thumb = 'https://picsum.photos/800/600?random=' . $this->cid;
                            }
                        ?>
                        <img class="index-post-bg" src="<?php echo get_post_img($this); ?>" onerror="this.src='<?php echo $thumb; ?>'" alt="<?php $this->title() ?>" loading="lazy">
                        <div class="index-post-content-glass">
                            <div class="vpost-meta-top">
                                <span class="index-post-category"><?php $this->category(',', false); ?></span>
                            </div>
                            <h2 class="index-post-title">
                                <?php $this->title() ?>
                            </h2>
                            <span class="index-post-excerpt fw-semibold">
                                <?php $this->excerpt(70, '...'); ?>
                            </span>
                            <div class="index-post-meta-bottom">
                                <span class="index-meta-item date">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 32 32" fill="none">
                                        <rect width="32" height="32" rx="8" fill="#FFFFFF"/>
                                        <rect x="6" y="9" width="20" height="16" rx="3" fill="#000000"/>
                                        <line x1="6" y1="14" x2="26" y2="14" stroke="#FFFFFF" stroke-width="2.5"/>
                                        <rect x="10" y="6" width="3" height="5" rx="1" fill="#000000"/>
                                        <rect x="19" y="6" width="3" height="5" rx="1" fill="#000000"/>
                                    </svg>
                                    <?php $this->date('Y 年 m 月 j 日'); ?>
                                </span>
                                <div>
                                    <span class="index-meta-item author">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 32 32" fill="none">
                                            <rect width="32" height="32" rx="8" fill="#FFFFFF"/>
                                            <circle cx="16" cy="11" r="5" fill="#000000"/>
                                            <path d="M25 25c0-4-4.5-7-9-7s-9 3-9 7a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2Z" fill="#000000"/>
                                        </svg>
                                        <?php $this->author(); ?>
                                    </span>
                                    <span class="index-meta-item read">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 32 32" fill="none">
                                            <rect width="32" height="32" rx="8" fill="#FFFFFF"/>
                                            <path d="M16 10C9 10 4 16 4 16s5 6 12 6 12-6 12-6-5-6-12-6Z" fill="#000000"/>
                                            <circle cx="16" cy="16" r="4.5" fill="#FFFFFF"/>
                                            <circle cx="16" cy="16" r="2.5" fill="#000000"/>
                                        </svg>
                                        <?php get_post_view($this) ?> 次阅读
                                    </span>
                                    <span class="index-meta-item index-comments">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 32 32" fill="none">
                                            <rect width="32" height="32" rx="8" fill="#FFFFFF"/>
                                            <path d="M16 7C10.5 7 6 11 6 16c0 2.8 1.4 5.3 3.6 6.9V27l4.5-2.5c.6.2 1.3.3 1.9.3 5.5 0 10-4 10-9s-4.5-9-10-9Z" fill="#000000"/>
                                        </svg>
                                        <?php $this->commentsNum('%d 条评论'); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                    <?php endwhile; ?>
                </section>
                <!-- 分页卡片容器 -->
                <div class="pagination-wrapper">
                    <div class="page-pagination">
                        <?php $this->pageNav('&laquo; PREV', 'NEXT &raquo;'); ?>
                    </div>
    
                    <div class="ajax-load-status">
                        <button type="button" class="btn-load-more" id="ajaxLoadBtn">
                            <span>加载更多</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                        </button>

                        <span class="status-loading">
                            <svg class="spinner" viewBox="0 0 50 50"><circle class="path" cx="25" cy="25" r="20" fill="none" stroke-width="5"></circle></svg>
                            正在加载中...
                        </span>
                        <span class="status-nomore">END OF STATION / 已加载全部</span>
                    </div>
                </div>
            </main>
            <!-- 右侧侧边栏区 -->
            <?php if ($this->options->sidebarStatus == 'on'): ?>
            <div class="col-lg-4 sidebar-wrapper">
                <?php $this->need('sidebar.php'); ?>
            </div>
            <?php endif; ?>
        </div><!-- end .row -->
    </div><!-- end .container -->
</div><!-- end #body -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const loadBtn = document.getElementById('ajaxLoadBtn');
    if (!loadBtn) return;

    //--------------------------------------------------
    // 使用文章列表最外层容器的类名或id，Ajax才能有效加载文章列表
    // class.article-list
    //--------------------------------------------------
    const postContainerSelector = '.article-list'; 

    const getNextPageUrl = () => {
        const nextLinkElement = document.querySelector('.page-pagination .next a');
        return nextLinkElement ? nextLinkElement.href : null;
    };

    if (!getNextPageUrl()) {
        loadBtn.style.display = 'none';
    }

    loadBtn.addEventListener('click', function(e) {
        e.preventDefault();
        
        const nextUrl = getNextPageUrl();
        if (!nextUrl) return;

        const loadingText = document.querySelector('.status-loading');
        const nomoreText = document.querySelector('.status-nomore');

        loadBtn.style.display = 'none';
        loadingText.style.display = 'inline-flex';

        fetch(nextUrl)
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                
                const currentContainer = document.querySelector(postContainerSelector);
                const newItems = doc.querySelectorAll(`${postContainerSelector} > *`);
                
                // 拼装新文章到文zhang列表下方
                if (currentContainer && newItems.length > 0) {
                    newItems.forEach(item => currentContainer.appendChild(item));
                }

                const oldPagination = document.querySelector('.page-pagination');
                const newPagination = doc.querySelector('.page-pagination');
                if (oldPagination && newPagination) {
                    oldPagination.innerHTML = newPagination.innerHTML;
                }

                const hasMore = doc.querySelector('.page-pagination .next a');
                loadingText.style.display = 'none';

                if (hasMore) {
                    loadBtn.style.display = 'inline-flex';
                } else {
                    nomoreText.style.display = 'inline-flex';
                }
            })
            .catch(err => {
                console.error('AJAX Load Error:', err);
                window.location.href = nextUrl;
            });
    });
});
</script>

<?php $this->need('footer.php'); ?>
