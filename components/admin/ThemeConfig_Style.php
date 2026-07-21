<link rel="stylesheet" href="<?php echo $themeUrl;?>/static/FontAwesome/css/all.min.css">
<style>
html,
body,
:root,
*{
    --primary-color: #122e8a;
    --red-color: #99152b;
}
body {
    background: url('<?php echo $themeUrl;?>/static/admin/img/bg/tamdblog-admin-bg@100.png') no-repeat center center fixed !important; 
    background-size: cover !important;
    
}
@media (max-width: 768px) {
    body {
        background: url('<?php echo $themeUrl;?>/static/admin/img/bg/tamdblog-admin-bg@100.png') no-repeat center center fixed !important; 
    }
}
 main {
    /*
    background: rgba(255, 255, 255, 0.8) !important;
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    */
}
img, svg {
    vertical-align: middle;
}
select { 
    height: auto;
}
.btn {
    border-radius: 3px!important;
}
@media (min-width: 768px) {
    .btn {
        border-radius: 5px!important;
    }
}

.row {
    margin-right: 0px; 
    margin-left: 0px;
    padding-right: 0px!important;
    padding-left: 0px!important;
}
.primary {
    background: var(--primary-color)!important;
}

.red {
    background: var(--red-color)!important;
    color: #fff;
}
/**
 设置界面ui容器
*/
.custom-theme-settings {
    --primary-color: #122e8a;
    --primary-light: #1e3da8;
    --primary-dark: #0a1d5c;
    --red-color: #99152b;
    --secondary-color: #f5efea;
    --secondary-dark: #e8ddd4;
    --text-primary: #2c2c2c;
    --text-secondary: #666;
    --text-light: #999;
    --border-color: #e5e5e5;
    --bg-white: #ffffff;
    --bg-gray: #f8f9fa;
    --success-color: #52c41a;
    --warning-color: #faad14;
    --error-color: #ff4d4f;
    --sidebar-width: 240px;
    --header-height: 80px;
    --border-radius: 8px;
    --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.08);
    --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.1);
    --shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.12);
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/**

*/
.custom-theme-settings * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

.custom-theme-settings {
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;

}

/**
 头部卡片
*/
.settings-header {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
    color: #fff;
    padding: 30px 40px;
    border-radius: var(--border-radius);
    margin-bottom: 24px;
    box-shadow: var(--shadow-md);
    position: relative;
    overflow: hidden;
}
@media (min-width: 768px) {
    .settings-header {
        position: sticky;
        top: 56px;
    }
}
.settings-header::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -10%;
    width: 300px;
    height: 300px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 50%;
}
 
.settings-header::after {
    content: '';
    position: absolute;
    bottom: -30%;
    left: 10%;
    width: 200px;
    height: 200px;
    background: rgba(255, 255, 255, 0.03);
    border-radius: 50%;
}
 
.settings-header h1 {
    font-size: 28px;
    font-weight: 600;
    margin-bottom: 8px;
    position: relative;
    z-index: 1;
}
 
.settings-header p {
    font-size: 14px;
    opacity: 0.9;
    position: relative;
    z-index: 1;
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

/* 导航栏容器 */
/* 后台顶部导航栏自定义 */
/*
.typecho-head-nav {
    background: linear-gradient(135deg, rgba(0, 30, 170, 0.95), #00d2ff) !important;
    box-shadow: 0 4px 20px rgba(0, 30, 170, 0.2);
    transition: all 0.4s ease;
}
@media (max-width: 575px) {
    .typecho-head-nav .menu-bar {
        background: linear-gradient(135deg, rgba(0, 30, 170, 0.95), #00d2ff) !important;
    }
}
.typecho-head-nav nav>menu>li:not(.operate).focus>a,.typecho-head-nav menu {
    background: transparent;
}
.typecho-head-nav nav>menu>li>a , .typecho-head-nav nav>menu>li.operate a:first-child,.typecho-head-nav nav>menu>li:first-child  {
    border-right: none; 
    border-left: none; 
}
.typecho-head-nav nav>menu>li menu {
 
}
.typecho-head-nav .operate a {
    color: rgba(255, 255, 255, 0.8) !important;
    transition: all 0.3s ease;
}
.typecho-head-nav .operate a:hover {
    color: #ffffff !important;
    text-shadow: 0 0 8px rgba(255, 255, 255, 0.5);
}
*/
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
.typecho-page-title {
    background: rgba(255, 255, 255, 0.8) !important;
    box-shadow: 2px 0 10px rgba(0,0,0,0.2); 

    -webkit-backdrop-filter: blur(10px);
    padding: 20px !important;
    border-radius: 12px !important;
    margin-top: 10px !important;
    margin-bottom: 0px !important;
 
}

@media (min-width: 768px) {
    .typecho-page-title {
        backdrop-filter: blur(10px);

 
    }
}
@media (max-width: 767px) {
    .typecho-page-title {
        margin-top: 0 !important;
        background: rgba(255, 255, 255) !important;
        width: calc(100% - 10px);
        position: sticky; 
        top: 46px;
        transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1) !important;
        transform-origin: top center !important;
    }

    /**
    js动态添加此元素实现动画
     */
    .typecho-page-title.is-sticky {
        margin-top: 0 !important;
        height: 36px;
        padding: 10px!important;
        width: calc(100% - 40px);
  
        box-shadow: 0 4px 30px rgba(0,0,0,0.12) !important;
        background: rgba(255, 255, 255, 0.98) !important;
    }
    
    /* 标题文字动画 */
    .typecho-page-title h2 {
        transition: all 0.3s ease !important;
        font-size: 18px !important;
    }
    
    .typecho-page-title.is-sticky h2 {
        font-size: 16px !important;
        opacity: 0.9 !important;
    }
    
    /* 按钮组动画 */
    .typecho-page-title .btn {
        transition: all 0.3s ease !important;
    }
    
    .typecho-page-title .btn {
        transform: scale(0.9) !important;
        padding: 4px 12px !important;
        font-size: 12px !important;
        margin-left: 0px !important;
    }
    .typecho-option-tabs {
        margin-top: 20px !important;
    }
}

