<?php

class Jobs_model extends CI_Model{
    public function __construct(){
        $this->load->database();
    }
	/*
     * Function: get_jobs
     * Purpose: This method is responsible for retrieving jobs from the database and then returning them 
     * Params:  $jobnum= FALSE: this is to get a specific job
				$limit = 0: default value of 0, this is for the use of pagination
				$offset = "": default value of 0, this is for the use of pagination
				$where = "": this is the where clause for search results. 
							Should be in form: array('field'=>FIELD, 'value'=>VALUE)
     * Return: query result - single array if $jobnum is not FALSE
     */
    public function get_jobs($jobnum= FALSE, $limit = 0, $offset = 0, $where = "")
    {
        if($limit)
        {
            $this->db->limit($limit, $offset);
        }
        
        $this->db->order_by('jobs.id', 'DESC');
        
        $this->db->join('occupations', 'occupations.id = jobs.occupation');
        $this->db->join('locations', 'locations.id = jobs.location');
		
		$this->db->where('dateposted <=',date("o-m-d",strtotime("now")));
        $this->db->where('dateending >=',date("o-m-d",strtotime("now")));
		
		//return all jobs
        if($jobnum==FALSE && empty($where))
        {
			
            $query = $this->db->get('jobs');
            return $query->result_array();
        }
		//return jobs based on query
		else if(!empty($where))
		{
			
			$query = $this->db->where($where['field'],$where['value']);
			$query = $this->db->get('jobs');
            return $query->result_array();
		}
		//return single job's
        else
        {
			
            $query = $this->db->get_where('jobs', array('jobnum'=>$jobnum));
            return $query->row_array();
        }
        
        return false;
    }
	/*
     * Function: create
     * Purpose: This method is responsible for inserting a new job posting into the database
     * Params:  $jobnum: the random job number generated
				$dateposted: the current date, should be a datetime object
				$dateending: the last day the job posting is valid
				$daysValid: number of days that the posting is valid
     * Return: none
     */
    public function create($jobnum, $dateposted, $dateending,$daysValid)
    {
        $data= array(
            'jobnum'=>$jobnum,
            'user_id'=>  $this->session->userdata('user_id'),
            'occupation'=>$this->input->post('occupation'),
            'title'=>$this->input->post('title'),
            'description'=>  $this->input->post('desc'),
            'location'=>  $this->input->post('location'),
            'dateposted'=>  $dateposted,
			'dateending'=> $dateending,
            'salary'=>$this->input->post('salary'),
            'emptype'=>  $this->input->post('emptype'),
            'numopenings'=>  $this->input->post('openings'),
			'daysvalid' => $daysValid
        );
        $this->security->xss_clean($data);
        $this->db->insert('jobs', $data);
    }
	/*
     * Function: delete
     * Purpose: This method is responsible for deleting a job posting from the database
     * Params:  $jobnum: the job number of the job posting to delete
     * Return: none
     */
    public function delete($jobnum)
    {
		$this->db->where('user_id', $this->session->userdata('user_id'));
		$this->db->delete('jobs');
		
        $this->db->where('jobnum', $jobnum);
        $this->db->delete('jobs');
    }
	/*
     * Function: update
     * Purpose: This method is responsible for updating a job posting based on the jobnum passed in
     * Params:  $jobnum: the job number of the job posting to update
     * Return: none
     */
    public function update($jobnum,$dateending)
    {
        $data= array(
            'occupation'=>$this->input->post('occupation'),
            'title'=>$this->input->post('title'),
			'dateending'=> $dateending,
            'description'=>  $this->input->post('desc'),
            'location'=>  $this->input->post('location'),
            'salary'=>$this->input->post('salary'),
            'emptype'=>  $this->input->post('emptype'),
            'numopenings'=>  $this->input->post('openings'),
        );
		$this->security->xss_clean($data);
        $this->db->where('jobnum', $jobnum);
        $this->db->update('jobs', $data);
    }
	
	
	
	
    /*
     * Function: get_occupations
     * Purpose: This method is responsible for returning an array of all occupations
     * Params:  none
     * Return: array of occupations from Occupation table
     */
    public function get_occupations()
    {
        $query = $this->db->get('occupations');
        return $query->result_array();
    }
	/*
     * Function: get_locations
     * Purpose: This method is responsible for returning an array of all locations
     * Params:  none
     * Return: array of locations from Location table
     */
    public function get_locations()
    {
        $this->db->order_by('name', 'ASC');
        $query = $this->db->get('locations');
        return $query->result_array();
    }
	/*
     * Function: get_poster
     * Purpose: This method is responsible for returning the user_id of the user who posted a particular job
     * Params:  $jobnum: the job number of the job posting to find
     * Return: the user_id for whom the job was posted by
     */
    public function get_poster($jobnum)
    {
        $query = $this->db->get_where('jobs', array('jobnum'=>$jobnum));

        return $query->row_array()['user_id'];
        
    }
	/*
     * Function: is_job_num_taken
     * Purpose: This method is responsible for checking if a job number exists. 
				Used for generating a random jobnum when a job is first created
     * Params:  $jobnum: the job number of the job posting to find
     * Return: 	False if jobnum does not exist
				True if jobnum exists
     */
    public function is_job_num_taken($jobnum)
    {
        $query = $this->db->get_where('jobs', array('jobnum'=>$jobnum));
        if(empty($query->row_array()))
        {
                return false;
        }
        else{
                return true;
        }
    }
	/*
     * Function: get_occupation_byid
     * Purpose: This method is responsible for getting the occupation that matches the id passed in.
				Used in jobs index view to get name of occupation
     * Params:  $catid: the id of the occupation
     * Return: 	array containing occupation info
     */
    public function get_occupation_byid($catid)
    {
        $query = $this->db->get_where('occupations', array('id'=>$catid));
        return $query->row_array();
    }
	/*
     * Function: get_location_byid
     * Purpose: This method is responsible for getting the location that matches the id passed in.
				Used in jobs index view to get name of location
     * Params:  $catid: the id of the location
     * Return: 	array containing location info
     */
    public function get_location_byid($locid)
    {
        $query = $this->db->get_where('locations', array('id'=>$locid));
        return $query->row_array();
    }
	/*
     * Function: get_occupation_byname
     * Purpose: This method is responsible for getting the occupation's id that matches the name passed in.
				Used in query to get id of occupation based on what was in url
     * Params:  $occupation: the name of the occupation
     * Return: 	array containing occupation info
     */
    public function get_occupation_byname($occupation)
    {
        $query = $this->db->get_where('occupations', array('name'=>$occupation));
        return $query->row_array();
    }
	/*
     * Function: get_location_byname
     * Purpose: This method is responsible for getting the location's id that matches the name passed in.
				Used in query to get id of location based on what was in url
     * Params:  $location: the name of the location
     * Return: 	array containing location info
     */
    public function get_location_byname($location)
    {
        $query = $this->db->get_where('locations', array('name'=>$location));
        return $query->row_array();
    }
	
