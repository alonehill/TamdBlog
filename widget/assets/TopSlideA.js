document.addEventListener('DOMContentLoaded', function() {
    const sliderImgContainer = document.getElementById('topSlideImg');
    const slideContents = document.querySelectorAll('.topslidecontent');
    const slideIndicators = document.querySelector('#topSlide .carousel-indicators');
    
    if (!sliderImgContainer) return;

    let ticking = false;

    function updateParallax() {
        if (window.innerWidth >= 768) {
            const scrollY = window.scrollY;
            const sliderHeight = sliderImgContainer.offsetHeight;
            
            let progress = scrollY / (sliderHeight * 0.8);
            progress = Math.min(Math.max(progress, 0), 1); 

            // 卡片收缩与圆角过渡
            const scale = 1 - (0.08 * progress);
            const borderRadius = 30 * Math.easeOutQuad(progress); 

            sliderImgContainer.style.transform = `scale(${scale})`;
            sliderImgContainer.style.borderRadius = `${borderRadius}px`;

            // 文字视差向上飘散与透明度渐隐
            slideContents.forEach(content => {
                const translateY = scrollY * 0.6; 
                const opacity = 1 - (progress * 1.5); 
                content.style.transform = `translate(-50%, calc(-50% - ${translateY}px))`;
                content.style.opacity = Math.max(opacity, 0);
            });

            // 底部分页按钮向下沉并渐隐
            if (slideIndicators) {
                // 按钮向下移动
                const indicatorTranslateY = scrollY * 0.3;
                // 按钮消失的速度和文字保持一致
                const indicatorOpacity = 1 - (progress * 1.5);
                
                // 开启 GPU 加速处理位移
                slideIndicators.style.transform = `translateY(${indicatorTranslateY}px)`;
                slideIndicators.style.opacity = Math.max(indicatorOpacity, 0);
                slideIndicators.style.willChange = 'transform, opacity';
            }

        } else {
            // 移动端重置所有样式
            sliderImgContainer.style.transform = '';
            sliderImgContainer.style.borderRadius = '';
            slideContents.forEach(content => {
                content.style.transform = '';
                content.style.opacity = '';
            });
            // 移动端重置按钮样式
            if (slideIndicators) {
                slideIndicators.style.transform = '';
                slideIndicators.style.opacity = '';
            }
        }
        ticking = false;
    }

    Math.easeOutQuad = function (t) {
        return t * (2 - t);
    };

    window.addEventListener('scroll', function() {
        if (!ticking) {
            window.requestAnimationFrame(updateParallax);
            ticking = true;
        }
    }, { passive: true });

    window.addEventListener('resize', function() {
        if (!ticking) {
            window.requestAnimationFrame(updateParallax);
            ticking = true;
        }
    });
});