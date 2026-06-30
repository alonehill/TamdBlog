<?php
/**
 * TamdBlog is a theme built on Typecho. It's vibrant and stunning, clear and elegant, with a minimalist white color scheme that makes your site the very definition of elegance
 * 
 * @package Tamd Blog
 * @author KAgDesign <3150675236@qq.com,me@gsav.cn>
 * @version 1.0.2
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
    //检查更新
    $themeVersion = '1.0.2'; 
    $apiUrl = 'http://code.gsav.cn/update/config.json'; 
    $updateInfo = getThemeUpdateInfo($apiUrl);

    if ($updateInfo && version_compare($themeVersion, $updateInfo['version'], '<')) {
        echo '<div style="color: #467B96; background: #E8F6FF; padding: 10px; border-radius: 4px; margin-bottom: 15px;">
                <strong>发现新版本：' . $updateInfo['version'] . '</strong><br>
                更新日志：<br>' . nl2br($updateInfo['changelog']) . '<br><br>
                <form method="post" action="?theme_action=update_theme">
                    <input type="hidden" name="download_url" value="' . $updateInfo['download_url'] . '">
                    <button type="submit" class="btn primary" onclick="return confirm(\'强烈建议更新前备份当前主题！确认要执行自动更新吗？\');">一键自动更新</button>
                </form>
            </div>';
    } else {
        echo '<div style="color: #468847; background: #DFF0D8; padding: 10px; border-radius: 4px; margin-bottom: 15px;">当前主题已是最新版本 (v' . $themeVersion . ')</div>';
    }
    //检查更新 end

    //数据更新
    $db = Typecho_Db::get();
    $themeName = 'TamdBlog'; 
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
                <a href="' . $backupUrl . '" class="" style="text-decoration: none;">备份模板设置</a>
                <a href="' . $restoreUrl . '" class="" style="text-decoration: none;" onclick="return confirm(\'确定要恢复吗？当前的未备份设置将被覆盖！\');">恢复模板设置</a>
            </div>
        </div>
    ');
   
    $form->addItem($backup_ui);
    //数据更新 end

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

    // 头像源选项 (预设了几个目前国内比较稳定的源)
    $avatarSource = new \Typecho\Widget\Helper\Form\Element\Select('avatarSource', array(
        'https://cravatar.cn/avatar' => 'Cravatar (推荐)',
        'https://cdn.v2ex.com/gravatar' => 'V2EX',
        'https://sdn.geekzu.org/avatar' => '极客族',
        'https://weavatar.com/avatar' => 'WeAvatar',
        'https://secure.gravatar.com/avatar' => 'Gravatar 官方'
    ), 'https://cravatar.cn/avatar', _t('头像加速源'), _t('选择一个国内访问速度较快的头像源。'));
    $form->addInput($avatarSource);

    // 自定义默认头像
    $defaultAvatar = new \Typecho\Widget\Helper\Form\Element\Text('defaultAvatar', NULL, NULL, _t('自定义默认头像'), _t('填入图片绝对链接。当用户没有设置头像时显示。留空则显示源站的默认图标 (通常是灰色人像)。'));
    $form->addInput($defaultAvatar);
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

function getThemeUpdateInfo($url) {
    if (!function_exists('curl_init')) return false;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5); // 设置超时避免后台卡顿
    $output = curl_exec($ch);
    curl_close($ch);
    
    if ($output) {
        return json_decode($output, true);
    }
    return false;
}

if (isset($_GET['theme_action']) && $_GET['theme_action'] == 'update_theme' && isset($_POST['download_url'])) {
    // 权限校验
    $user = Typecho_Widget::widget('Widget_User');
    if (!$user->pass('administrator', true)) {
        die('无权操作');
    }

    $downloadUrl = $_POST['download_url'];
    $themeDir = dirname(__FILE__); // 当前主题目录路径
    $tempZipFile = $themeDir . '/update_temp.zip';

    // 下载文件
    $zipData = file_get_contents($downloadUrl);
    if ($zipData === false) {
        die('下载更新包失败，请检查服务器网络或手动下载更新。');
    }
    file_put_contents($tempZipFile, $zipData);

    // 解压并覆盖文件
    if (class_exists('ZipArchive')) {
        $zip = new ZipArchive();
        if ($zip->open($tempZipFile) === TRUE) {
            // 解压到当前主题目录直接覆盖
            $zip->extractTo($themeDir);
            $zip->close();
            
            // 清理临时压缩包
            unlink($tempZipFile);
            
            // 更新成功，带提示跳转回主题设置页
            \Widget\Notice::alloc()->set(_t('主题自动更新成功！'), 'success');
            $adminUrl = \Widget\Options::alloc()->adminUrl;
            header('Location: ' . $adminUrl . 'options-theme.php');
            exit;
        } else {
            unlink($tempZipFile);
            die('解压失败，请检查主题目录读写权限。');
        }
    } else {
        unlink($tempZipFile);
        die('服务器未开启 ZipArchive 扩展，无法执行自动更新。');
    }
}


/**
 * 表情映射表：码点 => 短代码
 */
