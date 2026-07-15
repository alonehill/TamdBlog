<?php 
/**
 * 全局更新弹窗，可以自定义删除，作者还没挂前不建议删
 * UpdateBanner.php
 * foot.php->$this->need('widget/UpdateBanner.php');
 * function.php->if (!function_exists('getThemeUpdateInfo')) {}
 * function.php->function themeInit($archive) {}
 */

if ($this->user->hasLogin() && $this->user->pass('administrator', true)): 
?>
<style>
   .admin-update-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    z-index: 99999; 
    background: rgba(15, 23, 42, 0.4);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    
    display: flex;
    align-items: center;
    justify-content: center;
    
    opacity: 0;
    transition: opacity 0.4s cubic-bezier(0.16, 1, 0.3, 1);
}

.admin-update-overlay.is-visible {
    opacity: 1;
}

.admin-update-overlay.is-closing {
    opacity: 0;
}

.update-modal {
    width: 100%;
    max-width: 520px;
    background: #ffffff;
    border-radius: 20px;
    padding: 40px;
    box-shadow: 0 25px 80px rgba(0, 0, 0, 0.15), 0 0 0 1px rgba(255, 255, 255, 0.5) inset;
    position: relative;
    overflow: hidden;
    
    transform: scale(0.95) translateY(20px);
    opacity: 0;
    transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
}

.admin-update-overlay.is-visible .update-modal {
    transform: scale(1) translateY(0);
    opacity: 1;
}

.update-modal-header {
    margin-bottom: 24px;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 12px;
}

.update-icon {
    color: #0f172a;
}

.update-title {
    font-size: 16px;
    font-weight: 800;
    color: #0f172a;
    letter-spacing: 0.5px;
    margin: 0;
}

.update-version {
    font-size: 11px;
    font-weight: 700;
    background: #f1f5f9;
    color: #475569;
    padding: 4px 8px;
    border-radius: 6px;
    letter-spacing: 1px;
}

.update-modal-body p {
    font-size: 14.5px;
    line-height: 1.6;
    color: #334155;
    margin: 0 0 16px 0;
}

.update-modal-body ul {
    margin: 0 0 24px 0;
    padding-left: 20px;
    color: #475569;
    font-size: 13.5px;
    line-height: 1.8;
}

.update-modal-body strong {
    color: #0f172a;
}

.update-friendly-tip {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    background: #f8fafc;
    padding: 14px 18px;
    border-radius: 10px;
    border: 1px solid rgba(15, 23, 42, 0.03);
    margin-bottom: 32px;
}

.update-friendly-tip svg {
    color: #64748b;
    margin-top: 3px;
    flex-shrink: 0;
}

.update-friendly-tip span {
    font-size: 12px;
    color: #64748b;
    line-height: 1.6;
}

.update-modal-footer {
    text-align: right;
}

