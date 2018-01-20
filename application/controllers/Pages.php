<?php
class Pages extends CI_Controller{
	
	/*
     * Function: view
     * Purpose: This controller is responsible for showing static pages,
     * Params:  $page: optional parameter, default is home. This is the name of the page to view
     * Return: none
     */
    public function view($page='home'){
        
        if(!file_exists(APPPATH.'views/pages/'.$page.'.php'))
        {
            show_404();
        }
        $data['title'] = ucfirst($page);
        
        $this->load->view('templates/header');
        $this->load->view('pages/'.$page, $data);
        $this->load->view('templates/footer');
    }
	/*
     * Function: contact
     * Purpose: This controller is responsible for showing the contact page, 
				and processing the message sent 
				URL is /contact
     * Params:  $page: optional parameter, default is home. This is the name of the page to view
     * Return: none
     */
    public function contact()
    {
        $data['title'] = "Contact";
        
        $this->form_validation->set_rules('name','Name', 'required');
        $this->form_validation->set_rules('email','Email',  'required|valid_email');
        $this->form_validation->set_rules('body', 'Body', 'required');
        
        if($this->form_validation->run()==FALSE)
        {
            $this->load->view('templates/header');
            $this->load->view('pages/contact', $data);
            $this->load->view('templates/footer');
        }
        else
        {
			/*
            $this->load->library('email');
            
            $this->email->from('abc@abc.com', 'NAME');
            $this->email->to($this->input->post('email'),$this->input->post('name'));
            $this->email->subject('Email from ciblog');
            $this->email->message($this->input->post('body'));
            
            $this->email->send();
            
            //$this->email->send(FALSE);
            //$this->email->print_debugger(array('headers'));
            */
         
            $this->session->set_flashdata('msg_sent','Your message has been sent');
            redirect('contact');
        }
        
    }
}
