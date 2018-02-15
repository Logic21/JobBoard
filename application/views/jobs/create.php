<h2><?php echo $title?></h2>
<?php echo form_open('jobs/create') ?>
<div class='form-group'>
    <label>Title</label>
    <input type='text' name='title' class='form-control' value="<?php echo set_value('title'); ?>"/>
    <?php echo form_error('title','<span class="error">', '</span>'); ?>
</div>
<div class="form-group">
    <label>Location</label>

    <select name="location" class="form-control">
        <?php foreach($locations as $loc): ?>
			<?php
				if(set_value('location') && set_value('location')==$loc['id'])
				{
					echo "<option value=".$loc['id']." selected='selected'>".$loc['name']."</option>";
				}
				else
				{
					echo "<option value=".$loc['id'].">".$loc['name']."</option>";
				}
			?>
        <?php endforeach; ?>
    </select>
</div>
<div class='form-group'>
    <label>Days Valid</label>
    <input type='number' minlength='1' max='31' name='daysvalid' class='form-control' value="<?php if(!set_value('daysvalid')){echo 1;}else{echo set_value('daysvalid');}?>"/>
    <?php echo form_error('daysvalid','<span class="error">', '</span>'); ?>
</div>
<div class='form-group'>
    <label>Salary</label>
    <input type='text' name='salary' class='form-control' value="<?php echo set_value('salary');?>"/>
    <?php echo form_error('salary','<span class="error">', '</span>'); ?>
</div>

<div class='form-group'>
    <label>Employment Type</label>
    <input class='form-control' list="emptype" name="emptype">
	<datalist id="emptype">
		<option value="Full Time">
		<option value="Part Time">
		<option value="Temporary">
		<option value="Contract">
	</datalist>
	<?php echo form_error('emptype','<span class="error">', '</span>'); ?>
</div>

<div class='form-group'>
    <label>Number of Openings</label>
    <input type='number' name='openings' class='form-control' value="<?php if(!set_value('openings')){echo 1;}else{echo set_value('openings');}?>"/>
    <?php echo form_error('openings','<span class="error">', '</span>'); ?>
</div>
<div class="form-group">
    <label>Job Occupation</label>
    <select name="occupation" class="form-control">
        <?php foreach($occupations as $occupation): ?>
			<?php
				if(set_value('occupation') && set_value('occupation')==$occupation['id'])
				{
					echo "<option value=".$occupation['id']." selected='selected'>".$occupation['name']."</option>";
				}
				else
				{
					echo "<option value=".$occupation['id'].">".$occupation['name']."</option>";
				}
			?>
        <?php endforeach; ?>
    </select>
</div>
<div class='form-group'>
    <label>Description</label>
    <textarea class="form-control" name="desc" id="editor1"><?php echo set_value('desc');?></textarea>
    <?php echo form_error('desc','<span class="error">', '</span>'); ?>
</div>
<button type="submit" class="btn btn-default">Submit</button>
<?php echo form_close() ?>
