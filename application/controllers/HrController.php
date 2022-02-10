<?php
defined('BASEPATH') or exit('No direct script access allowed');

class HrController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('HrModel');
        $this->load->model('ServiceModel');
    }

    //====================== view render ===========================//
    public function WaitingApproveView()
    {
        $this->load->view('hr/WaitingApprovalView');
    }

    public function RedeemView()
    {
        $this->load->view('hr/RedeemView');
        $this->load->view('global/InvModal');
        $this->load->view('global/GlobalFunctions');
    }



    //=============================== fetching ================================//
    public function FetchAllWaitingApprove()
    {
        $data = $this->HrModel->FindAllWaitingApprove();
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function FetchPointEmp($empId)
    {
        $data = $this->HrModel->FindPointEmp($empId);
        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function FetchRedeemedData($redId)
    {
        $data = $this->HrModel->FindRedeemedData($redId);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }




    //============================== transactions ==============================//
    public function InitApprove()
    {
        $docNo = $this->input->post('docNo');
        $orgId = $this->input->post('orgId');

        $this->HrModel->ExecuteApprove($docNo);
        $this->SendMailApproveToHOD($docNo, $orgId);
    }

    public function InitReject()
    {
        $docNo = $this->input->post('docNo');
        $orgId = $this->input->post('orgId');
        $remark = $this->input->post('remark');

        $this->HrModel->ExecuteReject($docNo, $remark);
        $this->SendMailRejectToHOD($docNo, $orgId);
    }

    public function InitRedeem()
    {
        $this->HrModel->ExecuteRedeem();
    }

    public function InitDeductPoint()
    {
        $empId = $this->input->post('empId');
        $redeemPoint = $this->input->post('redPoint');
        $redeemId = $this->input->post('redId');

        $totalPoint = 0;

        $data['query'] =  $this->HrModel->FindPointDeduction($empId);

        foreach ($data['query'] as $point) {
            $totalPoint += $point->ReqPointAfterDeduct;

            $this->HrModel->SetRedeemState($point->ReqID, 0, 1, $redeemId, $point->ReqRedeemStatus);

            if ($totalPoint == $redeemPoint) {
                $this->HrModel->SetRedeemState($point->ReqID, 0, 1,  $redeemId, $point->ReqRedeemStatus);

                break;
            }

            if ($totalPoint > $redeemPoint) {
                $this->HrModel->SetRedeemState($point->ReqID, 0.5, 2,  $redeemId, $point->ReqRedeemStatus);

                break;
            }
        }
    }

    public function SendMailApproveToHOD($docNo, $orgId)
    {
        $data['toMails'] = $this->ServiceModel->FindMailApprovers($orgId);

        foreach ($data['toMails'] as $toMail) {
            $to = $toMail->ApprEmail;
            $subject = 'SRP Approved: ' . $docNo;
            $msg = "Dear HOD, \r\n\r\n";
            $msg .= "Your request has been approved from HR approver \r\n\r\n";
            $msg .= "Doc No: $docNo \r\n";
            $msg .= "You can access to SRP system and find document number on (Approved List) to look for more information";

            $this->ServiceModel->SendMail($to, $subject, $msg);
        }
    }

    public function SendMailRejectToHOD($docNo, $orgId)
    {
        $data['toMails'] = $this->ServiceModel->FindMailApprovers($orgId);

        foreach ($data['toMails'] as $toMail) {
            $to = $toMail->ApprEmail;
            $subject = 'SRP Rejected: ' . $docNo;
            $msg = "Dear HOD, \r\n\r\n";
            $msg .= "Your request has been rejected from HR approver \r\n\r\n";
            $msg .= "Doc No: $docNo \r\n";
            $msg .= "You can access to SRP system to look for the rejected reason";

            $this->ServiceModel->SendMail($to, $subject, $msg);
        }
    }
}