    /*USELESS FUNCTION
	public function get_jobs_filter($field, $value)
    {
        $this->db->order_by('jobs.id', 'DESC');
        $this->db->join('occupations', 'occupations.id = jobs.occupation');
        $query = $this->db->get_where('jobs', array('jobs.'.$field=>$value));
        return $query->result_array();
    }*/
	/*
     * Function: get_count_rows
     * Purpose: This method is responsible for counting the number of rows for pagination
     * Params:  $field: the name of the field
				$value: value of the field
     * Return: 	total number of jobs 
     */
    public function get_count_rows_jobs($field = "", $value = "")
	{
		if($field!=""&&$value!="")
		{
			switch($field)
			{
				case 'location':
				$this->db->join('locations', 'locations.id = jobs.location');
					break;
				case 'occupation':
					$this->db->join('occupations', 'occupations.id = jobs.occupation');
					break;
			}
			$query = $this->db->get_where('jobs', array($field=>$value));
			return $query->num_rows();

			
		}

		return $this->db->get('jobs')->num_rows();
		
		
		
	}
	/*
     * Function: get_jobs_from_location
     * Purpose: This method is responsible for taking in a location id,
				and returning all jobs from that location
	 * Params:  $locid: id of the location
     * Return: 	jobs as a result_array
     */
	public function get_jobs_from_location($locid)
	{
		$query = $this->db->get_where('jobs', array("location"=>$locid));
		return $query->result_array();
	}
	/*
     * Function: get_jobs_from_occupation
     * Purpose: This method is responsible for taking in an occupation id,
				and returning all jobs for that occupation
	 * Params:  $occid: id of the occupation
     * Return: 	jobs as a result_array
     */
    public function get_jobs_from_occupation($occid)
	{
		$query = $this->db->get_where('jobs', array("occupation"=>$occid));
		return $query->result_array();
	}
}