<?php
class Jobs extends CI_Controller{
    /*
     * Function: index
     * Purpose: This controller is responsible for the main jobs page which
     *          lists all jobs available. URL is /jobs/OFFSET(optional)
     * Params:  $offset: default value of 0, this is for the use of pagination
     * Return: none
     */
    public function index($offset=0){

        $config['base_url'] = base_url().'jobs/index';
        $config['total_rows'] = $this->jobs_model->get_count_rows_jobs();
        $config['per_page'] = 3;
        $config['uri_segment'] = 3;
        $config['attributes'] = array('class' => 'pagination-link');
        $this->pagination->initialize($config);
        $data['jobs'] = $this->jobs_model->get_jobs($jobnum= FALSE, $config['per_page'], $offset); 

        $data['title'] = 'Jobs Listing';
		
        
        if(!empty($data['jobs']))
        {
            $data['nojobs'] = "";
        }
        else
        {
            $data['nojobs'] = "No jobs available";
        }
        
        
        $this->load->view('templates/header');
        $this->load->view('jobs/index',$data);
        $this->load->view('templates/footer');
    }
    /*
     * Function: view
     * Purpose: This controller is responsible for listing
     *          an individual job. URL is /jobs/JOBNUM
     * Params:  $jobnum: Identifies which specific job
     * Return: none
     */
    public function view($jobnum)
    {
        $data['title'] = 'Job #'.$jobnum;
        
        $data['job'] = $this->jobs_model->get_jobs($jobnum); 
        $data['location']= $this->jobs_model->get_location_byid(ucfirst($data['job']['location']))['name'];
        $data['occupation']= $this->jobs_model->get_occupation_byid(ucfirst($data['job']['occupation']))['name'];

        
        $this->load->view('templates/header');
        $this->load->view('jobs/view',$data);
        $this->load->view('templates/footer');
    }
    /*
     * Function: create
     * Purpose: This is the controller used for the create jobs page
     *          URL is /jobs/create
     * Params:  none
     * Return: none
     */
    public function create()
    {
        
        if(!$this->session->userdata('logged_in')){
            redirect('users/login');
        }
        
        $data['title'] = 'Create Job Posting';
        $data['occupations'] = $this->jobs_model->get_occupations();
        $data['locations'] = $this->jobs_model->get_locations();
        
        $this->form_validation->set_rules('title', 'Title', 'required|max_length[255]');
        $this->form_validation->set_rules('occupation', 'occupation', 'required');
        $this->form_validation->set_rules('desc', 'Description', 'required');
        $this->form_validation->set_rules('location', 'Location', 'required');
        $this->form_validation->set_rules('daysvalid', 'Days Valid', 'required|is_natural_no_zero|max_length[31]');
        $this->form_validation->set_rules('openings', 'Number of Openings', 'required|is_natural_no_zero');
        
        if($this->form_validation->run() == FALSE){
            $this->load->view('templates/header');
            $this->load->view('jobs/create',$data);
            $this->load->view('templates/footer');
        }
        else
        {
			$daysValid = $this->input->post('daysvalid');
            $dateposted = date("o-m-d",strtotime("now"));
			$daysInSecs= $daysValid*24*60*60;
			$endInSecs = strtotime($dateposted) + $daysInSecs;
			$dateending = date("o-m-d",$endInSecs);
			
			$jobnum_taken = true;
            srand(mktime());
			
            while($jobnum_taken)
            {
				$jobnum = rand( 111111, 999999);
                $jobnum_taken = $this->jobs_model->is_job_num_taken($jobnum);
            }

            $this->jobs_model->create($jobnum, $dateposted,$dateending,$daysValid );
            $this->session->set_flashdata('job_created','Your job posting has been successfully created.');
            redirect('jobs/'.$jobnum);
        }
        
    }


