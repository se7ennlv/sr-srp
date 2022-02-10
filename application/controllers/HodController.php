<?php
defined('BASEPATH') or exit('No direct script access allowed');

class HodController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('HodModel');
        $this->load->model('EmpModel');
        $this->load->model('ServiceModel');
    }

    //====================== view render ===========================//
    public function index()
    {
        $data['emps'] = $this->EmpModel->FindAllEmps();
        $data['types'] = $this->HodModel->FindTypeOfBehavior();
        $data['mails'] = $this->ServiceModel->FindMailApprovers(10);

        $this->load->view('hod/index', $data);
    }
    


    //============================== operates ==============================//
    public function InitInsertRequest()
    {
        $this->HodModel->ExecuteInsertRequest();
        $lastId = $this->db->insert_id();

        $docNo = $this->HodModel->FindOneDoc($lastId);
        $empId = $this->input->post('ReqEmpID');
        $empName = $this->input->post('ReqEmpName');
        $point = $this->input->post('ReqPoints');
        $comment = $this->input->post('ReqHoDComment');

        $this->SendMailToHR($docNo,  $empId, $empName, $point, $comment);
    }

    public function SendMailToHR($docNo,  $empId, $empName, $point, $comment)
    {
        $data['toMails'] = $this->ServiceModel->FindMailApprovers(10);

        foreach ($data['toMails'] as $toMail) {
            $to = $toMail->ApprEmail;

            $subject = 'Staff Point Request: ' . $docNo;
            $msg = "Dear HR Approver, \r\n\r\n";
            $msg .= "This is submission from HOD \r\n\r\n";
            $msg .= "Doc No: $docNo \r\n";
            $msg .= "Employee ID: $empId \r\n";
            $msg .= "Employee Name: $empName \r\n";
            $msg .= "Points Request: ($point) \r\n";
            $msg .= "HOD Comment: $comment \r\n\r\n";
            $msg .= "You can access to the SRP system to approve via a link " . anchor('http://savrpt01/srp', 'SRP System');

            $this->ServiceModel->SendMail($to, $subject, $msg);
        }
    }

}