/**
【主题设置】下方的菜单栏
自定义修改
*/
.typecho-option-tabs {
    background: rgba(255, 255, 255, 0.8) !important;
    box-shadow: 2px 0 10px rgba(0,0,0,0.1); 
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    padding: 20px !important;
    border-radius: 12px !important;
    margin-top: 10px;
    margin-bottom: 0px !important;
 
}
@media (min-width: 768px) {
    .typecho-option-tabs {
        margin-top: 10px!important;
    }
}

.typecho-option-tabs li a {
    background: #f9f9f9;
    border: none!important;
    border-radius: 3px !important;
    margin-right: 5px !important;
}
    
.typecho-option-tabs li.current a, .typecho-option-tabs li.active a {
    background-color: #001eaa !important;
    color:#fff;
}

.typecho-foot {
    background: rgba(255, 255, 255, 0.8) !important;
    backdrop-filter: blur(10px);
    padding: 20px !important;
    margin-top: 30px;
    color: #555 !important;
}

/**
设置个体内容卡片
*/
.typecho-option {
    background: rgba(255, 255, 255, 0.6) !important;
    padding: 15px 20px !important;
    margin-bottom: 15px !important;
    border-radius: 8px !important;
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
.typecho-page-main {
    background: rgba(255, 255, 255, 0.8) !important;
    box-shadow: 2px 0 10px rgba(0,0,0,0.2); 
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    padding: 20px 10px !important;
    border-radius: 12px !important;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.05) !important;
    margin-top: 10px !important;
}

.typecho-page-title h2 {
    color: #333 !important;
    text-shadow: 0 1px 1px rgba(255,255,255,0.5);
}

input[type=\"text\"], input[type=\"password\"], textarea, select {
    background: rgba(255, 255, 255, 0.8) !important;
    border: 1px solid #ddd !important;
}

.typecho-page-title {
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
    flex-wrap: wrap !important;
    gap: 15px !important;
    z-index: 99;
}

.typecho-page-title h2 {
    margin: 0 !important;
    flex: 0 0 auto !important;
    order: 1 !important;
}

#typecho-save-topmenu {
    order: 2 !important;
    flex: 0 0 auto !important;
    background: transparent !important;
    box-shadow: none !important;
    position: relative !important;
    padding: 0 !important;
}

/*左侧侧边栏样式*/
.typecho-setting-sidebar { 
    padding: 15px; 
    border-right: 1px solid #e9ecef; 
}
.setting-sidebar-inner { 
    position: sticky; 
    top: 20px; 
}
.setting-search-box { 
    width: 100%; 
    padding: 8px 12px; 
    border: 1px solid #ccc; 
    border-radius: 4px; 
    margin-bottom: 15px; 
    box-sizing: border-box; 
}
.setting-menu-list { 
    list-style: none; 
    padding: 0; 
    margin: 0; 
}
.setting-menu-list li { 
    margin-bottom: 5px; 
}
.setting-menu-list a { 
    display: block; 
    padding: 8px 10px; 
   
    color: #444; 
    text-decoration: none; 
    border-radius: 4px; 
    transition: background 0.3s; 
}
.setting-menu-list a:hover, .setting-menu-list a.active { 
    background: #e9ecef; 
    color: #333; 
    font-weight: bold; 
}

/*隐藏移动端切换按钮*/
.mobile-menu-toggle { 
    display: none; 
}

