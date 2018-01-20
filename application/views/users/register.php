<h2><?= $title; ?></h2>


<?php echo form_open('users/register'); ?>
    <div class="form-group">
        <label>Business Name</label>
        <input type="text" class="form-control" name="name" placeholder="name" value="<?php echo set_value('name');?>"/>
        <?php echo form_error('name','<span class="error">', '</span>'); ?>
    </div>
	<div class="form-group">
        <label>Address</label>
        <input type="text" class="form-control" name="addr" placeholder="address" value="<?php echo set_value('addr');?>"/>
        <?php echo form_error('addr','<span class="error">', '</span>'); ?>
    </div>
	<div class="form-group">
        <label>City</label>
        <input type="text" class="form-control" name="city" placeholder="city" value="<?php echo set_value('city');?>"/>
        <?php echo form_error('city','<span class="error">', '</span>'); ?>
    </div>
	<div class="form-group">
        <label>Province*</label>
		<select name="prov" class="form-control">
			<option value="Alberta">Alberta</option>
			<option value="British Columbia">British Columbia</option>
			<option value="Manitoba">Manitoba</option>
			<option value="New Bruswick">New Bruswick</option>
			<option value="Newfoundland and Labrador">Newfoundland and Labrador</option>
			<option value="North West Territories">North West Territories</option>
			<option value="Nova Scotia">Nova Scotia</option>
			<option value="Nunavut">Nunavut</option>
			<option value="Ontario">Ontario</option>
			<option value="Prince Edward Island">Prince Edward Island</option>
			<option value="Quebec">Quebec</option>
			<option value="Saskatchewan">Saskatchewan</option>
			<option value="Yukon">Yukon</option>
		</select>
		<?php echo form_error('prov','<span class="error">', '</span>'); ?>
    </div>
	<div class="form-group">
        <label>Postal Code</label>
        <input type="text" class="form-control" name="pcode" placeholder="A1A 1A1" value="<?php echo set_value('pcode');?>"/>
        <?php echo form_error('pcode','<span class="error">', '</span>'); ?>
    </div>
	<div class="form-group">
        <label>Phone</label>
        <input type="text" class="form-control" name="phone" placeholder="111-111-1111" value="<?php echo set_value('phone');?>"/>
        <?php echo form_error('phone','<span class="error">', '</span>'); ?>
    </div>
	<div class="form-group">
        <label>Fax (not required)</label>
        <input type="text" class="form-control" name="fax" placeholder="111-111-1111" value="<?php echo set_value('fax');?>"/>
        <?php echo form_error('fax','<span class="error">', '</span>'); ?>
    </div>

    <div class="form-group">
        <label>Username</label>
        <input type="text" class="form-control" name="username" placeholder="username" value="<?php echo set_value('username');?>"/>
        <?php echo form_error('username','<span class="error">', '</span>'); ?>
    </div>
    <div class="form-group">
        <label>Email</label>
        <input type="email" class="form-control" name="email" placeholder="email" value="<?php echo set_value('email');?>"/>
        <?php echo form_error('email','<span class="error">', '</span>'); ?>
    </div>
    
    <div class="form-group">
        <label>Password</label>
        <input type="password" class="form-control" name="password" placeholder="password" />
        <?php echo form_error('password','<span class="error">', '</span>'); ?>
    </div>
    <div class="form-group">
        <label>Confirm Password</label>
        <input type="password" class="form-control" name="password2" placeholder="confirm password" />
        <?php echo form_error('password2','<span class="error">', '</span>'); ?>
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
<?php echo form_close(); ?>