function themeEmojiMap() {
    return [
        '1f479' => ':1f479:',
        '1f47a' => ':1f47a:',
        '1f47b' => ':1f47b:',
        '1f47d' => ':1f47d:',
        '1f47e' => ':1f47e:',
        '1f47f' => ':1f47f:',
        '1f480' => ':1f480:',
        '1f494' => ':1f494:',
        '1f4a2' => ':1f4a2:',
        '1f4a6' => ':1f4a6:',
        '1f4a9' => ':1f4a9:',
        '1f600' => ':1f600:',
        '1f601' => ':1f601:',
        '1f602' => ':1f602:',
        '1f603' => ':1f603:',
        '1f604' => ':1f604:',
        '1f605' => ':1f605:',
        '1f606' => ':1f606:',
        '1f607' => ':1f607:',
        '1f608' => ':1f608:',
        '1f609' => ':1f609:',
        '1f60a' => ':1f60a:',
        '1f60b' => ':1f60b:',
        '1f60c' => ':1f60c:',
        '1f60d' => ':1f60d:',
        '1f60e' => ':1f60e:',
        '1f60f' => ':1f60f:',
        '1f610' => ':1f610:',
        '1f611' => ':1f611:',
        '1f612' => ':1f612:',
        '1f613' => ':1f613:',
        '1f614' => ':1f614:',
        '1f615' => ':1f615:',
        '1f616' => ':1f616:',
        '1f617' => ':1f617:',
        '1f618' => ':1f618:',
        '1f619' => ':1f619:',
        '1f61a' => ':1f61a:',
        '1f61b' => ':1f61b:',
        '1f61c' => ':1f61c:',
        '1f61d' => ':1f61d:',
        '1f61e' => ':1f61e:',
        '1f61f' => ':1f61f:',
        '1f620' => ':1f620:',
        '1f621' => ':1f621:',
        '1f622' => ':1f622:',
        '1f623' => ':1f623:',
        '1f624' => ':1f624:',
        '1f625' => ':1f625:',
        '1f626' => ':1f626:',
        '1f627' => ':1f627:',
        '1f628' => ':1f628:',
        '1f629' => ':1f629:',
        '1f62a' => ':1f62a:',
        '1f62b' => ':1f62b:',
        '1f62c' => ':1f62c:',
        '1f62d' => ':1f62d:',
        '1f62e' => ':1f62e:',
        '1f62f' => ':1f62f:',
        '1f630' => ':1f630:',
        '1f631' => ':1f631:',
        '1f632' => ':1f632:',
        '1f633' => ':1f633:',
        '1f634' => ':1f634:',
        '1f635' => ':1f635:',
        '1f636' => ':1f636:',
        '1f637' => ':1f637:',
        '1f641' => ':1f641:',
        '1f642' => ':1f642:',
        '1f643' => ':1f643:',
        '1f644' => ':1f644:',
        '1f648' => ':1f648:',
        '1f649' => ':1f649:',
        '1f64a' => ':1f64a:',
        '1f910' => ':1f910:',
        '1f911' => ':1f911:',
        '1f912' => ':1f912:',
        '1f913' => ':1f913:',
        '1f914' => ':1f914:',
        '1f915' => ':1f915:',
        '1f916' => ':1f916:',
        '1f917' => ':1f917:',
        '1f920' => ':1f920:',
        '1f921' => ':1f921:',
        '1f922' => ':1f922:',
        '1f923' => ':1f923:',
        '1f924' => ':1f924:',
        '1f925' => ':1f925:',
        '1f927' => ':1f927:',
        '1f928' => ':1f928:',
        '1f929' => ':1f929:',
        '1f92a' => ':1f92a:',
        '1f92b' => ':1f92b:',
        '1f92c' => ':1f92c:',
        '1f92d' => ':1f92d:',
        '1f92e' => ':1f92e:',
        '1f92f' => ':1f92f:',
        '1f970' => ':1f970:',
        '1f971' => ':1f971:',
        '1f972' => ':1f972:',
        '1f973' => ':1f973:',
        '1f974' => ':1f974:',
        '1f975' => ':1f975:',
        '1f976' => ':1f976:',
        '1f978' => ':1f978:',
        '1f97a' => ':1f97a:',
        '1f9d0' => ':1f9d0:',
        '2620-fe0f' => ':2620-fe0f:',
        '2639-fe0f' => ':2639-fe0f:',
        '263a-fe0f' => ':263a-fe0f:',
        '2764-fe0f' => ':2764-fe0f:',

    ];
}

/**
 * 解析评论内容中的表情短代码
 * 将 :shortcode: 替换为 SVG 图片
 */
function parseEmoji($content) {
    $map = themeEmojiMap();
    $themeUrl = rtrim(Helper::options()->themeUrl, '/');
    
    foreach ($map as $codepoint => $shortcode) {
        // 检查 SVG 文件是否存在
        $svgFile = __DIR__ . '/emojis/' . $codepoint . '.svg';
        if (!file_exists($svgFile)) {
            continue; // 跳过不存在的文件
        }
        
        $svgUrl = $themeUrl . '/emojis/' . $codepoint . '.svg';
        $replacement = '<img src="' . $svgUrl . '" alt="' . $shortcode . '" class="emoji-img" width="24" height="24" loading="lazy">';
        $content = str_ireplace($shortcode, $replacement, $content);
    }
    
    return $content;
}

/**
 * 获取自定义头像 URL
 * 
 * @param string $email 用户的邮箱
 * @param int $size 头像尺寸
 * @return string
 */
function getCustomAvatar($email, $size = 100) {
    $options = \Widget\Options::alloc();
    $source = $options->avatarSource ? rtrim($options->avatarSource, '/') : 'https://cravatar.cn/avatar';
    $default = $options->defaultAvatar ? $options->defaultAvatar : 'mp';
    $hash = md5(strtolower(trim($email ?? '')));
    return $source . '/' . $hash . '?s=' . $size . '&d=' . urlencode($default);
}