<?php if($this->session->flashdata('msg_sent')): ?>
    <?php echo '<p class="alert alert-success">'.$this->session->flashdata('msg_sent').'</p>'; ?>
<?php endif; ?>
<h2><?php echo $title ?></h2>
<?php echo form_open('pages/contact')?>
<div class='form-group'>
    <label>Name</label>
    <input type='text' name='name' class='form-control'/>  
    <?php echo form_error('name','<span class="error">', '</span>'); ?>
</div>
<div class='form-group'>
    <label>Email</label>
    <input type='email' name='email' class='form-control' />
    <?php echo form_error('email','<span class="error">', '</span>'); ?>
</div>
<div class='form-group'>
    <label>Body</label>
    <textarea class="form-control" name="body" id="editor1"></textarea>
    <?php echo form_error('body','<span class="error">', '</span>'); ?>
</div>
<button type="submit" class="btn btn-default">Submit</button>
<?php echo form_close()?>


