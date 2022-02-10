<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PrizeController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('PrizeModel');
    }

    public function FetchAllPrizes()
    {
        $data = $this->PrizeModel->FindAllPrizes();
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

  
}
