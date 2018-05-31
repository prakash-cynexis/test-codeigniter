<!-- footer content -->
</div>
<div class="page-footer">
    <div class="page-footer-inner"> <?= COMPANY_NAME ?> {memory_usage}
        <a target="_blank" href="http://keenthemes.com">Keenthemes</a>
    </div>
    <div class="scroll-to-top">
        <i class="icon-arrow-up"></i>
    </div>
</div>
</div>
<script src="<?= assetUrl('backend/js/respond.min.js') ?>"></script>
<script src="<?= assetUrl('backend/js/excanvas.min.js') ?>"></script>
<script src="<?= assetUrl('backend/js/ie8.fix.min.js') ?>"></script>
<script src="<?= assetUrl('backend/js/js.cookie.min.js') ?>"></script>
<script src="<?= assetUrl('backend/js/jquery.blockui.min.js') ?>"></script>
<script src="<?= assetUrl('backend/js/app.min.js') ?>"></script>
<script src="<?= assetUrl('backend/js/layout.min.js') ?>"></script>
<script src="<?= assetUrl('backend/js/demo.min.js') ?>"></script>
<script src="<?= assetUrl('backend/js/quick-sidebar.min.js') ?>"></script>
<script src="<?= assetUrl('backend/js/quick-nav.min.js') ?>"></script>
<script src="<?= assetUrl('plugins/bootstrap/js/bootstrap.min.js') ?>"></script>
<script src="<?= assetUrl('plugins/jquery-slimscroll/jquery.slimscroll.min.js') ?>"></script>
<script src="<?= assetUrl('plugins/bootstrap-switch/js/bootstrap-switch.min.js') ?>"></script>
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