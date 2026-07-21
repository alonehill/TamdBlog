<style>
#menu-ui-container {
    margin-top: 10px;
    font-family: sans-serif;
}
.menu-item-box {
    border: 1px solid #d9d9d6;
    margin-bottom: 12px;
    border-radius: 4px;
    background: #fff;
    transition: all 0.2s;
}
.menu-item-box[draggable="true"] {
    cursor: grab;
}
.menu-item-box[draggable="true"]:active {
    cursor: grabbing;
}
.menu-item-box.over {
    border: 2px dashed #467b96;
    opacity: 0.8;
    transform: scale(0.99);
}
.menu-item-box.dragging {
    opacity: 0.4;
}
.menu-header {
    display: flex;
    align-items: center;
    padding: 12px 15px;
    background: #fcfcfc;
    border-radius: 4px;
    border-bottom: 1px solid transparent;
    user-select: none;
}
.menu-header:hover {
    background: #f5f5f5;
}
.drag-handle {
    color: #aaa;
    margin-right: 12px;
    font-size: 16px;
    cursor: grab;
}
.menu-title-preview {
    flex-grow: 1;
    font-weight: bold;
    color: #333;
    font-size: 14px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.toggle-icon {
    font-size: 12px;
    color: #888;
    transition: transform 0.2s;
}
.menu-body {
    padding: 15px;
    border-top: 1px solid #eee;
    background: #fafafa;
    border-radius: 0 0 4px 4px;
}
.menu-body label {
    font-size: 13px;
    color: #555;
    display: block;
    margin-bottom: 6px;
    font-weight: bold;
}
.menu-body input,
.menu-body select {
    width: 100%;
    margin-bottom: 15px;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 3px;
    box-sizing: border-box;
    transition: border 0.2s;
}
.menu-body input:focus,
.menu-body select:focus {
    border-color: #467b96;
    outline: none;
}
.menu-btn {
    display: inline-block;
    padding: 0 15px;
    height: 32px;
    line-height: 32px;
    text-align: center;
    color: #fff;
    border: none;
    cursor: pointer;
    border-radius: 3px;
    box-shadow: 0 1px 2px rgba(0,0,0,0.1);
}
.tamd-btn-add {
    background: #122e8a;
    width: 100%;
    font-size: 14px;
    margin-top: 5px;
}
.tamd-btn-remove {
    background: #900021;
    font-size: 12px;
    height: 28px;
    line-height: 28px;
    padding: 0 12px;
}
.action-row {
    text-align: right;
    margin-top: -5px;
}
</style>

<div id="menu-ui-container" style="display:none;">
    <div id="menu-wrapper"></div>
    <button type="button" class="menu-btn tamd-btn-add" id="add-menu-btn">+ 添加新菜单项</button>
</div>

<script>
(function initMenuEditor() {
    var textarea = document.getElementsByName("menuData")[0];
    if (!textarea) {
        setTimeout(initMenuEditor, 200);
        return;
    }
    textarea.style.display = "none";
    var uiContainer = document.getElementById("menu-ui-container");
    uiContainer.style.display = "block";
    textarea.parentNode.insertBefore(uiContainer, textarea.nextSibling);

    var wrapper = document.getElementById("menu-wrapper");
    var addBtn = document.getElementById("add-menu-btn");
    var menus = [];
    var dragSrcIndex = -1;

    try {
        menus = JSON.parse(textarea.value || "[]");
    } catch (e) {
        menus = [];
    }

    // 渲染所有菜单项
    function render() {
        wrapper.innerHTML = "";
        menus.forEach(function(menu, index) {
            var item = document.createElement("div");
            item.className = "menu-item-box";
            item.setAttribute("draggable", "true");
            item.dataset.index = index;

            var isOpen = menu.isOpen === true;
            var displayStyle = isOpen ? 'block' : 'none';
            var iconRotate = isOpen ? 'rotate(0deg)' : 'rotate(-90deg)';

            var previewText = menu.name ? menu.name : "未命名菜单项";
            item.innerHTML =
                '<div class="menu-header">' +
                '<span class="drag-handle" title="按住拖拽排序">☰</span>' +
                '<span class="menu-title-preview">' + previewText + '</span>' +
                '<span class="toggle-icon" style="transform: ' + iconRotate + ';">▼</span>' +
                '</div>' +
                '<div class="menu-body" style="display:' + displayStyle + ';">' +
                '<label>菜单名称</label><input type="text" class="m-name" placeholder="例如：首页">' +
                '<label>链接地址</label><input type="text" class="m-url" placeholder="https:// 或 /">' +
                '<label>打开方式</label><select class="m-target"><option value="_self">当前窗口</option><option value="_blank">新窗口</option></select>' +
                '<div class="action-row"><button type="button" class="menu-btn tamd-btn-remove" data-index="' + index + '">删除此项</button></div>' +
                '</div>';

            // 填入数据
            item.querySelector(".m-name").value = menu.name || "";
            item.querySelector(".m-url").value = menu.url || "";
            item.querySelector(".m-target").value = menu.target || "_self";

            // 拖拽事件
            item.addEventListener('dragstart', handleDragStart);
            item.addEventListener('dragover', handleDragOver);
            item.addEventListener('dragleave', handleDragLeave);
            item.addEventListener('drop', handleDrop);
            item.addEventListener('dragend', handleDragEnd);

            wrapper.appendChild(item);
        });
    }

    // 更新数据并同步预览
    function updateData() {
        var newMenus = [];
        wrapper.querySelectorAll(".menu-item-box").forEach(function(item) {
            var nameVal = item.querySelector(".m-name").value;
            var urlVal = item.querySelector(".m-url").value;
            var isOpen = item.querySelector(".menu-body").style.display !== 'none';
            var previewText = nameVal ? nameVal : "未命名菜单项";
            item.querySelector(".menu-title-preview").innerText = previewText;

            newMenus.push({
                name: nameVal,
                url: urlVal,
                target: item.querySelector(".m-target").value,
                isOpen: isOpen
            });
        });
        menus = newMenus;
        textarea.value = JSON.stringify(menus);
    }

    function handleDragStart(e) {
        dragSrcIndex = parseInt(this.dataset.index);
        e.dataTransfer.effectAllowed = 'move';
        e.dataTransfer.setData('text/plain', dragSrcIndex);
        setTimeout(() => this.classList.add('dragging'), 0);
    }
    function handleDragOver(e) {
        e.preventDefault();
        this.classList.add('over');
        return false;
    }
    function handleDragLeave(e) {
        this.classList.remove('over');
    }
    function handleDrop(e) {
        e.stopPropagation();
        this.classList.remove('over');
        var dropIndex = parseInt(this.dataset.index);
        if (dragSrcIndex !== dropIndex && dragSrcIndex !== -1) {
            updateData();                 // 先保存当前编辑状态
            var movedItem = menus.splice(dragSrcIndex, 1)[0];
            menus.splice(dropIndex, 0, movedItem);
            render();
            updateData();
        }
        return false;
    }
    function handleDragEnd(e) {
        this.classList.remove('dragging');
        wrapper.querySelectorAll('.menu-item-box').forEach(i => i.classList.remove('over'));
    }

    // 监听输入、点击（删除/折叠）
    wrapper.addEventListener("input", updateData);
    wrapper.addEventListener("click", function(e) {
        // 删除按钮
        if (e.target.classList.contains("tamd-btn-remove")) {
            menus.splice(parseInt(e.target.dataset.index), 1);
            render();
            updateData();
            return;
        }
        // 折叠/展开
        var header = e.target.closest('.menu-header');
        if (header) {
            var box = header.closest('.menu-item-box');
            var body = box.querySelector('.menu-body');
            var icon = box.querySelector('.toggle-icon');
            var index = parseInt(box.dataset.index);
            if (body.style.display === 'none') {
                body.style.display = 'block';
                icon.style.transform = 'rotate(0deg)';
                menus[index].isOpen = true;
            } else {
                body.style.display = 'none';
                icon.style.transform = 'rotate(-90deg)';
                menus[index].isOpen = false;
            }
            updateData();
        }
    });

    // 新增菜单项
    addBtn.addEventListener("click", function() {
        updateData();
        menus.forEach(m => m.isOpen = false);
        menus.push({ name: "", url: "", target: "_self", isOpen: true });
        render();
        updateData();
    });

    // 初始化渲染
    render();
})();
</script>