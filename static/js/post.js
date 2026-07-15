(function() {
    'use strict';

    const desktopTocList = document.getElementById('tocList');
    const mobileTocList = document.getElementById('tocListMobile');
    const tocFab = document.getElementById('tocFab');
    const tocDrawer = document.getElementById('tocDrawer');
    const tocOverlay = document.getElementById('tocOverlay');
    const tocBadge = document.getElementById('tocBadge');
    const postContent = document.querySelector('.post-content');

    function debounce(fn, wait = 80) {
      let timer = null;
      return function(...args) {
        clearTimeout(timer);
        timer = setTimeout(() => fn.apply(this, args), wait);
      };
    }

    let allHeadings = [];
    let headingIds = [];
    let activeId = null;
    let isScrolling = false;

    function buildToc() {
      const headings = postContent.querySelectorAll('h2, h3, h4');
      if (!headings.length) {
        const empty = '<li class="toc-empty">📭 暂无标题</li>';
        desktopTocList.innerHTML = empty;
        mobileTocList.innerHTML = empty;
        if (tocBadge) tocBadge.textContent = '0';
        return;
      }

      allHeadings = Array.from(headings);
      headingIds = allHeadings.map(h => h.id);

      let html = '';
      allHeadings.forEach((heading) => {
        const level = heading.tagName.toLowerCase();
        let levelClass = '';
        const linkText = heading.textContent.trim() || '标题';
        let id = heading.id;
        if (!id) {
          id = linkText.replace(/\s+/g, '-').replace(/[^a-zA-Z0-9\u4e00-\u9fa5\-]/g, '').substring(0, 30);
          if (!id) id = 'heading-' + Math.random().toString(36).substr(2, 6);
          heading.id = id;
        }
        if (level === 'h2') levelClass = 'toc-level-2';
        else if (level === 'h3') levelClass = 'toc-level-3';
        else if (level === 'h4') levelClass = 'toc-level-4';
        else levelClass = 'toc-level-2';

        html += `<li class="${levelClass}">
          <a class="toc-link" href="#${id}" data-target="${id}">${linkText}</a>
        </li>`;
      });

      desktopTocList.innerHTML = html;
      mobileTocList.innerHTML = html;
      if (tocBadge) tocBadge.textContent = allHeadings.length;

      document.querySelectorAll('#tocList, #tocListMobile').forEach((list) => {
        list.addEventListener('click', function(e) {
          const link = e.target.closest('.toc-link');
          if (!link) return;
          e.preventDefault();
          const targetId = link.getAttribute('data-target');
          if (!targetId) return;
          const targetEl = document.getElementById(targetId);
          if (!targetEl) return;

          const offset = 80;
          const top = targetEl.getBoundingClientRect().top + window.pageYOffset - offset;
          window.scrollTo({ top, behavior: 'smooth' });

          setActiveLink(targetId);
          closeDrawer();
        });
      });

      setTimeout(() => {
        updateActiveHeading();
      }, 200);

      const scrollHandler = debounce(() => {
        updateActiveHeading();
      }, 60);

      let ticking = false;
      window.addEventListener('scroll', () => {
        if (!ticking) {
          window.requestAnimationFrame(() => {
            scrollHandler();
            ticking = false;
          });
          ticking = true;
        }
      }, { passive: true });

      window.addEventListener('resize', debounce(() => {
        updateActiveHeading();
      }, 100), { passive: true });
    }

    function updateActiveHeading() {
      if (allHeadings.length === 0) return;

      const offset = 90;
      let currentId = null;
      let minDist = Infinity;

      for (let i = allHeadings.length - 1; i >= 0; i--) {
        const h = allHeadings[i];
        const rect = h.getBoundingClientRect();
        const dist = rect.top - offset;

        if (dist <= 0) {
          if (Math.abs(dist) < minDist) {
            minDist = Math.abs(dist);
            currentId = h.id;
          }
        }
      }

      if (!currentId && allHeadings.length > 0) {
        const firstRect = allHeadings[0].getBoundingClientRect();
        if (firstRect.top > offset) {
          currentId = allHeadings[0].id;
        }
      }

      if (!currentId) {
        const last = allHeadings[allHeadings.length - 1];
        const lastRect = last.getBoundingClientRect();
        if (lastRect.top < window.innerHeight) {
          currentId = last.id;
        }
      }

      if (currentId && currentId !== activeId) {
        setActiveLink(currentId);
      }
    }

    function setActiveLink(id) {
      if (!id || id === activeId) return;
      activeId = id;

      document.querySelectorAll('.toc-link.active').forEach((el) => el.classList.remove('active'));

      document.querySelectorAll(`.toc-link[data-target="${id}"]`).forEach((el) => {
        el.classList.add('active');
      });
    }

    function openDrawer() {
      tocDrawer.classList.add('open');
      tocOverlay.classList.add('active');
      tocFab.setAttribute('aria-expanded', 'true');
    }

    function closeDrawer() {
      tocDrawer.classList.remove('open');
      tocOverlay.classList.remove('active');
      tocFab.setAttribute('aria-expanded', 'false');
    }

    function toggleDrawer() {
      if (tocDrawer.classList.contains('open')) {
        closeDrawer();
      } else {
        openDrawer();
      }
    }

    tocFab.addEventListener('click', toggleDrawer);
    tocOverlay.addEventListener('click', closeDrawer);
    tocDrawer.addEventListener('click', (e) => e.stopPropagation());

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && tocDrawer.classList.contains('open')) {
        closeDrawer();
      }
    });

    const mediaQuery = window.matchMedia('(min-width: 801px)');
    mediaQuery.addEventListener('change', (e) => {
      if (e.matches) closeDrawer();
    });

    buildToc();

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