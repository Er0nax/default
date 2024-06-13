document.addEventListener("DOMContentLoaded", () => {
    let lazyBackgroundObserver;

    const loadImages = () => {
        const lazyBackgrounds = document.querySelectorAll('.lazy-bg:not(.loaded)');
        lazyBackgrounds.forEach(lazyBackground => {
            if (!lazyBackground.classList.contains('loaded')) {
                if ('IntersectionObserver' in window) {
                    lazyBackgroundObserver.observe(lazyBackground);
                } else {
                    // Fallback for older browsers
                    lazyBackground.style.backgroundImage = `url(${lazyBackground.dataset.bg})`;
                    lazyBackground.classList.add('loaded');
                }
            }
        });
    };

    const debounce = (func, wait) => {
        let timeout;
        return (...args) => {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), wait);
        };
    };

    const requestIdleCallbackShim = (callback) => {
        if ('requestIdleCallback' in window) {
            return requestIdleCallback(callback);
        } else {
            return setTimeout(callback, 0);
        }
    };

    if ('IntersectionObserver' in window) {
        lazyBackgroundObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const lazyBackground = entry.target;
                    requestIdleCallbackShim(() => {
                        lazyBackground.style.backgroundImage = `url(${lazyBackground.dataset.bg})`;
                        lazyBackground.classList.add('loaded');
                    });
                    observer.unobserve(lazyBackground);
                }
            });
        }, {
            rootMargin: '0px 0px 300px 0px' // Preload images 300px before they come into view
        });
    }

    loadImages();

    document.addEventListener('swchanged', debounce(loadImages, 0), { passive: true });
});
