<?php 
if (!defined('__TYPECHO_ROOT_DIR__')) exit; 

$this->need('components/header/Head.php'); 

$this->need('components/header/Navbar.php'); ?>

<main class="archive-container" role="main">
    <header class="archive-header">
        <div class="archive-meta-tag">CATEGORY</div>
        <h1 class="archive-title">
            <?php $this->archiveTitle(array(
                'category'  =>  _t('%s'),
                'search'    =>  _t('搜索: %s'),
                'tag'       =>  _t('标签: %s'),
                'author'    =>  _t('作者: %s')
            ), '', ''); ?>
        </h1>
        <p class="archive-description">
            目前共计 <?php echo $this->getTotal(); ?> 篇文章
        </p>
    </header>
    <div class="archive-list">
        <?php if ($this->have()): ?>
            <?php while($this->next()): ?>
                <article class="archive-item" itemscope itemtype="http://schema.org/BlogPosting">
                    <time class="item-date" datetime="<?php $this->date('c'); ?>">
                        <?php $this->date('m . d'); ?>
                    </time>
                    <div class="item-main">
                        <h2 class="item-title" itemprop="name headline">
                            <a itemprop="url" href="<?php $this->permalink() ?>"><?php $this->title() ?></a>
                        </h2>
                    </div>
                </article>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="archive-empty">
                <p>该分类下暂无内容</p>
            </div>
        <?php endif; ?>
    </div>
    <?php if ($this->thePageNav()): ?>
        <nav class="archive-navigator">
            <?php $this->pageNav('&larr;', '&arr;'); ?>
        </nav>
    <?php endif; ?>
</main>
<?php 
$this->need('components/footer/Footer.php'); 
$this->need('components/footer/Foot.php'); ?>
