document.addEventListener('DOMContentLoaded', function() {
    const sliderImgContainer = document.getElementById('topSlideImg');
    const slideContents = document.querySelectorAll('.topslidecontent');
    const slideIndicators = document.querySelector('#topSlide .carousel-indicators');
    
    if (!sliderImgContainer) return;

    let ticking = false;

    function updateLightAndShadow() {
        if (window.innerWidth >= 768) {
            const scrollY = window.scrollY;
            const sliderHeight = sliderImgContainer.offsetHeight;
            
            let progress = scrollY / sliderHeight;
            progress = Math.min(Math.max(progress, 0), 1); 

            const bgTranslateY = scrollY * 0.5; 
            sliderImgContainer.style.setProperty('--bg-translate', `${bgTranslateY}px`);
            
            sliderImgContainer.style.setProperty('--night-opacity', progress * 1.2);

            const elementOpacity = 1 - (progress * 1.8); 
            
            slideContents.forEach(content => {
                const translateY = scrollY * 0.4; 
                content.style.transform = `translate(-50%, calc(-50% - ${translateY}px))`;
                content.style.opacity = Math.max(elementOpacity, 0);
            });

            if (slideIndicators) {
                const indicatorTranslateY = scrollY * 0.2;
                slideIndicators.style.transform = `translateY(${indicatorTranslateY}px)`;
                slideIndicators.style.opacity = Math.max(elementOpacity, 0);
            }

        } else {
            // 移动端重置
            sliderImgContainer.style.setProperty('--bg-translate', '0px');
            sliderImgContainer.style.setProperty('--night-opacity', '0');
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
            window.requestAnimationFrame(updateLightAndShadow);
            ticking = true;
        }
    }, { passive: true });

    window.addEventListener('resize', function() {
        if (!ticking) {
            window.requestAnimationFrame(updateLightAndShadow);
            ticking = true;
        }
    });
});