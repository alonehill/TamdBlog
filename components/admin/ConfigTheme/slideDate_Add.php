<style>
#slide-ui-container { 
    margin-top: 10px; 
    font-family: sans-serif; 
}
.slide-item-box { 
    border: 1px solid #d9d9d6; 
    margin-bottom: 12px; 
    border-radius: 4px; 
    background: #fff; 
    transition: all 0.2s; 
}
.slide-item-box[draggable="true"] { 
    cursor: grab; 
}
.slide-item-box[draggable="true"]:active { 
    cursor: grabbing; 
}
.slide-item-box.over { 
    border: 2px dashed #467b96; 
    opacity: 0.8; 
    transform: scale(0.99); 
}
.slide-item-box.dragging { 
    opacity: 0.4; 
}
.slide-header { 
    display: flex; 
    align-items: center; 
    padding: 12px 15px; 
    background: #fcfcfc; 
    border-radius: 4px; 
    border-bottom: 1px solid transparent; 
    user-select: none; 
}
.slide-header:hover { 
    background: #f5f5f5; 
}
.drag-handle { 
    color: #aaa; 
    margin-right: 12px; 
    font-size: 16px; 
    cursor: grab; 
}
.slide-title-preview { 
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
.slide-body { 
    padding: 15px; 
    border-top: 1px solid #eee; 
    background: #fafafa; 
    border-radius: 0 0 4px 4px; 
}
.slide-body label { 
    font-size: 13px; 
    color: #555; 
    display: block; 
    margin-bottom: 6px; 
    font-weight: bold; 
}
.slide-body input { 
    width: 100%; 
    margin-bottom: 15px; 
    padding: 8px; 
    border: 1px solid #ccc; 
    border-radius: 3px; 
    box-sizing: border-box; 
    transition: border 0.2s; 
}
.slide-body input:focus { 
    border-color: #467b96; 
    outline: none; 
}
.slide-btn { 
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
.btn-add { 
    background: #467b96; 
    width: 100%; 
    font-size: 14px; 
    margin-top: 5px; 
}
.btn-add:hover { 
    background: #3b667d; 
}
.btn-remove { 
    background: #e85600; 
    font-size: 12px; 
    height: 28px; 
    line-height: 28px; 
    padding: 0 12px; 
}
.btn-remove:hover { 
    background: #c94a00; 
}
.action-row { 
    text-align: right; 
    margin-top: -5px; 
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
</style>
<div id="slide-ui-container" style="display:none;">
    <div id="slide-wrapper"></div>
    <button type="button" class="slide-btn tamd-btn-add" id="add-slide-btn">+ 添加新幻灯片</button>
</div>
<script>
(function initSlideEditor() {
    var textarea = document.getElementsByName("slideData")[0];
    if (!textarea) {
        setTimeout(initSlideEditor, 200);
        return;
    }
    textarea.style.display = "none";
    var uiContainer = document.getElementById("slide-ui-container");
    uiContainer.style.display = "block";
    textarea.parentNode.insertBefore(uiContainer, textarea.nextSibling);
    var wrapper = document.getElementById("slide-wrapper");
    var addBtn = document.getElementById("add-slide-btn");
    var slides = [];
    var dragSrcIndex = -1; // 记录拖拽元素的索引
    try { slides = JSON.parse(textarea.value || "[]"); } catch (e) { slides = []; }
    // 渲染界面
    function render() {
        wrapper.innerHTML = "";
        slides.forEach(function(slide, index) {
            var item = document.createElement("div");
            item.className = "slide-item-box";
            item.setAttribute("draggable", "true"); // 开启拖拽
            item.dataset.index = index;
            // 默认老数据收起，新数据展开
            var isOpen = slide.isOpen === true;
            var displayStyle = isOpen ? 'block' : 'none';
            var iconRotate = isOpen ? 'rotate(0deg)' : 'rotate(-90deg)'; // 箭头动画
            // 实时预览标题逻辑
            var previewText = slide.title ? slide.title : (slide.img ? "未命名幻灯片 (已配图)" : "新建幻灯片 - 请展开编辑");
            item.innerHTML =
                '<div class="slide-header">' +
                '<span class="drag-handle" title="按住拖拽排序">☰</span>' +
                '<span class="slide-title-preview">' + previewText + '</span>' +
                '<span class="toggle-icon" style="transform: ' + iconRotate + ';">▼</span>' +
                '</div>' +
                '<div class="slide-body" style="display:' + displayStyle + ';">' +
                '<label>图片链接</label><input type="text" class="s-img" placeholder="https://...">' +
                '<label>标题</label><input type="text" class="s-title" placeholder="显示在幻灯片上的大标题">' +
                '<label>描述</label><input type="text" class="s-desc" placeholder="简短的一段描述...">' +
                '<label>跳转链接</label><input type="text" class="s-link" placeholder="点击幻灯片跳转的网页地址">' +
                '<div class="action-row"><button type="button" class="slide-btn tamd-btn-remove" data-index="' + index + '">删除此项</button></div>' +
                '</div>';
            // 填充数据
            item.querySelector(".s-img").value = slide.img || "";
            item.querySelector(".s-title").value = slide.title || "";
            item.querySelector(".s-desc").value = slide.desc || "";
            item.querySelector(".s-link").value = slide.link || "";
            // 绑定拖拽事件
            item.addEventListener('dragstart', handleDragStart);
            item.addEventListener('dragover', handleDragOver);
            item.addEventListener('dragleave', handleDragLeave);
            item.addEventListener('drop', handleDrop);
            item.addEventListener('dragend', handleDragEnd);
            wrapper.appendChild(item);
        });
    }
    // 数据更新与实时预览
    function updateData() {
        var newSlides = [];
        wrapper.querySelectorAll(".slide-item-box").forEach(function(item) {
            var titleVal = item.querySelector(".s-title").value;
            var imgVal = item.querySelector(".s-img").value;
            var isOpen = item.querySelector(".slide-body").style.display !== 'none';
            // 实时更新头部横条的预览文本
            var previewText = titleVal ? titleVal : (imgVal ? "未命名幻灯片 (已配图)" : "新建幻灯片 - 请展开编辑");
            item.querySelector(".slide-title-preview").innerText = previewText;
            newSlides.push({
                img: imgVal,
                title: titleVal,
                desc: item.querySelector(".s-desc").value,
                link: item.querySelector(".s-link").value,
                isOpen: isOpen // 保存卡片的伸缩状态
            });
        });
        slides = newSlides;
        textarea.value = JSON.stringify(slides);
    }
    // 拖拽排序
    function handleDragStart(e) {
        dragSrcIndex = parseInt(this.dataset.index);
        e.dataTransfer.effectAllowed = 'move';
        e.dataTransfer.setData('text/plain', dragSrcIndex); // 兼容 Firefox
        setTimeout(() => this.classList.add('dragging'), 0);
    }
    function handleDragOver(e) {
        e.preventDefault(); // 允许放置
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
        // 如果位置发生了变化
        if (dragSrcIndex !== dropIndex && dragSrcIndex !== -1) {
            updateData(); // 拖拽前先保存当前输入的文字
            var movedItem = slides.splice(dragSrcIndex, 1)[0];
            slides.splice(dropIndex, 0, movedItem); // 调换位置
            render();     // 重新渲染
            updateData(); // 更新数据
        }
        return false;
    }
    function handleDragEnd(e) {
        this.classList.remove('dragging');
        wrapper.querySelectorAll('.slide-item-box').forEach(i => i.classList.remove('over'));
    }
    // 点击事件(折叠与删除)
    wrapper.addEventListener("input", updateData);
    wrapper.addEventListener("click", function(e) {
        // 删除
        if (e.target.classList.contains("tamd-btn-remove")) {
            slides.splice(parseInt(e.target.dataset.index), 1);
            render();
            updateData();
            return;
        }
        // 折叠/展开
        var header = e.target.closest('.slide-header');
        if (header) {
            var box = header.closest('.slide-item-box');
            var body = box.querySelector('.slide-body');
            var icon = box.querySelector('.toggle-icon');
            var index = parseInt(box.dataset.index);
            if (body.style.display === 'none') {
                body.style.display = 'block';
                icon.style.transform = 'rotate(0deg)';
                slides[index].isOpen = true;
            } else {
                body.style.display = 'none';
                icon.style.transform = 'rotate(-90deg)';
                slides[index].isOpen = false;
            }
            updateData();
        }
    });
    // 新增幻灯片
    addBtn.addEventListener("click", function() {
        updateData();
        // 新增时自动收起其他幻灯片，展开新创建的这一个
        slides.forEach(s => s.isOpen = false);
        slides.push({img: "", title: "", desc: "", link: "", isOpen: true});
        render();
        updateData();
    });
    render();
})();

</script>