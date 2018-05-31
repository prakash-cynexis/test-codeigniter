<div class="page-content-wrapper">
    <div class="page-content">
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li><a href="<?= base_url('dashboard') ?>">Home</a> <i class="fa fa-circle"></i></li>
            </ul>
        </div>
        <h1 class="page-title"> <?= $title ?>
            <small>blank page layout</small>
        </h1>
        <div class="note note-info">
            <?php var_dump($current_user) ?>
        </div>
    </div>
</div>
<script>
    $(document).on('click', '#user', function (event) {
        $.post('<?=('admin/dashboard/User')?>', {id: 'prakash'}, function (data) {
            alert(data.message);
        });
    })
</script>