<?php

class ReportModel extends CI_Model
{

    //=================================== fetching =================================//
    public function FindAllPointSummary()
    {
        $superv = $this->session->userdata('userEmpId');
        $isAllDept = $this->session->userdata('userIsAllDept');

        if ($superv === 'admin' || $isAllDept == 1) {
            $query = $this->db->get('PointSummary');
        } else {
            if($this->session->userdata('userOrgId') == 3){
                $superv = '200001';
            }

            $condition = "Manager1 = '{$superv}' OR Manager2 = '{$superv}' OR Suvisor1 = '{$superv}' OR Suvisor2 = '{$superv}' ";
            $this->db->where($condition, "", FALSE);
            $query = $this->db->get('PointSummary');
        }

        return $query->result();
    }

    public function FindAllRedeemed()
    {
        $fDate = $this->input->post('fromDate');
        $tDate = $this->input->post('toDate');

        $superv = $this->session->userdata('userEmpId');
        $isAllDept = $this->session->userdata('userIsAllDept');

        if ($superv === 'admin' || $isAllDept == 1) {
            $condition = "CONVERT(DATE, RedCreatedAt) BETWEEN '{$fDate}' AND '{$tDate}'";
        } else {
            if($this->session->userdata('userOrgId') == 3){
                $superv = '200001';
            }
            
            $condition = "CONVERT(DATE, RedCreatedAt) BETWEEN '{$fDate}' AND '{$tDate}' AND (Manager1 = '{$superv}' OR Manager2 = '{$superv}' OR Suvisor1 = '{$superv}' OR Suvisor2 = '{$superv}') ";
        }

        $this->db->where($condition, "", FALSE);
        $query = $this->db->get('RedeemData');

        return $query->result();
    }

    public function FindPointRoute($empId)
    {
        $this->db->select("ReqEmpID, ReqPointAfterDeduct, ReqComment, FORMAT(ReqRequestedAt, 'dd-MMM-yy hh:mm') AS ReqRequestedAt, ReqRequesters, FORMAT(ReqHRActionAt, 'dd-MMM-yy hh:mm') AS ReqHRActionAt, ReqHRActionBy");
        $this->db->from('RawData');
        $this->db->where('ReqEmpID', $empId);
        $this->db->where('ReqState', 2);
        $this->db->where('ReqPointAfterDeduct > 0');
        $this->db->where_in('ReqRedeemState', array('0', '2'));
        $query = $this->db->get();

        return $query->result();
    }

    public function FindPointSummaryOneEmp()
    {
        $empId = $this->input->post('empCode');
        $this->db->where('EmpID', $empId);
        $query = $this->db->get('PointSummary');

        return $query->row();
    }

    public function FindAllRequests()
    {
        $fDate = $this->input->post('fromDate');
        $tDate = $this->input->post('toDate');
        $state = $this->input->post('state');

        $superv = $this->session->userdata('userEmpId');
        $isAllDept = $this->session->userdata('userIsAllDept');

        if ($superv === 'admin' || $isAllDept == 1) {
            if ($state == 0) {
                $this->db->where("CONVERT(DATE, ReqRequestedAt) BETWEEN '{$fDate}' AND '{$tDate}'");
            } else {
                $this->db->where("CONVERT(DATE, ReqRequestedAt) BETWEEN '{$fDate}' AND '{$tDate}' AND ReqState = {$state} ");
            }
        } else {
            if($this->session->userdata('userOrgId') == 3){
                $superv = '200001';
            }

            if ($state == 0) {
                $this->db->where("CONVERT(DATE, ReqRequestedAt) BETWEEN '{$fDate}' AND '{$tDate}' AND (Manager1 = '{$superv}' OR Manager2 = '{$superv}' OR Suvisor1 = '{$superv}' OR Suvisor2 = '{$superv}') ");
            } else {
                $this->db->where("CONVERT(DATE, ReqRequestedAt) BETWEEN '{$fDate}' AND '{$tDate}' AND ReqState = '{$state}' AND (Manager1 = '{$superv}' OR Manager2 = '{$superv}' OR Suvisor1 = '{$superv}' OR Suvisor2 = '{$superv}') ");
            }
        }

        $query = $this->db->get('RawData');

        return $query->result();
    }


    //========================== operates ================================//
    public function ExecuteVoid($reqId)
    {
        $data = array(
            'ReqState' => 4,
            'ReqHRActionAt' => date('Y-m-d H:i:s'),
            'ReqHRActionBy' => $this->session->userdata('username')
        );

        $this->db->where('ReqID', $reqId);
        $this->db->update('Requests', $data);
        $this->output->set_content_type('application/json')->set_output(json_encode(array("status" => "success", "message" => 'Voided.')));
    }
}
