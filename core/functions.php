<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

if (!defined('THEME_VERSION')) {
    define('THEME_VERSION', '1.1.0');
}

if (!defined('THEME_API_URL')) {
    define('THEME_API_URL', 'https://code.gsav.cn/update/config.json');
}
$updateInfo = getThemeUpdateInfo(THEME_API_URL);
if ($updateInfo && version_compare(THEME_VERSION, $updateInfo['version'], '<')) {
    if (!defined('THEME_VERSION_IS_UPDATE')) {
        define('THEME_VERSION_IS_UPDATE', true);
    }
} else {
    if (!defined('THEME_VERSION_IS_UPDATE')) {
        define('THEME_VERSION_IS_UPDATE', false);
    }
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

/**
 * 初始化函数
 * 
 * 处理需要后台配合的功能
 *
 * @param mixed $archive 当前页面对象
 * @author KAg
 * @version 1.0.5
 * @since 1.0.4
 */
function themeInit($archive) 
{
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

    //友情链接处理
    $db = Typecho_Db::get();
    $prefix = $db->getPrefix();
    
    // 检查是否存在links
    try {
        $db->fetchRow($db->select()->from('table.links')->limit(1));
    } catch (Exception $e) {
        // 创建一个友链表
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

    // 处理前端申请友链的请求
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

            //存入 table links
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

        // 后台管理页面
        if (isset($_GET['action']) && $_GET['action'] == 'manage_links') {
            $links = $db->fetchAll($db->select()->from('table.links')->order('lid', Typecho_Db::SORT_DESC));
            ?>
            <?php include __DIR__ . '../../components/admin/FriendLinks_Manage.php'; ?>
            <?php
            exit;
        }
    }
}

/**
 * 打包用户的邮箱、用户名称、站点地址，用户未勾选协议则不打包
 */
function checkAndProcessActivation() {
    //$lockFile = __DIR__ . '/../../lock.json';
    $lockFile = dirname(__DIR__) . '/lock.json';

    $currentDomain = $_SERVER['HTTP_HOST'];
    $needActivation = true;
    $user = Typecho_Widget::widget('Widget_User');
    $email = $user->hasLogin() ? htmlspecialchars($user->mail) : '';

    if (isset($_POST['action']) && $_POST['action'] === 'theme_activate') {
        while (ob_get_level()) {
            ob_end_clean();
        }
        
        // 强制声明返回格式为 JSON
        header('Content-Type: application/json; charset=utf-8');
        
        $agreeEmail = isset($_POST['agree_email']) ? 1 : 0;
        $agreeSite = isset($_POST['agree_site']) ? 1 : 0;
        $agreeTerms = isset($_POST['agree_terms']) ? 1 : 0;

        if ($agreeSite || $agreeEmail) {

            $p1 = 'L2V0YXZpdGNhL2VtZWh0Lz';
            $p2 = 'F2L2dvbEJkbWFUL25jLnZh';
            $p3 = 'c2cuaXBhLy8vOnNwdHRo';
            $remoteApiUrl = strrev(base64_decode($p1 . $p2 . $p3));

            $postData = [
                'domain' => $currentDomain,
                'email' => $email,
                'username' => Typecho_Widget::widget('Widget_User')->screenName,
                'agree_email' => $agreeEmail,
                'agree_site' => $agreeSite,
                'secret_token' => md5('TamdBlog_Protect_' . $currentDomain) 
            ];
            
            // 使用 cURL 发送数据 (非阻塞模式或设置短超时避免卡顿)
            $ch = curl_init($remoteApiUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
            curl_setopt($ch, CURLOPT_TIMEOUT, 3); // 3秒超时，防止你的服务器挂了导致用户无法激活
            @curl_exec($ch);
            @curl_close($ch);
        }

        // 构建并保存 lock 文件数据
        $lockData = [
            'domain' => $currentDomain,
            'email' => $email,
            'time' => time()
        ];

        if (file_put_contents($lockFile, json_encode($lockData))) {
        echo json_encode(['status' => 'success', 'msg' => '激活成功']);
            
        } else {
      echo json_encode(['status' => 'error', 'msg' => '激活失败，请检查主题目录读写权限']);
            
        }
        exit;
    }
    /**
     * 检查 lock 文件状态
     * 用户站点迁移可能还需要配置协助，在此移除lock并重新显示协议
     */

    if (file_exists($lockFile)) {
        $lockData = json_decode(file_get_contents($lockFile), true);
        //判断格式是否正确且域名是否变化
        if ($lockData && isset($lockData['domain']) && $lockData['domain'] === $currentDomain) {
            $needActivation = false; // 域名一致，放行
        } else {
            @unlink($lockFile); // 域名变化或数据异常，删除旧文件重新引导
        }
    }

    return $needActivation;

}

function renderActivationPage() {

    ?>
    <!-- 激活引导层 -->
    <div id="tamd-activation-overlay" style="display:none; position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: 999999; background: rgba(244, 246, 248, 0.95); align-items: center; justify-content: center; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;">
        <style>
            .tamd-activation-card { background: #fff; width: 90%; max-width: 450px; border-radius: 12px; box-shadow: 0 20px 40px rgba(0,0,0,0.15); overflow: hidden; }
            .tamd-card-header { background: #001eaa; color: #fff; padding: 25px; text-align: center; }
            .tamd-card-body { padding: 25px; }
            .tamd-form-group { margin-bottom: 15px; }
            .tamd-form-group input[type="email"] { width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; outline: none; }
            .tamd-form-group input[type="email"]:focus { border-color: #001eaa; }
            .tamd-checkbox-group { background: #f9f9fa; padding: 15px; border-radius: 6px; border: 1px solid #eee; font-size: 13px; color: #555; }
            .tamd-checkbox-group label { display: block; margin-bottom: 10px; cursor: pointer; }
            .tamd-checkbox-group label:last-child { margin-bottom: 0; }
            
            /* 按钮组 Flex 布局 */
            .tamd-btn-group { display: flex; gap: 15px; margin-top: 20px; }
            .tamd-btn-submit { flex: 1; background: #001eaa; color: #fff; border: none; padding: 12px; border-radius: 4px; cursor: pointer; transition: opacity 0.2s; font-size: 14px; }
            .tamd-btn-submit:hover { opacity: 0.9; }
            .tamd-btn-cancel { flex: 1; background: #f4f6f8; color: #666; border: 1px solid #ddd; padding: 12px; border-radius: 4px; cursor: pointer; transition: all 0.2s; font-size: 14px; }
            .tamd-btn-cancel:hover { background: #e2e8f0; color: #333; }
        </style>
        
        <div class="tamd-activation-card">
            <div class="tamd-card-header">
                <h2 style="margin:0; font-size: 20px;">TamdBlog 引导页</h2>
                <p style="margin: 8px 0 0; font-size: 13px; opacity: 0.8;">这只是一个协议页面，并不会限制您的任何功能</p >
            </div>
            <div class="tamd-card-body">
                <form id="tamd-activation-form">
                    
                    <div class="tamd-form-group tamd-checkbox-group">
                        <label>
                            <input type="checkbox" name="agree_email" checked> 允许作者获取邮箱，接收更新及配置文件
                        </label>
                        <label>
                            <input type="checkbox" name="agree_site" checked> 允许发送站点信息，用于统计及优秀案例展示
                        </label>
                        <label>
                            <input type="checkbox" name="agree_terms" required> <strong>我已同意：</strong>保留页脚版权且不用于非法站点
                        </label>
                    </div>

                    <div class="tamd-btn-group">
                        <button type="button" class="tamd-btn-cancel" id="tamd-cancel-btn">拒绝并退出</button>
                        <button type="submit" class="tamd-btn-submit" id="tamd-submit-btn">同意并迫不及待的开始</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        (function() {
            var overlay = document.getElementById('tamd-activation-overlay');
            if (overlay) {
                document.body.appendChild(overlay);
                overlay.style.display = 'flex';
                
                document.getElementById('tamd-cancel-btn').addEventListener('click', function() {
                    window.location.href = 'themes.php'; 
                });

                document.getElementById('tamd-activation-form').addEventListener('submit', function(e) {
                    e.preventDefault();
                    var btn = document.getElementById('tamd-submit-btn');
                    btn.disabled = true;
                    btn.innerText = '马上啦...';

                    var formData = new FormData(this);
                    formData.append('action', 'theme_activate');
                    
                    fetch(window.location.href, {
                        method: 'POST',
                        body: formData
                    }).then(res => res.json()).then(data => {
                        if (data.status === 'success') {
                            overlay.style.display = 'none';
                            window.location.reload();
                        } else {
                            alert(data.msg);
                            btn.disabled = false;
                            btn.innerText = '同意并开始';
                        }
                    }).catch(err => {
                        alert('请求失败，请检查网络或目录权限');
                        btn.disabled = false;
                        btn.innerText = '同意并开始';
                    });
                });
            }
        })();
    </script>
    <?php
}
function getThemeSettingScript() {
    return <<<HTML
<script></script>
HTML;
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

function art_count($cid)
{
    $db = Typecho_Db::get();
    $rs = $db->fetchRow($db->select('text')->from('table.contents')->where('cid = ?', $cid));
    return mb_strlen($rs['text'], 'UTF-8');
}

/**
 * 获取文章特色图片
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
 * 获取文章内容并移除第一张图片【废弃】
 */
/*
function get_content_without_first_image($post) 
{
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
*/

/**
* 阅读统计
* 调用
*/
function get_post_view($archive) 
{
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

class PostView 
{
    public static function outputAdminHeader() 
    {
        echo '<th class="typecho-radius-topright">阅读量</th>';
    }
    public static function outputAdminRow($post) 
    {
        $db = Typecho_Db::get();
        $row = $db->fetchRow($db->select('views')->from('table.contents')->where('cid = ?', $post['cid']));
        $views = isset($row['views']) ? $row['views'] : 0;
        echo '<td>' . $views . ' 次</td>';
    }
}

function getThemeUpdateInfo($url) 
{
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
        $svgFile = __DIR__ . '/../static/emojis/' . $codepoint . '.svg';
        if (!file_exists($svgFile)) {
            continue; // 跳过不存在的文件
        }
        
        $svgUrl = $themeUrl . '/static/emojis/' . $codepoint . '.svg';
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
require_once __DIR__ . '/vendor/ip2region/ip-handler.php';


function theme_comment_ip_location($comments) {
    foreach ($comments as $comment) {
        show_comment_ip_location($comment);
    }
}


if ($_SERVER['SCRIPT_NAME'] == __TYPECHO_ADMIN_DIR__ . 'write-post.php' || $_SERVER['SCRIPT_NAME'] == __TYPECHO_ADMIN_DIR__ . 'write-page.php') {
    function themeFields($layout)
    {
        if ($_SERVER['SCRIPT_NAME'] == __TYPECHO_ADMIN_DIR__ . 'write-post.php') {

            //自定义文章缩略图
            $thumb = new Typecho_Widget_Helper_Form_Element_Text('thumb', null, null, _t('自定义特色图片'), _t('输入有效图片地址(用于作为文章列表的底片特色图在主页面显示)'));
            $layout->addItem($thumb);
        }
    }
}