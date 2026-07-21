<?php 
$fabDataStr = $this->options->fabData;
$fabPosition = $this->options->fabPosition ? $this->options->fabPosition : 'bottom-right';
$buttons = json_decode((string)$fabDataStr, true);
if (!is_array($buttons)) $buttons = [];
?>

<?php if (!empty($buttons)): ?>
<style>
.fab-container {
    position: fixed;
    z-index: 9999;
    display: flex;
    flex-direction: column;
    gap: 6px;
    --tamd-tab-color: #999;
    --tamd-tab-bg: rgba(200, 200, 200, 0.4);
}
.fab-pos-bottom-right { bottom: 40px; right: 30px; }
.fab-pos-bottom-left { bottom: 40px; left: 30px; }
.fab-pos-top-right { top: 40px; right: 30px; }
.fab-pos-top-left { top: 40px; left: 30px; }
.fab-pos-center-right { top: 50%; right: 30px; transform: translateY(-50%); }
.fab-pos-center-left { top: 50%; left: 30px; transform: translateY(-50%); }

.fab-item-wrap {
    position: relative;
    display: flex;
    align-items: center;
}

@media (max-width: 768px) {
    .fab-hide-mobile { display: none !important; }
}
@media (min-width: 769px) {
    .fab-hide-desktop { display: none !important; }
}

.fab-btn {
    width: 42px;
    height: 42px;
    border-radius: 8px;
    background-color: var(--tamd-tab-bg);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    color: var(--tamd-tab-color);
    text-decoration: none;
    transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    -webkit-backdrop-filter: saturate(2) blur(10px);
    backdrop-filter: saturate(2) blur(10px);
}
.fab-btn:hover { 

    transform: scale(1.08);
   
}
.fab-btn svg { width: 22px; height: 22px; fill: currentColor; transition: transform 0.3s; }

.fab-panel, .fab-tooltip {
    position: absolute;
    background: #fff;
    box-shadow: 0 5px 20px rgba(0,0,0,0.12);
    border-radius: 10px;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    pointer-events: none;
    z-index: 10;
}

.fab-tooltip {
    background: rgba(30, 30, 30, 0.85);
    color: #fff;
    padding: 6px 12px;
    font-size: 13px;
    white-space: nowrap;
    border-radius: 6px;
    box-shadow: none;
    backdrop-filter: blur(4px);
}

.fab-panel {
    padding: 15px;
    min-width: 140px;
    text-align: center;
    pointer-events: auto;
}

/* 容器在右侧 -> 面板向左弹 */
.fab-pos-bottom-right .fab-panel, .fab-pos-top-right .fab-panel, .fab-pos-center-right .fab-panel,
.fab-pos-bottom-right .fab-tooltip, .fab-pos-top-right .fab-tooltip, .fab-pos-center-right .fab-tooltip {
    right: 100%;
    margin-right: 16px;
    top: 50%;
    transform: translateY(-50%) translateX(15px);
}
/* 悬停触发向左滑入显示 */
.fab-item-wrap:hover .fab-panel, .fab-item-wrap:hover .fab-tooltip {
    opacity: 1;
    visibility: visible;
    transform: translateY(-50%) translateX(0);
}

/* 容器在左侧 -> 面板向右弹 */
.fab-pos-bottom-left .fab-panel, .fab-pos-top-left .fab-panel, .fab-pos-center-left .fab-panel,
.fab-pos-bottom-left .fab-tooltip, .fab-pos-top-left .fab-tooltip, .fab-pos-center-left .fab-tooltip {
    left: 100%;
    margin-left: 16px;
    top: 50%;
    transform: translateY(-50%) translateX(-15px);
}
/* 悬停触发向右滑入显示 */
.fab-item-wrap:hover .fab-panel, .fab-item-wrap:hover .fab-tooltip {
    opacity: 1;
    visibility: visible;
    transform: translateY(-50%) translateX(0);
}

