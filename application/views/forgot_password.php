<div class="col-md-6 col-md-offset-3">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4>Forgot Password?</h4>
        </div>
        <div class="panel-body">
            <form action="<?php echo $this->config->base_url('/account/forgot_password/'); ?>" method="post">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input class="form-control" name="email" placeholder="email" type="text" value="<?php echo set_value('email'); ?>" />
                <span class="text-danger"><?php echo form_error('email'); ?></span>
            </div>

            <div class="form-group">
                <button name="submit" type="submit" class="btn btn-default">Reset Password</button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>