<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;



function themeConfig($form) {
    /**
     * 此处启用用户安装引导和用户体验计划的数据发送，用户未同意则不会提取数据
     */
    $needActivation = checkAndProcessActivation();
    if ($needActivation) {
        renderActivationPage();
    } 

    echo '<div class="custom-theme-settings"><div class="settings-header"><h1>TamdBlog<span class="tmd-hero-version">' . THEME_VERSION . '</span> 主题设置<span class="badge-dot pure pulse-ring" id="dotPure"></span></h1><p>自定义您的主题外观和功能</p></div></div>';

    $themeUrl = Helper::options()->themeUrl;    
    $themeIconStyle = 'streamline'; //streamline  phosphoricons
    
    // ---------------
    // 基础设置
    // ---------------
    $title1 = new Typecho_Widget_Helper_Layout('h2', array('class' => 'typecho-setting-title tamd-h2'));
    $title1->html('<img style="width: 19px;height: 19px;" src="' . $themeUrl . '/static/admin/' . $themeIconStyle . '/faders.svg">&nbsp基础设置');
    $form->addItem($title1);
    
    /**
     * 
     */
    $logoUrlLight = new \Typecho\Widget\Helper\Form\Element\Text(
        'logoUrlLight', 
        null, 
        null, 
        _t('站点 LOGO 地址-日间主题'), 
        _t('在这里填入一个图片 URL 地址, 以在网站标题前加上一个 logo, 如果不填留空则显示文字 logo'));
    $form->addInput($logoUrlLight->addRule('url', _t('请填写一个合法的URL地址')));

    $logoUrlDark = new \Typecho\Widget\Helper\Form\Element\Text(
        'logoUrlDark', 
        null, 
        null, 
        _t('站点 LOGO 地址-夜间主题'), 
        _t(''));
    $form->addInput($logoUrlDark->addRule('url', _t('请填写一个合法的URL地址')));
    /**
     * 
     */
    $miitbeian = new Typecho_Widget_Helper_Form_Element_Text('miitbeian', NULL, NULL, '工信部备案号', '填入后将以极简字母大写化的形式优雅呈现于页脚右侧。');
    $form->addInput($miitbeian);

    /**
     * 选择头像源
     */
    $avatarSource = new \Typecho\Widget\Helper\Form\Element\Select('avatarSource', array(
        'https://cravatar.cn/avatar' => 'Cravatar (推荐)',
        'https://cdn.v2ex.com/gravatar' => 'V2EX',
        'https://sdn.geekzu.org/avatar' => '极客族',
        'https://weavatar.com/avatar' => 'WeAvatar',
        'https://secure.gravatar.com/avatar' => 'Gravatar 官方'
    ), 'https://cravatar.cn/avatar', _t('头像加速源'), _t('选择一个国内访问速度较快的头像源。'));
    $form->addInput($avatarSource);

    /**
     * 自定义默认头像
     */ 
    $defaultAvatar = new \Typecho\Widget\Helper\Form\Element\Text('defaultAvatar', NULL, NULL, _t('自定义默认头像'), _t('填入图片绝对链接。当用户没有设置头像时显示。留空则显示源站的默认图标 (通常是灰色人像)。'));
    $form->addInput($defaultAvatar);

    /**
     * 字体路线选择
     */
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

    // -------------------
    // 外观设置
    // -------------------
    $title4 = new Typecho_Widget_Helper_Layout('h2', array('class' => 'typecho-setting-title  tamd-h2'));
    $title4->html('<img style="width: 19px;height: 19px;" src="' . $themeUrl . '/static/admin/' . $themeIconStyle . '/eyedropper-sample.svg">&nbsp外观设置');
    $form->addItem($title4);

    $navStyle = new Typecho_Widget_Helper_Form_Element_Radio(
        'navStyle',
        array(
            'nav-right' => '<img src="' . $themeUrl . '/static/admin/img/menu-nav-right.png">', 
            'nav-left'  => '<img src="' . $themeUrl . '/static/admin/img/menu-nav-left.png">'
        ),
        'nav-right',
        _t('导航栏布局样式'),
        _t('点击下方卡片选择你想要的导航栏对齐方式。')
    );
    $navStyle->setAttribute('class', 'typecho-option nav-style-selector');
    $form->addInput($navStyle);

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

    $sliderStatus = new Typecho_Widget_Helper_Form_Element_Radio(
        'sliderStatus',
        array('on' => '开启幻灯片', 'off' => '关闭幻灯片'),
        'off',
        '幻灯片状态',
        '选择是否在首页顶部渲染流光幻灯片。'
    );
    $form->addInput($sliderStatus);

    $slideData = new Typecho_Widget_Helper_Form_Element_Textarea(
        'slideData', 
        NULL, 
        '', 
        _t('<span class="badge-fresh">新功能</span>首页幻灯片设置'), 
        _t('点击下方按钮动态添加幻灯片图片及信息。')
    );
    $form->addInput($slideData);
    include __DIR__ . '../../components/admin/ConfigTheme/slideDate_Add.php';
    
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
        '<s>【废弃】幻灯片专用分类别名 (Slug)</s>', 
        '在此输入你多带带为幻灯片创建的分类别名（例如 slider）。系统将自动抓取该分类下的文章图片与文字生成海报墙。'
    );
    $form->addInput($sliderSlug);

    $sliderNum = new Typecho_Widget_Helper_Form_Element_Text(
        'sliderNum', 
        NULL, 
        '3', 
        '<s>【废弃】幻灯片最大显示数量</s>', 
        '建议 3 - 5 张，保持视觉紧凑、不臃肿。'
    );
    $form->addInput($sliderNum);

    // -------------------
    // 菜单设置
    // -------------------
    $title9 = new Typecho_Widget_Helper_Layout('h2', array('class' => 'typecho-setting-title  tamd-h2'));
    $title9->html('<img style="width: 19px;height: 19px;" src="' . $themeUrl . '/static/admin/' . $themeIconStyle . '/nav.svg">&nbsp菜单设置');
    $form->addItem($title9);

    $defaultMenu = new Typecho_Widget_Helper_Form_Element_Radio(
        'defaultMenu',
        array('on' => '开启默认页面菜单', 'off' => '关闭默认页面菜单'),
        'off',
        '默认页面菜单',
        '你要你在后台创建页面，系统会默认添加页面为菜单项，建议关闭使用下方主题菜单逻辑，方便排序和隐藏'
    );
    $form->addInput($defaultMenu);

    $menuData = new Typecho_Widget_Helper_Form_Element_Textarea(
        'menuData', 
        NULL, 
        '', 
        _t('<span class="badge-fresh">新功能</span>菜单设置'), 
        _t('点击下方按钮动态添加菜单。')
    );
    $form->addInput($menuData);
    include __DIR__ . '../../components/admin/ConfigTheme/menuData_Add.php';

    // -------------------
    // 悬浮按钮
    // -------------------
    $title10 = new Typecho_Widget_Helper_Layout('h2', array('class' => 'typecho-setting-title  tamd-h2'));
    $title10->html('<img style="width: 19px;height: 19px;" src="' . $themeUrl . '/static/admin/' . $themeIconStyle . '/tap.svg">&nbsp悬浮按钮');
    $form->addItem($title10);
        // 1. 悬浮按钮组显示位置
        $fabPosition = new Typecho_Widget_Helper_Form_Element_Radio('fabPosition',
        array(
            'bottom-right' => '右下角',
            'bottom-left'  => '左下角',
            'top-right'    => '右上角',
            'top-left'     => '左上角',
            'center-right' => '右中间',
            'center-left'  => '左中间',
        ),
        'bottom-right', '悬浮按钮组位置', '选择悬浮按钮在页面中的显示位置');
    $form->addInput($fabPosition);

    // 2. 悬浮按钮数据存储（隐藏的Textarea）
    $fabData = new Typecho_Widget_Helper_Form_Element_Textarea('fabData', NULL, NULL, '悬浮按钮管理', '支持拖拽排序，内置三个基础按钮不可删除。');
    $fabData->input->setAttribute('id', 'fabDataTextarea');
    $form->addInput($fabData);
        include __DIR__ . '/../components/admin/ConfigTheme/tabDate_Add.php';
        
    

    // -------------------
    // 文章列表
    // -------------------
    $title5 = new Typecho_Widget_Helper_Layout('h2', array('class' => 'typecho-setting-title  tamd-h2'));
    $title5->html('<img style="width: 19px;height: 19px;" src="' . $themeUrl . '/static/admin/' . $themeIconStyle . '/note.svg">&nbsp文章列表');
    $form->addItem($title5);

    $indexPostListStyle = new Typecho_Widget_Helper_Form_Element_Radio(
        'indexPostListStyle',
        array(
            'layout-default' => '<img src="' . $themeUrl . '/static/admin/img/post-list.png">', 
            'layout-right' => '<img src="' . $themeUrl . '/static/admin/img/post-list-right.png">', 
            'layout-left'  => '<img src="' . $themeUrl . '/static/admin/img/post-list-left.png">'
        ),
        'layout-default',
        _t('文章列表样式'),
        _t('选择你喜欢的文章列表样式')
    );
    $indexPostListStyle->setAttribute('class', 'typecho-option index-post-list-style-selector');
    $form->addInput($indexPostListStyle);

    $postToc = new Typecho_Widget_Helper_Form_Element_Radio(
        'postToc',
        array('on' => '开启', 'off' => '关闭'),
        'on',
        '文章段落导航',
        '选择是否在文章页面开启段落导航。'
    );
    $form->addInput($postToc);

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

    // -------------------
    // 底部页脚
    // -------------------
    $title8 = new Typecho_Widget_Helper_Layout('h2', array('class' => 'typecho-setting-title  tamd-h2'));
    $title8->html('<img style="width: 19px;height: 19px;" src="' . $themeUrl . '/static/admin/' . $themeIconStyle . '/minus.svg">&nbsp底部页脚');
    $form->addItem($title8);

    $footStyle = new Typecho_Widget_Helper_Form_Element_Radio(
        'footStyle',
        array(
            'layout-default' => '<img src="' . $themeUrl . '/static/admin/img/foot.png">', 
        ),
        'layout-default',
        _t(''),
        _t('')
    );
    $footStyle->setAttribute('class', 'typecho-option foot-style-selector');
    $form->addInput($footStyle);

    $footLogoUrlLight = new \Typecho\Widget\Helper\Form\Element\Text(
        'footLogoUrlLight', 
        null, 
        null, 
        _t('板块一 LOGO 地址-日间主题'), 
        _t('在这里填入一个图片 URL 地址, 以在网站标题前加上一个 logo, 如果不填留空则显示文字 logo'));
    $form->addInput($footLogoUrlLight->addRule('url', _t('请填写一个合法的URL地址')));

    $footLogoUrlDark = new \Typecho\Widget\Helper\Form\Element\Text(
        'footLogoUrlDark', 
        null, 
        null, 
        _t('板块一 LOGO 地址-夜间主题'), 
        _t(''));
    $form->addInput($footLogoUrlDark->addRule('url', _t('请填写一个合法的URL地址')));

    $footLinks = new \Typecho\Widget\Helper\Form\Element\Textarea(
        'footLinks',
        NULL,
        NULL,
        _t('板块二 自定义底部链接'),
        _t('如友情链接、网站地图等&lt;a href=""&gt;友情链接&lt;/a&gt;')
    );
    $form->addInput($footLinks);
    
    $footCopyright = new Typecho_Widget_Helper_Form_Element_Radio(
        'footCopyright',
        array('on' => '使用', 'off' => '算啦'),
        'on',
        '是否使用自带Copyright',
        '使用系统自带时间主动更新版权时间'
    );
    $form->addInput($footCopyright);

    $footMates = new \Typecho\Widget\Helper\Form\Element\Textarea(
        'footMates',
        NULL,
        NULL,
        _t('板块二 自定义底部信息'),
        _t('如ICP备案号,版权信息等·')
    );
    $form->addInput($footMates);

    // -------------------
    // 自定义代码
    // -------------------
    $title7 = new Typecho_Widget_Helper_Layout('h2', array('class' => 'typecho-setting-title  tamd-h2'));
    $title7->html('<img style="width: 19px;height: 19px;" src="' . $themeUrl . '/static/admin/' . $themeIconStyle . '/code.svg">&nbsp自定义代码');
    $form->addItem($title7);

    $customCss = new \Typecho\Widget\Helper\Form\Element\Textarea(
        'customCss',
        NULL,
        NULL,
        _t('自定义 CSS'),
        _t('在这里填入你的自定义 CSS 代码，不需要加 &lt;style&gt; 标签。')
    );
    $form->addInput($customCss);
    
    $customJs = new \Typecho\Widget\Helper\Form\Element\Textarea(
        'customJs',
        NULL,
        NULL,
        _t('自定义 JS'),
        _t('在这里填入你的自定义 JavaScript 代码，不需要加 &lt;script&gt; 标签。代码会在页面底部输出。')
    );
    $form->addInput($customJs);
    
    $customHeaderHtml = new \Typecho\Widget\Helper\Form\Element\Textarea(
        'customHeaderHtml',
        NULL,
        NULL,
        _t('自定义头部 HTML'),
        _t('在这里填入你的自定义头部 HTML 代码，将输出在 &lt;head&gt; 标签内。适合添加 meta 标签或第三方样式。')
    );
    $form->addInput($customHeaderHtml);
    
    $customFooterHtml = new \Typecho\Widget\Helper\Form\Element\Textarea(
        'customFooterHtml',
        NULL,
        NULL,
        _t('自定义底部 HTML'),
        _t('在这里填入你的自定义底部 HTML 代码，将输出在 &lt;/body&gt; 标签前。')
    );
    $form->addInput($customFooterHtml);
    
    $analyticsCode = new \Typecho\Widget\Helper\Form\Element\Textarea(
        'analyticsCode',
        NULL,
        NULL,
        _t('网站统计代码'),
        _t('在这里填入你的网站统计代码（如百度统计、Google Analytics 等），包含完整的 &lt;script&gt; 标签。')
    );
    $form->addInput($analyticsCode);

    // -------------------
    // 数据备份（导出&导入）
    // -------------------
    $title2 = new Typecho_Widget_Helper_Layout('h2', array('class' => 'typecho-setting-title  tamd-h2'));
    $title2->html('<img style="width: 19px;height: 19px;" src="' . $themeUrl . '/static/admin/' . $themeIconStyle . '/cloud-arrow-up.svg">&nbsp备份和导入');
    $form->addItem($title2);

    // 数据备份与恢复
    $db = Typecho_Db::get();
    $themeName = 'TamdBlog'; 
    $themeKey = 'theme:' . $themeName;
    $backupsKey = 'theme_' . $themeName . '_backups'; // 存储多条记录
    $adminUrl = Helper::options()->adminUrl . 'options-theme.php';

    // 闭包函数：保存备份数组，限制最多保留 20 条
    $saveBackups = function($data) use ($db, $backupsKey) {
        if (count($data) > 20) {
            $data = array_slice($data, -20);
        }
        $json = json_encode($data);
        $exists = $db->fetchRow($db->select('value')->from('table.options')->where('name = ?', $backupsKey));
        if ($exists) {
            $db->query($db->update('table.options')->rows(['value' => $json])->where('name = ?', $backupsKey));
        } else {
            $db->query($db->insert('table.options')->rows(['name' => $backupsKey, 'value' => $json]));
        }
    };

    // 获取当前主题设置配置
    $currentConfigRow = $db->fetchRow($db->select('value')->from('table.options')->where('name = ?', $themeKey));
    $currentConfig = $currentConfigRow ? $currentConfigRow['value'] : '';

    // 获取已有的备份记录
    $backupsRow = $db->fetchRow($db->select('value')->from('table.options')->where('name = ?', $backupsKey));
    $backups = $backupsRow ? json_decode($backupsRow['value'], true) : [];
    if (!is_array($backups)) $backups = [];

    // 处理操作
    
    // 触发备份
    if (isset($_GET['themeBackup']) && $currentConfig) {
        $backups[] = [
            'id' => time(),
            'type' => 'manual',
            'name' => date('Y-m-d H:i:s') . ' (手动)',
            'data' => $currentConfig
        ];
        $saveBackups($backups);
        Typecho_Widget::widget('Widget_Notice')->set(_t('备份成功！'), 'success');
        Typecho_Widget::widget('Widget_Options')->response->redirect($adminUrl);
    }

    // 恢复备份
    if (isset($_GET['themeRestore'])) {
        $restoreId = (int)$_GET['themeRestore'];
        $restoreData = null;
        foreach ($backups as $b) {
            if ($b['id'] === $restoreId) { $restoreData = $b['data']; break; }
        }
        if ($restoreData) {
            $db->query($db->update('table.options')->rows(['value' => $restoreData])->where('name = ?', $themeKey));
            Typecho_Widget::widget('Widget_Notice')->set(_t('恢复成功！'), 'success');
        } else {
            Typecho_Widget::widget('Widget_Notice')->set(_t('备份数据不存在！'), 'error');
        }
        Typecho_Widget::widget('Widget_Options')->response->redirect($adminUrl);
    }

    // 删除备份
    if (isset($_GET['themeDelBackup'])) {
        $delId = (int)$_GET['themeDelBackup'];
        $newBackups = [];
        foreach ($backups as $b) {
            if ($b['id'] !== $delId) { $newBackups[] = $b; }
        }
        $saveBackups($newBackups);
        Typecho_Widget::widget('Widget_Notice')->set(_t('备份已删除！'), 'success');
        Typecho_Widget::widget('Widget_Options')->response->redirect($adminUrl);
    }

    // 导入配置，识别明文和Base64
    if (isset($_POST['themeImportData'])) {
        $importStr = trim($_POST['themeImportData']);
        $finalData = null;

        // 检查是否直接粘贴的是明文
        if (json_decode($importStr) !== null || @unserialize($importStr) !== false || $importStr === 'b:0;') {
            $finalData = $importStr;
        } else {
            // 解码后再检查
            $decoded = base64_decode($importStr);
            if ($decoded && (json_decode($decoded) !== null || @unserialize($decoded) !== false)) {
                $finalData = $decoded;
            }
        }

        if ($finalData !== null) {
            $db->query($db->update('table.options')->rows(['value' => $finalData])->where('name = ?', $themeKey));
            Typecho_Widget::widget('Widget_Notice')->set(_t('配置导入成功！'), 'success');
        } else {
            Typecho_Widget::widget('Widget_Notice')->set(_t('导入失败：代码格式不正确，无法识别为有效数据！'), 'error');
        }
        Typecho_Widget::widget('Widget_Options')->response->redirect($adminUrl);
    }

    // 自动备份检查机制
    $themeOpts = $currentConfig ? (json_decode($currentConfig, true) ?: @unserialize($currentConfig)) : [];
    if (isset($themeOpts['autoBackupToggle']) && $themeOpts['autoBackupToggle'] == '1') {
        $lastAuto = 0;
        foreach ($backups as $b) {
            if ($b['type'] == 'auto' && $b['id'] > $lastAuto) { $lastAuto = $b['id']; }
        }
        if (time() - $lastAuto > 86400) {
            $backups[] = [
                'id' => time(),
                'type' => 'auto',
                'name' => date('Y-m-d H:i:s') . ' (自动)',
                'data' => $currentConfig
            ];
            $saveBackups($backups);
            $backupsRow = $db->fetchRow($db->select('value')->from('table.options')->where('name = ?', $backupsKey));
            $backups = $backupsRow ? json_decode($backupsRow['value'], true) : [];
        }
    }
    // 注册设置表单组件
    // 开启自动备份的选项
    $autoBackupToggle = new Typecho_Widget_Helper_Form_Element_Radio(
        'autoBackupToggle', 
        ['0' => '关闭', '1' => '开启'], 
        '0', 
        '每天自动备份系统设置', 
        '开启后，每天第一次进入本后台设置页面时，系统会自动静默创建一份备份。'
    );
    $form->addInput($autoBackupToggle);

    // 控制面板
    $html = '<div class="typecho-option">';
    
    // 备份列表
    $html .= '<h4 style="margin-top: 0; padding-bottom: 10px; border-bottom: 1px solid #eee;">备份列表与操作</h4>';
    $html .= '<div style="margin-bottom: 15px; display: flex; gap: 10px;">
                <button type="button" id="btn-save-backup" class="btn primary" onclick="doSaveAndBackup()">保存当前设置并备份</button>
                <a href="' . $adminUrl . '?themeBackup=1" class="btn" style="display:inline-flex;align-items:center;justify-content:center;text-decoration:none;text-align:center;">手动生成新备份</a>
                
              </div>
              <p>系统最多保留20份，多出会自动删除</P>';
    
    $html .= '<table class="typecho-list-table" style="width:100%; margin-bottom:20px; text-align: left;">
                <thead><tr><th>备份时间 / 类型</th><th style="text-align: right;">操作</th></tr></thead><tbody>';
    if (empty($backups)) {
        $html .= '<tr><td colspan="2" style="text-align:center; padding: 15px; color: #999;">暂无备份数据</td></tr>';
    } else {
        $reverseBackups = array_reverse($backups); 
        foreach ($reverseBackups as $b) {
            $html .= '<tr>';
            $html .= '<td>' . htmlspecialchars($b['name']) . '</td>';
            $html .= '<td style="text-align: right;">
                        <a href="' . $adminUrl . '?themeRestore=' . $b['id'] . '" style="color:#467B96;" onclick="return confirm(\'确定要恢复到此时间点的备份吗？当前的配置将被覆盖！\');">恢复</a> &nbsp;|&nbsp; 
                        <a href="' . $adminUrl . '?themeDelBackup=' . $b['id'] . '" style="color:#c00;" onclick="return confirm(\'确定要删除此备份吗？\');">删除</a>
                      </td>';
            $html .= '</tr>';
        }
    }
    $html .= '</tbody></table>';

    // 导入导出------------------------------------
    $html .= '<h4 style="margin-top: 30px; padding-bottom: 10px; border-bottom: 1px solid #eee;">配置导入与导出</h4>';
    
    // 加密导出选项
    $html .= '<label style="display: flex; align-items: center; gap: 6px; cursor: pointer; margin-bottom: 10px; font-size: 13px; color: #555;">
                <input type="checkbox" id="export-encrypt" checked> 使用 Base64 加密导出（取消勾选则导出可读明文，方便您手动修改数据）
              </label>';
              
    $html .= '<textarea id="theme-config-data" style="width:100%; height:120px; font-family:monospace; margin-bottom:10px; padding: 10px; box-sizing: border-box;" placeholder="点击【生成导出代码】会在这里生成配置数据；或者在此处粘贴已有的配置代码并点击【导入上方配置】"></textarea>';
    
    $html .= '<div>
                <button type="button" class="btn" onclick="doThemeExport()">生成导出代码</button>
                <button type="button" class="btn primary" onclick="doThemeImport()" style="margin-left:10px;">导入上方配置</button>
              </div>';

    $html .= '</div>'; 
    $jsSafeRawConfig = json_encode($currentConfig, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    $jsSafeB64Config = base64_encode($currentConfig);
    
    $html .= <<<HTML
    <script>
        // --- 保存并备份 ---
        function doSaveAndBackup() {
            var form = document.querySelector('.typecho-page-main form');
            if(!form) {
                alert('未找到设置表单，请尝试点击页面底部的普通保存按钮。');
                return;
            }
            var btn = document.getElementById('btn-save-backup');
            var origText = btn.innerText;
            btn.innerText = '正在保存并备份...';
            btn.disabled = true;

            var formData = new FormData(form);
            fetch(form.action, {
                method: 'POST',
                body: formData
            }).then(function(res) {
                if(res.ok) {
                    // 表单保存成功后，跳转去触发备份
                    window.location.href = '{$adminUrl}?themeBackup=1';
                } else {
                    alert('保存失败，请检查网络或刷新重试。');
                    btn.innerText = origText;
                    btn.disabled = false;
                }
            }).catch(function(err) {
                alert('请求失败！');
                btn.innerText = origText;
                btn.disabled = false;
            });
        }

        // --- 导出 ---
        var rawData = {$jsSafeRawConfig};
        var b64Data = "{$jsSafeB64Config}";
        function doThemeExport() {
            var isEncrypted = document.getElementById('export-encrypt').checked;
            var textArea = document.getElementById('theme-config-data');
            textArea.value = isEncrypted ? b64Data : rawData;
        }

        // --- 导入 ---
        function doThemeImport() {
            var val = document.getElementById('theme-config-data').value.trim();
            if(!val) { alert('请先在输入框内粘贴配置代码！'); return; }
            if(confirm('高危操作：确定要导入该配置吗？导入后现有的未备份设置将被覆盖！')) {
                var tempForm = document.createElement('form');
                tempForm.method = 'post';
                tempForm.action = '{$adminUrl}?themeImport=1';
                
                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'themeImportData';
                input.value = val;
                
                tempForm.appendChild(input);
                document.body.appendChild(tempForm);
                tempForm.submit();
            }
        }
    </script>
HTML;

    $backup_ui = new Typecho_Widget_Helper_Layout('div', ['class=' => '']);
    $backup_ui->html($html);
    $form->addItem($backup_ui);

/**
 * 数据备份
 * 1.0.5版本保留
 * 1.0.6废弃
 * 
 */
    /*
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
        <div class="typecho-option" style="">
            <h4 style="margin-top: 0;">数据保护 (防止切换主题丢失)</h4>
            <p style="color: #666; font-size: 13px;">请在切换主题前点击【备份】，重新换回本主题后再点击【恢复】。</p>
            <div style="display: flex; gap: 10px;">
                <a href="' . $backupUrl . '" class="" style="text-decoration: none;">备份模板设置</a>
                <a href="' . $restoreUrl . '" class="" style="text-decoration: none;" onclick="return confirm(\'确定要恢复吗？当前的未备份设置将被覆盖！\');">恢复模板设置</a>
            </div>
        </div>
    ');
    $form->addItem($backup_ui);
    */

    // -------------------
    // 更新
    // -------------------
    $title3 = new Typecho_Widget_Helper_Layout('h2', array('class' => 'typecho-setting-title  tamd-h2', 'id' => 'themeUpdateTitle'));
    $title3->html('<img style="width: 19px;height: 19px;" src="' . $themeUrl . '/static/admin/' . $themeIconStyle . '/download-simple.svg">&nbsp主题更新' . (THEME_VERSION_IS_UPDATE ? '<span class="badge-corner bounce" id="badgeWithNumber">有更新</span>' : ''));
    $form->addItem($title3);


    // 检查更新
    $updateInfo = getThemeUpdateInfo(THEME_API_URL);
    if ($updateInfo && version_compare(THEME_VERSION, $updateInfo['version'], '<')) {
        $update_html ='<div style="color: #467B96; background: #E8F6FF; padding: 10px; border-radius: 4px; margin-bottom: 15px;">
                <strong>发现新版本：' . $updateInfo['version'] . '</strong><br>
                更新日志：<br>' . nl2br($updateInfo['changelog']) . '<br><br>
                <form method="post" action="?theme_action=update_theme">
                    <input type="hidden" name="download_url" value="' . $updateInfo['download_url'] . '">
                    <button type="submit" class="btn primary" onclick="return confirm(\'强烈建议更新前备份当前主题！确认要执行自动更新吗？\');">一键自动更新</button>
                </form>
            </div>';
    } else {
        $update_html ='
        <!--原生样式-->
        <!--
        <div style="color: #468847; background: #DFF0D8; padding: 10px; border-radius: 4px; margin-bottom: 15px;">当前主题已是最新版本 (v' . THEME_VERSION . ')</div>
        -->

        <!----><!--
        <div class="theme-update-alert-vibrant">
             <div class="alert-text">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                <span>当前主题已是最新版本</span>
            </div>
            <span class="version-badge">v'. THEME_VERSION . '</span>
        </div>-->

        <!--仿苹果-->
        
        <div class="theme-update-alert-minimal">
            <div class="icon-wrapper">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
            </div>
            <span class="alert-text">当前主题已是最新版本</span>
            <span class="version-badge">v'. THEME_VERSION . '</span>
        </div>
        ';

    }
    $update_ui = new Typecho_Widget_Helper_Layout('div', ['class=' => '']);
    $update_ui->html($update_html);
    $form->addItem($update_ui);

    $script = new Typecho_Widget_Helper_Layout('div');
    $script->html(getThemeSettingScript()); 
    $form->addItem($script);

    // -------------------
    // 介绍&帮助
    // -------------------
    $title6 = new Typecho_Widget_Helper_Layout('h2', array('class' => 'typecho-setting-title  tamd-h2'));
    $title6->html('<img style="width: 19px;height: 19px;" src="' . $themeUrl . '/static/admin/' . $themeIconStyle . '/armchair.svg">&nbsp介绍&帮助');
    $form->addItem($title6);

    $theme_wersion = THEME_VERSION;
    $introHtml = <<<HTML
<div class="tmd-bento-container">
        <div class="tmd-bento-card tmd-bento-hero">
     
            <div class="tmd-hero-info">
                <h2>TAMDBLOG <span class="tmd-hero-version">v$theme_wersion</span></h2>
                <p>探索极简与优雅的边界，专注于内容的现代化响应式主题</p >
            </div>
            <div class="tmd-hero-logo-group">
                <img src="$themeUrl/static/img/logo/dark_logo.png" class="tmd-hero-logo" style="height:40px; margin-right: 10px;">
                <img src="https://www.gsav.cn/res/img/logo/logo.svg" class="tmd-hero-logo" style="height:40px;">
            </div>
        </div>

        <a href="https://blog.gsav.cn/244.htm" target="_blank" class="tmd-bento-card tmd-bento-doc">
            <div class="tmd-card-icon">📖</div>
            <div class="tmd-card-title">主题文档</div>
            <div class="tmd-card-desc">查阅完整的配置指南与高级玩法技巧。</div>
        </a >

        <a href="https://github.com/alonehill/TamdBlog" target="_blank" class="tmd-bento-card">
            <div class="tmd-card-icon">💻</div>
            <div class="tmd-card-title">GitHub 仓库</div>
            <div class="tmd-card-desc">获取最新源码，欢迎 Star 与 PR 支持。</div>
        </a >

        <a href="https://gitee.com/alonehill/tamd-blog" target="_blank" class="tmd-bento-card">
            <div class="tmd-card-icon">📦</div>
            <div class="tmd-card-title">Gitee 仓库</div>
            <div class="tmd-card-desc">国内镜像加速，版本同步更新。</div>
        </a >

        <div class="tmd-contact-group">
            <div class="tmd-contact-item">
                <div class="tmd-contact-dot"></div>
                <div class="tmd-contact-text">
                    <span class="tmd-contact-label">官方微信</span>
                    <span class="tmd-contact-value">hill1947</span>
                </div>
            </div>
            <div class="tmd-contact-item">
                <div class="tmd-contact-dot" style="background:#0ea5e9; box-shadow: 0 0 8px rgba(14, 165, 233, 0.4);"></div>
                <div class="tmd-contact-text">
                    <span class="tmd-contact-label">QQ 交流</span>
                    <span class="tmd-contact-value">3150675236</span>
                </div>
            </div>
            <div class="tmd-contact-item">
                <div class="tmd-contact-dot" style="background:#8b5cf6; box-shadow: 0 0 8px rgba(139, 92, 246, 0.4);"></div>
                <div class="tmd-contact-text">
                    <span class="tmd-contact-label">用户支持群</span>
                    <span class="tmd-contact-value">927605702</span>
                </div>
            </div>
        </div>
    </div>

HTML;

    $layout = new Typecho_Widget_Helper_Layout('div', array('class' => 'theme-intro-bottom'));
    $layout->html($introHtml);
    $form->addItem($layout);

    // 系统环境参数
    $sys_data = [
        '核心系统' => [
            'Typecho 版本' => Helper::options()->version,
            'PHP 版本'     => PHP_VERSION,
            '服务器软件'   => $_SERVER['SERVER_SOFTWARE'],
            '操作系统'     => PHP_OS,
        ],
        'PHP 性能指标' => [
            '内存限制'     => ini_get('memory_limit'),
            '最大上传'     => ini_get('upload_max_filesize'),
            'POST 最大值'  => ini_get('post_max_size'),
            '最大执行时间' => ini_get('max_execution_time') . 's',
        ]
    ];

$html = '<div class="sys-info-wrapper">';

foreach ($sys_data as $title => $items) {
    $html .= '<h4 class="table-title">' . $title . '</h4>';
    $html .= '<table class="sys-info-table">';
    foreach ($items as $key => $value) {
        $html .= "<tr><th>{$key}</th><td>{$value}</td></tr>";
    }
    $html .= '</table>';
}

$html .= '</div>';

$sysLayout = new Typecho_Widget_Helper_Layout('div');
$sysLayout->html($html);
$form->addItem($sysLayout);
 
    include __DIR__ . '../../components/admin/ThemeConfig_Style.php';
}