/* 二维码面板 */
.fab-qr-panel img { 
    display: block; 
    width: 120px; 
    height: 120px; 
    margin: 0 auto 8px auto; 
    border-radius: 4px; 
}
.fab-qr-panel span { 
    font-size: 12px; 
    color: #888; 
    white-space: nowrap; 
}

/* 默认隐藏 */
.fab-item-wrap.top-btn-hidden {
    opacity: 0;
    visibility: hidden;
    transform: translateY(15px);
    transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    pointer-events: none;
}
/* 显示状态 */
.fab-item-wrap.top-btn-show {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
    transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
}

#fab_fixed_theme svg {
    transition: transform 0.3s ease;
}
#fab_fixed_theme:active svg {
    transform: scale(0.8) rotate(-15deg);
}

/* 方便用户自定义按钮样式 */
#fab_fixed_qr {
    --this-color: var(--tamd-tab-color);
    --this-bg: var(--tamd-tab-bg);
    color: var(--this-color);
    background: var(--this-bg);
}
#fab_fixed_theme {
    --this-color: var(--tamd-tab-color);
    --this-bg: var(--tamd-tab-bg);
    color: var(--this-color);
    background: var(--this-bg);
}
#fab_fixed_top {
    --this-color: var(--tamd-tab-color);
    --this-bg: var(--tamd-tab-bg);
    color: var(--this-color);
    background: var(--this-bg);
}
</style>

<!-- 悬浮容器 -->
<div class="fab-container fab-pos-<?php echo $fabPosition; ?>">
    <?php foreach ($buttons as $btn): 
        // 显隐判断
        $wrapClasses = ['fab-item-wrap'];
        if (empty($btn['showDesktop'])) $wrapClasses[] = 'fab-hide-desktop';
        if (empty($btn['showMobile'])) $wrapClasses[] = 'fab-hide-mobile';
        
        // 图标解析
        $iconHtml = (isset($btn['iconType']) && $btn['iconType'] === 'custom') 
            ? $btn['iconCustom'] 
            : '<i class="' . (isset($btn['iconPreset']) ? $btn['iconPreset'] : '') . '"></i>';

        $btnTag = 'div';
        $btnAttr = ' class="fab-btn"';
        
        // 分离各种功能逻辑
        $isPanel = false; 
        
        if (!empty($btn['isFixed'])) {
            $btnAttr .= ' id="fab_' . $btn['id'] . '"';
            if ($btn['id'] === 'fixed_qr') $isPanel = true; // 二维码固定走面板
        } else {
            if ($btn['actionType'] === 'link') {
                $btnTag = 'a';
                $btnAttr .= ' href="'. htmlspecialchars($btn['link']) .'" target="_blank"';
            } else if ($btn['actionType'] === 'popup') {
                $isPanel = true;
            }
        }
    ?>
    <div class="<?php echo implode(' ', $wrapClasses); ?>">
        
        <!-- 主按钮 -->
        <<?php echo $btnTag; ?><?php echo $btnAttr; ?>>
            <?php echo $iconHtml; ?>
        </<?php echo $btnTag; ?>>
        
        <!-- 悬停内容：如果是面板类型，显示白色大块；否则显示黑色文本 Tooltip -->
        <?php if ($isPanel): ?>
            <div class="fab-panel <?php echo ($btn['id'] === 'fixed_qr') ? 'fab-qr-panel' : ''; ?>">
                <?php if ($btn['id'] === 'fixed_qr'): ?>
                    <!-- 二维码图片将在 JS 中动态插入以保证获取的是当前浏览网址 -->
                    <div id="fab_qr_img_container"></div>
                    <span>在手机上浏览此页面</span>
                <?php else: ?>
                    <!-- 自定义的 HTML 面板内容 -->
                    <?php echo $btn['popupHtml']; ?>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="fab-tooltip"><?php echo htmlspecialchars($btn['name']); ?></div>
        <?php endif; ?>
        
    </div>
    <?php endforeach; ?>
