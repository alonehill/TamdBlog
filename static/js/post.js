(function() {

    /**
     * 代码块添加复制按钮逻辑
     */
    function addCopyButtons() {
      // 选择所有 pre 元素（或者更精确地 pre > code）
      const pres = document.querySelectorAll('pre');
      
      pres.forEach(pre => {
          // 防止重复添加按钮
          if (pre.querySelector('.copy-btn')) return;

          // 创建按钮
          const btn = document.createElement('button');
          btn.className = 'copy-btn';
          btn.textContent = '复制';

          // 给按钮绑定点击事件
          btn.addEventListener('click', () => {
              // 获取代码文本（从 <code> 中取，或直接用 pre.innerText）
              const code = pre.querySelector('code') 
                  ? pre.querySelector('code').innerText 
                  : pre.innerText;
              copyToClipboard(code, btn);
          });

          // 将按钮插入到 pre 容器中
          pre.style.position = 'relative'; // 确保定位正确
          pre.appendChild(btn);
      });
  }

  // 复制到剪贴板（兼容新旧浏览器）
  function copyToClipboard(text, btn) {
      // 优先使用现代 API
      if (navigator.clipboard && window.isSecureContext) {
          navigator.clipboard.writeText(text).then(() => {
              showSuccess(btn);
          }).catch(() => {
              fallbackCopy(text, btn);
          });
      } else {
          fallbackCopy(text, btn);
      }
  }

  function fallbackCopy(text, btn) {
      const textarea = document.createElement('textarea');
      textarea.value = text;
      textarea.style.position = 'fixed';
      textarea.style.opacity = '0';
      document.body.appendChild(textarea);
      textarea.select();
      try {
          document.execCommand('copy');
          showSuccess(btn);
      } catch (err) {
          alert('复制失败，请手动复制');
      }
      document.body.removeChild(textarea);
  }

  function showSuccess(btn) {
      const original = btn.textContent;
      btn.textContent = '已复制';
      btn.classList.add('copied');
      setTimeout(() => {
          btn.textContent = original;
          btn.classList.remove('copied');
      }, 2000);
  }

  // 页面加载完成后执行
  if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', addCopyButtons);
  } else {
      addCopyButtons();
  }

  // 如果页面有动态加载的内容（比如无限滚动），可以监听新插入的节点
  const observer = new MutationObserver(() => {
      addCopyButtons();
  });
  observer.observe(document.body, { childList: true, subtree: true });
})();