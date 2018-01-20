

<h2><?= $title; ?></h2>


<?php echo form_open('users/forgot'); ?>

    <div class="form-group">
        <label>Username</label>
        <input type="text" class="form-control" name="username" placeholder="username" value="<?php echo set_value('username');?>"/>
        <?php echo form_error('username','<span class="error">', '</span>'); ?>
    </div>

    <button type="submit" class="btn btn-primary">Submit</button>
<?php echo form_close(); ?>