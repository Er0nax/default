export function loadImages() {
    const observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                const $element = $(entry.target);
                const imageUrl = $element.data('setbg');
                $element.css('background-image', `url(${imageUrl})`);
                observer.unobserve(entry.target);
            }
        });
    });

    $('.set-bg').each(function () {
        observer.observe(this);
    });
}