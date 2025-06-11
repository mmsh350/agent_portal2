(function () {

    var preloader = document.querySelector('.page-loading');

    preloader.classList.add('active');
    preloader.style.display = 'block';

    function hideLoader() {
        preloader.classList.remove('active');
        setTimeout(function () {
            preloader.style.display = 'none';
        }, 1000);
    }

    window.addEventListener('load', function () {
        hideLoader();
    });

})();

(function () {
    'use strict'
    var forms = document.querySelectorAll('.needs-validation')
    Array.prototype.slice.call(forms)
        .forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        })
})();

