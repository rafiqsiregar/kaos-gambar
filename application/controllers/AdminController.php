<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminController extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function __construct(){
		parent::__construct();
        $this->load->library('form_validation');
	}
	public function index()
	{
		$this->load->view('login');
	}
	// dashboard
	public function dashboard(){
		$this->load->view('dashboard/home');
	}
	// product
	public function product(){
		$this->load->view('dashboard/product');
	}
	public function productedit($id = 0){
		if($id > 0){
			$this->load->view('dashboard/productedit');
		}
	}
	// purchase
	public function purchase(){
		$this->load->view('dashboard/purchase');
	}
	public function get_token_valid($token = ''){
		$data = $this->db->get_where('users', ['token' => $token])->result();
		return count($data) != 0;
	}
}