.btn-enter-workspace {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    height: 48px;
    padding: 0 32px;
    background: #0f172a;
    color: #ffffff;
    font-size: 13px;
    font-weight: 700;
    letter-spacing: 1px;
    border-radius: 10px;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-enter-workspace:hover {
    background: #334155;
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(15, 23, 42, 0.1);
}

.admin-update-notifier {
    position: fixed;
    bottom: 32px;
    right: 32px;
    z-index: 99990;
    
    background: #0f172a;
    color: #ffffff;
    border-radius: 14px;
    padding: 16px 20px;
    box-shadow: 0 16px 40px rgba(15, 23, 42, 0.2), 0 0 0 1px rgba(255, 255, 255, 0.1) inset;
    
    display: flex;
    align-items: center;
    gap: 16px;
    
    transform: translateY(20px) scale(0.95);
    opacity: 0;
    transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
}

.admin-update-notifier.is-active {
    transform: translateY(0) scale(1);
    opacity: 1;
}

.notifier-pulsing-dot {
    width: 8px;
    height: 8px;
    background-color: #10b981;
    border-radius: 50%;
    position: relative;
    flex-shrink: 0;
}

.notifier-pulsing-dot::after {
    content: '';
    position: absolute;
    top: -4px;
    left: -4px;
    right: -4px;
    bottom: -4px;
    background-color: #10b981;
    border-radius: 50%;
    opacity: 0.4;
    animation: dotPulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

@keyframes dotPulse {
    0% { transform: scale(0.5); opacity: 0.8; }
    100% { transform: scale(2.5); opacity: 0; }
}

.notifier-content {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.notifier-title {
    font-size: 14px;
    font-weight: 700;
    letter-spacing: 0.5px;
    color: #f8fafc;
}

#remoteVersionLabel {
    color: #10b981;
    background: rgba(16, 185, 129, 0.1);
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 11px;
    margin-left: 4px;
}

.notifier-desc {
    font-size: 12px;
    color: #94a3b8;
}

.notifier-actions {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-left: 12px;
    padding-left: 16px;
    border-left: 1px solid rgba(255, 255, 255, 0.1);
}

.btn-check-update {
    color: #ffffff;
    font-size: 12px;
    font-weight: 700;
    text-decoration: none;
    background: rgba(255, 255, 255, 0.1);
    padding: 6px 12px;
    border-radius: 6px;
    transition: all 0.2s;
    white-space: nowrap;
}

.btn-check-update:hover {
    background: #ffffff;
    color: #0f172a;
}

.btn-dismiss-update {
    background: transparent;
    border: none;
    color: #64748b;
    padding: 4px;
    cursor: pointer;
    border-radius: 4px;
    display: flex;
    transition: color 0.2s;
}

.btn-dismiss-update:hover {
    color: #ffffff;
    background: rgba(255, 255, 255, 0.1);
}
</style>

<?php
    // 查询其他设备已读状态 
    $db = Typecho_Db::get();
    $dismissedLocal = '';
    $dismissedRemote = '';
    
    $rowLocal = $db->fetchRow($db->select('value')->from('table.options')->where('name = ?', 'theme_dismissed_local_version'));
    if ($rowLocal) $dismissedLocal = $rowLocal['value'];
    
    $rowRemote = $db->fetchRow($db->select('value')->from('table.options')->where('name = ?', 'theme_dismissed_remote_version'));
    if ($rowRemote) $dismissedRemote = $rowRemote['value'];
?>

<?php 
/**
 * 全屏欢迎页，数据库记录的已读版本等于当前版本时输出
 */
if ($dismissedLocal !== THEME_VERSION): 
?>
<div class="admin-update-overlay" id="adminUpdateOverlay" style="display: flex;">
    <div class="update-modal m-4">
        <div class="update-modal-header">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="update-icon"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
            <h2 class="update-title">SYSTEM UPDATE COMPLETED</h2>
            <span class="update-version">v<?php echo THEME_VERSION; ?></span>
        </div>
        <div class="update-modal-body">
            <p>主理人，欢迎回来。站点已成功升级成功！</p >
            <ul>
                <li><strong>稳定：</strong>比平时多看了两眼项目，应该稳定了吧</li>
                <li><strong>增加：</strong>增加了文章页面的段落导航，适配手机端</li>
                <li><strong>审查：</strong>重新检查了一遍代码</li>
            </ul>
        </div>
        <div class="update-friendly-tip">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><path d="M12 16v-4"></path><path d="M12 8h.01"></path></svg>
            <span>点击开始使用后此页面在下次更新期间内将无法显示</span>
        </div>
        <div class="update-modal-footer">
            <button class="btn-enter-workspace" id="closeUpdateOverlayBtn">迫不及待的开始使用</button>
        </div>
    </div>
</div>
<script>
    setTimeout(() => document.getElementById('adminUpdateOverlay').classList.add('is-visible'), 10);
    document.getElementById('closeUpdateOverlayBtn').addEventListener('click', function() {
        const overlay = document.getElementById('adminUpdateOverlay');
        overlay.classList.remove('is-visible');
        overlay.classList.add('is-closing');
        
        //版本号写入数据库
        const formData = new FormData();
        formData.append('type', 'local');
        formData.append('version', '<?php echo THEME_VERSION; ?>');
        fetch('<?php echo Helper::options()->siteUrl();?>/?action=dismiss_theme_update', { method: 'POST', body: formData });
        
        setTimeout(() => overlay.style.display = 'none', 400);
    });
</script>
<?php endif; ?>

<div class="admin-update-notifier ms-4" id="adminUpdateNotifier" style="display: none;">
    <div class="notifier-pulsing-dot"></div>
    <div class="notifier-content">
        <span class="notifier-title">发现新版本 <span id="remoteVersionLabel"></span></span>
        <span class="notifier-desc">当前运行的是 v<?php echo THEME_VERSION; ?>，建议升级。</span>
    </div>
    <div class="notifier-actions">
        <a href="<?php echo Helper::options()->siteUrl();?>/admin/options-theme.php" class="btn-check-update">一键更新</a >
        <button class="btn-dismiss-update" id="dismissUpdateNotifier">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </button>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const currentVersion = '<?php echo THEME_VERSION; ?>';
    const serverDismissedRemote = '<?php echo $dismissedRemote; ?>'; // 获取服务器存的已忽略远端版本
    let fetchedRemoteVersion = ''; // 暂存抓取到的版本号

    const versionToInt = (ver) => {
        let parts = ver.replace('v', '').split('.');
        while (parts.length < 3) parts.push('0');
        return parseInt(parts[0]) * 1000000 + parseInt(parts[1]) * 1000 + parseInt(parts[2]);
    };

    fetch('<?php echo Helper::options()->siteUrl();?>/?action=check_theme_update')
        .then(response => response.json())
        .then(data => {
            if (data && data.version) {
                fetchedRemoteVersion = data.version;
                
                // 如果发现新版本，且这个新版本没有在别的设备上被你忽略过
                if (versionToInt(fetchedRemoteVersion) > versionToInt(currentVersion) && fetchedRemoteVersion !== serverDismissedRemote) {
                    document.getElementById('remoteVersionLabel').innerText = 'v' + fetchedRemoteVersion.replace('v', '');
                    const notifier = document.getElementById('adminUpdateNotifier');
                    notifier.style.display = 'flex';
                    setTimeout(() => notifier.classList.add('is-active'), 100);
                }
            }
        });

    document.getElementById('dismissUpdateNotifier').addEventListener('click', function() {
        const notifier = document.getElementById('adminUpdateNotifier');
        notifier.classList.remove('is-active');
        
        if (fetchedRemoteVersion) {
            const formData = new FormData();
            formData.append('type', 'remote');
            formData.append('version', fetchedRemoteVersion);
            fetch('<?php echo Helper::options()->siteUrl();?>/?action=dismiss_theme_update', { method: 'POST', body: formData });
        }
        setTimeout(() => notifier.style.display = 'none', 300);
    });
});
</script>
<?php endif; ?>