/* 设置项区块样式 */
.setting-section { 
    padding-top: 15px; 
    margin-bottom: 30px; 
    border-bottom: 1px dashed #eee; 
}
.typecho-setting-title { 
    margin-top: 0; 
    color: #333; 
    font-size: 1.2em; 

    padding-left: 10px; 
}

@media (max-width: 767px) {
    .typecho-setting-sidebar { 
        position: fixed; 
        top: 0; 
        left: -380px; 
        width: 280px; 
        height: 100%; 
        background: #fff; 
        z-index: 9999; 
        box-shadow: 2px 0 10px rgba(0,0,0,0.1); 
        transition: left 0.3s ease; 
        padding-top: 50px; 
        overflow-y: auto; 
    }
    .typecho-setting-sidebar.open { 
        left: 0; 
    }
    
    .mobile-menu-toggle { 
        display: block; 
        width: 100%; 
        background: #467b96; 
        color: #fff; 
        border: none; 
        padding: 12px; 
        font-size: 16px; 
        text-align: left; 
        position: relative; 
        z-index: 10000; 
        border-radius: 4px; 
        margin-bottom: 15px;
    }
    .mobile-menu-toggle::after { 
        content: '☰ 展开设置菜单'; 
    }
    .typecho-setting-sidebar.open + .typecho-page-main .mobile-menu-toggle::after { 
        content: '✕ 关闭菜单'; 
    }
}
.desktop-sidebar { 
    border-right: 1px solid #e9ecef; 
}
.sidebar-inner { 
    position: sticky; 
    top: 56px; 
    padding-right: 15px; 
}
.setting-search { 
    width: 100%; 
    padding: 10px; 
    border: 1px solid #d1d5db; 
    border-radius: 6px; 
    margin-bottom: 20px; 
    box-sizing: border-box; 
    outline: none; 
}
.setting-search:focus { 
    border-color: #467b96; 
}
.setting-menu { 
    list-style: none; 
    padding: 0; 
    margin: 0; 
}
.setting-menu li { 
    margin-bottom: 8px; 
}
.setting-menu a { 
    display: block; 
    padding: 8px 12px;  
    margin-left: 0px!important; 
    color: #555; 
    text-decoration: none; 
    border-radius: 6px; 
    background: #f9f9f9; 
    transition: all 0.2s; 
    font-size: 14px; 
}
.setting-menu a:hover, .setting-menu a.active { 
    background: #122e8a; 
    color: #fff; 
}
.setting-section { 
    padding: 10px 0 20px; 
    margin-bottom: 20px; 
    border-bottom: 1px dashed #e5e7eb; 
}
.typecho-setting-title { 
    margin-top: 0; 
    color: #333; 
    font-size: 16px; 
    padding-left: 12px; 
    font-weight: bold; 
}
.mobile-sidebar, .mobile-mask, .mobile-toggle-btn { 
    display: none !important; 
}
@media (max-width: 768px) {
    .desktop-sidebar { display: none !important; }
    .mobile-toggle-btn {
        display: block !important;
    }
    .mobile-sidebar {
        display: block !important;
        position: fixed; top: 0; left: -920px; width: 60%; height: 100vh;
        background: #fff; z-index: 99999;
        padding: 60px 20px 20px;
        box-shadow: 2px 0 15px rgba(0,0,0,0.2);
        transition: left 0.3s ease;
        overflow-y: auto;
    }
    .mobile-sidebar.open { left: 0; }
    .mobile-mask {
        display: none;
        position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;
        background: rgba(0,0,0,0.5); z-index: 99998;
    }
    .mobile-mask.show { display: block !important; }
    .typecho-page-main form { padding-top: 0px; }
}
    /* 文字 + 角标容器 */
    .title-with-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        position: relative;
    }

    /* 右上角角标 */
    .badge-corner {
        position: relative;
        top: -12px;
        right: 0;
        display: inline-block;
        min-width: 18px;
        height: 18px;
        padding: 0 6px;
        background: #e74c3c;
        color: white;
        font-size: 11px;
        font-weight: 700;
        line-height: 18px;
        text-align: center;
        border-radius: 9px;
        box-shadow: 0 2px 8px rgba(231, 76, 60, 0.4);
        margin-left: -4px;
        flex-shrink: 0;
    }

    /* 纯红点（无数字） */
    .badge-corner.pure {
        min-width: 10px;
        height: 10px;
        padding: 0;
        border-radius: 50%;
        top: -8px;
        margin-left: -2px;
    }

    /* 呼吸灯效果 */
    .badge-corner.pulse-ring {
        position: relative;
    }

    .badge-corner.pulse-ring::after {
        content: '';
        position: absolute;
        top: -3px;
        left: -3px;
        right: -3px;
        bottom: -3px;
        border-radius: 50%;
        border: 2px solid #e74c3c;
        animation: ringPulse 2s ease-out infinite;
    }

    @keyframes ringPulse {
        0% {
            transform: scale(0.8);
            opacity: 1;
        }
        100% {
            transform: scale(1.8);
            opacity: 0;
        }
    }

    /* 弹入动画 */
    .badge-corner.bounce {
        animation: bounceIn 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    }

    @keyframes bounceIn {
        0% {
            transform: scale(0);
        }
        60% {
            transform: scale(1.3);
        }
        100% {
            transform: scale(1);
        }
    }
