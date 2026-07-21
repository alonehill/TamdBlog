<link rel="stylesheet" href="<?php $this->options->themeUrl('static/css/post.css'); ?>">
<div class="container">
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
                        <span class="post-mate-item">
                            <?php get_post_view($this); ?> 次阅读
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
            <div class="post-content text-content tamd-article" itemprop="articleBody">
                <?php echo $this->content(); ?>
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
                '); ?>
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
                ');?>
            </div>
        </nav>
        
        <section class="post-comments-section">
            <?php $this->need('comments.php'); ?>
        </section>
        </div>
    </main>
    <?php if ($this->options->postToc == 'on'): ?>
    <aside class="toc-wrapper" id="tocWrapper">
        <div class="toc-title">
            文章目录
        </div>
        <nav id="tocNav">
            <ul class="toc-list" id="tocList">
                <li class="toc-empty">正在提取标题...</li>
            </ul>
        </nav>
    </aside>
    <?php endif; ?>
</div>
<?php if ($this->options->postToc == 'on'): ?>
<div class="toc-fab-wrapper" id="tocFabWrapper">
    <button class="toc-fab" id="tocFab" aria-label="打开段落导航">
      <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
        <path d="M4 6h16M4 12h16M4 18h10" />
        <circle cx="20" cy="6" r="1.5" fill="white" stroke="none" />
        <circle cx="20" cy="12" r="1.5" fill="white" stroke="none" />
        <circle cx="20" cy="18" r="1.5" fill="white" stroke="none" />
      </svg>
      目录
      <span class="toc-fab-badge" id="tocBadge">0</span>
    </button>

    <div class="toc-drawer" id="tocDrawer" role="dialog" aria-label="段落导航">
      <div class="toc-title">
        <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
          <path d="M4 6h16M4 12h16M4 18h10" />
          <circle cx="20" cy="6" r="1.5" fill="#3b82f6" stroke="none" />
          <circle cx="20" cy="12" r="1.5" fill="#3b82f6" stroke="none" />
          <circle cx="20" cy="18" r="1.5" fill="#3b82f6" stroke="none" />
        </svg>
        段落导航
      </div>
      <nav>
        <ul class="toc-list" id="tocListMobile">
          <li class="toc-empty">加载中...</li>
        </ul>
      </nav>
    </div>
  </div>
  <div class="toc-overlay" id="tocOverlay"></div>
  <?php endif; ?>
<?php if ($this->options->postToc == 'on'): ?>

<script src="<?php $this->options->themeUrl('static/js/post-sidebar.js'); ?>" defer></script>
<?php endif; ?>
<script src="<?php $this->options->themeUrl('static/js/post.js'); ?>" defer></script>