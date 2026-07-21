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
            <?php if ($this->options->logoUrlLight): ?>
                    <img class="logo-color" src="<?php $this->options->logoUrlDark(); ?>" />
                    <img class="logo-bw" src="<?php $this->options->logoUrlLight(); ?>" />
                <?php else: ?>
                    <?php $this->options->title() ?>
                <?php endif; ?>
        </a>
        <label for="nav-toggle" class="nav-toggle-btn">
            <span class="hamburger-line"></span>
        </label>
        <label for="nav-toggle" class="nav-mask"></label>

        <div class="navbar-collapse-zone">
            <nav class="navbar-menu">
                <?php $this->widget('Widget_Contents_Page_List')->to($pages); 
                if ($this->options->defaultMenu == 'on'): 
                     while($pages->next()): 
                ?>
                    <a href="<?php $pages->permalink(); ?>"><?php $pages->title(); ?></a>
                <?php 
                    endwhile; 
                endif;
                ?>

                <?php 
                    $menus = !empty($this->options->menuData) ? json_decode($this->options->menuData, true) : [];
    
                    if (!empty($menus)): 
                ?><?php foreach ($menus as $index => $menu): ?>
                <a href="<?php echo htmlspecialchars($menu['url']); ?>" target="<?php echo htmlspecialchars($menu['target']); ?>"><?php echo htmlspecialchars($menu['name']); ?></a>
                <?php endforeach; ?>
                <?php else: ?>   <?php endif; ?>
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