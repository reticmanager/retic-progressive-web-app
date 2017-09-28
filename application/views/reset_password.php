<div class="col-md-6 col-md-offset-3">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4>Reset Password for <?php echo $email; ?></h4>
        </div>
        <div class="panel-body">
            <form action="<?php echo $this->config->base_url('/account/reset_password/'.$token); ?>" method="post">
            <div class="form-group">
                <label for="subject">New Password</label>
                <input class="form-control" name="password" placeholder="Password" type="password" />
                <span class="text-danger"><?php echo form_error('password'); ?></span>
            </div>

            <div class="form-group">
                <label for="subject">Confirm New Password</label>
                <input class="form-control" name="confirm_password" placeholder="Confirm Password" type="password" />
                <span class="text-danger"><?php echo form_error('confirm_password'); ?></span>
            </div>

            <div class="form-group">
                <button name="submit" type="submit" class="btn btn-default">Reset Password</button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>