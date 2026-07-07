document.addEventListener('DOMContentLoaded', function() {
    const sliderImgContainer = document.getElementById('topSlideImg');
    const carouselItems = document.querySelectorAll('#topSlideImg .carousel-item');
    const slideContents = document.querySelectorAll('.topslidecontent');
    const slideIndicators = document.querySelector('#topSlide .carousel-indicators');
    
    if (!sliderImgContainer) return;

    let ticking = false;

    function updateCinematicFocus() {
        if (window.innerWidth >= 768) {
            const scrollY = window.scrollY;
            const sliderHeight = sliderImgContainer.offsetHeight;
            
            let progress = scrollY / sliderHeight;
            progress = Math.min(Math.max(progress, 0), 1); 
            const bgTranslateY = scrollY * 0.4; 
            const blurValue = progress * 12; 
            const brightnessValue = 1 - (progress * 0.4); 

            carouselItems.forEach(item => {
                // scale(1.05)防白边
                item.style.transform = `translateY(${bgTranslateY}px) scale(1.05)`;
                item.style.filter = `blur(${blurValue}px) brightness(${brightnessValue})`;
            });

            const contentOpacity = 1 - (progress * 2);
            
            slideContents.forEach(content => {
                const translateY = scrollY * 0.5; 
                content.style.transform = `translate(-50%, calc(-50% - ${translateY}px))`;
                content.style.opacity = Math.max(contentOpacity, 0);
            });

            if (slideIndicators) {
                const indicatorTranslateY = scrollY * 0.3; // 0.3
                slideIndicators.style.transform = `translateY(${indicatorTranslateY}px)`;
                slideIndicators.style.opacity = Math.max(contentOpacity, 0);
            }

        } else {
            // 移动端重置所有样式
            carouselItems.forEach(item => {
                item.style.transform = '';
                item.style.filter = '';
            });
            slideContents.forEach(content => {
                content.style.transform = '';
                content.style.opacity = '';
            });
            if (slideIndicators) {
                slideIndicators.style.transform = '';
                slideIndicators.style.opacity = '';
            }
        }
        ticking = false;
    }

    window.addEventListener('scroll', function() {
        if (!ticking) {
            window.requestAnimationFrame(updateCinematicFocus);
            ticking = true;
        }
    }, { passive: true });

    window.addEventListener('resize', function() {
        if (!ticking) {
            window.requestAnimationFrame(updateCinematicFocus);
            ticking = true;
        }
    });
});