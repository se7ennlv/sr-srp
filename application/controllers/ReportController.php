<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ReportController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ReportModel');
    }


    //====================== view render ===============================//
    public function PointSummaryView()
    {
        $this->load->view('reports/PointSummaryView');
    }

    public function RedeemedView()
    {
        $this->load->view('reports/RedeemedView');
        $this->load->view('global/InvModal');
        $this->load->view('global/GlobalFunctions');
    }

    public function RequestView()
    {
        $this->load->view('reports/RequestView');
    }



    //============================== fetching =============================//
    public function FetchAllPointSummary()
    {
        $data = $this->ReportModel->FindAllPointSummary();
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function FetchAllRedeemed()
    {
        $data = $this->ReportModel->FindAllRedeemed();
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function PointRouteView($empId)
    {
        $data = $this->ReportModel->FindPointRoute($empId);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function CheckPointSummary()
    {
        $data = $this->ReportModel->FindPointSummaryOneEmp();
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function FetchAllRequests()
    {
        $data = $this->ReportModel->FindAllRequests();
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }


    //======================= operates ================================//
    public function InitVoid($itemId)
    {
        $this->ReportModel->ExecuteVoid($itemId);
    }
}
