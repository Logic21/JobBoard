<?php
class User_model extends CI_Model{
    public function register($enc_password){
        $data = array(
            'name' => $this->input->post('name'),
            'email' => $this->input->post('email'),
            'username' => $this->input->post('username'),
            'password' => $enc_password,
            'pcode' => $this->input->post('pcode'),
			'address' => $this->input->post('addr'),
			'city' => $this->input->post('city'),
			'prov' => $this->input->post('prov'),
			'phone' => $this->input->post('phone'),
			'fax' => $this->input->post('fax')
        );

		$this->security->xss_clean($data);
        return $this->db->insert('users', $data);
    }
    public function login($username, $password){

        $this->db->where('username', $username);
        $result = $this->db->get('users');

        if($result->num_rows() == 1){
			$hash = $result->row(0)->password;
			
			if (password_verify($password, $hash))
			{
				return $result->row(0)->id;
			}
			else
			{
				return false;
			}
            
			
			
        } else {
            return false;
        }
    }
	
	/*
     * Function: check_username_exists
     * Purpose: This method checks to see if passed in username exists in database, 
				if does will return true, otherwise false
     * Params: $username - the username
     * Return: True if username exists, false if not
     */
    public function check_user_exists($username)
	{
		$this->db->where('username', $username);
		$result = $this->db->get('users');
		if($result)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	
	public function remove_user()
	{
		$this->db->where('user_id', $this->session->userdata('user_id'));
		$this->db->delete('jobs');
		
		$this->db->where('id', $this->session->userdata('user_id'));
		$this->db->delete('users');
	}
	
	public function set_password_reset_token($username, $token)
	{
		
		$this->db->where('username', $username);
		$data = array(
			'password_reset_token'=>$token
		);
		$this->db->update('users', $data);

	}
	
	public function check_token_exists($token)
	{

		$this->db->where('password_reset_token', $token);
		$result = $this->db->get('users');
		if($result)
		{
			return $result->row(0)->id;
		}
		else
		{
			return 0;
		}
	}
	public function change_password($user_id,$enc_password)
	{
		$this->db->where('id', $user_id);
		$data = array(
			'password'=>$enc_password,
			'password_reset_token'=>""
		);
		$this->db->update('users', $data);

		
		
	}

	public function make_session()
	{
		$user_id = get_cookie('jobboard_user_id');
		if($user_id)
		{
			$this->db->where('id', $user_id);
			$result = $this->db->get('users');
			
			$user_data = array(
				'user_id' => $user_id,
				'username' => $result->row(0)->username,
				'logged_in' => true
			);
			$this->session->set_userdata($user_data);
		}
		


	}
	
	public function get_userinfo($user_id)
	{
		$this->db->where('id', $user_id);
		$result = $this->db->get('users');
		
		return $result->row_array();
	}
	
	/*
     * Function: get_empname
     * Purpose: This method is responsible for returning the name of an employer( user's name)
     * Params:  $userid: the id of the user
     * Return: name of a user
     */
    public function get_empname($userid)
    {
        $query = $this->db->get_where('users', array('id'=>$userid));
        return $query->row_array()['name'];
    }
	
	public function update($user_id)
	{
		$data = array(
            'name' => $this->input->post('name'),
            'email' => $this->input->post('email'),
            'username' => $this->input->post('username'),
            'pcode' => $this->input->post('pcode'),
			'address' => $this->input->post('addr'),
			'city' => $this->input->post('city'),
			'prov' => $this->input->post('prov'),
			'phone' => $this->input->post('phone'),
			'fax' => $this->input->post('fax')
        );
		$this->security->xss_clean($data);
		$this->db->where('id', $user_id);
        $this->db->update('users', $data);
	}
}


