<?php if($this->session->flashdata('user_updated')): ?>
    <?php echo '<p class="alert alert-success">'.$this->session->flashdata('user_updated').'</p>'; ?>
<?php endif; ?>

<h2><?= $title; ?></h2>

<p><b>Business Name:</b> <?php echo $user["name"]?> </p>
<p><b>Address:</b> <?php echo $user["address"]?> </p>
<p><b>City:</b> <?php echo $user["city"]?> </p>
<p><b>Province:</b> <?php echo $user["prov"]?> </p>
<p><b>Postal Code:</b> <?php echo $user["pcode"]?> </p>
<p><b>Phone</b> <?php echo $user["phone"]?> </p>
<p><b>Fax:</b> <?php echo $user["fax"]?> </p>
<p><b>Email:</b> <?php echo $user["email"]?> </p>

<p><b>User Name:</b> <?php echo $user["username"]?> </p>

<p><b>Date Registered:</b> <?php echo  date('F j, Y',strtotime($user["register_date"]));?> </p>

<p><a class='btn btn-default pull-left' href='<?php echo base_url().'users/edit/'.$user["id"]?>'>Edit</a></p>
<button id="del_acc" style="margin-left:20px" type="submit" class="btn btn-warning">Delete Account</button>


<script>
$(document).ready(function(){
	$("#del_acc").click(function(){
		if(confirm('Are you sure you want to delete your account?') == true ){
			window.location.href = "users/remove";
		}
	});
});

</script>