<?php if($this->session->flashdata('job_created')): ?>
    <?php echo '<p class="alert alert-success">'.$this->session->flashdata('job_created').'</p>'; ?>
<?php endif; ?>
<?php if($this->session->flashdata('job_updated')): ?>
    <?php echo '<p class="alert alert-success">'.$this->session->flashdata('job_updated').'</p>'; ?>
<?php endif; ?>


<h2><?php echo $title?></h2>
<div id="jobPosting" class="jobListing">
	<p class="name">Employer: <span><?php echo $this->user_model->get_empname($job['user_id']);?></span></p>
	<p class="loc">Location: <span><a href="<?php echo base_url()?>jobs/search/location/<?php echo $location; ?>"><?php echo $location; ?></a></span></p>
	<p class="occ">Job Occupation: <span><a href="<?php echo base_url()?>jobs/search/occupation/<?php echo $occupation; ?>"><?php echo $occupation; ?></a></span></p>
	<p class="emptype">Employment Type: <span><?php echo $job['empType'];?></span></p>
	<p class="salary">Salary: <span><?php echo $job['salary'];?></span></p>
	
	<p class="numop">Number of Openings: <span><?php echo $job['numopenings'];?></span></p>
	<p class="datePos">Date Posted: <span><?php echo date('F j, Y',strtotime($job['date_posted'])); ?></span></p>
	<p class="dateEnd">Apply By: <span><?php echo date('F j, Y',strtotime($job['date_ending'])); ?></span></p>
	<div id="desc">
		<h4 class="descH">Description:</h4>
		<?php echo $job['description'];?>
	<div>
</div>
<div id="editposting">
<?php
if($this->jobs_model->get_poster($job['jobnum']) == $this->session->userdata('user_id'))
{
    echo "<p><a class='btn btn-default pull-left' href='".base_url()."jobs/edit/".$job['jobnum']."'>Edit</a></p>";
    echo form_open('jobs/delete/'.$job['jobnum']);
        echo '<button style="margin-left:20px" type="submit" class="btn btn-default">Delete</button>';
    echo form_close();
}
?>
</div>

