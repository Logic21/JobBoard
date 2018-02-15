<h2><?php echo $title; ?></h2>

<?php if($jobs): ?>

	<?php foreach($jobs as $job):?>
		<div class="jobListing">
			<h3 class="joblink"><a href="<?php echo base_url()."jobs/".$job['jobnum']; ?>"><span><?php echo $job['title'];?></a></span></h3>
			<p class="name">Employer: <span><?php echo $this->user_model->get_empname($job['user_id']);?></span></p>
			<p class="location">Location: <span><?php echo $this->jobs_model->get_location_byid($job["location"])["name"] ?></span></p>
			<p class="occ">Job Occupation: <span><?php echo $this->jobs_model->get_occupation_byid($job["occupation"])["name"] ?></span></p>
			<p class="date">Date Posted: <span><?php echo date('F j, Y',strtotime($job['date_posted'])); ?></span></p>
			<hr>
		</div>
			
	<?php endforeach; ?>
<?php endif?> 