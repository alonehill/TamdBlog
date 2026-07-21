<style>
#fab-ui-container { margin-top: 10px; font-family: sans-serif; }
.fab-item-box { border: 1px solid #d9d9d6; margin-bottom: 12px; border-radius: 4px; background: #fff; transition: all 0.2s; }
.fab-item-box[draggable="true"] { cursor: grab; }
.fab-item-box[draggable="true"]:active { cursor: grabbing; }
.fab-item-box.over { border: 2px dashed #467b96; opacity: 0.8; transform: scale(0.99); }
.fab-item-box.dragging { opacity: 0.4; }
.fab-header { display: flex; align-items: center; padding: 12px 15px; background: #fcfcfc; border-radius: 4px; border-bottom: 1px solid transparent; user-select: none; }
.fab-header:hover { background: #f5f5f5; }
.drag-handle { color: #aaa; margin-right: 12px; font-size: 16px; cursor: grab; }
.fab-title-preview { flex-grow: 1; font-weight: bold; color: #333; font-size: 14px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.toggle-icon { font-size: 12px; color: #888; transition: transform 0.2s; }
.fab-body { padding: 15px; border-top: 1px solid #eee; background: #fafafa; border-radius: 0 0 4px 4px; }
.fab-body label { font-size: 13px; color: #555; display: block; margin-bottom: 6px; font-weight: bold; margin-top: 10px; }
.fab-body input[type="text"], .fab-body select, .fab-body textarea { width: 100%; margin-bottom: 5px; padding: 8px; border: 1px solid #ccc; border-radius: 3px; box-sizing: border-box; transition: border 0.2s; }
.fab-body textarea { height: 80px; resize: vertical; font-family: monospace;}
.fab-body input:focus, .fab-body select:focus, .fab-body textarea:focus { border-color: #467b96; outline: none; }
.checkbox-group { display: flex; gap: 15px; margin-bottom: 10px; }
.checkbox-group label { display: flex; align-items: center; gap: 5px; font-weight: normal; margin-top: 0; }
.checkbox-group input { margin: 0; width: auto; }
.fab-action-btn { display: inline-block; padding: 0 15px; height: 32px; line-height: 32px; text-align: center; color: #fff; border: none; cursor: pointer; border-radius: 3px; box-shadow: 0 1px 2px rgba(0,0,0,0.1); }
.tamd-btn-add { background: #122e8a; width: 100%; font-size: 14px; margin-top: 5px; }
.tamd-btn-remove { background: #900021; font-size: 12px; height: 28px; line-height: 28px; padding: 0 12px; }
.action-row { text-align: right; margin-top: 10px; }
.fixed-badge { font-size: 12px; background: #e3f2fd; color: #0d47a1; padding: 2px 6px; border-radius: 3px; margin-left: 10px; font-weight: normal; }
</style>

<div id="fab-ui-container" style="display:none;">
    <div id="fab-wrapper"></div>
    <button type="button" class="fab-action-btn tamd-btn-add" id="add-fab-btn">+ 添加自定义按钮</button>
</div>

<script>
(function initFabEditor() {
    var textarea = document.getElementById("fabDataTextarea");
    if (!textarea) { setTimeout(initFabEditor, 200); return; }
    
    textarea.style.display = "none";
    var uiContainer = document.getElementById("fab-ui-container");
    uiContainer.style.display = "block";
    textarea.parentNode.insertBefore(uiContainer, textarea.nextSibling);
    
    var wrapper = document.getElementById("fab-wrapper");
    var addBtn = document.getElementById("add-fab-btn");
    var fabItems = [];
    var dragSrcIndex = -1;
    
    var defaultFixedButtons = [
        { id: "fixed_top", isFixed: true, name: "回到顶部", showDesktop: true, showMobile: true, iconType: "custom", iconCustom: "<svg viewBox='0 0 24 24' width='20' height='20' stroke='currentColor' stroke-width='2' fill='none'><polyline points='18 15 12 9 6 15'></polyline></svg>" },
        { id: "fixed_theme", isFixed: true, name: "日夜切换", showDesktop: true, showMobile: true, iconType: "custom", iconCustom: "<svg viewBox='0 0 24 24' width='20' height='20' stroke='currentColor' stroke-width='2' fill='none' stroke-linecap='round' stroke-linejoin='round'><path d='M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z'></path></svg>" },
        { id: "fixed_qr", isFixed: true, name: "页面二维码", showDesktop: true, showMobile: false, iconType: "custom", iconCustom: "<i class=\"fa-solid fa-qrcode\"></i>" }
    ];

    try { fabItems = JSON.parse(textarea.value || "[]"); } catch (e) { fabItems = []; }

    defaultFixedButtons.forEach(function(fixedBtn) {
        if (!fabItems.some(function(item) { return item.id === fixedBtn.id; })) {
            fabItems.push(fixedBtn);
        }
    });

    function render() {
        wrapper.innerHTML = "";
        fabItems.forEach(function(itemData, index) {
            var item = document.createElement("div");
            item.className = "fab-item-box";
            item.setAttribute("draggable", "true");
            item.dataset.index = index;
            
            var isOpen = itemData.isOpen === true;
            var displayStyle = isOpen ? 'block' : 'none';
            var iconRotate = isOpen ? 'rotate(0deg)' : 'rotate(-90deg)';
            var previewText = itemData.name ? itemData.name : "未命名按钮";
            var badgeText = itemData.isFixed ? '<span class="fixed-badge">系统预设(不可删)</span>' : '';
            
            var actionConfigHtml = '';
            var deleteBtnHtml = '';

            if (itemData.isFixed) {
                deleteBtnHtml = '<span style="color:#aaa;font-size:12px;">系统预设按钮：只允许配置外观与显隐，不可删除</span>';
            } else {
                actionConfigHtml = 
                    '<label>功能动作</label>' +
                    '<select class="s-action-type">' +
                        '<option value="link" ' + (itemData.actionType === 'link' ? 'selected' : '') + '>跳转链接</option>' +
                        '<option value="popup" ' + (itemData.actionType === 'popup' ? 'selected' : '') + '>侧边悬浮面板 (HTML)</option>' +
                    '</select>' +
                    '<input type="text" class="s-link" placeholder="输入跳转链接 https://..." style="display:' + (itemData.actionType !== 'popup' ? 'block' : 'none') + ';" value="' + (itemData.link || '') + '">' +
                    '<textarea class="s-popup" placeholder="输入悬浮面板显示的 HTML 代码..." style="display:' + (itemData.actionType === 'popup' ? 'block' : 'none') + ';">' + (itemData.popupHtml || '') + '</textarea>';
                deleteBtnHtml = '<button type="button" class="fab-action-btn tamd-btn-remove" data-index="' + index + '">删除此项</button>';
            }

            var iconType = itemData.iconType || 'custom';
            
            item.innerHTML =
                '<div class="fab-header">' +
                    '<span class="drag-handle" title="按住拖拽排序">☰</span>' +
                    '<span class="fab-title-preview">' + previewText + badgeText + '</span>' +
                    '<span class="toggle-icon" style="transform: ' + iconRotate + ';">▼</span>' +
                '</div>' +
                '<div class="fab-body" style="display:' + displayStyle + ';">' +
                    '<label>按钮名称 (鼠标靠近提示)</label>' +
                    '<input type="text" class="s-name" placeholder="例如：联系我们" value="' + (itemData.name || '') + '">' +
                    
                    '<label>显示设备</label>' +
                    '<div class="checkbox-group">' +
                        '<label><input type="checkbox" class="s-desktop" ' + (itemData.showDesktop !== false ? 'checked' : '') + '> 桌面端显示</label>' +
                        '<label><input type="checkbox" class="s-mobile" ' + (itemData.showMobile !== false ? 'checked' : '') + '> 移动端显示</label>' +
                    '</div>' +

                    '<label>图标设置</label>' +
                    '<select class="s-icon-type">' +
                        '<option value="preset" ' + (iconType === 'preset' ? 'selected' : '') + '>使用内置图标 Class (如 fa fa-home)</option>' +
                        '<option value="custom" ' + (iconType === 'custom' ? 'selected' : '') + '>自定义 SVG / HTML 代码</option>' +
                    '</select>' +
                    '<div class="icon-input-group" style="display:' + (iconType !== 'custom' ? 'flex' : 'none') + ';">' +
                        '<input type="text" class="s-icon-preset" placeholder="如 fa fa-home" value="' + (itemData.iconPreset || '') + '">' +
                        '<button type="button" class="btn-choose-icon">图标库</button>' +
                    '</div>' +
                    '<textarea class="s-icon-custom" placeholder="直接粘贴 <svg>...</svg> 代码" style="display:' + (iconType === 'custom' ? 'block' : 'none') + ';">' + (itemData.iconCustom || '') + '</textarea>' +
                    
                    actionConfigHtml +
                    
                    '<div class="action-row">' + deleteBtnHtml + '</div>' +
                '</div>';
                
            item.addEventListener('dragstart', handleDragStart);
            item.addEventListener('dragover', handleDragOver);
            item.addEventListener('dragleave', handleDragLeave);
            item.addEventListener('drop', handleDrop);
            item.addEventListener('dragend', handleDragEnd);
            wrapper.appendChild(item);
        });
    }

    function updateData() {
        var newItems = [];
        wrapper.querySelectorAll(".fab-item-box").forEach(function(item, index) {
            var oldData = fabItems[index];
            var nameVal = item.querySelector(".s-name").value;
            var iconType = item.querySelector(".s-icon-type").value;
            var actionTypeEl = item.querySelector(".s-action-type");
            var isOpen = item.querySelector(".fab-body").style.display !== 'none';
            
            item.querySelector(".fab-title-preview").innerHTML = (nameVal || "未命名") + (oldData.isFixed ? '<span class="fixed-badge">系统预设(不可删)</span>' : '');

            var newData = {
                id: oldData.id || ('custom_' + Date.now() + Math.random().toString(36).substr(2, 5)),
                isFixed: oldData.isFixed || false,
                name: nameVal,
                showDesktop: item.querySelector(".s-desktop").checked,
                showMobile: item.querySelector(".s-mobile").checked,
                iconType: iconType,
                iconPreset: item.querySelector(".s-icon-preset") ? item.querySelector(".s-icon-preset").value : '',
                iconCustom: item.querySelector(".s-icon-custom") ? item.querySelector(".s-icon-custom").value : '',
                isOpen: isOpen
            };

            if (!oldData.isFixed && actionTypeEl) {
                newData.actionType = actionTypeEl.value;
                newData.link = item.querySelector(".s-link").value;
                newData.popupHtml = item.querySelector(".s-popup").value;
            }
            newItems.push(newData);
        });
        fabItems = newItems;
        textarea.value = JSON.stringify(fabItems);
    }

    function handleDragStart(e) { dragSrcIndex = parseInt(this.dataset.index); e.dataTransfer.effectAllowed = 'move'; e.dataTransfer.setData('text/plain', dragSrcIndex); setTimeout(() => this.classList.add('dragging'), 0); }
    function handleDragOver(e) { e.preventDefault(); this.classList.add('over'); return false; }
    function handleDragLeave(e) { this.classList.remove('over'); }
    function handleDrop(e) {
        e.stopPropagation(); this.classList.remove('over');
        var dropIndex = parseInt(this.dataset.index);
        if (dragSrcIndex !== dropIndex && dragSrcIndex !== -1) {
            updateData();
            var movedItem = fabItems.splice(dragSrcIndex, 1)[0];
            fabItems.splice(dropIndex, 0, movedItem);
            render(); updateData();
        }
        return false;
    }
    function handleDragEnd(e) { this.classList.remove('dragging'); wrapper.querySelectorAll('.fab-item-box').forEach(i => i.classList.remove('over')); }

    wrapper.addEventListener("change", function(e) {
        if(e.target.classList.contains("s-icon-type")) {
            var body = e.target.closest('.fab-body');
            body.querySelector('.icon-input-group').style.display = e.target.value === 'preset' ? 'flex' : 'none';
            body.querySelector('.s-icon-custom').style.display = e.target.value === 'custom' ? 'block' : 'none';
        }
        if(e.target.classList.contains("s-action-type")) {
            var body = e.target.closest('.fab-body');
            body.querySelector('.s-link').style.display = e.target.value === 'link' ? 'block' : 'none';
            body.querySelector('.s-popup').style.display = e.target.value === 'popup' ? 'block' : 'none';
        }
        updateData();
    });
    wrapper.addEventListener("input", updateData);

    wrapper.addEventListener("click", function(e) {
            // 新增图标库按钮
    if (e.target.classList.contains("btn-choose-icon")) {
        // 找到它旁边的 input 输入框，并传入独立函数
        var inputEl = e.target.previousElementSibling;
        showIconPicker(inputEl);
        return;
    }
        if (e.target.classList.contains("tamd-btn-remove")) {
            fabItems.splice(parseInt(e.target.dataset.index), 1);
            render(); updateData(); return;
        }
        var header = e.target.closest('.fab-header');
        if (header) {
            var box = header.closest('.fab-item-box');
            var body = box.querySelector('.fab-body');
            var icon = box.querySelector('.toggle-icon');
            var index = parseInt(box.dataset.index);
            if (body.style.display === 'none') {
                body.style.display = 'block'; icon.style.transform = 'rotate(0deg)'; fabItems[index].isOpen = true;
            } else {
                body.style.display = 'none'; icon.style.transform = 'rotate(-90deg)'; fabItems[index].isOpen = false;
            }
            updateData();
        }
    });

    addBtn.addEventListener("click", function() {
        updateData();
        fabItems.forEach(s => s.isOpen = false);
        fabItems.push({
            id: 'custom_' + Date.now(), isFixed: false, name: "", showDesktop: true, showMobile: true,
            iconType: "preset", iconPreset: "", actionType: "link", link: "", popupHtml: "", isOpen: true
        });
        render(); updateData();
    });

    render();
    updateData();
})();
</script>