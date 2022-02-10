<?php
defined('BASEPATH') or exit('No direct script access allowed');

class EmpController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('EmpModel');
    }

    public function FetchOneEmp($empId)
    {
        $data = $this->EmpModel->FindOneEmp($empId);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

  
}
