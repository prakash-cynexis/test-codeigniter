<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Welcome to CodeIgniter</title>
    <style type="text/css">
        ::selection {
            background-color: #E13300;
            color: white;
        }

        ::-moz-selection {
            background-color: #E13300;
            color: white;
        }

        body {
            background-color: #fff;
            margin: 40px;
            font: 13px/20px normal Helvetica, Arial, sans-serif;
            color: #4F5155;
        }

        a {
            color: #003399;
            background-color: transparent;
            font-weight: normal;
        }

        h1 {
            color: #444;
            background-color: transparent;
            border-bottom: 1px solid #D0D0D0;
            font-size: 19px;
            font-weight: normal;
            margin: 0 0 14px 0;
            padding: 14px 15px 10px 15px;
        }

        code {
            font-family: Consolas, Monaco, Courier New, Courier, monospace;
            font-size: 12px;
            background-color: #f9f9f9;
            border: 1px solid #D0D0D0;
            color: #002166;
            display: block;
            margin: 14px 0 14px 0;
            padding: 12px 10px 12px 10px;
        }

        #body {
            margin: 0 15px 0 15px;
        }

        p.footer {
            text-align: right;
            font-size: 11px;
            border-top: 1px solid #D0D0D0;
            line-height: 32px;
            padding: 0 10px 0 10px;
            margin: 20px 0 0 0;
        }

        #container {
            margin: 10px;
            border: 1px solid #D0D0D0;
            box-shadow: 0 0 8px #D0D0D0;
        }
    </style>
    <?= jquery() ?>
    <link rel="stylesheet" href="<?= assetUrl('plugins/bootstrap/css/bootstrap.css') ?>">
    <link href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css" rel="stylesheet"/>
</head>
<body>
<div id="container">
    <h1>Welcome to CodeIgniter!</h1>
    <div id="body">
        <input type="text" class="form-control" id="created_at">
        <button id="btn-filter" class="btn btn-xs btn-primary">btn-filter</button>
        <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>#.</th>
                <th>User Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Action</th>
            </tr>
            </thead>
        </table>
    </div>
</div>
</body>
<script type="text/javascript">
    var table;
    var custom_search_value = null;
    var url_data = $(location).attr('href').split('://')[1].split('/')[5];
    if (typeof url_data !== 'undefined') {
        custom_search_value = url_data;
    }
    $(document).ready(function () {
        table = $('#table').DataTable({
            "processing": true,
            "serverSide": true,
            oLanguage: {
                sProcessing: "<img src='<?=assetUrl('images/loader.gif')?>'>"
            },
            "ajax": {
                "url": "<?=base_url('testing/lists') ?>",
                "type": "POST",
                "data": function (data) {
                    if (custom_search_value) data.search['value'] = custom_search_value;
                    data.search['created_at'] = $('#created_at').val();
                }
            },
            //Set column definition initialisation properties.
            "columnDefs": [{orderable: false, targets: -1}]
        });
        $('#btn-filter').click(function () { //button filter event click
            table.ajax.reload(null, false); //just reload table
        });
        $('#btn-reset').click(function () { //button reset event click
            $('#form-filter')[0].reset();
            custom_search_value = null;
            table.ajax.reload(null, false); //just reload table
        });
        $(".dataTables_length").hide();
    });
</script>
<!--data table-->
<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js" type="text/javascript"></script>
</html>