    /*
     * Function: edit
     * Purpose: This is the controller used for the edit jobs page
     *          URL is /jobs/edit/JOBNUM
     * Params:  $jobnum: Identifies which specific job
     * Return: none
     */
    public function edit($jobnum)
    {
        $userid = $this->jobs_model->get_poster($jobnum);
        if(!$this->session->userdata('logged_in') && $userid == $this->session->userdata('user_id')){
            redirect('users/login');
        }
        
        $data['title'] = 'Edit Job Posting #'.$jobnum;
        $data['occupations'] = $this->jobs_model->get_occupations();
        $data['locations'] = $this->jobs_model->get_locations();
        $data['job'] = $this->jobs_model->get_jobs($jobnum); 
        
        if(empty($data['job']))
        {
            show_404();
        }
        
        
        $this->load->view('templates/header');
        $this->load->view('jobs/edit',$data);
        $this->load->view('templates/footer');
    }
    /*
     * Function: update
     * Purpose: This is the controller used for post from the edit jobs page
     *          URL is /jobs/update/JOBNUM
     * Params:  $jobnum: Identifies which specific job
     * Return: none
     */
    public function update($jobnum)
    {
        if(!$this->jobs_model->get_poster($jobnum) == $this->session->userdata('user_id')){
            redirect('users/login');
        }
        
        $this->form_validation->set_rules('title', 'Title', 'required|max_length[255]');
        $this->form_validation->set_rules('occupation', 'occupation', 'required');
        $this->form_validation->set_rules('desc', 'Description', 'required');
        $this->form_validation->set_rules('location', 'Location', 'required');
        $this->form_validation->set_rules('daysvalid', 'Days Valid', 'required|is_natural_no_zero|max_length[31]');
        $this->form_validation->set_rules('openings', 'Number of Openings', 'required|is_natural_no_zero');
        
        if($this->form_validation->run() == FALSE){
            $data['title'] = 'Edit Job Posting #'.$jobnum;
			
            $data['occupations'] = $this->jobs_model->get_occupations();
			$data['locations'] = $this->jobs_model->get_locations();
			
            $data['job'] = $this->jobs_model->get_jobs($jobnum); 

            $this->load->view('templates/header');
            $this->load->view('jobs/edit',$data);
            $this->load->view('templates/footer');
        }
        else
        {
			

			$daysInSecs= $this->input->post('daysvalid')*24*60*60;
			$endInSecs = strtotime($this->input->post('dateposted')) + $daysInSecs;
			$dateending = date("o-m-d",$endInSecs);

            $this->jobs_model->update($jobnum,$dateending);
			$this->session->set_flashdata('job_updated','The job posting has been successfully updated.');
            redirect('jobs/'.$jobnum);
        }
        
  
        
        
    }

    /*
     * Function: delete
     * Purpose: This is the controller responsible for the deletion of a job posting
     *          URL is /jobs/delete/JOBNUM
     * Params:  $jobnum: Identifies which specific job
     * Return: none
     */
    public function delete($jobnum)
    {
        if(!$this->jobs_model->get_poster($jobnum) == $this->session->userdata('user_id')){
            redirect('users/login');
        }
        
        $this->session->set_flashdata('job_deleted','The job posting has been successfully deleted');
		redirect('jobs');

    }
	
