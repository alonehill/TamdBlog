<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php $this->need('header.php'); ?>

<main class="error-container">
    <div class="error-box">
        <h1 class="error-code">404</h1>
        <h2 class="error-title">页面迷路了</h2>
        <p class="error-desc">你所寻找的页面可能已经移动，或者在创作的宇宙中从未存在过。</p>
        
        <div class="error-actions">
            <a href="<?php $this->options->siteUrl(); ?>" class="btn-back-home">
                回到首页
            </a>
        </div>
    </div>
</main>

<?php $this->need('footer.php'); ?>
