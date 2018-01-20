<?php
class Users extends CI_Controller{
	
	public function index()
	{
		$userid= $this->session->userdata('user_id');
		$currUser = $this->user_model->get_userinfo($userid);

		$data['user'] = $currUser;
		$data['title'] = "User Account";
		$this->load->view('templates/header');
        $this->load->view('users/index', $data);
        $this->load->view('templates/footer');
	}
	
	/*
     * Function: register
     * Purpose: This controller is responsible for registering a new user
     *          URL is /register
     * Params: none
     * Return: none
     */
    public function register(){
        
        $data['title'] = 'Sign Up';
        $this->form_validation->set_rules('name', 'Name', 'required');
		$this->form_validation->set_rules('addr', 'Address', 'required');
		$this->form_validation->set_rules('prov', 'Province', 'required');
		$this->form_validation->set_rules('city', 'City', 'required');
		$this->form_validation->set_rules('phone', 'Phone', 'required|regex_match[/^\d{3}-\d{3}-\d{4}$/]');
		$this->form_validation->set_rules('fax', 'Fax', 'regex_match[/^\d{3}-\d{3}-\d{4}$/]');
		$this->form_validation->set_rules('pcode', 'Postal Code', 'required|regex_match[/^[A-Z][0-9][A-Z]\s[0-9][A-Z][0-9]$/]');
        $this->form_validation->set_rules('username', 'Username', 'required|is_unique[users.username]');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
        $this->form_validation->set_rules('password', 'Password', 'required');
        $this->form_validation->set_rules('password2', 'Confirm Password', 'required|matches[password]');
        if($this->form_validation->run() === FALSE){
            
            $this->load->view('templates/header');
            $this->load->view('users/register', $data);
            $this->load->view('templates/footer');
        }
        else{
			$enc_password = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
            //$enc_password = md5($this->input->post('password'));
            $this->user_model->register($enc_password);

            $this->session->set_flashdata('user_registered', 'You are now registered and can log in');
            redirect('users/login');
            
        }
        
         
    }
    /*
     * Function: login
     * Purpose: This controller is responsible for logging in an existing user
				by checking username and password
     *          URL is /login
     * Params: none
     * Return: none
     */
    public function login(){
		if($this->session->userdata('logged_in')){
            redirect('jobs');
        }
		
        $data['title'] = 'Sign In';
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');
        if($this->form_validation->run() === FALSE){
            $this->load->view('templates/header');
            $this->load->view('users/login', $data);
            $this->load->view('templates/footer');
        } 
        else {
            $username = $this->input->post('username');
            //$password = md5($this->input->post('password'));
			$password = $this->input->post('password');
			
			
            $user_id = $this->user_model->login($username, $password);
            if($user_id){
                $user_data = array(
                    'user_id' => $user_id,
                    'username' => $username,
                    'logged_in' => true
                );
                $this->session->set_userdata($user_data);
				
				
				//set cookie for 1 year
				$cookie = array(
					'name'   => 'jobboard_user_id',
					'value'  => $user_id,
					'expire' => time()+31556926
				);
				$this->input->set_cookie($cookie);

				
                $this->session->set_flashdata('user_loggedin', 'You are now logged in');
                redirect('jobs');
            } 
            else {

                $this->session->set_flashdata('login_failed', 'Login is invalid. Incorrect username or password.');
                redirect('users/login');
            }		
        }
    }

    /*
     * Function: logout
     * Purpose: This controller is responsible for logging out an existing 
				user by removing session data and cookie
     *          URL is /logout 
     * Params: none
     * Return: none
     */
    public function logout(){

        $this->session->unset_userdata('logged_in');
        $this->session->unset_userdata('user_id');
        $this->session->unset_userdata('username');
		
		delete_cookie('jobboard_user_id');

        $this->session->set_flashdata('user_loggedout', 'You are now logged out');
        redirect('users/login');
    }
    
	/*
     * Function: remove
     * Purpose: This controller is responsible for removing the current logged in user
     *          URL is user/remove
     * Params: none
     * Return: none
     */
	public function remove()
	{
		if(!$this->session->userdata('logged_in')){
            redirect('users/login');
        }
		
		$this->user_model->remove_user();
		
		$this->session->unset_userdata('logged_in');
        $this->session->unset_userdata('user_id');
        $this->session->unset_userdata('username');
		
		redirect('jobs');
	}
	
	/*
     * Function: forgot
     * Purpose: This controller is responsible for sending a user an 
				email with a link to reset their forgotten password
     *          URL is user/forgot
     * Params: none
     * Return: none
     */
	public function forgot()
	{
		if($this->session->userdata('logged_in')){
            redirect('jobs');
        }
		$this->form_validation->set_rules('username', 'Username', 'callback_username_exists[' . $this->input->post('username') . ']');
		
        $data['title'] = 'Forgot Password';
        if($this->form_validation->run() === FALSE){
            $this->load->view('templates/header');
            $this->load->view('users/forgot', $data);
            $this->load->view('templates/footer');
        } 
        else {
			$this->session->set_flashdata('email_sent', 'You have been sent an email with a recovery password link.');
			
			$token = rand( 111111, 999999);
			$this->user_model->set_password_reset_token($this->input->post('username'), $token);
			
			//For emailing reset password link
			/*$this->load->library('email');
            
            $this->email->from('abc@abc.com', 'MegzKay');
            $this->email->to($this->input->post('email'),$this->input->post('name'));
            $this->email->subject('Email from ciblog');
            $this->email->message("Please click the following link to recover your email: ".base_url()."reset/".$token);
            
            $this->email->send();

			*/
			
			redirect('users/login');
        }
		
	}
	
