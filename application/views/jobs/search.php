<?php if($this->session->flashdata('job_category_does_not_exist')): ?>
    <?php echo '<p class="alert alert-warning">'.$this->session->flashdata('job_category_does_not_exist').'</p>'; ?>
<?php endif; ?>
<h2><?php $title ?></h2>

<h3>Occupations</h3>
    <ul>
        <?php foreach($occupations as $occupation): ?>
        <li><a href="<?php echo base_url()?>jobs/search/occupation/<?php echo $occupation['name']?>">
			<?php 
				$name = str_replace("_",", ",$occupation['name']);
				echo $name;  
			?>
		</a></li>
        <?php endforeach; ?>
    </ul>
<h3>Locations</h3>
<ul>
    <?php foreach($locations as $location): ?>
        <li><a href="<?php echo base_url()?>jobs/search/location/<?php echo $location['name']?>"><?php echo $location['name'] ?></a></li>
    <?php endforeach; ?>
</ul>