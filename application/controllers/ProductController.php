<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ProductController extends CI_Controller {

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
	// get all product
	public function all(){
		$this->form_validation->set_rules('token','Token','trim|required');

		if ($this->form_validation->run() === TRUE) {

			$input = $this->input->post();//get input post
			$data = $this->get_token_valid($input['token']);

			if($data){
				
				$data = $this->db->get('product')->result();
				$this->type = 'success';
				$this->status = 200;
				$this->response = $data;

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
	// create new product
	public function create(){
		$this->form_validation->set_rules('token','Token','trim|required');
		$this->form_validation->set_rules('name','Product Name','trim|required');
		$this->form_validation->set_rules('price','Product Price','trim|required');
		$this->form_validation->set_rules('description','Product Description','trim');

		if ($this->form_validation->run() === TRUE) {

			$input = $this->input->post();//get input post
			$data = $this->get_token_admin($input['token']);


			if($data){

				if(isset($_FILES['photo'])){

					unset($input['token']); //delete token

					$config['upload_path']          = './uploads/';
	                $config['allowed_types']        = 'gif|jpg|png';
	                $config['max_size']             = 100;
	                $config['max_width']            = 1024;
	                $config['max_height']           = 768;
	                $config['encrypt_name']         = TRUE;

	                $this->load->library('upload', $config);

	                if ($this->upload->do_upload('photo')){

	                	$input['photo'] = $this->upload->data()['file_name'];
	                	$insert = $this->db->insert('product', $input);
						if($insert) {
							$this->type = 'success';
							$this->status = 200;
							$this->response = 'Product added successfully';
						}else{
							$this->type = 'error';
							$this->status = 400;
							$this->response = 'Cannot create new product';
						}
	                }else{
	                	$this->type = 'error';
						$this->status = 400;
						$this->response = 'File Upload Failed';
	                }
				}else{
					$this->type = 'error';
					$this->status = 400;
					$this->response = 'File photo is empty';
				}

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
	public function view(){
		$this->form_validation->set_rules('token','Token','trim|required');
		$this->form_validation->set_rules('id','ID Product','trim|required');

		if ($this->form_validation->run() === TRUE) {

			$input = $this->input->post();//get input post
			$data = $this->get_token_admin($input['token']);

			if($data){
				
				$data = $this->db->get_where('product', ['id' => $input['id']])->result()[0];
				$this->type = 'success';
				$this->status = 200;
				$this->response = $data;

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
	public function edit(){
		$this->form_validation->set_rules('token','Token','trim|required');
		$this->form_validation->set_rules('id','ID Product','trim|required');

		$this->form_validation->set_rules('token','Token','trim|required');
		$this->form_validation->set_rules('name','Product Name','trim|required');
		$this->form_validation->set_rules('price','Product Price','trim|required');
	    $this->form_validation->set_rules('photo', 'Photo', 'required');
		$this->form_validation->set_rules('description','Product Description','trim');

		if ($this->form_validation->run() === TRUE) {

			$input = $this->input->post();//get input post
			$data = $this->get_token_admin($input['token']);

			if($data){
				
				// $data = $this->db->get_where('product', ['id' => $input['id']])->result()[0];

				// $this->type = 'success';
				// $this->status = 200;
				// $this->response = $data;

				unset($input['token']); //delete token from array

				$this->db->where('id', $input['id']);

				unset($input['id']); // delete id from array

				$data = array(
			        'name' => $input['name'],
			        'price' => $input['price'],
			        'photo' => $input['photo'],
			        'description' => $input['description']
				);

				$update = $this->db->update('product', $data);

				if($update){
					$this->type = 'success';
					$this->status = 200;
					$this->response = 'Data has been edited';
				}else{
					$this->type = 'error';
					$this->status = 400;
					$this->response = 'Data was not edited successfully';
				}

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
	public function delete(){
		$this->form_validation->set_rules('token','Token','trim|required');
		$this->form_validation->set_rules('id','ID Product','trim|required');

		if ($this->form_validation->run() === TRUE) {

			$input = $this->input->post();//get input post
			$data = $this->get_token_admin($input['token']);

			if($data){
				
				$this->db->where('id', $input['id']);
				$delete = $this->db->delete('product');
				if($delete){
					$this->type = 'success';
					$this->status = 200;
					$this->response = 'The product you selected was successfully deleted';
				}else{
					$this->type = 'error';
					$this->status = 400;
					$this->response = 'Your token has expired, or maybe you are not an admin';
				}

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
	public function generate_name($length = 32){
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $randomString;
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
