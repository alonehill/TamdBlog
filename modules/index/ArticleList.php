<section class="article-list  <?php $this->options->indexPostListStyle(); ?>">
    <?php while($this->next()): ?>
    <a href="<?php $this->permalink() ?>" class="index-post-card">
    <?php 
        $thumb = '';
        $baseUrl = $this->options->themeUrl . '/api/random-images/';
        $bgUrl = $baseUrl . '?w=800&h=300&id=' . $this->cid;
        // 缩略图暂时废弃
        $frontUrl = $baseUrl . '?w=300&h=300&id=' . $this->cid;
    ?>
        <img class="index-post-bg" src="<?php echo $this->fields->thumb; ?>" onerror="this.src='<?php echo $bgUrl; ?>'" alt="<?php $this->title() ?>" loading="lazy">
        <div class="index-post-front-img-box">
            <img src="<?php echo $this->fields->thumb; ?>" onerror="this.src='<?php echo $bgUrl; ?>'" alt="<?php $this->title() ?>" loading="lazy">
        </div>
        <div class="index-post-content-glass">
            <div class="index-post-meta-top">
                <span class="index-post-category"><i class="fa-regular fa-folder-open"></i> <?php $this->category('</span><span class="index-post-category"><i class="fa-regular fa-folder-open"></i> ', false); ?></span>
                <?php if ($this->tags): ?>
                    <span class="index-post-tag"><i class="fa-regular fa-bookmark"></i> <?php $this->tags('</span><span class="index-post-tag"><i class="fa-regular fa-bookmark"></i> ', false, ''); ?></span>
                <?php endif; ?>
            </div>
            <h2 class="index-post-title">
                <?php $this->title() ?>
            </h2>
            <span class="index-post-excerpt fw-semibold">
                <?php $this->excerpt(70, '...'); ?>
            </span>
            <div class="index-post-meta-bottom">
                <span class="index-meta-item date">
                    <?php if ($this->options->indexPostIcon == 'b') { ?>
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 32 32" fill="none">
                        <rect width="32" height="32" rx="8" fill="#0093FF"/>
                        <rect x="6" y="9" width="20" height="16" rx="3" fill="#FFFFFF"/>
                        <line x1="6" y1="14" x2="26" y2="14" stroke="#0093FF" stroke-width="2.5"/>
                        <rect x="10" y="6" width="3" height="5" rx="1" fill="#FFFFFF"/>
                        <rect x="19" y="6" width="3" height="5" rx="1" fill="#FFFFFF"/>
                    </svg>
                    <?php } elseif ($this->options->indexPostIcon == 'a') { ?>
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                    <?php } ?>
                    <?php $this->date('Y 年 m 月 j 日'); ?>
                </span>
                <div>
                    <!--
                    <span class="index-meta-item author">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 32 32" fill="none">
                            <rect width="32" height="32" rx="8" fill="#FFFFFF"/>
                            <circle cx="16" cy="11" r="5" fill="#000000"/>
                            <path d="M25 25c0-4-4.5-7-9-7s-9 3-9 7a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2Z" fill="#000000"/>
                        </svg>
                        <?php $this->author(); ?>
                    </span>
                    -->
                    <span class="index-meta-item read me-1">
                        <?php if ($this->options->indexPostIcon == 'b') { ?>
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 32 32" fill="none">
                            <rect width="32" height="32" rx="8" fill="#06CC76"/>
                            <path d="M16 10C9 10 4 16 4 16s5 6 12 6 12-6 12-6-5-6-12-6Z" fill="#FFFFFF"/>
                            <circle cx="16" cy="16" r="4.5" fill="#06CC76"/>
                            <circle cx="16" cy="16" r="2.5" fill="#FFFFFF"/>
                        </svg>
                                        
                        <?php } elseif ($this->options->indexPostIcon == 'a') { ?>
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                        <?php } ?>
                        <?php get_post_view($this) ?> 次阅读
                    </span>
                    <span class="index-meta-item index-comments">
                        <?php if ($this->options->indexPostIcon == 'b') { ?>
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 32 32" fill="none">
                            <rect width="32" height="32" rx="8" fill="#FA0202"/>
                            <path d="M16 7C10.5 7 6 11 6 16c0 2.8 1.4 5.3 3.6 6.9V27l4.5-2.5c.6.2 1.3.3 1.9.3 5.5 0 10-4 10-9s-4.5-9-10-9Z" fill="#FFFFFF"/>
                        </svg>
                                        
                        <?php } elseif ($this->options->indexPostIcon == 'a') { ?>
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                        </svg>
                        <?php } ?>
                        <?php $this->commentsNum('%d 条评论'); ?>
                    </span>
                </div>
            </div>
        </div>
    </a>
    <?php endwhile; ?>
</section>