<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AppController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('AppModel');
        $this->load->model('UserModel');
    }

    //======================= view render =============================//
    public function LoginView()
    {
        $this->load->view('app/LoginView');
    }

    public function CheckPointView()
    {
        $this->load->view('app/CheckPointView');
        $this->load->view('global/GlobalFunctions');
    }



    //========================= operates ============================//
    public function ExecuteLogin()
    {
        $usr = $this->input->post('Username');
        $pwd = sha1($this->input->post('Password'));
        $data = $this->UserModel->LoginValid($usr, $pwd);

        if (!empty($data)) {
            $setData = array(
                'userId' => $data->UserID,
                'userEmpId' => $data->UserEmpID,
                'userEmpCode' => $data->UserEmpCode,
                'username' => $data->UserUsername,
                'userFname' => $data->UserFname,
                'userLname' => $data->UserLname,
                'userMgroupId' => $data->UserMenuGroupID,
                'userMenuId'  => $data->UserMenuID,
                'userCreatedAt' => $data->UserCreatedAt,
                'userIsAllDept' => $data->UserIsAllDept,
                'userOrgId' => $data->UserOrgID
            );

            $this->session->set_userdata($setData);
            $this->output->set_content_type('application/json')->set_output(json_encode(array('status' => 'success', 'message' => 'Login Success')));
        } else {
            $this->output->set_content_type('application/json')->set_output(json_encode(array('status' => 'danger', 'message' => 'Incorrect username or password')));
            $array_items = array('userId' => '', 'userFname' => '', 'userLname' => '');
            $this->session->unset_userdata($array_items);
        }
    }

    public function ExecuteLogout()
    {
        $this->session->unset_userdata('userId');
        redirect('AppController/LoginView', 'refresh');

        exit;
    }

}
