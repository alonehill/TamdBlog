<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php $this->need('header.php'); ?>

<main class="page-main-container">
    <article class="page-content-box">
        <header class="page-header">
            <h1 class="page-title"><?php $this->title() ?></h1>
        </header>

        <div class="post-content">
            <?php $this->content(); ?>
        </div>

        <?php $this->need('comments.php'); ?>
    </article>
</main>
<?php $this->need('footer.php'); ?>
