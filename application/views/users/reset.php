<h2><?= $title ?></h2>

<?php echo form_open('users/reset/'.$token); ?>

    <div class="form-group">
        <label>Password</label>
        <input type="password" class="form-control" name="password" placeholder="password" value="<?php echo set_value('password');?>"/>
        <?php echo form_error('password','<span class="error">', '</span>'); ?>
    </div>

    <button type="submit" class="btn btn-primary">Submit</button>
<?php echo form_close(); ?>