<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AuthController extends CI_Controller {

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
	private $type = '';
	private $status = 400;
	private $response = '';
	private $token = '';

	public function __construct(){
		parent::__construct();
        $this->load->library('form_validation');
	}
	// register logic
	public function register()
	{
		$this->form_validation->set_rules('email','Email','trim|required|valid_email');
		$this->form_validation->set_rules('username','Username','trim|required|min_length[3]');
		$this->form_validation->set_rules('password', 'Password', 'required');
		$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[password]');

		if ($this->form_validation->run() === TRUE) {
			
			$input = $this->input->post();//get input post
			unset($input['confirm_password']); //delete confirm password
			$input['token'] = $this->generate_token(); //generate token
			
			$input['password'] = password_hash($input['password'], PASSWORD_DEFAULT);

			if($this->check_user($input['email'])){ // check user
				$register = $this->db->insert('users', $input);
				if($register) {
					$this->type= 'success';
					$this->status = 200;
					$this->response = 'Your account has been created!';
					$this->token = $input['token'];
				}else{
					$this->type= 'error';
					$this->status = 400;
					$this->response = 'The account was not successfully created!';
				}
			}else{
				$this->type = 'account exist';
				$this->status = 400;
				$this->response = 'Your account already exists!';
				$this->token = $this->get_token($input['email']);
			}

		}else{
			$this->type = 'error validation';
			$this->status = 401;
			$this->response = $this->get_error_validate();
		}
		$this->response();
	}
	//login logic
	public function login(){
		$this->form_validation->set_rules('email','Email','trim|required|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'required');

		if ($this->form_validation->run() === TRUE) {
			
			$input = $this->input->post();//get input post
			$query = $this->db->get_where('users', ['email' => $input['email']]);
			if($query->num_rows() > 0){
			    $query = $query->result()[0];
			    
			    if(password_verify($input['password'], $query->password)){
    				//if login true
    
    				$token = $this->generate_token();// generate token
    
    				$this->db->set('token', $token);
    				$this->db->where('email', $input['email']);
    				$update_token = $this->db->update('users');
    
    				if($update_token){
    					$this->type = 'success';
    					$this->status = 200;
    					$this->response = 'login successful';
    					$this->token = $token;
    				}else{
    					$this->type = 'error';
    					$this->status = 400;
    					$this->response = 'login failed';
    				}
    			}else{
    				//if login false
    
    				$this->type = 'error';
    				$this->status = 400;
    				$this->response = 'Unsuccessful login to your account';
    			}
			}else{
			    $this->type = 'error';
    		    $this->status = 400;
    			$this->response = 'Your email does not match any account';
			}

		}else{
			$this->type = 'error validation';
			$this->status = 401;
			$this->response = $this->get_error_validate();
		}
		$this->response();
	}
	public function logout(){
		$this->form_validation->set_rules('token','Token','trim|required');

		if ($this->form_validation->run() === TRUE) {

			$input = $this->input->post();//get input post
			if($this->get_token_valid($input['token'])){

				$this->db->set('token', '');
				$this->db->where('token', $input['token']);
				$update_token = $this->db->update('users');

				if($update_token){
					$this->type = 'success';
					$this->status = 200;
					$this->response = 'Logout success!';
				}else{
					$this->type = 'error';
					$this->status = 400;
					$this->response = 'Logout failed!';
				}

			}else{
				$this->type = 'error';
				$this->status = 400;
				$this->response = 'Your token has expired';
			}

		}else{
			$this->type = 'error validation';
			$this->status = 401;
			$this->response = $this->get_error_validate();
		}
		$this->response();
	}
	public function topup(){
		$this->form_validation->set_rules('token','Token','trim|required');
		$this->form_validation->set_rules('nominal', 'Nominal', 'trim|required');

		if ($this->form_validation->run() === TRUE) {
			
			$input = $this->input->post();//get input post
			if($this->get_token_valid($input['token'])){

				$data = $this->get_token_data($input['token']);
				$balance = $data->balance += $input['nominal'];

				$payload = [
					'balance' => $balance
				];
				$this->db->where('id', $data->id);
				$process = $this->db->update('users', $payload);
				if($process){
					$this->type = 'success';
					$this->status = 200;
					$this->response = 'Top up successfully';
				}else{
					$this->type = 'error';
					$this->status = 400;
					$this->response = 'Top failed';
				}

			}else{
				$this->type = 'error';
				$this->status = 400;
				$this->response = 'Your token has expired';
			}

		}else{
			$this->type = 'error validation';
			$this->status = 401;
			$this->response = $this->get_error_validate();
		}
		$this->response();
	}
	public function check_token(){
		$this->form_validation->set_rules('token','Token','trim|required');

		if ($this->form_validation->run() === TRUE) {

			$input = $this->input->post();//get input post
			$data = $this->get_token_valid($input['token']);

			if($data){
				$this->type = 'success';
				$this->status = 200;
				$this->response = 'your token can be used';
			}else{
				$this->type = 'error';
				$this->status = 400;
				$this->response = 'Your token has expired';
			}

		}else{
			$this->type = 'error validation';
			$this->status = 401;
			$this->response = $this->get_error_validate();
		}
		$this->response();
	}
	public function check_token_admin(){
		$this->form_validation->set_rules('token','Token','trim|required');

		if ($this->form_validation->run() === TRUE) {

			$input = $this->input->post();//get input post
			$data = $this->get_token_admin($input['token']);

			if($data){
				$this->type = 'success';
				$this->status = 200;
				$this->response = 'Your token can be used';
			}else{
				$this->type = 'error';
				$this->status = 400;
				$this->response = 'Your token has expired, or maybe you are not an admin';
			}

		}else{
			$this->type = 'error validation';
			$this->status = 401;
			$this->response = $this->get_error_validate();
		}
		$this->response();
	}
	public function generate_token($len = 64){
	    $char = "bcdfghjkmnpqrstvzBCDFGHJKLMNPQRSTVWXZaeiouyAEIOUY!@#%";
	    $token = '';
	    for ($i = 0; $i < $len; $i++) $token .= $char[(rand() % strlen($char))];
	    return $token;
	}
	public function check_user($email){
		$check = $this->db->get_where('users', ['email' => $email])->result();
		return count($check) == 0;
	}
	public function get_token($email){
		$token = $this->db->get_where('users', ['email' => $email])->result();
		return $token[0]->token;
	}
	public function get_token_data($token = ''){
		$data = $this->db->get_where('users', ['token' => $token])->result();
		return $data[0];
	}
	public function get_token_valid($token = ''){
		$data = $this->db->get_where('users', ['token' => $token])->result();
		return count($data) != 0;
	}
	public function get_token_admin($token = ''){
		$data = $this->db->get_where('users', ['token' => $token, 'role' => 'admin'])->result();
		return count($data) != 0;
	}
	public function get_error_validate(){
		$error = $this->form_validation->error_array();
		$msg = '';
		foreach ($error as $key => $value) {
			$msg .= $value . PHP_EOL;
		}
		return $msg;
	}
	public function response(){
	    return $this->output
        ->set_content_type('application/json')
        ->set_status_header($this->status)
        ->set_output(json_encode([
        	'type' => $this->type,
        	'response' => $this->response,
        	'token' => $this->token
        ]));
	}
}
