<script src="<?php $this->options->themeUrl('static/js/qrcodejs/qrcode.js'); ?>"></script>
<script src="<?php $this->options->themeUrl('static/js/script.js'); ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
<script>

</script>
<!-- 自定义底部 HTML -->
<?php if ($this->options->customFooterHtml): ?>
    <?php $this->options->customFooterHtml(); ?>
<?php endif; ?>

<!-- 自定义 JS -->
<?php if ($this->options->customJs): ?>
    <script type="text/javascript">
        <?php $this->options->customJs(); ?>
    </script>
<?php endif; ?>

<!-- 网站统计代码 -->
<?php if ($this->options->analyticsCode): ?>
    <div style="display:none;">
        <?php $this->options->analyticsCode(); ?>
    </div>
<?php endif; ?>
<?php $this->footer(); ?>
</body>
</html>