</div>

<script>

// 回到顶部功能
var topBtn = document.getElementById('fab_fixed_top');
if (topBtn) {
    var topWrap = topBtn.parentElement;
    
    topWrap.classList.add('top-btn-hidden');

    window.addEventListener('scroll', function() {
        var scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
        if (scrollTop > 300) {
            topWrap.classList.remove('top-btn-hidden');
            topWrap.classList.add('top-btn-show');
        } else {
            topWrap.classList.remove('top-btn-show');
            topWrap.classList.add('top-btn-hidden');
        }
    });

    topBtn.onclick = function() {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    };
}


// 页面二维码动态生成
/*
var qrWrap = document.querySelector('.fab-qr-panel');
if (qrWrap) {
    var qrWrapItem = qrWrap.closest('.fab-item-wrap');
    var isQrGenerated = false;
    qrWrapItem.addEventListener('mouseenter', function() {
        if (!isQrGenerated) {
            var currentUrl = encodeURIComponent(window.location.href);
            var qrImgSrc = 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&margin=5&data=' + currentUrl;
            document.getElementById('fab_qr_img_container').innerHTML = '<img src="' + qrImgSrc + '" alt="Current Page QR" />';
            isQrGenerated = true;
        }
    });
}
*/
var qrWrap = document.querySelector('.fab-qr-panel');
if (qrWrap) {
    var qrWrapItem = qrWrap.closest('.fab-item-wrap');
    var isQrGenerated = false;
    qrWrapItem.addEventListener('mouseenter', function() {
        if (!isQrGenerated) {
            var container = document.getElementById('fab_qr_img_container');
            container.innerHTML = ''; // 清空旧内容
            new QRCode(container, {
                text: window.location.href,
                width: 150,
                height: 150,
                colorDark : "#000000",
                colorLight : "#ffffff",
                correctLevel : QRCode.CorrectLevel.M
            });
            isQrGenerated = true;
        }
    });
}

// 日夜模式切换
// 提前定义好美化后的图标字符串
const sunIcon = `<svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>`;

const moonIcon = `<svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>`;

const themeBtn = document.getElementById('fab_fixed_theme');

if (themeBtn) {
    const updateThemeIcon = () => {
        const isDarkMode = document.body.classList.contains('dark-mode');
        themeBtn.innerHTML = isDarkMode ? sunIcon : moonIcon;
    };

    updateThemeIcon();

    themeBtn.onclick = function() {
        document.body.classList.toggle('dark-mode'); 
        updateThemeIcon();
    };
}
</script>
<?php endif; ?>
<footer class="tamd-left-right-footer" role="contentinfo">
    <div class="footer-container">
        <div class="footer-left">
            <p class="logo">
            <a href="<?php $this->options->siteUrl(); ?>">
            <?php if ($this->options->footLogoUrlLight): ?>
                    <img class="" style="height:40px;" src="<?php $this->options->footLogoUrlLight(); ?>" />
                <?php else: ?>
                    <?php $this->options->title() ?>
                <?php endif; ?>
            </a>
            </p>
            <p class="site-tech">
                Built with <a href="https://typecho.org" target="_blank" rel="nofollow">Typecho</a> · 
                Designed by <a href="https://www.gsav.cn/" target="_blank">TamdBlog</a>
            </p>
            <!-- 创作不易，修改请保留版权，谢谢 -->
        </div>
        <div class="footer-right">
            <ul class="footer-links" style="max-width: 550px;">
                <li><a href="">友情链接</a><a href="">网站地图</a><?php $this->options->footLinks(); ?></li>
            </ul>
            <ul class="footer-mates" style="max-width: 550px;">
                <?php if ($this->options->miitbeian): ?>
                <li>
                    <img class="" style="width: 16px;height: 16px;" src="<?php $this->options->themeUrl('static/img/icp.png'); ?>">&nbsp<a href="https://beian.miit.gov.cn/" target="_blank" rel="nofollow"><?php $this->options->miitbeian(); ?></a>
                </li>
                <?php endif; ?>
                <?php if ($this->options->footCopyright == 'on'): ?>
                <li>
                    &copy; <?php echo date('Y'); ?> <a href="<?php $this->options->siteUrl(); ?>"><?php $this->options->title(); ?></a>
                </li>
                <?php endif; ?>
                <?php $this->options->footMates(); ?>
                
            </ul>
            <ul class="footer-tabs" style="max-width: 550px;">

            </ul>
        </div>
    </div>
