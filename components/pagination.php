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