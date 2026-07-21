<main class="search-container" role="main">
    
    <header class="search-header">
        <div class="search-meta-tag">SEARCH STATION</div>
        <form id="search" method="post" action="<?php $this->options->siteUrl(); ?>" role="search" class="search-form">
            <input type="text" id="s" name="s" class="search-input" 
                   placeholder="输入关键词，回车探索..." 
                   value="<?php $this->archiveTitle(array('search' => '%s'), '', ''); ?>" required autocomplete="off" />
        </form>
        
        <p class="search-status">
            <?php if($this->is('search')): ?>
                关于 “<span class="keyword"><?php $this->archiveTitle(array('search' => '%s'), '', ''); ?></span>” 
                共寻得 <?php echo $this->getTotal(); ?> 个相关结果
            <?php else: ?>
                探索本站的所有文字与灵感
            <?php endif; ?>
        </p>
    </header>

    <div class="search-list">
        <?php if ($this->have()): ?>
            <?php while($this->next()): ?>
                <article class="search-item" itemscope itemtype="http://schema.org/BlogPosting">
                    <time class="item-date" datetime="<?php $this->date('c'); ?>">
                        <?php $this->date('m . d'); ?>
                    </time>
                    
                    <div class="item-main">
                        <h2 class="item-title" itemprop="name headline">
                            <a itemprop="url" href="<?php $this->permalink() ?>"><?php $this->title() ?></a>
                        </h2>
                        <span class="item-category"><?php $this->category(' · '); ?></span>
                    </div>
                </article>
            <?php endwhile; ?>
        <?php else: ?>
            <?php if($this->is('search')): ?>
                <div class="search-empty">
                    <div class="empty-icon">✕</div>
                    <p>未找到相关内容，尝试换个词敲击回车</p>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <?php if ($this->thePageNav()): ?>
        <nav class="search-navigator">
            <?php $this->pageNav('&larr;', '&arr;'); ?>
        </nav>
    <?php endif; ?>

</main>