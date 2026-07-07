<?php
/**
 * TamdBlog is a theme built on Typecho. It's vibrant and stunning, clear and elegant, with a minimalist white color scheme that makes your site the very definition of elegance
 * 
 * @package Tamd Blog
 * @author KAg Design <3150675236@qq.com,me@gsav.cn>
 * @version 1.0.4
 * @link http://gsav.cn/
 *
 * This file is part of Tamdblog.
 * Tamdblog is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version. 
 */

if (!defined('__TYPECHO_ROOT_DIR__')) exit;
// 注册主题全局版本号常量
if (!defined('THEME_VERSION')) {
    define('THEME_VERSION', '1.0.4');
}

if (!defined('THEME_API_URL')) {
    define('THEME_API_URL', 'https://code.gsav.cn/update/config.json');
}

if (!function_exists('getThemeUpdateInfo')) {
    function getThemeUpdateInfo($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data ? json_decode($data, true) : false;
    }
}

function themeInit($archive) {
    $user = Typecho_Widget::widget('Widget_User');
    
    if ($user->hasLogin() && $user->pass('administrator', true)) {
        
        if (isset($_GET['action']) && $_GET['action'] == 'check_theme_update') {
            header('Content-Type: application/json');
            $updateInfo = getThemeUpdateInfo(THEME_API_URL);
            if ($updateInfo) {
                echo json_encode($updateInfo);
            } else {
                echo '{"version":"' . THEME_VERSION . '"}'; 
            }
            exit;
        }
        
        if (isset($_GET['action']) && $_GET['action'] == 'dismiss_theme_update') {
            $type = isset($_POST['type']) ? $_POST['type'] : 'local';
            $version = isset($_POST['version']) ? $_POST['version'] : THEME_VERSION;
            
            $db = Typecho_Db::get();
            $optionName = 'theme_dismissed_' . $type . '_version';
            
            $row = $db->fetchRow($db->select('value')->from('table.options')->where('name = ?', $optionName));
            if ($row) {
                $db->query($db->update('table.options')->rows(array('value' => $version))->where('name = ?', $optionName));
            } else {
                $db->query($db->insert('table.options')->rows(array('name' => $optionName, 'value' => $version, 'user' => 0)));
            }
            
            header('Content-Type: application/json');
            echo '{"status":"success"}';
            exit;
        }
    }

    /**
     * 友情链接
     * 
     */

    $db = Typecho_Db::get();
    $prefix = $db->getPrefix();
    // 检查是否存在links
    try {
        $db->fetchRow($db->select()->from('table.links')->limit(1));
    } catch (Exception $e) {
        //自动创建一个基础的友链表
        $sql = "CREATE TABLE IF NOT EXISTS `{$prefix}links` (
            `lid` int(10) unsigned NOT NULL AUTO_INCREMENT,
            `name` varchar(200) DEFAULT NULL,
            `url` varchar(200) DEFAULT NULL,
            `sort` varchar(200) DEFAULT NULL,
            `image` varchar(200) DEFAULT NULL,
            `description` varchar(200) DEFAULT NULL,
            `user` int(10) unsigned DEFAULT '0',
            `order` int(10) unsigned DEFAULT '0',
            PRIMARY KEY (`lid`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        $db->query($sql);
    }

    //处理前端申请友链的请求
    if (isset($_GET['action']) && $_GET['action'] == 'apply_link') {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            
            $name = isset($_POST['name']) ? trim(strip_tags($_POST['name'])) : '';
            $url = isset($_POST['url']) ? trim(strip_tags($_POST['url'])) : '';
            $avatar = isset($_POST['avatar']) ? trim(strip_tags($_POST['avatar'])) : '';
            $desc = isset($_POST['desc']) ? trim(strip_tags($_POST['desc'])) : '';

            //校验
            if (empty($name) || empty($url)) {
                echo json_encode(['status' => 'error', 'msg' => '名称和链接不能为空']);
                exit;
            }

            //存入 table.links
            try {
                $insertData = [
                    'name'        => $name,
                    'url'         => $url,
                    'image'       => $avatar,
                    'description' => $desc,
                    'sort'        => 'pending',
                    'user'        => 0
                ];
                $db->query($db->insert('table.links')->rows($insertData));
                
                echo json_encode(['status' => 'success', 'msg' => '申请成功，等待主理人审核']);
            } catch (Exception $e) {
                echo json_encode(['status' => 'error', 'msg' => '数据库写入失败']);
            }
            exit;
        }
    }
    $user = Typecho_Widget::widget('Widget_User');
    if ($user->hasLogin() && $user->pass('administrator', true)) {
        
        //审核通过或删除
        if (isset($_GET['manage_action']) && isset($_GET['lid'])) {
            $lid = intval($_GET['lid']);
            if ($_GET['manage_action'] == 'approve') {
                $db->query($db->update('table.links')->rows(['sort' => 'approved'])->where('lid = ?', $lid));
            } elseif ($_GET['manage_action'] == 'delete') {
                $db->query($db->delete('table.links')->where('lid = ?', $lid));
            }
            //完成后刷新控制台
            header('Location: /?action=manage_links');
            exit;
        }

        if (isset($_GET['action']) && $_GET['action'] == 'manage_links') {
            $links = $db->fetchAll($db->select()->from('table.links')->order('lid', Typecho_Db::SORT_DESC));
            ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>友链管理 · 后台控制台</title>
  <style>
    * {margin: 0;padding: 0;box-sizing: border-box;}
    body {background: #c0c0c0;color: #000;font-family: 'Times New Roman', Times, serif;padding: 20px 15px;line-height: 1.5;}
    .main-panel {max-width: 720px;margin: 0 auto;background: #d4d0c8;border: 2px solid;border-color: #ffffff #808080 #808080 #ffffff;padding: 2px;box-shadow: 1px 1px 0 #000;}
    .title-bar {background: #000080;color: white;padding: 4px 8px;font-size: 14px;font-weight: bold;display: flex;justify-content: space-between;align-items: center;margin: 0;font-family: 'Times New Roman', Times, serif;}
    .title-bar h1 {font-size: 14px;font-weight: bold;margin: 0;letter-spacing: 0.5px;}
    .user-info {font-size: 12px;background: #c0c0c0;color: #000;padding: 2px 10px;border: 1px solid;border-color: #808080 #ffffff #ffffff #808080;}
    .content-area {background: #d4d0c8;padding: 16px 12px;}
    .link-list {display: flex;flex-direction: column;gap: 12px;}
    .card {background: #d4d0c8;border: 2px solid;border-color: #ffffff #808080 #808080 #ffffff;padding: 14px 12px;display: flex;flex-direction: column;gap: 8px;}
    .card-header {display: flex;justify-content: space-between;align-items: center;border-bottom: 1px solid #808080;padding-bottom: 6px;margin-bottom: 2px;}
    .site-name {font-size: 15px;font-weight: bold;color: #000;word-break: break-word;}
    .status-tag {font-size: 11px;font-weight: bold;padding: 2px 10px;border: 1px solid;background: #c0c0c0;white-space: nowrap;}
    .status-pending {color: #804000;border-color: #808080 #ffffff #ffffff #808080;background: #e0d8c0;}
    .status-approved {color: #004000;border-color: #808080 #ffffff #ffffff #808080;background: #c0d8c0;}
    .desc-text {font-size: 13px;color: #000;background: white;border: 1px solid;border-color: #808080 #ffffff #ffffff #808080;padding: 6px 8px;word-break: break-all;}
    .link-url {font-size: 12px;color: #0000cc;text-decoration: underline;font-family: 'Courier New', monospace;background: white;padding: 4px 8px;border: 1px solid;border-color: #808080 #ffffff #ffffff #808080;display: inline-block;word-break: break-all;}
    .link-url:visited {color: #800080;}
    .action-group {display: flex;gap: 10px;margin-top: 6px;flex-wrap: wrap;}
    .btn {font-family: 'Times New Roman', Times, serif;font-size: 13px;font-weight: bold;text-decoration: none;padding: 6px 18px;border: 2px solid;border-color: #ffffff #808080 #808080 #ffffff;background: #c0c0c0;color: #000;cursor: pointer;text-align: center;display: inline-block;letter-spacing: 0.3px;}
    .btn:active {border-color: #808080 #ffffff #ffffff #808080;background: #a0a0a0;}
    .btn-approve {background: #c0c0c0;color: #000;font-weight: bold;}
    .btn-delete {background: #c0c0c0;color: #800000;font-weight: bold;}
    .empty-state {text-align: center;color: #000;padding: 30px 0;font-size: 14px;background: #d4d0c8;border: 1px dashed #808080;}
    .status-bar {margin-top: 16px;border-top: 1px solid #808080;padding-top: 6px;font-size: 11px;color: #000;display: flex;justify-content: space-between;background: #d4d0c8;}
  </style>
</head>
<body>
  <div class="main-panel">
    <div class="title-bar">
      <h1>📎 友链管理控制台</h1>
      <span class="user-info"><?php $user->screenName(); ?> [管理员]</span>
    </div>

    <div class="content-area">
      <div class="link-list">
        <?php if(empty($links)): ?>
          <div class="empty-state">
            【暂无友链申请记录】
          </div>
        <?php else: ?>
          <?php foreach($links as $link): ?>
            <div class="card">
              <div class="card-header">
                <span class="site-name"><?php echo htmlspecialchars($link['name']); ?></span>
                <span class="status-tag <?php echo ($link['sort'] === 'pending') ? 'status-pending' : 'status-approved'; ?>">
                  <?php echo ($link['sort'] === 'pending') ? '待审核' : '已批准'; ?>
                </span>
              </div>

              <div class="desc-text">
                <?php echo htmlspecialchars($link['description'] ? $link['description'] : '（未提供描述）'); ?>
              </div>

              <a href="<?php echo htmlspecialchars($link['url']); ?>" target="_blank" rel="noopener" class="link-url">
                <?php echo htmlspecialchars($link['url']); ?>
              </a>

              <div class="action-group">
                <?php if($link['sort'] === 'pending'): ?>
                  <a href="<?php echo Helper::options()->siteUrl();?>/?action=manage_links&manage_action=approve&lid=<?php echo $link['lid']; ?>" class="btn btn-approve">
                    ✔ 批准
                  </a>
                <?php endif; ?>
                <a href="<?php echo Helper::options()->siteUrl();?>/?action=manage_links&manage_action=delete&lid=<?php echo $link['lid']; ?>" 
                   class="btn btn-delete" 
                   onclick="return confirm('警告：确定要删除此友链吗？');">
                  ✘ 删除
                </a>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>

      <div class="status-bar">
        <span>就绪</span>
        <span>友链控制台 v1.0</span>
      </div>
    </div>
  </div>
</body>
</html>
            <?php
            exit;
        }
    }
}

function themeConfig($form) {
    //检查更新
    $updateInfo = getThemeUpdateInfo(THEME_API_URL);

    if ($updateInfo && version_compare(THEME_VERSION, $updateInfo['version'], '<')) {
        echo '<div style="color: #467B96; background: #E8F6FF; padding: 10px; border-radius: 4px; margin-bottom: 15px;">
                <strong>发现新版本：' . $updateInfo['version'] . '</strong><br>
                更新日志：<br>' . nl2br($updateInfo['changelog']) . '<br><br>
                <form method="post" action="?theme_action=update_theme">
                    <input type="hidden" name="download_url" value="' . $updateInfo['download_url'] . '">
                    <button type="submit" class="btn primary" onclick="return confirm(\'强烈建议更新前备份当前主题！确认要执行自动更新吗？\');">一键自动更新</button>
                </form>
            </div>';
    } else {
        echo '<div style="color: #468847; background: #DFF0D8; padding: 10px; border-radius: 4px; margin-bottom: 15px;">当前主题已是最新版本 (v' . THEME_VERSION . ')</div>';
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
            'ShowName'           => _t('显示名片'),
            'ShowRecentPosts'    => _t('显示最新文章'),
            'ShowRecentComments' => _t('显示最近回复'),
            'ShowCategory'       => _t('显示分类'),
            'ShowArchive'        => _t('显示归档'),
            'ShowOther'          => _t('显示其它杂项')
        ],
        ['ShowName', 'ShowRecentPosts', 'ShowRecentComments', 'ShowCategory', 'ShowArchive', 'ShowOther'],
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
    
    $sliderStyle = new Typecho_Widget_Helper_Form_Element_Radio(
        'sliderStyle',
        array(
            '0' => '无样式',
            'a' => '样式 A (TopSlideA)',
            'b' => '样式 B (TopSlideB)',
            'c' => '样式 C (TopSlideC)'
        ),
        '0',
        '轮播图/小部件样式',
        '请选择你要在页面中应用的小部件样式。'
    );
    
    $form->addInput($sliderStyle);

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

    // 头像源选项
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

    $fontsCdnLink = new Typecho_Widget_Helper_Form_Element_Radio(
        'fontsCdnLink',
        array(
            'a' => '路线一(国内镜像CDN)',
            'b' => '路线二(谷歌CDN)'
        ),
        'a',
        '字体CDN路线选择',
        '如果发现页面加载缓慢，且使用的路线二，切换路线一。'
    );
    $form->addInput($fontsCdnLink);

    $indexPostIcon = new Typecho_Widget_Helper_Form_Element_Radio(
        'indexPostIcon',
        array(
            'a' => '线条图标（适配主题风格）',
            'b' => '彩块（年轻活力，视觉效果精彩）'
        ),
        'a',
        '主页面文章列表底部mate图标',
        '选择你喜欢的样式'
    );
    $form->addInput($indexPostIcon);

    $postToc = new Typecho_Widget_Helper_Form_Element_Radio(
        'postToc',
        array('on' => '开启', 'off' => '关闭'),
        'on',
        '文章段落导航',
        '选择是否在文章页面开启段落导航。'
    );
    $form->addInput($postToc);
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
 * 获取文章内容并移除第一张图片
 */
function get_content_without_first_image($post) {
    $content = $post->content;
    
    // 如果文章有自定义特色图，直接返回完整内容
    if (isset($post->fields->thumb) && !empty($post->fields->thumb)) {
        return $content;
    }
    
    // 移除第一张图片
    $pattern = '/<p>\s*<img[^>]+>\s*<\/p>/i';
    $content = preg_replace($pattern, '', $content, 1);
    
    if ($content === $post->content) {
        $pattern = '/<img[^>]+>/i';
        $content = preg_replace($pattern, '', $content, 1);
    }
    
    return $content;
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

// 加载 IP 查询模块
require_once __DIR__ . '/inc/ip2region/ip-handler.php';

function theme_comment_ip_location($comments) {
    foreach ($comments as $comment) {
        show_comment_ip_location($comment);
    }
}
