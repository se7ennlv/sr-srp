<?php

class HodModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('HodModel');
    }

    
    //========================== fetching ========================================//
    public function FindOneDoc($reqId)
    {
        $this->db->select('ReqDocNo');
        $this->db->from('Requests');
        $this->db->where('ReqID', $reqId);
        $query = $this->db->get();

        return $query->row('ReqDocNo');
    }

    public function FindTypeOfBehavior()
    {
        $query = $this->db->get('Behaviors');
        return $query->result();
    }



    //============================= operates ===============================//
    public function ExecuteInsertRequest()
    {
        $data = array(
            'ReqEmpID' => $this->input->post('ReqEmpID'),
            'ReqEmpName' => $this->input->post('ReqEmpName'),
            'ReqPosition' => $this->input->post('ReqPosition'),
            'ReqOrgID' => $this->input->post('ReqOrgID'),
            'ReqDeptCode' => $this->input->post('ReqDeptCode'),
            'ReqDeptName' => $this->input->post('ReqDeptName'),
            'ReqPoints' => $this->input->post('ReqPoints'),
            'ReqBehID' => $this->input->post('ReqBehID'),
            'ReqComment' => $this->input->post('ReqComment'),
            'ReqState' => 1,
            'ReqRequesters' => $this->session->userdata('username')
        );

        $this->db->insert('Requests', $data);
        $this->output->set_content_type('application/json')->set_output(json_encode(array('status' => 'success', 'message' => 'Submited')));
    }

    
}
