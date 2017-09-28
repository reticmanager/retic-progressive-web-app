
				
<div class="col-md-6 col-md-offset-3">
    <div class="panel panel-default">
        <div class="panel-heading"style="background:#FFF;">
            <h4>ReticManager User Login</h4>
			<h4>  <img  alt="reticmanager logo" src="<?php echo base_url('/assets/images/RMLogo215x40.png') ?>" style="width: 215px; height: 40px;"></h4>
        </div>
        </div>
        <div class="panel-body">
            <form action="<?php echo $this->config->base_url('/account/login'); ?>" method="post">

            <div class="form-group">
                <label for="email">Email Address</label>
                <input class="form-control" id="email" name="email" placeholder="email" type="text" value="<?php if ($this->session->flashdata('login_email') != '') { echo $this->session->flashdata('login_email'); } else { echo set_value('email'); } ?>" <?php if ($this->session->flashdata('login_email') == '' && set_value('email') == '') { echo 'autofocus'; } ?> />
                <span class="text-danger"><?php echo form_error('email'); ?></span>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input class="form-control" id="password" name="password" placeholder="Password" type="password" <?php if ($this->session->flashdata('login_email') != '' || set_value('email') != '') { echo 'autofocus'; } ?> />
                <span class="text-danger"><?php echo form_error('password'); ?></span>
            </div>

            <div class="form-group">
                <input id="remember_me" name="remember_me" type="checkbox">
                <label for="remember_me">Remember Me</label>
            </div>

            <div class="form-group">
                <button name="submit" type="submit" class="btn btn-default">Login</button>
				<button name="support" class="btn btn-default"><a href="mailto:info@reticmanager.com"></a><a href="mailto:info@reticmanager.com?subject=ReticManager%20registation%20request">Register</a></button>
            </div>
            <div class="form-group">
                <a href="<?php echo $this->config->base_url('/account/forgot_password'); ?>">Forgot Password?</a>
            </div>

            <?php echo form_close(); ?>
            <?php echo $this->session->flashdata('msg'); ?>
        </div>
    </div>
</div>