<style>
    body {
        background: url('<?php echo $themeUrl;?>/adstatic/img/bg/tamdblog-admin-bg@100.jpg') no-repeat center center fixed !important; 
        background-size: cover !important;
    }
    @media (max-width: 768px) {
        body {
        background: url('<?php echo $themeUrl;?>/adstatic/img/bg/tamdblog-admin-bg@60.jpg') no-repeat center center fixed !important; 
    }

    }
    main {
        /*
        background: rgba(255, 255, 255, 0.8) !important;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        */
    }
    .row {
        margin-right: 0px; 
        margin-left: 0px;
        padding-right: 0px!important;
        padding-left: 0px!important;
    }

    .tamd-h2 {
        font-size: 1.45em;
        color: #0f172a;
        font-weight: 700;
        margin: 0 0 1em;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .tamd-h2::before {
        content: '';
        display: inline-block;
        width: 6px;
        height: 1.25em;
        background-color: #0969da;
        border-radius: 4px;
    }
    /*导航栏样式选择*/
    .nav-style-selector input[type="radio"] {
            display: none;
        }
    
        .nav-style-selector span {
            display: flex !important;
            flex-wrap: wrap !important;
            gap: 0px !important;
            height: max-content !important;
            min-height: max-content !important;
            margin-top: 15px !important;
            margin-right: 0px !important;
            align-items: stretch !important;
        }

        .nav-style-selector span label {
            display: block;
            cursor: pointer;
            border: 2px solid #e2e8f0;
            border-radius: 2px;
            padding: 1px;
            text-align: center;
            transition: all 0.2s ease;
            background: #ffffff;
            color: #64748b;
        }

        .nav-style-selector span label img {
            width: 100%;
            height: auto;
            display: block;
            border-radius: 2px;
        }

        .nav-style-selector input[type="radio"]:checked + label {
            border-color: #3b82f6;
            background: #eff6ff;
            color: #1e3a8a;        
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
        }

    /* 新功能提示徽章 */
            .badge-fresh {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                background: #e8f5e9;
                color: #2e7d32;
                font-size: 12px;
                font-weight: 600;
                height: 24px;
                padding: 0 8px;
                border-radius: 12px;
                margin-left: 1px;
                margin-right: 1px!important;
                vertical-align: middle;
                letter-spacing: 0.3px;
            }
        
    .typecho-page-title, .typecho-option-tabs {
        background: rgba(255, 255, 255, 0.8) !important;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        padding: 20px !important;
        border-radius: 12px !important;
        margin-top: 10px !important;
        margin-bottom: 0px !important;
 
    }

    .typecho-option-tabs li a {
        background: rgba(255, 255, 255, 0.8) !important;
        border-radius: 6px !important;
        margin-right: 5px !important;
    }

    .typecho-foot {
        background: rgba(255, 255, 255, 0.8) !important;
        backdrop-filter: blur(10px);
        padding: 20px !important;
        margin-top: 30px;
        color: #555 !important;
    }

    .typecho-option {
        background: rgba(255, 255, 255, 0.6) !important;
        padding: 15px 20px !important;
        margin-bottom: 15px !important;
        border-radius: 8px !important;
    }

    .typecho-page-main {
        
        background: rgba(255, 255, 255, 0.8) !important;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        padding: 20px 10px !important;
        border-radius: 12px !important;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.05) !important;
        margin-top: 10px !important;
    }

    .typecho-option {
        background: rgba(255, 255, 255, 0.6) !important;
        padding: 15px 20px !important;
        margin-bottom: 15px !important;
        border-radius: 8px !important;
        box-shadow: 0 2px 10px rgba(0,0,0,0.03) !important;
        transition: all 0.3s ease;
    }

    .typecho-option:hover {
        background: rgba(255, 255, 255, 0.8) !important;
        box-shadow: 0 5px 15px rgba(0,0,0,0.06) !important;
    }

    .typecho-page-title h2 {
        color: #333 !important;
        text-shadow: 0 1px 1px rgba(255,255,255,0.5);
    }

    input[type=\"text\"], input[type=\"password\"], textarea, select {
        background: rgba(255, 255, 255, 0.8) !important;
        border: 1px solid #ddd !important;
    }
</style>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // 获取表单和提交按钮 (排除文章/页面编辑器)
        var form = document.querySelector('form:not(#write_post):not(#write_page)');
        if (!form) return;
        
        var submitBtn = form.querySelector('button[type=\"submit\"]');
        if (!submitBtn) return;
        
        // 创建悬浮提示框
        var banner = document.createElement('div');
        banner.id = 'typecho-save-reminder';
        banner.style.cssText = 'display: none; opacity: 0; transition: opacity 0.3s ease; position: fixed; bottom: 30px; right: 30px; background: #fff; padding: 15px 25px; box-shadow: 0 4px 20px rgba(0,0,0,0.15); border-radius: 4px; z-index: 9999; border-left: 4px solid #467b96; align-items: center; justify-content: space-between; min-width: 280px;';
        
        banner.innerHTML = 
            '<span style=\"color:#444; font-size:14px; font-weight:bold;\">⚠️ 您有未保存的设置更改</span>' +
            '<button id=\"typecho-reminder-save-btn\" style=\"background:#467b96; color:#fff; border:none; padding:6px 16px; border-radius:3px; cursor:pointer; font-size:13px; margin-left:20px; outline:none; transition:0.2s;\">立即保存</button>';
            
        document.body.appendChild(banner);
        
        var isChanged = false;
        
        // 监听表单改变 (利用事件冒泡监听 input 和 change)
        function triggerChange(e) {
            // 只响应输入框、文本域和下拉菜单的改变
            var tag = e.target.tagName.toLowerCase();
            if ((tag === 'input' || tag === 'textarea' || tag === 'select') && !isChanged) {
                isChanged = true;
                banner.style.display = 'flex';
                // 触发浏览器重绘以保证动画生效
                void banner.offsetWidth; 
                banner.style.opacity = '1';
            }
        }
        
        form.addEventListener('input', triggerChange);
        form.addEventListener('change', triggerChange);
        
        var reminderBtn = document.getElementById('typecho-reminder-save-btn');
        reminderBtn.addEventListener('click', function(e) {
            e.preventDefault();
            this.innerText = '保存中...';
            this.style.background = '#3b677d';
            submitBtn.click(); 
        });
        
        // 原生提交按钮点击，隐藏提示框
        submitBtn.addEventListener('click', function() {
            banner.style.opacity = '0';
            setTimeout(function() {
                banner.style.display = 'none';
            }, 300);
        });
    });
    </script>