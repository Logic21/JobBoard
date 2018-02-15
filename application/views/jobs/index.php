<?php if($this->session->flashdata('job_deleted')): ?>
    <?php echo '<p class="alert alert-success">'.$this->session->flashdata('job_deleted').'</p>'; ?>
<?php endif; ?>

<h2 class="jobListingTitle"><?php echo $title; ?></h2>
<?php 
if(!$jobs)
{
	echo "No jobs have been posted";
}
?>
<?php foreach ($jobs as $job): ?>

    <?php 
    ?>
	<div class="jobListing">
		<h3 class="joblink"><a href="<?php echo base_url()."jobs/".$job['jobnum']; ?>"><span><?php echo $job['title'];?></a></span></h3>
		<p class="name">Employer: <span><?php echo $this->user_model->get_empname($job['user_id']);?></span></p>
		
		<p class="location">Location: <span><?php echo $this->jobs_model->get_location_byid($job["location"])["name"] ?></span></p>
		<p class="occ">Job Occupation: <span><?php echo $this->jobs_model->get_occupation_byid($job["occupation"])["name"] ?></span></p>

		<p class="date">Date Posted: <span><?php echo date('F j, Y',strtotime($job['date_posted'])); ?></span></p>
		<hr>
	</div>
<?php endforeach; ?>
<div class='pagination-links'>
    <?php echo $this->pagination->create_links(); ?>
</div>
