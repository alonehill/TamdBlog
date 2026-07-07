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
  })();