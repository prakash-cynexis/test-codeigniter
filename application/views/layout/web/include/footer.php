<?php getJavascript() ?>
<script>
    $(document).on('click', '.btn-spinner', function (e) {
        $(this).html('<i class="fa fa-spinner fa-spin" style="font-size:18px"></i> Processing...');
    });
    $(document).ready(function () {
        $(".drop-menu").hover(
            function () {
                $(this).addClass("open");
            },
            function () {
                $(this).removeClass("open");
            }
        );
    });
    function successAlert(message) {
        $.each(message, function (index, value) {
            $(document).ready(function () {
                setTimeout(function () {
                    toastr.options = {
                        closeButton: true,
                        progressBar: true,
                        showMethod: 'slideDown',
                        positionClass: 'toast-bottom-right',
                        timeOut: 5000
                    };
                    toastr.success(value);
                }, 1300);
            });
        });
    }
    function errorAlert(message) {
        $.each(message, function (index, value) {
            $(document).ready(function () {
                setTimeout(function () {
                    toastr.options = {
                        closeButton: true,
                        progressBar: true,
                        showMethod: 'slideDown',
                        positionClass: 'toast-bottom-right',
                        timeOut: 5000
                    };
                    toastr.error(value);
                }, 1300);
            });
        });
    }
    $(document).ajaxError(function (event, jqxhr, settings, thrownError) {
        if (jqxhr.responseJSON.message) {
            errorAlert(jqxhr.responseJSON.message);
            NProgress.done();
        }
    });
</script>
</body>
</html>