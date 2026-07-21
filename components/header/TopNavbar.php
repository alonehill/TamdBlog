<?php if ($this->options->navStyle == 'nav-left'): ?>
<style>
.navbar-collapse-zone {
    flex: 1;
    justify-content: space-between;
}

.navbar-menu {
    margin-left: 12px;
}
@media (max-width: 768px) {
    .navbar-menu { 
        margin-left: 0;
        
    }
}
</style>
<?php else: ?>
<style></style>
<?php endif; ?>