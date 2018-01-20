<?php if($this->session->flashdata('email_sent')): ?>
    <?php echo '<p class="alert alert-success">'.$this->session->flashdata('email_sent').'</p>'; ?>
<?php endif; ?>

<?php if($this->session->flashdata('user_registered')): ?>
    <?php echo '<p class="alert alert-success">'.$this->session->flashdata('user_registered').'</p>'; ?>
<?php endif; ?>
<?php if($this->session->flashdata('login_failed')): ?>
    <?php echo '<p class="alert alert-danger">'.$this->session->flashdata('login_failed').'</p>'; ?>
<?php endif; ?>

<h2><?php echo $title; ?></h2>
<?php echo form_open('users/login'); ?>
    <div>
        <div class="form-group">
            <input type="text" name="username" class="form-control" placeholder="username">
        </div>
        <div class="form-group">
            <input type="password" name="password" class="form-control" placeholder="password">
        </div>
        <button type="submit" class="btn btn-primary btn-block">Login</button>
    </div>
<?php echo form_close(); ?>
<p><a href="forgot">Forgot Password</a></p>