	/*
     * Function: query
     * Purpose: This is the controller responsible for searching for a job posting based on the url
     *          URL is /jobs/search/FIELD/VALUE/OFFSET(optional)
     * Params:  $field: The field to search for(occupation or location)
				$value: The value of the field to search for
				$offset: default value of 0, this is for the use of pagination
     * Return: none
     */
    public function query($field, $value, $offset=0)
    {
        
        if($field != "occupation" && $field != "location" )
        {
			$this->session->set_flashdata('job_category_does_not_exist','Sorry but the cateogry you are trying to search for does not exist.');
            redirect('jobs/search');
        }
        
        $value = str_replace('%20'," ",$value);

        if($field == "occupation")
        {
            $valID = $this->jobs_model->get_occupation_byname(ucwords($value))['id'];
			if(!$valID) $valID = 0;
        }
        if($field == "location")
        {
            $valID = $this->jobs_model->get_location_byname(ucwords($value))['id'];
			if(!$valID) $valID = 0;
        }

		if( $valID == 0)
		{
			$this->session->set_flashdata('job_category_does_not_exist','Sorry but the sub-cateogry you are trying to search for does not exist.');
            redirect('jobs/search');
		}
		
        //$data['jobs'] = $this->jobs_model->get_jobs_filter($field, $valID);
		
		$config['base_url'] = base_url().'jobs/search/'.$field.'/'.$value;
		$config['total_rows'] = $this->jobs_model->get_count_rows_jobs($field, $valID);
		$config['per_page'] = 3;
		$config['uri_segment'] = 5;
		$config['attributes'] = array('class' => 'pagination-link');
		
        $data['jobs'] = $this->jobs_model->get_jobs($jobnum= FALSE, $config['per_page'],$offset,array('field'=>$field, 'value'=>$valID) );
        
        if(!empty($data['jobs']))
        {
			$this->pagination->initialize($config);
			$data['title'] = 'Jobs for ' . str_replace("_"," ",$value);
            
			$this->load->view('templates/header');
            $this->load->view('jobs/index',$data);
            $this->load->view('templates/footer');
        }
        else
        {
            $data['title'] = "No jobs for " . str_replace("_","/",$value) . " under the category ". ucwords($field);
            
            $this->load->view('templates/header');
            $this->load->view('jobs/index',$data);
            $this->load->view('templates/footer');
        }
        
        
        
    }
	/*
     * Function: search
     * Purpose: This is the controller responsible for listing options for searches
     *          URL is /jobs/search
     * Params:  none
     * Return: none
     */
    public function search()
    {
        $data['occupations'] = $this->jobs_model->get_occupations();
        $data['locations'] = $this->jobs_model->get_locations();
        
        $data['title'] = "Search";
        $this->load->view('templates/header');
        $this->load->view('jobs/search',$data);
        $this->load->view('templates/footer');
    }
	
	
	/*
     * Function: querystr
     * Purpose: This is the controller responsible for taking a querystring 
				and finding all jobs that match that query
     * Params:  none
     * Return: none
     */
	public function querystr()
	{
		
		//break down the url 
		$uri = $_SERVER['REQUEST_URI'];
		//break down by /
		$uriArr = explode('/',$uri);
		//the info we need is in the last string
		$lastString = explode('?',$uriArr[count($uriArr)-1]);
		//do not need the first string as that is just querystr
		array_splice($lastString,0, 1);
		$queryStrFull= explode('&',$lastString[0]);
		
		
		//add each seperate query string to $aaQryStrings, 
		//with each key holding an array of the query string's values
		$aaQryStrings = array();
		for($i =0; $i< count($queryStrFull);$i++){
			
			$qry = explode('=',$queryStrFull[$i]);
		
			
			if(!array_key_exists($qry[0], $aaQryStrings))
			{
				$aaQryStrings[$qry[0]] = array();
			}
			
			array_push($aaQryStrings[$qry[0]],str_replace('%20'," ",$qry[1]));
		}
		
		
		//associative array which includes all job postings that were searched for
		$jobs = array();
		
		
		//check if any of the keys in $aaQryStrings is loc, this means we need to search for a location
		if(array_key_exists("loc", $aaQryStrings))
		{
			//loop through $aaQryStrings["loc"]
			for($i=0; $i< count($aaQryStrings["loc"]);$i++)
			{
				//get location's id by using the name
				$location = $this->jobs_model->get_location_byname(ucwords($aaQryStrings["loc"][$i]));
				//check if there is a location in the database
				if($location)
				{
					//get an array of jobs for that location
					$job = $this->jobs_model->get_jobs_from_location($location["id"]);
					//check if there is at least one job returned
					
					if($job)
					{
						//loop through jobs returned
						for($i=0; $i<count($job);$i++){

							//check if that job has been added to the jobs array based on id of the job
							if(!array_key_exists($job[$i]["id"], $jobs))
							{
								$jobs[$job[$i]["id"]] = array();
								$jobs[$job[$i]["id"]] = $job[$i];
							}
						}
					}
				}
			}
		}
	


		//ooc = occupation
		//check if any of the keys in $aaQryStrings is occ, this means we need to search for an occupation
		if(array_key_exists("occ", $aaQryStrings))
		{
			//loop through $aaQryStrings["occ"]
			for($i=0; $i< count($aaQryStrings["occ"]);$i++)
			{
				//get occupation's id by using the name
				$occupation = $this->jobs_model->get_occupation_byname(ucwords($aaQryStrings["occ"][$i]));
				//check if there is a occupation in the database
				if($occupation)
				{
					//get an array of jobs for that occupation
					$job = $this->jobs_model->get_jobs_from_occupation($occupation["id"]);
					//check if there is at least one job returned
					
					if($job)
					{
						//loop through jobs returned
						for($i=0; $i<count($job);$i++){

							//check if that job has been added to the jobs array based on id of the job
							if(!array_key_exists($job[$i]["id"], $jobs))
							{
								$jobs[$job[$i]["id"]] = array();
								$jobs[$job[$i]["id"]] = $job[$i];
							}
						}
					}
				}
			}
		}
		
		$data['jobs'] = $jobs;
		$data['title'] = "Job Search";

		$this->load->view('templates/header');
        $this->load->view('jobs/querystr',$data);
        $this->load->view('templates/footer');
		
	}
}



