<?php 
if($this->session->userdata('logged_in')){
	$this->user_model->make_session();
}

?>

<html>
    <head>
        <title>Job Board</title>
        <link rel="stylesheet" href="https://bootswatch.com/3/paper/bootstrap.min.css" />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css">
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/scripts/main.js"></script>
        <script src="http://cdn.ckeditor.com/4.7.1/standard/ckeditor.js"></script>
    </head>
    
<body>    
<nav class="navbar navbar-inverse">
  <div class="container-fluid">
      
    <div class="navbar-header">
		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>                        
		</button>
        <a class="navbar-brand" href="<?php echo base_url(); ?>">Job Board</a>
    </div>
	<div class="collapse navbar-collapse" id="myNavbar">
		<ul class="nav navbar-nav">
			<li><a href="<?php echo base_url(); ?>">Home</a></li>
			<li><a href="<?php echo base_url(); ?>about">About</a></li>
			<li><a href="<?php echo base_url(); ?>jobs">Jobs</a></li>
			<li><a href="<?php echo base_url(); ?>jobs/search">Job Search</a></li>
		</ul>
		<ul class="nav navbar-nav navbar-right">
			
			<?php if(!$this->session->userdata('logged_in')) : ?>
				<li><a href="<?php echo base_url(); ?>users/login">Login</a></li>
				<li><a href="<?php echo base_url(); ?>users/register">Register</a></li>
			<?php endif; ?>
			<?php if($this->session->userdata('logged_in')) : ?>
				<li><a href="<?php echo base_url(); ?>users">Account</a></li>
				<li><a href="<?php echo base_url(); ?>jobs/create">Create Job Posting</a></li>
				<li><a href="<?php echo base_url(); ?>users/logout">Logout</a></li>
			<?php endif; ?>
			<li><a href="<?php echo base_url(); ?>contact">Contact</a></li>
		</ul>
	</div>
  </div>
</nav>

<div class="container">
