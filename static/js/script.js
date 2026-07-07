// 全局页面滑速度数调整
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
  })();