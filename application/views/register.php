
<div class="col-md-6 col-md-offset-3">
    <div class="panel panel-default">
        <div class="panel-heading" style="background:#FFF;">
            <h4>User Registration - <?php echo $council->name;?></h4>
			<h4>  <img  alt="reticmanager logo" src="<?php echo base_url('/assets/images/RMLogo215x40.png') ?>" style="width: 215px; height: 40px;">&nbsp; &nbsp;&nbsp;<img src="<?php echo base_url($council->logo_url) ?>" alt=" client logo" style="width: auto; height: 40px;"></h4>
        </div>
        <div class="panel-body">
            <form action="<?php echo $this->config->base_url('/account/register/'.$council->code); ?>" method="post">
            <div class="form-group">
                <label for="first_name">First Name</label>
                <input class="form-control" name="first_name" placeholder="Your First Name" type="text" value="<?php echo set_value('first_name'); ?>" />
                <span class="text-danger"><?php echo form_error('first_name'); ?></span>
            </div>

            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input class="form-control" name="last_name" placeholder="Your Last Name" type="text" value="<?php echo set_value('last_name'); ?>" />
                <span class="text-danger"><?php echo form_error('last_name'); ?></span>
            </div>

            <div class="form-group">
                <label for="organisation">Organisation</label>
                <input class="form-control" name="organisation" placeholder="Your Organisation" type="text" value="<?php echo set_value('organisation'); ?>" />
                <span class="text-danger"><?php echo form_error('organisation'); ?></span>
            </div>

            <div class="form-group">
                <label for="position">Position</label>
                <input class="form-control" name="position" placeholder="Your Position" type="text" value="<?php echo set_value('position'); ?>" />
                <span class="text-danger"><?php echo form_error('position'); ?></span>
            </div>

			<div class="form-group">
                <label for="phone">Phone Number</label>
                <input class="form-control" name="phone" placeholder="Your Phone Number" type="text" value="<?php echo set_value('phone'); ?>" />
                <span class="text-danger"><?php echo form_error('phone'); ?></span>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input class="form-control" name="email" placeholder="email" type="text" value="<?php echo set_value('email'); ?>" />
                <span class="text-danger"><?php echo form_error('email'); ?></span>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input class="form-control" name="password" placeholder="Password" type="password" />
                <span class="text-danger"><?php echo form_error('password'); ?></span>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input class="form-control" name="confirm_password" placeholder="Confirm Password" type="password" />
                <span class="text-danger"><?php echo form_error('confirm_password'); ?></span>
            </div>

            <div class="form-group">
                <button name="submit" type="submit" class="btn btn-default" >Register</button>
				<button name="support" class="btn btn-default"><a href="mailto:info@reticmanager.com"></a><a href="mailto:info@reticmanager.com?subject=ReticManager%20registation%20support">Support</a></button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>