(function () {
    function attachZoom(containerSelector, imageSelector) {
        document.querySelectorAll(containerSelector).forEach(function (container) {
            var img = container.matches(imageSelector) ? container : container.querySelector(imageSelector);
            if (!img) return;

            container.addEventListener('mousemove', function (event) {
                var rect = container.getBoundingClientRect();
                var x = ((event.clientX - rect.left) / rect.width) * 100;
                var y = ((event.clientY - rect.top) / rect.height) * 100;
                img.style.transformOrigin = x + '% ' + y + '%';
                img.classList.add('is-hover-zooming');
            });

            container.addEventListener('mouseleave', function () {
                img.classList.remove('is-hover-zooming');
                img.style.transformOrigin = 'center center';
            });
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        attachZoom('.product-image-frame', 'img');
        attachZoom('.main-image', 'img');
    });
})();
