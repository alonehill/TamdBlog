<?php
/**
 * TamdBlog is a theme built on Typecho. It's vibrant and stunning, clear and elegant, with a minimalist white color scheme that makes your site the very definition of elegance
 * 
 * @package Tamd Blog
 * @author KAgDesign <3150675236@qq.com,me@gsav.cn>
 * @version 1.0.0
 * @link http://gsav.cn/
 *
 * This file is part of Tamdblog.
 * Tamdblog is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version. 
 */

if (!defined('__TYPECHO_ROOT_DIR__')) exit;

function themeConfig($form) {
        $currentVersion = '1.0.0'; 
        $updateApiUrl = 'http://cdn.gsav.cn/Tamd_Blog/update/config.json';
    
        echo '
        <div id="theme-update-notice" style="background: #f6f8fa; padding: 15px; border-radius: 4px; margin-bottom: 20px; border-left: 4px solid #005fb8; font-size: 14px;">
            <span id="update-msg">正在检查主题更新...</span>
        </div>
        
        <script>
        document.addEventListener("DOMContentLoaded", function() {
            var currentVersion = "' . $currentVersion . '";
            var apiUrl = "' . $updateApiUrl . '?t=" + new Date().getTime(); // 加时间戳防止缓存
            var noticeBox = document.getElementById("theme-update-notice");
            var msgBox = document.getElementById("update-msg");
    
            fetch(apiUrl)
                .then(function(response) {
                    if (!response.ok) throw new Error("Network response was not ok");
                    return response.json();
                })
                .then(function(data) {
                    // 简单的版本号比对 (假设格式为 x.y.z)
                    if (data.version !== currentVersion && compareVersion(data.version, currentVersion)) {
                        noticeBox.style.borderLeftColor = "#d73a49"; // 变成红色警示
                        noticeBox.style.background = "#ffeef0";
                        msgBox.innerHTML = "<strong>发现新版本：V" + data.version + "</strong> (当前版本 V" + currentVersion + ")<br>" +
                                           "<div style=\'margin: 8px 0; color: #586069;\'>" + data.changelog + "</div>" +
                                           "<a href=\'" + data.download_url + "\' target=\'_blank\' style=\'display: inline-block; background: #28a745; color: #fff; padding: 5px 15px; border-radius: 3px; text-decoration: none;\'>立即下载更新</a >";
                    } else {
                        noticeBox.style.borderLeftColor = "#28a745"; // 变成绿色安全
                        msgBox.innerHTML = "当前主题已是最新版本 (V" + currentVersion + ")，无需更新。";
                    }
                })
                .catch(function(error) {
                    msgBox.innerHTML = "检查主题更新失败，请稍后重试或前往作者主页查看。";
                });
                
            function compareVersion(v1, v2) {
                var parts1 = v1.split(".");
                var parts2 = v2.split(".");
                for (var i = 0; i < Math.max(parts1.length, parts2.length); i++) {
                    var p1 = parseInt(parts1[i]) || 0;
                    var p2 = parseInt(parts2[i]) || 0;
                    if (p1 > p2) return true;
                    if (p1 < p2) return false;
                }
                return false;
            }
        });
        </script>
        ';
    $db = Typecho_Db::get();
    $themeName = 'test-theme'; 
    $themeKey = 'theme:' . $themeName;
    $backupKey = 'theme_' . $themeName . '_backup';

    $backupUrl = Helper::options()->adminUrl . 'options-theme.php?themeBackup=1';
    $restoreUrl = Helper::options()->adminUrl . 'options-theme.php?themeRestore=1';

    if (isset($_GET['themeBackup'])) {
        $currentConfig = $db->fetchRow($db->select('value')->from('table.options')->where('name = ?', $themeKey));
        if ($currentConfig) {
            $hasBackup = $db->fetchRow($db->select('value')->from('table.options')->where('name = ?', $backupKey));
            if ($hasBackup) {
                $db->query($db->update('table.options')->rows(['value' => $currentConfig['value']])->where('name = ?', $backupKey));
            } else {
                $db->query($db->insert('table.options')->rows(['name' => $backupKey, 'value' => $currentConfig['value']]));
            }
            Typecho_Widget::widget('Widget_Notice')->set(_t('备份成功！(备份后切换主题的丢失数据可以在这里找回)。'), 'success');
        } else {
            Typecho_Widget::widget('Widget_Notice')->set(_t('备份失败：未找到当前主题的设置数据。'), 'error');
        }
        Typecho_Widget::widget('Widget_Options')->response->redirect(Helper::options()->adminUrl . 'options-theme.php');
    }

    if (isset($_GET['themeRestore'])) {
        $backupConfig = $db->fetchRow($db->select('value')->from('table.options')->where('name = ?', $backupKey));
        if ($backupConfig) {
            $db->query($db->update('table.options')->rows(['value' => $backupConfig['value']])->where('name = ?', $themeKey));
            Typecho_Widget::widget('Widget_Notice')->set(_t('恢复成功！'), 'success');
        } else {
            Typecho_Widget::widget('Widget_Notice')->set(_t('找不到任何备份数据，请确认之前是否进行过备份。'), 'notice');
        }
        Typecho_Widget::widget('Widget_Options')->response->redirect(Helper::options()->adminUrl . 'options-theme.php');
    }

    $backup_ui = new Typecho_Widget_Helper_Layout('div', ['class=' => 'typecho-page-title']);
    $backup_ui->html('
        <div style="background:#fff; padding: 15px; border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 20px;">
            <h4 style="margin-top: 0;">数据保护 (防止切换主题丢失)</h4>
            <p style="color: #666; font-size: 13px;">请在切换主题前点击【备份】，重新换回本主题后再点击【恢复】。</p>
            <div style="display: flex; gap: 10px;">
                <a href="' . $backupUrl . '" class="btn btn-primary" style="text-decoration: none;">备份模板设置</a>
                <a href="' . $restoreUrl . '" class="btn warn" style="text-decoration: none;" onclick="return confirm(\'确定要恢复吗？当前的未备份设置将被覆盖！\');">恢复模板设置</a>
            </div>
        </div>
    ');
    
    $form->addItem($backup_ui);

     $logoUrl = new \Typecho\Widget\Helper\Form\Element\Text(
        'logoUrl',
        null,
        null,
        _t('站点 LOGO 地址'),
        _t('在这里填入一个图片 URL 地址, 以在网站标题前加上一个 LOGO')
    );

    $form->addInput($logoUrl->addRule('url', _t('请填写一个合法的URL地址')));

    // 侧边栏控制开关
    $sidebarStatus = new Typecho_Widget_Helper_Form_Element_Radio(
        'sidebarStatus',
        array('on' => '开启侧边栏', 'off' => '关闭侧边栏'),
        'off',
        '侧边栏状态',
        '选择是否显示全局侧边栏。'
    );

    $form->addInput($sidebarStatus);

    $sidebarBlock = new \Typecho\Widget\Helper\Form\Element\Checkbox(
        'sidebarBlock',
        [
            'ShowRecentPosts'    => _t('显示最新文章'),
            'ShowRecentComments' => _t('显示最近回复'),
            'ShowCategory'       => _t('显示分类'),
            'ShowArchive'        => _t('显示归档'),
            'ShowOther'          => _t('显示其它杂项')
        ],
        ['ShowRecentPosts', 'ShowRecentComments', 'ShowCategory', 'ShowArchive', 'ShowOther'],
        _t('侧边栏显示')
    );

    $form->addInput($sidebarBlock->multiMode());
    
    echo '<h2 style="font-size:18px; font-weight:600; color:#0f172a; margin-bottom:24px; letter-spacing:1px;">STUDIO THEME CONFIGURATION</h2>';

    $miitbeian = new Typecho_Widget_Helper_Form_Element_Text(
        'miitbeian', 
        NULL, 
        NULL, 
        '工信部备案号', 
        '填入后将以极简字母大写化的形式优雅呈现于页脚右侧。'
    );
    $form->addInput($miitbeian);

    $sliderStatus = new Typecho_Widget_Helper_Form_Element_Radio(
        'sliderStatus',
        array('on' => '开启幻灯片', 'off' => '关闭幻灯片'),
        'off',
        '幻灯片状态',
        '选择是否在首页顶部渲染流光幻灯片。'
    );
    $form->addInput($sliderStatus);

    $sliderSlug = new Typecho_Widget_Helper_Form_Element_Text(
        'sliderSlug', 
        NULL, 
        'slider', 
        '幻灯片专用分类别名 (Slug)', 
        '在此输入你多带带为幻灯片创建的分类别名（例如 slider）。系统将自动抓取该分类下的文章图片与文字生成海报墙。'
    );
    $form->addInput($sliderSlug);

    $sliderNum = new Typecho_Widget_Helper_Form_Element_Text(
        'sliderNum', 
        NULL, 
        '3', 
        '幻灯片最大显示数量', 
        '建议 3 - 5 张，保持视觉紧凑、不臃肿。'
    );
    $form->addInput($sliderNum);
}

function postMeta(
    \Widget\Archive $archive,
    string $metaType = 'archive'
)
{
    $titleTag = $metaType == 'archive' ? 'h2' : 'h1';
?>
    <<?php echo $titleTag ?> class="post-title" itemprop="name headline">
        <a itemprop="url"
           href="<?php $archive->permalink() ?>"><?php $archive->title() ?></a>
    </<?php echo $titleTag ?>>
    <?php if ($metaType != 'page'): ?>
        <ul class="post-meta">
            <li itemprop="author" itemscope itemtype="http://schema.org/Person">
            <?php _e('作者'); ?>: <a itemprop="name"
                                       href="<?php $archive->author->permalink(); ?>"
                                       rel="author"><?php $archive->author(); ?></a>
            </li>
            <li><?php _e('时间'); ?>:
                <time datetime="<?php $archive->date('c'); ?>" itemprop="datePublished"><?php $archive->date(); ?></time>
            </li>
            <li><?php _e('分类'); ?>: <?php $archive->category(','); ?></li>
            <?php if ($metaType == 'archive'): ?>
                <li itemprop="interactionCount">
                    <a itemprop="discussionUrl"
                       href="<?php $archive->permalink() ?>#comments"><?php $archive->commentsNum(_t('暂无评论'), _t('1 条评论'), _t('%d 条评论')); ?></a>
                </li>
            <?php endif; ?>
        </ul>
    <?php endif; ?>
<?php
}

function art_count($cid){
    $db = Typecho_Db::get();
    $rs = $db->fetchRow($db->select('text')->from('table.contents')->where('cid = ?', $cid));
    return mb_strlen($rs['text'], 'UTF-8');
}

/**
 * 获取文章首张图片
 * @param $archive
 * @param string $default 无图默认地址
 * @return string
 */
function get_post_img($archive, $default = '')
{
    $content = $archive->content;
    // 匹配第一张img标签
    preg_match('/<img.*?src="(.*?)"/i', $content, $match);
    if (!empty($match[1])) {
        return $match[1];
    }
    // 读取自定义字段 thumb
    $thumb = $archive->fields->thumb;
    if (!empty($thumb)) {
        return $thumb;
    }
    return $default;
}

/**
* 阅读统计
* 调用
*/
function get_post_view($archive) {
    $db = Typecho_Db::get();
    $cid = $archive->cid;
    if (!array_key_exists('views', $db->fetchRow($db->select()->from('table.contents')))) {
        $db->query('ALTER TABLE `'.$db->getPrefix().'contents` ADD `views` INT(10) DEFAULT 0;');
    }
    $exist = $db->fetchRow($db->select('views')->from('table.contents')->where('cid = ?', $cid))['views'];
    if ($archive->is('single')) {
        $cookie = Typecho_Cookie::get('contents_views');
        $cookie = $cookie ? explode(',', $cookie) : array();
        if (!in_array($cid, $cookie)) {
            $db->query($db->update('table.contents')
                ->rows(array('views' => (int)$exist+1))
                ->where('cid = ?', $cid));
            $exist = (int)$exist+1;
            array_push($cookie, $cid);
            $cookie = implode(',', $cookie);
            Typecho_Cookie::set('contents_views', $cookie);
        }
    }
    echo $exist == 0 ? '0' :  $exist;
}

// 在后台文章列表增加阅读量列
Typecho_Plugin::factory('admin/manage-posts.php')->writeRow = array('PostView', 'outputAdminRow');
Typecho_Plugin::factory('admin/manage-posts.php')->header = array('PostView', 'outputAdminHeader');

class PostView {
    public static function outputAdminHeader() {
        echo '<th class="typecho-radius-topright">阅读量</th>';
    }
    public static function outputAdminRow($post) {
        $db = Typecho_Db::get();
        $row = $db->fetchRow($db->select('views')->from('table.contents')->where('cid = ?', $post['cid']));
        $views = isset($row['views']) ? $row['views'] : 0;
        echo '<td>' . $views . ' 次</td>';
    }
}