</footer>
<!--更多的页脚设计，但是暂时还不好看，等下个版本再发布吧-->
<!--
<footer class="tamd-footer-magazine" role="contentinfo">
    <div class="desktop-footer">
        <div class="magazine-top">
            <div class="brand-huge"><a href="<?php $this->options->siteUrl(); ?>">
            <?php if ($this->options->logoUrlLight): ?>
                    <img class="logo-color" style="height:40px;" src="<?php $this->options->logoUrlLight(); ?>" />

                <?php else: ?>
                    <?php $this->options->title() ?>
                <?php endif; ?>
        </a></div>
            <div class="nav-links">
                <a href=" >options->feedUrl(); ?>">RSS</a >
                <a href="<?php $this->options->siteUrl(); ?>sitemap.xml">SITEMAP</a >
            </div>
        </div>
        <div class="magazine-bottom">
            <div class="copy-info">&copy; <?php echo date('Y'); ?> All Rights Reserved.</div>
            <div class="tech-info">
                Powered by <a href="https://typecho.org" target="_blank" rel="nofollow">Typecho</a >
                <span class="dot">·</span>
                Theme <a href="https://www.gsav.cn/" target="_blank">TamdBlog</a >
            </div>
            <?php if ($this->options->miitbeian): ?>
                <div class="icp-info">
                    <img src="<?php $this->options->themeUrl('static/img/icp.png'); ?>" alt="ICP">
                    <a href="https://beian.miit.gov.cn/" target="_blank" rel="nofollow"><?php $this->options->miitbeian(); ?></a >
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="mobile-footer">
        <div class="brand-huge"><a href="<?php $this->options->siteUrl(); ?>">
            <?php if ($this->options->logoUrlLight): ?>
                    <img class="logo-color" style="height:40px;" src="<?php $this->options->logoUrlLight(); ?>" />

                <?php else: ?>
                    <?php $this->options->title() ?>
                <?php endif; ?>
        </a></div>
        <div class="nav-links">
            <a href="<?php $this->options->feedUrl(); ?>">RSS</a >
            <a href="<?php $this->options->siteUrl(); ?>sitemap.xml">SITEMAP</a >
        </div>
        <div class="divider"></div>
        <div class="mobile-details">
            <p>&copy; <?php echo date('Y'); ?> All Rights Reserved.</p >
            <p>Powered by Typecho · Theme TamdBlog</p >
            <?php if ($this->options->miitbeian): ?>
                <p class="icp-info">
                    <img src="<?php $this->options->themeUrl('static/img/icp.png'); ?>" alt="ICP">
                    <a href="https://beian.miit.gov.cn/" target="_blank" rel="nofollow"><?php $this->options->miitbeian(); ?></a >
                </p>
            <?php endif; ?>
        </div>
    </div>
</footer>

