const modal = document.getElementById('applyModal');
document.getElementById('openApplyBtn').onclick = () => modal.classList.add('is-active');
document.getElementById('closeApplyBtn').onclick = () => modal.classList.remove('is-active');

document.getElementById('linkApplyForm').onsubmit = function(e) {
    e.preventDefault();
    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerText = '正在提交...';

    const formData = new FormData(this);
    fetch('/?action=apply_link', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        alert(data.msg);
        if (data.status === 'success') {
            modal.classList.remove('is-active');
            this.reset();
        }
    })
    .catch(err => alert('服务器繁忙，请稍后再试'))
    .finally(() => {
        btn.disabled = false;
        btn.innerText = '提交申请';
    });
};

document.addEventListener("DOMContentLoaded", function() {
    const lazyImages = document.querySelectorAll('.lazyload');
    
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    
                    img.onload = () => {
                        img.classList.add('loaded');
                    };
                    
                    observer.unobserve(img);
                }
            });
        });
        
        lazyImages.forEach(img => imageObserver.observe(img));
    } else {
        lazyImages.forEach(img => {
            img.src = img.dataset.src;
            img.classList.add('loaded');
        });
    }
});