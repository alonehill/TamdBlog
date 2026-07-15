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