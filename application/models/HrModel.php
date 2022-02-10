<?php

class HrModel extends CI_Model
{
    //========================== fetching =======================//
    public function FindAllWaitingApprove()
    {
        $this->db->where('ReqState', 1);
        $query = $this->db->get('Requests');

        return $query->result();
    }

    public function FindPointEmp($empId)
    {
        $this->db->where('EmpID', $empId);
        $query = $this->db->get('PointSummary');

        return $query->row();
    }

    public function FindPointDeduction($empId)
    {
        $this->db->where('ReqEmpID', $empId);
        $query = $this->db->get('PointDeduction');

        return $query->result();
    }

    public function FindRedeemedData($redId)
    {
        $this->db->where('RedID', $redId);
        $query = $this->db->get('Redeemed');

        return $query->row();
    }



    //========================== operates ===========================//
    public function ExecuteApprove($docNo)
    {
        $data = array(
            'ReqState' => 2,
            'ReqHRActionBy' => $this->session->userdata('username'),
            'ReqHRActionAt' => date('Y-m-d H:i:s')
        );

        $this->db->where('ReqDocNo', $docNo);
        $this->db->update('Requests', $data);
    }

    public function ExecuteReject($docNo, $remark)
    {
        $data = array(
            'ReqState' => 3,
            'ReqHRActionBy' => $this->session->userdata('username'),
            'ReqHRActionAt' => date('Y-m-d H:i:s'),
            'ReqRejectRemark' => $remark
        );

        $this->db->where('ReqDocNo', $docNo);
        $this->db->update('Requests', $data);
    }

    public function ExecuteRedeem()
    {
        $data = array(
            'RedEmpID' => $this->input->post('empId'),
            'RedEmpName' => $this->input->post('empName'),
            'RedPosition' => $this->input->post('position'),
            'RedOrgID' => $this->input->post('orgId'),
            'RedDeptCode' => $this->input->post('deptCode'),
            'RedPrizeID' => $this->input->post('prizeId'),
            'RedPrizeDetail' => $this->input->post('prizeDetail'),
            'RedPrizeUnit' => $this->input->post('prizeUnit'),
            'RedPrizePpNo' => $this->input->post('points'),
            'RedBy' => $this->session->userdata('username')
        );

        $this->db->insert('Redeemed', $data);
        $lastId = $this->db->insert_id();
        $this->output->set_content_type('application/json')->set_output(json_encode($lastId));
    }

    public function SetRedeemState($reqId, $pointAfter, $redState, $redId, $checkStatus)
    {
        if ($checkStatus == 2) {
            $data = array(
                'ReqPointAfterDeduct' => $pointAfter,
                'ReqRedeemState' => $redState,
                'ReqRedeemChildID' => $redId
            );
        } else {
            $data = array(
                'ReqPointAfterDeduct' => $pointAfter,
                'ReqRedeemState' => $redState,
                'ReqRedeemParentID' => $redId
            );
        }

        $this->db->where('ReqID', $reqId);
        $this->db->update('Requests', $data);
    }
}
