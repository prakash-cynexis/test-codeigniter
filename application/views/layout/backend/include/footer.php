<!-- footer content -->
<footer>
    <div class="pull-right">
        by <a href="https://cynexis.com">Cynexis Media {memory_usage}</a>
    </div>
    <div class="clearfix"></div>
</footer>
<!-- /footer content -->
</div>
</div>
<!-- jQuery -->
<script src="<?= assetUrl('plugins/jquery/js/jquery.min.js') ?>"></script>
<!-- Bootstrap -->
<script src="<?= assetUrl('plugins/bootstrap/js/bootstrap.min.js') ?>"></script>
<!-- NProgress -->
<script src="<?= assetUrl('plugins/nprogress/nprogress.js') ?>"></script>
<!-- Custom Theme Scripts -->
<script src="<?= assetUrl('backend/js/custom.min.js') ?>"></script>
<?php getJavascript() ?>
<script>
    $(document).on('click', '.btn-spinner', function (e) {
        $(this).html('<i class="fa fa-spinner fa-spin" style="font-size:18px"></i> Processing...');
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
<?= jqueryFlash(); ?>
</body>
</html>