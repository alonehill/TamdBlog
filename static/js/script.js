// 全局页面滑速度数调整
/*
(function() {
    const wheelEvents = ['wheel', 'mousewheel', 'DOMMouseScroll'];
    wheelEvents.forEach(event => {
      document.removeEventListener(event, null, true);
      window.removeEventListener(event, null, true);
    });
  
    let targetScroll = window.scrollY;
    let currentScroll = window.scrollY;
    let isScrolling = false;
    const SPEED = 3.8;// 滑动倍数
    const DAMPING = 0.95;   // 阻尼系数，0.90~0.95 之间，值越大滑得越远
  
    function smoothScroll() {
      currentScroll += (targetScroll - currentScroll) * (1 - DAMPING);
      window.scrollTo(0, currentScroll);
      
      if (Math.abs(targetScroll - currentScroll) > 0.5) {
        requestAnimationFrame(smoothScroll);
      } else {
        isScrolling = false;
        window.scrollTo(0, targetScroll);
      }
    }
  
    document.addEventListener('wheel', function(e) {
      if (e.target.closest('input, textarea, select, [contenteditable]')) {
        return;
      }
      
      targetScroll += e.deltaY * SPEED;
      targetScroll = Math.max(0, Math.min(targetScroll, document.documentElement.scrollHeight - window.innerHeight));
      
      if (!isScrolling) {
        isScrolling = true;
        requestAnimationFrame(smoothScroll);
      }
      
      e.preventDefault();
    }, { passive: false, capture: true });
    
    document.documentElement.style.scrollBehavior = 'auto';
  })();*/

  document.addEventListener('DOMContentLoaded', function() {
    /**
 * 导航栏根据高度，幻灯片位置切换便于观察的状态
 */

const navbar = document.querySelector('.main-navbar');
const slider = document.getElementById('topSlide');

let isScrolledState = false;

function checkScroll() {
    const triggerHeight = slider ? slider.offsetHeight - 20 : -9999;
    const isScrolled = window.scrollY > triggerHeight;
    
    if (isScrolled !== isScrolledState) {
        isScrolledState = isScrolled;
        navbar.classList.toggle('is-scrolled', isScrolled);
    }
}

window.addEventListener('scroll', checkScroll);
checkScroll();
  
    const loadBtn = document.getElementById('ajaxLoadBtn');
    if (!loadBtn) return;

    //--------------------------------------------------
    // 使用文章列表最外层容器的类名或id，Ajax才能有效加载文章列表
    // class.article-list
    //--------------------------------------------------
    const postContainerSelector = '.article-list'; 

    const getNextPageUrl = () => {
        const nextLinkElement = document.querySelector('.page-pagination .next a');
        return nextLinkElement ? nextLinkElement.href : null;
    };

    if (!getNextPageUrl()) {
        loadBtn.style.display = 'none';
    }

    loadBtn.addEventListener('click', function(e) {
        e.preventDefault();
        
        const nextUrl = getNextPageUrl();
        if (!nextUrl) return;

        const loadingText = document.querySelector('.status-loading');
        const nomoreText = document.querySelector('.status-nomore');

        loadBtn.style.display = 'none';
        loadingText.style.display = 'inline-flex';

        fetch(nextUrl)
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                
                const currentContainer = document.querySelector(postContainerSelector);
                const newItems = doc.querySelectorAll(`${postContainerSelector} > *`);
                
                // 拼装新文章到文zhang列表下方
                if (currentContainer && newItems.length > 0) {
                    newItems.forEach(item => currentContainer.appendChild(item));
                }

                const oldPagination = document.querySelector('.page-pagination');
                const newPagination = doc.querySelector('.page-pagination');
                if (oldPagination && newPagination) {
                    oldPagination.innerHTML = newPagination.innerHTML;
                }

                const hasMore = doc.querySelector('.page-pagination .next a');
                loadingText.style.display = 'none';

                if (hasMore) {
                    loadBtn.style.display = 'inline-flex';
                } else {
                    nomoreText.style.display = 'inline-flex';
                }
            })
            .catch(err => {
                console.error('AJAX Load Error:', err);
                window.location.href = nextUrl;
            });
    });

});