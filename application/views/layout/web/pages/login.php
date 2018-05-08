<div class="container" style="margin-top: 50px;">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Please sign in</h3>
                </div>
                <div class="panel-body">
                    <?= form_open('welcome/login') ?>
                    <?= htmlFlash() ?>
                    <fieldset>
                        <div class="form-group">
                            <input class="form-control" placeholder="E-mail" name="email" type="text" value="<?= getFlashValue('email') ?>">
                        </div>
                        <div class="form-group">
                            <input class="form-control" placeholder="Password" name="password" type="password">
                        </div>
                        <button class="btn btn-lg btn-success btn-block btn-spinner" type="submit">Login</button>
                    </fieldset>
                    <?= form_close() ?>
                </div>
            </div>
        </div>
    </div>
</div>