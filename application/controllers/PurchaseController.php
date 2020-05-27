<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PurchaseController extends CI_Controller {

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
				
				$data = $this->db->select('purchase.id, users.username,product.id as id_product, product.name, product.price, purchase.quantity, (purchase.quantity * product.price) as total, product.description, product.photo, notification.id_purchase as status, notification.type, purchase.timestamp')
				->from('purchase')
				->join('users', 'users.id = purchase.user_id')
				->join('product', 'product.id = purchase.product_id')
				->join('notification', 'notification.id_purchase = purchase.id', 'left')
				->get()
				->result();

				if(count($data) == 1){
					$this->type = $data[0]->id == null ? 'error' : 'success';
					$this->status = $data[0]->id == null ? 400 : 200;
					$this->response = $data[0]->id == null ? 'data not found' : $data;
				}else{
					$this->type = 'success';
					$this->status = 200;
					$this->response = $data;
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
	// create new product
	public function add(){
		$this->form_validation->set_rules('token','Token','trim|required');
		$this->form_validation->set_rules('product_id','Product ID','trim|required');
		$this->form_validation->set_rules('description','Description','trim');
		$this->form_validation->set_rules('quantity','Quantity','trim|required');

		if ($this->form_validation->run() === TRUE) {

			$input = $this->input->post();//get input post
			$data = $this->get_token_valid($input['token']);

			if($data){

				$user_data = $this->get_token_data($input['token']);
				$product_data = $this->get_product_data($input['product_id']);
				if($product_data != false){

					if($user_data->balance >= ($product_data->price * $input['quantity'])){
						$data = array(
					        'user_id' => $user_data->id,
					        'product_id' => $input['product_id'],
					        'description' => $input['description'],
					        'quantity' => $input['quantity']
						);

						$insert = $this->db->insert('purchase', $data);

						$balance = $user_data->balance - ($product_data->price * $input['quantity']);

						$this->db->where('id', $user_data->id);
						$this->db->update('users', [
							'balance' => $balance
						]);

						if($insert){
							$this->type = 'success';
							$this->status = 200;
							$this->response = 'groceries successfully added to the cart';
						}else{
							$this->type = 'error';
							$this->status = 400;
							$this->response = 'You did not succeed in adding the cart';
						}

					}else{
						$this->type = 'error';
						$this->status = 400;
						$this->response = 'your balance is not enough';
					}
				}else{
					$this->type = 'error';
					$this->status = 400;
					$this->response = 'Product item not found';
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
	public function view(){
		$this->form_validation->set_rules('token','Token','trim|required');
		$this->form_validation->set_rules('id','ID Product','trim|required');

		if ($this->form_validation->run() === TRUE) {

			$input = $this->input->post();//get input post
			$data = $this->get_token_valid($input['token']);

			if($data){
				
				//view purchase
				$data = $this->db->select('purchase.id, users.username,product.id as id_product, product.name, product.price, purchase.quantity, (purchase.quantity * product.price) as total, product.description, product.photo, notification.id_purchase as status, notification.type, purchase.timestamp')
				->from('purchase')
				->where('purchase.id', $input['id'])
				->join('users', 'users.id = purchase.user_id', 'left')
				->join('product', 'product.id = purchase.product_id', 'left')
				->join('notification', 'notification.id_purchase = purchase.id', 'left')
				->get()->result()[0];

				$this->type = $data->id == null ? 'error' : 'success';
				$this->status = $data->id == null ? 400 : 200;
				$this->response = $data->id == null ? 'data not found' : $data;
				

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
		$this->form_validation->set_rules('product_id','Product ID','trim|required');
		$this->form_validation->set_rules('description','Description','trim');
		$this->form_validation->set_rules('quantity','Quantity','trim|required');

		if ($this->form_validation->run() === TRUE) {

			$input = $this->input->post();//get input post
			$data = $this->get_token_valid($input['token']);

			if($data){

				$user_data = $this->get_token_data($input['token']);
				$data = array(
			        'user_id' => $user_data->id,
			        'product_id' => $input['product_id'],
			        'description' => $input['description'],
			        'quantity' => $input['quantity']
				);
				$this->db->where('id', $input['id']);
				$insert = $this->db->update('purchase', $data);
				if($insert){
					$this->type = 'success';
					$this->status = 200;
					$this->response = 'groceries successfully edit';
				}else{
					$this->type = 'error';
					$this->status = 400;
					$this->response = 'You failed to edit the cart';
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
	public function delete(){
		$this->form_validation->set_rules('token','Token','trim|required');
		$this->form_validation->set_rules('id','Product ID','trim|required');

		if ($this->form_validation->run() === TRUE) {

			$input = $this->input->post();//get input post
			$data = $this->get_token_valid($input['token']);

			if($data){

				$user_data = $this->get_token_data($input['token']);
				
				$delete = $this->db->delete('purchase', ['id' => $input['id']]); 

				if($delete){
					$this->type = 'success';
					$this->status = 200;
					$this->response = 'items from the cart have been deleted successfully';
				}else{
					$this->type = 'error';
					$this->status = 400;
					$this->response = 'items from the cart failed to be removed';
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
	public function cancel(){
		$this->form_validation->set_rules('token','Token','trim|required');
		$this->form_validation->set_rules('id','Purchase ID','trim|required');

		if ($this->form_validation->run() === TRUE) {

			$input = $this->input->post();//get input post
			$data = $this->get_token_valid($input['token']);

			if($data){

				$user_data = $this->get_token_data($input['token']);
				$purchase_data = $this->get_purchase_data($input['id']);

				if($purchase_data != false){
					$message = 'Anda tidak berhasil membeli '.$purchase_data->quantity.' buah "'.$purchase_data->name.', kami akan mengembalikan uang anda secepatnya';

					$process = $this->db->insert('notification', [
						'id_purchase' => $input['id'],
						'type' => 'cancel',
						'message' => $message
					]);

					$this->db->where('id', $user_data->id);
					$process = $this->db->update('users', [
						'balance' => $user_data->balance + $purchase_data->total
					]);

					if($process){
						$this->type = 'success';
						$this->status = 200;
						$this->response = 'Successfully to cancel item';
					}else{
						$this->type = 'error';
						$this->status = 400;
						$this->response = 'Failed to cancel item';
					}
				}else{
					$this->type = 'error';
					$this->status = 400;
					$this->response = 'Purchase data not found';
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
	public function accept(){
		$this->form_validation->set_rules('token','Token','trim|required');
		$this->form_validation->set_rules('id','Purchase ID','trim|required');

		if ($this->form_validation->run() === TRUE) {

			$input = $this->input->post();//get input post
			$data = $this->get_token_valid($input['token']);

			if($data){

				$user_data = $this->get_token_data($input['token']);
				$purchase_data = $this->get_purchase_data($input['id']);

				if($purchase_data != false){
					$message = 'Anda telah berhasil membeli '.$purchase_data->quantity.' buah "'.$purchase_data->name.'" dengan harga '.$this->rupiah($purchase_data->total);

					$process = $this->db->insert('notification', [
						'id_purchase' => $input['id'],
						'type' => 'accept',
						'message' => $message
					]);
					if($process){
						$this->type = 'success';
						$this->status = 200;
						$this->response = 'Successfully to accept item';
					}else{
						$this->type = 'error';
						$this->status = 400;
						$this->response = 'Failed to accept item';
					}
				}else{
					$this->type = 'error';
					$this->status = 400;
					$this->response = 'Purchase data not found';
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
	public function rupiah($angka){
		$hasil_rupiah = "Rp " . number_format($angka,2,',','.');
		return $hasil_rupiah;
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
	public function get_product_data($id = ''){
		$data = $this->db->get_where('product', ['id' => $id])->result();
		return count($data) > 0 ? $data[0] : false;
	}
	public function get_purchase_data($id = ''){
		$data = $this->db->select('purchase.id, users.username,product.id as id_product, product.name, product.price, purchase.quantity, (purchase.quantity * product.price) as total, product.description, product.photo')
				->from('purchase')
				->where('purchase.id', $id)
				->join('users', 'users.id = purchase.user_id', 'left')
				->join('product', 'product.id = purchase.product_id', 'left')
				->get()->result();
		return $data[0]->id == null ? false : $data[0];
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