	/*
     * Function: username_exists
     * Purpose: This is a callback method used to check if a user's name exists
     *          URL is user/forgot
     * Params: $username: the name of the user
     * Return: True if user exists, false if user doesn't exist
     */
	public function username_exists($username)
	{
		
		$user_exists = $this->user_model->check_user_exists($username);
		if (!$user_exists )
		{
			$this->form_validation->set_message('username_exists', 'That username does not exist');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	/*
     * Function: reset
     * Purpose: This controller is responsible for reseting a user account with a new password
     *          URL is user/reset/TOKEN
     * Params: $token: a unique token that indicates which user needs to update their password
     * Return: none
     */
	public function reset($token)
	{

		$data['title'] = 'Reset';
		$data['token'] = $token;
		
		$user_id = $this->user_model->check_token_exists($token);
		
		if($user_id)
		{
			$this->form_validation->set_rules('password', 'Password', 'required');
			if($this->form_validation->run() === FALSE)
			{
				$this->load->view('templates/header');
				$this->load->view('users/reset', $data);
				$this->load->view('templates/footer');
			} 
			else 
			{
				$enc_password = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
				$this->user_model->change_password($user_id,$enc_password);
				redirect('users/login');
			}
			
		}
		else
		{
			$this->session->set_flashdata('token_failed', "The link you have clicked has expired. Please click forgot password again.");
		}
		
	}
	
	/*
     * Function: edit
     * Purpose: This is the controller used for the edit users page
     *          URL is /jobs/edit/USER_ID
     * Params:  $user_id: Identifies which specific user
     * Return: none
     */
    public function edit($user_id)
    {
        if(!$this->session->userdata('logged_in') && $userid == $this->session->userdata('user_id')){
            redirect('users/login');
        }
        
        $data['title'] = 'Edit User Account';
        $data['user'] = $this->user_model->get_userinfo($user_id); 
        
        if(empty($data['user']))
        {
            show_404();
        }
        
        
        $this->load->view('templates/header');
        $this->load->view('users/edit',$data);
        $this->load->view('templates/footer');
    }
	
	/*
     * Function: update
     * Purpose: This is the controller used for post from the edit users page
     *          URL is /jobs/users/USER_ID
     * Params:  $user_id: Identifies which specific user
     * Return: none
     */
	 //NOT WORKING RIGHT NOW
    public function update($user_id)
    {
		
        $this->form_validation->set_rules('name', 'Name', 'required');
		$this->form_validation->set_rules('addr', 'Address', 'required');
		$this->form_validation->set_rules('prov', 'Province', 'required');
		$this->form_validation->set_rules('city', 'City', 'required');
		$this->form_validation->set_rules('phone', 'Phone', 'required|regex_match[/^\d{3}-\d{3}-\d{4}$/]');
		$this->form_validation->set_rules('fax', 'Fax', 'regex_match[/^\d{3}-\d{3}-\d{4}$/]');
		$this->form_validation->set_rules('pcode', 'Postal Code', 'required|regex_match[/^[A-Z][0-9][A-Z]\s[0-9][A-Z][0-9]$/]');
        $this->form_validation->set_rules('username', 'Username', 'required|is_unique[users.username]');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
        //$this->form_validation->set_rules('password', 'Password', 'required|callback_checkpwdmatch');
        if($this->form_validation->run() === FALSE){
            
			$data['title'] = 'Edit User Account';
			$data['user'] = $this->user_model->get_userinfo($user_id); 
			
            $this->load->view('templates/header');
            $this->load->view('users/edit', $data);
            $this->load->view('templates/footer');
        }
        else{
            $this->user_model->update($user_id);

            $this->session->set_flashdata('user_updated', 'Your account is now updated.');
            redirect('users');
            
        }
		
		
        redirect('users');
    }
	
	/*
     * Function: checkpwdmatch
     * Purpose: This is a callback method used to check if the password 
				entered by a user matches that user's password in the database
     * Params: $pwd: password user entered
     * Return: True if password belongs to the user, false otherwise
     */
	public function checkpwdmatch($pwd)
	{
		$this->db->where('id', $this->session->userdata('user_id'));
        $result = $this->db->get('users');
		
        if($result->num_rows() == 1){
			$hash = $result->row(0)->password;
			
			if (password_verify($password, $hash))
			{
				return true;
			}
			else
			{
				return false;
			}
            
			
			
        } else {
            return false;
        }
	}
	
}