<style>
.tamd-footer-magazine {
    background: #ffffff;
    color: var(--c-muted, #94a3b8);
    font-family: -apple-system, "Helvetica Neue", Arial, sans-serif;
    border-top: 1px solid #eaeaea;
}
.tamd-footer-magazine a { color: var(--c-muted, #94a3b8); text-decoration: none; transition: opacity 0.3s; }
.tamd-footer-magazine a:hover { opacity: 0.5; }

.tamd-footer-magazine .desktop-footer { display: block; max-width: 1200px; margin: 0 auto; padding: 70px 40px; }
.tamd-footer-magazine .mobile-footer { display: none; }

.tamd-footer-magazine .magazine-top { display: flex; justify-content: space-between; align-items: flex-end; border-bottom: 2px solid var(--c-muted, #94a3b8); padding-bottom: 20px; margin-bottom: 20px; }
.tamd-footer-magazine .brand-huge { font-size: 48px; font-weight: 800; letter-spacing: -1px; line-height: 1; }
.tamd-footer-magazine .nav-links { display: flex; gap: 30px; }
.tamd-footer-magazine .nav-links a { font-size: 13px; font-weight: 700; letter-spacing: 2px; }

.tamd-footer-magazine .magazine-bottom { display: flex; justify-content: space-between; align-items: center; font-size: 13px; color: var(--c-muted, #94a3b8); }
.tamd-footer-magazine .magazine-bottom a { color: var(--c-muted, #94a3b8); font-weight: 500; }
.tamd-footer-magazine .dot { margin: 0 10px; }
.tamd-footer-magazine .icp-info { display: flex; align-items: center; gap: 6px; }
.tamd-footer-magazine .icp-info img { width: 14px; height: 14px; }

@media screen and (max-width: 768px) {
    .tamd-footer-magazine .desktop-footer { display: none; }
    .tamd-footer-magazine .mobile-footer { display: block; padding: 50px 25px; }
    
    .tamd-footer-magazine .mobile-footer .brand-huge { font-size: 36px; font-weight: 800; margin-bottom: 25px; text-align: center; letter-spacing: -0.5px;}
    .tamd-footer-magazine .mobile-footer .nav-links { display: flex; justify-content: center; gap: 25px; margin-bottom: 30px; }
    .tamd-footer-magazine .mobile-footer .nav-links a { font-size: 12px; font-weight: 700; letter-spacing: 1.5px; }
    .tamd-footer-magazine .mobile-footer .divider { height: 2px; background: #000; margin-bottom: 25px; }
    .tamd-footer-magazine .mobile-footer .mobile-details { text-align: center; font-size: 12px; color: var(--c-muted, #94a3b8); line-height: 2.2; }
    .tamd-footer-magazine .mobile-footer .icp-info { display: flex; justify-content: center; align-items: center; gap: 6px; margin-top: 15px; }
    .tamd-footer-magazine .mobile-footer .icp-info img { width: 14px; height: 14px; }
}
</style>

<footer class="tamd-footer-grid" role="contentinfo">
    <div class="desktop-footer">
        <div class="grid-container">
            <div class="grid-left">
                <div class="brand">
                    <a href="<?php $this->options->siteUrl(); ?>">
            <?php if ($this->options->logoUrlLight): ?>
                    <img class="logo-color" style="height:40px;" src="<?php $this->options->logoUrlLight(); ?>" />

                <?php else: ?>
                    <?php $this->options->title() ?>
                <?php endif; ?>
        </a></div>
                <div class="copyright">&copy; <?php echo date('Y'); ?> All Rights Reserved.</div>
            </div>
            <div class="grid-middle">
                <div class="tech">
                    <span>Powered by <a href=" " target="_blank" rel="nofollow">Typecho</a ></span>
                    <span>Theme <a href="https://www.gsav.cn/" target="_blank">TamdBlog</a ></span>
                </div>
            </div>
            <div class="grid-right">
                <div class="links">
                    <a href="<?php $this->options->feedUrl(); ?>">RSS</a >
                    <a href="<?php $this->options->siteUrl(); ?>sitemap.xml">SITEMAP</a >
                </div>
                <?php if ($this->options->miitbeian): ?>
                    <div class="icp">
                        <img src="<?php $this->options->themeUrl('static/img/icp.png'); ?>" alt="ICP">
                        <a href="https://beian.miit.gov.cn/" target="_blank" rel="nofollow"><?php $this->options->miitbeian(); ?></a >
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="mobile-footer">
        <div class="m-grid-top">
            <div class="brand"><a href="<?php $this->options->siteUrl(); ?>">
            <?php if ($this->options->logoUrlLight): ?>
                    <img class="logo-color" style="height:40px;" src="<?php $this->options->logoUrlLight(); ?>" />

                <?php else: ?>
                    <?php $this->options->title() ?>
                <?php endif; ?>
        </a></div>
            <div class="links">
                <a href="<?php $this->options->feedUrl(); ?>">RSS</a >
                <a href="<?php $this->options->siteUrl(); ?>sitemap.xml">SITEMAP</a >
            </div>
        </div>
        <div class="m-grid-bottom">
            <p>&copy; <?php echo date('Y'); ?> All Rights Reserved.</p >
            <p>Typecho &times; TamdBlog</p >
            <?php if ($this->options->miitbeian): ?>
                <p class="icp">
                    <img src="<?php $this->options->themeUrl('static/img/icp.png'); ?>" alt="ICP">
                    <a href="https://beian.miit.gov.cn/" target="_blank" rel="nofollow"><?php $this->options->miitbeian(); ?></a >
                </p >
            <?php endif; ?>
        </div>
    </div>
</footer>

<style>
.tamd-footer-grid {
    background: #ffffff;
    color: var(--c-muted, #94a3b8);
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
}
.tamd-footer-grid a { color: var(--c-muted, #94a3b8); text-decoration: none; transition: color 0.2s; }
.tamd-footer-grid a:hover { color: #999999; }

.tamd-footer-grid .desktop-footer { display: block; border-top: 1px solid var(--c-muted, #94a3b8); }
.tamd-footer-grid .mobile-footer { display: none; }

.tamd-footer-grid .grid-container { display: grid; grid-template-columns: 1fr 2fr 1fr; max-width: 1400px; margin: 0 auto; }
.tamd-footer-grid .grid-container > div { padding: 50px 40px; }
.tamd-footer-grid .grid-left {  }
.tamd-footer-grid .grid-middle { display: flex; align-items: flex-end; }
.tamd-footer-grid .grid-right { display: flex; flex-direction: column; justify-content: space-between; align-items: flex-end; }

.tamd-footer-grid .brand { font-size: 22px; font-weight: 700; margin-bottom: 25px; letter-spacing: -0.5px;}
.tamd-footer-grid .copyright, .tamd-footer-grid .tech { font-size: 13px; color: var(--c-muted, #94a3b8); }
.tamd-footer-grid .tech span { display: block; margin-top: 8px; }

.tamd-footer-grid .links { display: flex; gap: 24px; }
.tamd-footer-grid .links a { font-size: 12px; font-weight: 600; letter-spacing: 1.5px; }
.tamd-footer-grid .icp { display: flex; align-items: center; gap: 6px; font-size: 12px; color: var(--c-muted, #94a3b8); }
.tamd-footer-grid .icp img { width: 14px; height: 14px;  }

@media screen and (max-width: 768px) {
    .tamd-footer-grid .desktop-footer { display: none; }
    .tamd-footer-grid .mobile-footer { display: block; padding: 0; }
    
    .tamd-footer-grid .m-grid-top { padding: 30px 25px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #eaeaea; }
    .tamd-footer-grid .brand { font-size: 18px; font-weight: 700; margin: 0; }
    .tamd-footer-grid .links { display: flex; gap: 15px; }
    .tamd-footer-grid .links a { font-size: 11px; font-weight: 600; letter-spacing: 1px; }
    
    .tamd-footer-grid .m-grid-bottom { padding: 30px 25px; font-size: 12px; color: var(--c-muted, #94a3b8); line-height: 2; }
    .tamd-footer-grid .m-grid-bottom p { margin: 0 0 6px 0; }
    .tamd-footer-grid .icp { display: flex; align-items: center; gap: 6px; margin-top: 15px !important; }
    .tamd-footer-grid .icp img { width: 14px; height: 14px;  }
}
</style>
-->