/**
 主题更新提示ui
*/
.theme-update-alert-vibrant {
    background: linear-gradient(135deg, #001eaa 0%, #2b5cff 100%);
    color: #ffffff;
    padding: 16px 20px;
    border-radius: 12px;
    margin-bottom: 15px;
    box-shadow: 0 8px 20px rgba(0, 30, 170, 0.25);
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.theme-update-alert-vibrant:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0, 30, 170, 0.35);
}
.theme-update-alert-vibrant .alert-text {
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: 500;
    font-size: 15px;
}
.theme-update-alert-vibrant .version-badge {
    background: rgba(255, 255, 255, 0.2);
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
    backdrop-filter: blur(8px); /* 毛玻璃效果 */
}
.theme-update-alert-minimal {
    background: #ffffff;
    color: #1a1a1a;
    padding: 16px 20px;
    border-radius: 12px;
    margin-bottom: 15px;
    border: 1px solid #eef0f5;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
    display: flex;
    align-items: center;
    gap: 12px;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    transition: all 0.3s ease;
}
.theme-update-alert-minimal:hover {
    box-shadow: 0 6px 20px rgba(0, 30, 170, 0.08);
    border-color: #ccd8ff;
}
.theme-update-alert-minimal .icon-wrapper {
    background: #f0f4ff;
    color: #001eaa;
    padding: 6px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.theme-update-alert-minimal .alert-text {
    font-size: 15px;
    font-weight: 500;
}
.theme-update-alert-minimal .version-badge {
    margin-left: auto;
    color: #001eaa;
    font-size: 13px;
    font-weight: 600;
    background: #f0f4ff;
    padding: 4px 10px;
    border-radius: 8px;
}
/**
系统参数 */
.sys-info-wrapper { 
    margin: 20px 0; 
    font-size: 13px; 
}
.sys-info-table { 
    width: 100%; 
    border-collapse: collapse; 
    margin-bottom: 20px; 
    background: #fff; 
}
.sys-info-table th, .sys-info-table td { 
    padding: 10px; 
    border: 1px solid #eee; 
    text-align: left; 
}
.sys-info-table th { 
    background: #f4f7fa; 
    color: #001eaa; 
    width: 30%; 
    font-weight: 600; 
}
.sys-info-table tr:hover { 
    background: #fcfcfc; 
}
.table-title { 
    margin-bottom: 10px; 
    color: #333; 
    border-left: 4px solid #001eaa; 
    padding-left: 10px; 
}
/**
文章列表样式选择 */
.index-post-list-style-selector input[type="radio"] {
    display: none;
}
.index-post-list-style-selector span {
    display: inline-flex !important;
    flex-direction: row !important;
    flex-wrap: nowrap !important;
    gap: 10px !important;
    margin-top: 15px !important;
    align-items: center !important;
    width: auto !important; 
}

.index-post-list-style-selector span label {
    display: flex !important;
    cursor: pointer;
    border: 2px solid #e2e8f0;
    border-radius: 2px;
    padding: 2px;
    transition: all 0.2s ease;
    background: #ffffff;
    
    flex: 0 0 200px !important; 
    width: 200px !important;
    box-sizing: border-box;
}

.index-post-list-style-selector span label img {
    width: 100%;
    height: auto;
    display: block;
    border-radius: 2px;
}

.index-post-list-style-selector input[type="radio"]:checked + label {
    border-color: #3b82f6;
    background: #eff6ff;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
}
/**
底部页脚样式选择 */
.foot-style-selector input[type="radio"] {
    display: none !important;
}
    
.foot-style-selector span {
    display: flex !important;
    flex-wrap: wrap !important;
    gap: 0px !important;
    height: max-content !important;
    min-height: max-content !important;
    margin-top: 15px !important;
    margin-right: 0px !important;
    align-items: stretch !important;
}

.foot-style-selector span label {
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

.foot-style-selector span label img {
    width: 100%;
    height: auto;
    display: block;
    border-radius: 2px;
}

.foot-style-selector input[type="radio"]:checked + label {
    border-color: #3b82f6;
    background: #eff6ff;
    color: #1e3a8a;        
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
}
/**
介绍帮助页 */
.tmd-bento-container {
    margin-top: 40px;
    padding-top: 20px;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
    box-sizing: border-box;
}

.tmd-bento-card {
    background: #fff;
    border-radius: 16px;
    padding: 24px;
    border: 1px solid #f1f5f9;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.02);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
    text-decoration: none;
    display: flex;
    flex-direction: column;
    justify-content: center;
}
.tmd-bento-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 24px -8px rgba(0, 30, 170, 0.15);
    border-color: #dbeafe;
}

.tmd-bento-hero {
    grid-column: span 3;
    background: var(--primary-color);
    color: #fff;
    padding: 32px 40px;
    border: none;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.tmd-bento-hero:hover {
    transform: translateY(-2px);
    box-shadow: 0 16px 32px -10px rgba(0, 30, 170, 0.3);
}
.tmd-hero-info h2 {
    margin: 0 0 8px 0;
    font-size: 26px;
    font-weight: 700;
    letter-spacing: -0.5px;
    color: #fff;
}
.tmd-hero-version {
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(4px);
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 500;
    margin-left: 12px;
    vertical-align: middle;
}
.tmd-hero-info p {
    margin: 0;
    font-size: 14px;
    color: rgba(255, 255, 255, 0.85);
}
.tmd-hero-logo-group {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.tmd-hero-logo {
    margin-top: 10px;
    font-size: 32px;
    font-weight: 900;
    opacity: 0.9;
    letter-spacing: 2px;
}

.tmd-bento-doc {
    background: linear-gradient(to bottom right, #f0f4ff, #e0e7ff);
    border-color: #dbeafe;
}
.tmd-bento-doc .card-title {
    color: #001eaa;
}
        
.tmd-card-icon {
    font-size: 24px;
    margin-bottom: 12px;
}
.tmd-card-title {
    font-size: 16px;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 6px;
}
.tmd-card-desc {
    font-size: 13px;
    color: #64748b;
    line-height: 1.5;
}

.tmd-contact-group {
    grid-column: span 3;
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
    margin-top: 8px;
}
.tmd-contact-item {
    display: flex;
    align-items: center;
    padding: 16px 20px;
    background: #fafafa;
    border-radius: 12px;
    border: 1px solid #f1f5f9;
    transition: all 0.3s ease;
}
.tmd-contact-item:hover {
    background: #fff;
    border-color: #001eaa;
    box-shadow: 0 4px 12px rgba(0, 30, 170, 0.08);
}
.tmd-contact-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #001eaa;
    margin-right: 12px;
    box-shadow: 0 0 8px rgba(0, 30, 170, 0.4);
}
.tmd-contact-text {
    display: flex;
    flex-direction: column;
}
.tmd-contact-label {
    font-size: 12px;
    color: #94a3b8;
    margin-bottom: 2px;
}
.tmd-contact-value {
    font-size: 14px;
    font-weight: 600;
    color: #334155;
}

@media (max-width: 900px) {
    .tmd-bento-container {
        grid-template-columns: repeat(2, 1fr);
    }
    .tmd-bento-hero, .tmd-contact-group {
        grid-column: span 2;
    }
    .tmd-contact-group {
        grid-template-columns: repeat(2, 1fr);
    }
}
@media (max-width: 600px) {
    .tmd-tmd-bento-container {
        grid-template-columns: 1fr;
    }
    .tmd-bento-hero, .tmd-contact-group {
        grid-column: 1;
    }
    .tmd-contact-group {
        grid-template-columns: 1fr;
    }
    .tmd-hero-logo {
        display: none;
    }
}

/* 图标输入框与按钮组合 */
.icon-input-group { display: flex; gap: 8px; margin-bottom: 5px; }
.icon-input-group input { margin-bottom: 0 !important; flex: 1; }
.btn-choose-icon { background: #f0f0f0; border: 1px solid #ccc; padding: 0 12px; border-radius: 3px; cursor: pointer; color: #333; font-size: 13px; transition: background 0.2s; }
.btn-choose-icon:hover { background: #e4e4e4; }

/* 图标选择器弹窗*/
.fa-picker-modal { position: fixed; top: 0; left: 0; width: 100%; height: 100vh; background: rgba(0,0,0,0.4); z-index: 99999; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(2px); }
.fa-picker-content { background: #fff;width:100%; max-width: 800px; max-height: 70vh; border-radius: 6px; margin-left:10px;margin-right:10px; display: flex; flex-direction: column; box-shadow: 0 10px 30px rgba(0,0,0,0.2); overflow: hidden; }
.fa-picker-header { padding: 12px 15px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; background: #fafafa; font-weight: bold; color: #333; }
.fa-picker-close { cursor: pointer; color: #999; font-size: 18px; line-height: 1; }
.fa-picker-close:hover { color: #f00; }
.fa-picker-grid { padding: 15px; overflow-y: auto; display: grid; grid-template-columns: repeat(auto-fill, minmax(45px, 1fr)); gap: 10px; }
.fa-picker-item { display: flex; align-items: center; justify-content: center; height: 45px; font-size: 18px; color: #555; cursor: pointer; border: 1px solid #eee; border-radius: 4px; transition: all 0.2s; }
.fa-picker-item:hover { background: #467b96; color: #fff; border-color: #467b96; transform: scale(1.05); }
</style>
<script src="<?php echo $themeUrl;?>/static/admin/js/iconLibrary.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {

    // 创建悬浮提示框
    var topmenu = document.createElement('div');
    topmenu.id = 'typecho-save-topmenu';
    topmenu.style.cssText = 'transition: opacity 0.3s ease; box-shadow: 0 4px 20px rgba(0,0,0,0.15); border-radius: 4px; z-index: 99; align-items: center; justify-content: space-between; display:flex; justify-content:space-between;';
        
    topmenu.innerHTML = 
        '<div class="mobile-sidebar">' +
        '<input type="text" class="setting-search" placeholder="🔍 搜索设置...">' +
        '<ul class="setting-menu" style="margin-top:10px;"></ul>' +
        '</div>' +
        '<div class="mobile-mask"></div>' +
        '<button id="" class="typecho-reminder-save-btn btn primary" style="margin-left:10px;">' +
        '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#e6e6e6" viewBox="0 0 256 256"><path d="M222.14,69.17,186.83,33.86A19.86,19.86,0,0,0,172.69,28H48A20,20,0,0,0,28,48V208a20,20,0,0,0,20,20H208a20,20,0,0,0,20-20V83.31A19.86,19.86,0,0,0,222.14,69.17ZM164,204H92V160h72Zm40,0H188V156a20,20,0,0,0-20-20H88a20,20,0,0,0-20,20v48H52V52H171l33,33ZM164,84a12,12,0,0,1-12,12H96a12,12,0,0,1,0-24h56A12,12,0,0,1,164,84Z"></path></svg>' +
        '</button>' +
        '<button type=\"button\" id=\"btn-save-backup\" class=\"btn primary\" style=\"margin-left:10px;\" onclick=\"doSaveAndBackup()\">' +
        '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#e6e6e6" viewBox="0 0 256 256"><path d="M196.49,151.51a12,12,0,0,1-17,17L168,157v51a12,12,0,0,1-24,0V157l-11.51,11.52a12,12,0,1,1-17-17l32-32a12,12,0,0,1,17,0ZM160,36A92.08,92.08,0,0,0,79,84.37,68,68,0,1,0,72,220h28a12,12,0,0,0,0-24H72a44,44,0,0,1-1.81-87.95A91.7,91.7,0,0,0,68,128a12,12,0,0,0,24,0,68,68,0,1,1,132.6,21.29,12,12,0,1,0,22.8,7.51A92.06,92.06,0,0,0,160,36Z"></path></svg>' +
        '</button>' +
        '<span style="display:inline-block;">' +
        '<button class="mobile-toggle-btn btn red" style="margin-left:10px;">'+
        '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#e6e6e6" viewBox="0 0 256 256"><path d="M228,128a12,12,0,0,1-12,12H120a12,12,0,0,1,0-24h96A12,12,0,0,1,228,128ZM120,76h96a12,12,0,0,0,0-24H120a12,12,0,0,0,0,24Zm96,104H40a12,12,0,0,0,0,24H216a12,12,0,0,0,0-24ZM31.51,144.49a12,12,0,0,0,17,0l40-40a12,12,0,0,0,0-17l-40-40a12,12,0,0,0-17,17L63,96,31.51,127.51A12,12,0,0,0,31.51,144.49Z"></path></svg>'+
        '</button>' +
        '</span>';
            
    document.querySelector('.typecho-page-title').appendChild(topmenu);


    // 获取表单和提交按钮 (排除文章/页面编辑器)
    var form = document.querySelector('form:not(#write_post):not(#write_page)');
    if (!form) return;
        
    var submitBtn = form.querySelector('button[type=\"submit\"]');
    if (!submitBtn) return;
        
    // 悬浮提示框
    var banner = document.createElement('div');
    banner.id = 'typecho-save-reminder';
    banner.style.cssText = 'display: none; opacity: 0; transition: opacity 0.3s ease; position: fixed; bottom: 30px; right: 30px; background: #fff; padding: 15px 25px; box-shadow: 0 4px 20px rgba(0,0,0,0.15); border-radius: 4px; z-index: 9999; border-left: 4px solid #467b96; align-items: center; justify-content: space-between; min-width: 280px;';
        
    banner.innerHTML = 
        '<span style=\"color:#444; font-size:14px; font-weight:bold;\">⚠️ 您有未保存的设置更改</span>' +
        '<button id=\"\" class=\"typecho-reminder-save-btn btn primary\" style=\"margin-left:20px;\">立即保存</button><button type=\"button\" id=\"btn-save-backup\" class=\"btn primary\" style=\"margin-left:20px;\" onclick=\"doSaveAndBackup()\">保存当前设置并备份</button>';
            
    document.body.appendChild(banner);
        
    var isChanged = false;
        
    function triggerChange(e) {
        var tag = e.target.tagName.toLowerCase();
        if ((tag === 'input' || tag === 'textarea' || tag === 'select') && !isChanged) {
            isChanged = true;
            banner.style.display = 'flex';
            void banner.offsetWidth; 
            banner.style.opacity = '1';
        }
    }
        
    form.addEventListener('input', triggerChange);
    form.addEventListener('change', triggerChange);
    
    //保存提交
    var reminderBtns = document.querySelectorAll('.typecho-reminder-save-btn');
    reminderBtns.forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            this.innerText = '保存中...';
            this.style.background = '#3b677d';
            submitBtn.click(); 
        });
    });
    

    // 原生提交按钮点击，隐藏提示框
    submitBtn.addEventListener('click', function() {
        banner.style.opacity = '0';
        setTimeout(function() {
            banner.style.display = 'none';
        }, 300);
    });

    if (typeof jQuery === 'undefined') return;
    var $ = jQuery;

    /*
    var $mainCol = $('.typecho-page-main > div[role="form"]');
    $mainCol.removeClass('col-tb-8 col-tb-offset-2').addClass('col-tb-9');
    */

    // 插入左侧边栏和移动端按钮
    /*
    var sidebarHTML = `
        <div class="col-tb-3 typecho-setting-sidebar">
            <div class="setting-sidebar-inner">
                <input type="text" id="setting-search" class="setting-search-box" placeholder="搜索设置项 (如: logo)...">
                <ul id="setting-menu" class="setting-menu-list"></ul>
            </div>
        </div>`;
    $mainCol.before(sidebarHTML);
    $mainCol.prepend('<button class="mobile-menu-toggle" type="button"></button>');
    */

    // 遍历标题，分组内容，并生成菜单
    $('.typecho-setting-title').each(function(index) {
        var $title = $(this);
        var id = 'setting-group-' + index;
        $title.attr('id', id); // 赋予锚点ID
        
        // 生成左侧菜单项
        $('#setting-menu').append('<li><a href=" ' + id + '">' + $title.html() + '</a></li>');
        
        // 将标题及紧跟的选项包裹成一个 Section
        // 和搜索逻辑重复了，重复包裹，删除无影响
        // $title.nextUntil('.typecho-setting-title, .typecho-option-submit').addBack().wrapAll('<div class="setting-section"></div>');
    });

    // 平滑滚动与菜单高亮
    $('#setting-menu a').on('click', function(e) {
        e.preventDefault();
        var target = $(this).attr('href');
        
        // 样式切换
        $('#setting-menu a').removeClass('active');
        $(this).addClass('active');

        // 平滑滚动
        $('html, body').animate({
            scrollTop: $(target).offset().top - 20
        }, 300);

        // 移动端点击后自动收起侧边栏
        if ($(window).width() < 768) {
            $('.typecho-setting-sidebar').removeClass('open');
        }
    });

    // 移动端抽屉菜单切换
    $('.mobile-menu-toggle').on('click', function() {
        $('.typecho-setting-sidebar').toggleClass('open');
    });

    // 搜索过滤功能
    $('#setting-search').on('input', function() {
        var keyword = $(this).val().toLowerCase();
        
        if (keyword === '') {
            $('.setting-section').show();
            $('.typecho-option').show();
            return;
        }

        // 遍历所有设置项区块
        $('.setting-section').each(function() {
            var $section = $(this);
            var sectionHasMatch = false;

            $section.find('.typecho-option').each(function() {
                var $option = $(this);
                // 搜索标签文字和描述文字
                var text = $option.find('label').text() + ' ' + $option.find('.description').text();
                if (text.toLowerCase().indexOf(keyword) > -1) {
                    $option.show();
                    sectionHasMatch = true;
                } else {
                    $option.hide();
                }
            });

            // 如果该区块内没有任何匹配项，隐藏整个区块包括标题
            if (sectionHasMatch) {
                $section.show();
            } else {
                $section.hide();
            }
        });
    });

    var checkJQuery = setInterval(function() {
        if (typeof window.jQuery !== 'undefined') {
            clearInterval(checkJQuery);
            initDoubleMenu(window.jQuery);
        }
    }, 50);

    function initDoubleMenu($) {
        // 获取主内容区
        var $main = $('.typecho-page-main > div[role="form"]');
        if ($main.length === 0) return;

        // 右侧表单占9列
        $main.removeClass('col-tb-8 col-tb-offset-2').addClass('col-tb-9');

    
        var desktopHTML = `
            <div class="col-tb-3 desktop-sidebar">
                <div class="sidebar-inner">
                    <input type="text" class="setting-search" placeholder="搜索设置...">
                    <ul class="setting-menu"></ul>                
                <button type="button" id="" class="typecho-reminder-save-btn btn primary" style="margin-top:10px;margin-right:10px; ">立即保存</button>
                <button type="button" id="btn-save-backup" class="btn primary" style="margin-top:10px;" onclick="doSaveAndBackup()">保存并备份</button>
                </div>
            </div>`;
        $main.before(desktopHTML);
        var reminderBtns = document.querySelectorAll('.typecho-reminder-save-btn');
        reminderBtns.forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                this.innerText = '保存中...';
                this.style.background = '#3b677d';
                submitBtn.click(); 
            });
        });
  
        // 解析设置项，生成目录列表
        $('.typecho-setting-title').each(function(index) {
  
            var $title = $(this);
            var id = 'setting-group-' + index;
            $title.attr('id', id);
            
            // 将分类包裹起来方便搜索过滤
            $title.nextUntil('.typecho-setting-title, .typecho-option-submit').addBack().wrapAll('<div class="setting-section"></div>');
            
            var liHTML = '<li><a href="#' + id + '">' + $title.html() + '</a ></li>';
            // 分别向桌面端和移动端注入菜单项
            $('.desktop-sidebar .setting-menu').append(liHTML);

            $('.mobile-sidebar .setting-menu').append(liHTML);
        });

        // 点击锚点跳转交互
        $('.setting-menu a').on('click', function(e) {
            e.preventDefault();
            var target = $(this).attr('href');
            
            // 同步高亮状态
            $('.setting-menu a').removeClass('active');
            $('a[href="' + target + '"]').addClass('active');

            // 移动端：点击后自动收起菜单
            $('.mobile-sidebar').removeClass('open');
            $('.mobile-mask').removeClass('show');

            // 滚动到锚点 (减去 60px 防止被移动端按钮盖住)
            $('html, body').animate({
                scrollTop: $(target).offset().top - 128
            }, 300);
        });

        // 移动端菜单呼出/关闭控制
        $('.mobile-toggle-btn, .mobile-mask').on('click', function() {
            $('.mobile-sidebar').toggleClass('open');
            $('.mobile-mask').toggleClass('show');
        });

        // 搜索过滤
        $('.setting-search').on('input', function() {
            var keyword = $(this).val().toLowerCase();
            
            // 双向同步搜索框的内容
            $('.setting-search').val($(this).val());

            if (keyword === '') {
                $('.setting-section, .typecho-option').show();
                return;
            }

            $('.setting-section').each(function() {
                var sectionHasMatch = false;
                $(this).find('.typecho-option').each(function() {
                    if ($(this).text().toLowerCase().indexOf(keyword) > -1) {
                        $(this).show();
                        sectionHasMatch = true;
                    } else {
                        $(this).hide();
                    }
                });

                if (sectionHasMatch) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });
    }

});


/**
 * 唤起图标选择器
 * @param {HTMLElement} targetInput - 接收选中图标类名的 input 元素
 */
function showIconPicker(targetInput) {
    // 移除已存在的弹窗
    let existing = document.getElementById('fa-icon-picker');
    if (existing) existing.remove();
    
    // 创建弹窗容器
    let modal = document.createElement('div');
    modal.id = 'fa-icon-picker';
    modal.className = 'fa-picker-modal';
    
    // 遍历生成图标网格 HTML
    let gridHtml = iconLibrary.map(function(cls) {
        return '<div class="fa-picker-item" data-cls="' + cls + '" title="' + cls + '"><i class="' + cls + '"></i></div>';
    }).join('');
    
    // 组装 HTML
    modal.innerHTML = 
        '<div class="fa-picker-content">' +
            '<div class="fa-picker-header">' +
                '<span>请选择图标</span>' +
                '<span class="fa-picker-close" onclick="document.getElementById(\'fa-icon-picker\').remove()">×</span>' +
            '</div>' +
            '<div class="fa-picker-grid">' + gridHtml + '</div>' +
        '</div>';
        
    // 绑定点击图标事件
    modal.querySelector('.fa-picker-grid').addEventListener('click', function(e) {
        let item = e.target.closest('.fa-picker-item');
        if (item) {
            let selectedClass = item.getAttribute('data-cls');
            targetInput.value = selectedClass;
            
            targetInput.dispatchEvent(new Event('input', { bubbles: true })); 
            
            modal.remove(); // 关闭弹窗
        }
    });
    
    // 点击遮罩层关闭
    modal.addEventListener('click', function(e) {
        if (e.target === modal) modal.remove();
    });
    
    document.body.appendChild(modal);
}
 
</script>