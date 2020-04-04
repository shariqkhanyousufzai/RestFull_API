<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class Api_model extends CI_Model {



	public function createUser($userInfo)

	{

		if ($this->db->insert('users',$userInfo)) {

			return true;

		}

		return false;

	}

	public function createSessionLog($userInfo)

	{

		if ($this->db->insert('session_log',$userInfo)) {

			return true;

		}

		return false;

	}



	public function SessionLogExpire($user_hash)

	{	

		$this->db->where('user_hash',$user_hash);

		if ($this->db->delete('session_log')) {

			return true;

		}

		return false;

	}

	public function deleteCartItems($user_id)

	{	

		$this->db->where('u_id',$user_id);

		if ($this->db->delete('cart')) {

			return true;

		}

		return false;

	}



	public function createBrand($createBrand)

	{

		if ($this->db->insert('brands',$createBrand)) {

			return true;

		}

		return false;

	}



	public function createCart($cartInfo)

	{

		if ($this->db->insert('cart',$cartInfo)) {

			return true;

		}

		return false;

	}

	public function getOrders($user_id)
	{
		$this->db->select('*');
		$this->db->from('orders');
		$this->db->where('u_id',$user_id);
		$this->db->order_by('id','DESC');
		$q = $this->db->get();
		if ($q->num_rows() > 0 ) {
			foreach ($q->result() as $row ) {
				$data[] = $row;
			}
			return $data;

		}

		return false;
	}

	public function getOrdersDetails($order_id)
	{
		$this->db->select('*');
		$this->db->from('order_details');
		$this->db->where('o_id',$order_id);
		$q = $this->db->get();
		if ($q->num_rows() > 0 ) {
			$i = 0;
			foreach ($q->result() as $row ) {
				$data[$i] = $row;
				$data[$i]->image = $this->getImage($row->p_id,$row->pf_id);
				$data[$i]->review = $this->checkReview($order_id,$row->p_id,$row->pf_id);
				$data[$i]->delivered = $this->checkDelivered($order_id,$row->id,$row->pf_id);
				$data[$i]->color = $this->getColor($row->p_id,$row->pf_id);
				$i++;
			}
			return $data;
		}

		return false;
	}

	public function getImage($p_id,$pf_id)
	{
		$this->db->select('image');
		$this->db->from('product_features');
		$this->db->where('p_id',$p_id);
		$this->db->where('id',$pf_id);
		$q = $this->db->get();
		if ($q->num_rows() > 0 ) {
			return $q->result()[0]->image;
		}
		return false;
	}
	public function getColor($p_id,$pf_id)
	{
		$this->db->select('color');
		$this->db->from('product_features');
		$this->db->where('p_id',$p_id);
		$this->db->where('id',$pf_id);
		$q = $this->db->get();
		if ($q->num_rows() > 0 ) {
			return $q->result()[0]->color;
		}
		return false;
	}


	public function addOrder($orderInfo)

	{

		if ($this->db->insert('orders',$orderInfo)) {

			return $this->db->insert_id();

		}
		return false;
	}

	public function createCartDetails($orderInfo)

	{

		if ($this->db->insert('order_details',$orderInfo)) {

			return $this->db->insert_id();

		}
		return false;
	}

	public function orderDetails($orderDetailsInfo)

	{

		if ($this->db->insert('order_details',$orderDetailsInfo)) {

			return true;

		}
		return false;
	}

	public function addPayments($payments)

	{

		if ($this->db->insert('payments',$payments)) {
			return true;

		}
		return false;
	}



	public function updateCartQty($product_id,$user_id,$quanity)

	{	

		$this->db->set('quantity',$quanity);

		$this->db->where('u_id',$user_id);

		$this->db->where('p_id',$product_id);

		if ($this->db->update('cart')) {

			return true;

		}

		return false;

	}



	public function removeCart($product_id,$user_id,$feature_id)

	{

		$this->db->where('p_id',$product_id);

		$this->db->where('u_id',$user_id);

		$this->db->where('pf_id',$feature_id);

		if ($this->db->delete('cart')) {

			return true;

		}

		return false;

	}



	public function removeWish($product_id,$user_id,$feature_id)

	{

		$this->db->where('p_id',$product_id);

		$this->db->where('u_id',$user_id);

		$this->db->where('pf_id',$feature_id);

		if ($this->db->delete('wishlist')) {

			return true;

		}

		return false;

	}



	public function createWishlist($wishlistInfo)

	{

		if ($this->db->insert('wishlist',$wishlistInfo)) {

			return true;

		}

		return false;

	}


	public function addHelp($data)

	{

		if ($this->db->insert('customer_help',$data)) {

			return true;

		}

		return false;

	}

	public function addReview($data)

	{

		if ($this->db->insert('order_reviews',$data)) {

			return true;

		}

		return false;

	}



	public function checkUserExist($cnic)

	{

		$this->db->select('*');

		$this->db->from('users');

		$this->db->where('cnic',$cnic);

		$this->db->where('is_active',1);

		$q = $this->db->get();

		if ($q->num_rows() > 0 ) {

			foreach ($q->result() as $row ) {

				$data[] = $row;

			}

			return $data;

		}

		return false;



	}

	public function getVariants($p_id)

	{

		$this->db->select('id,UPPER(color) as color');

		$this->db->from('product_features');

		$this->db->where('p_id',$p_id);

		$q = $this->db->get();

		if ($q->num_rows() > 0 ) {

			foreach ($q->result() as $row ) {

				$data[] = $row;

			}

			return $data;

		}

		return false;



	}




	public function checkCart($product_id,$user_id,$feature_id)

	{

		$this->db->select('id');

		$this->db->from('cart');

		$this->db->where('p_id',$product_id);

		$this->db->where('u_id',$user_id);

		$this->db->where('pf_id',$feature_id);

		$q = $this->db->get();

		if ($q->num_rows() > 0 ) {

			return true;

		}

		return false;



	}


	public function checkWishlist($product_id,$user_id,$feature_id)

	{

		$this->db->select('id');

		$this->db->from('wishlist');

		$this->db->where('p_id',$product_id);

		$this->db->where('u_id',$user_id);

		$this->db->where('pf_id',$feature_id);

		$q = $this->db->get();

		if ($q->num_rows() > 0 ) {

			return true;

		}

		return false;



	}



	public function getProductsFilter($category_id = NULL, $min = NULL,$max = NULL,$brand = NULL,$type = NULL, $color = NULL)

	{

		$this->db->select('p.*,pc.name as category,pf.id as feature_id,b.name as brand');

		$this->db->from('products p');


		$this->db->join('product_categories pc','p.pc_id = pc.id','left');

		$this->db->join('brands b','p.b_id = b.id','left');

		$this->db->join('product_features pf','pf.p_id = p.id','left');

		$this->db->where('p.isdeleted',0);
		if (isset($category_id)) {
		$this->db->where('p.pc_id',$category_id);

		}

		if (isset($brand) && !empty($brand)) {
		$this->db->like('b.name', $brand);
		}

		if (isset($color) && !empty($color)) {
		
		$this->db->like('pf.color', $color);
		}

		if (isset($material) && !empty($material)) {
		$this->db->like('pf.material', $material);
		}

		if (isset($type) && !empty($type)) {
		$this->db->like('pf.type', $type);

		}

		if (isset($min) && !empty($min) || isset($max) && !empty($max)) {

			if (isset($min)  && !empty($min)) {

			$this->db->where('p.price >=',$min);

			}

			if (isset($max)  && !empty($max)) {

			$this->db->where('p.price <=',$max);

			}

			$this->db->order_by('p.price');

		}

		$q = $this->db->get();

		if ($q->num_rows() > 0 ) {

			foreach ($q->result() as $row ) {

				$data[] = $row;

			}

			return $data;

		}

		return false;



	}

	public function getProducts()

	{

		$this->db->select('p.*,pc.name as category,b.name as brand');

		$this->db->from('products p');

		$this->db->join('product_categories pc','p.pc_id = pc.id','left');

		$this->db->join('brands b','p.b_id = b.id','left');

		$this->db->where('p.isdeleted',0);


		$q = $this->db->get();

		if ($q->num_rows() > 0 ) {

			foreach ($q->result() as $row ) {

				$data[] = $row;

			}

			return $data;

		}

		return false;



	}

	public function getAllCartDetails($user_id)
	{
		$this->db->select('*');
		$this->db->from('cart');
		$this->db->where('u_id',$user_id);
		$q = $this->db->get();
		if ($q->num_rows() > 0 ) {
			$i = 0;
			foreach ($q->result() as $row ) {
				$data[$i] = $row;
				$data[$i]->price = $this->getPrice($row->p_id);
				$i++;
			}
		return $data;
		}
		return false;
	}

	public function getPrice($id)
	{
		$this->db->select('price');
		$this->db->from('products');
		$this->db->where('id',$id);
		$q = $this->db->get();
		if ($q->num_rows() > 0 ) {
			return $q->result()[0]->price;
		}
		return false;
	}

	public function getTotal($o_id)
	{
		$this->db->select('total');
		$this->db->from('orders');
		$this->db->where('id',$o_id);
		$q = $this->db->get();
		if ($q->num_rows() > 0 ) {
		return $q->result()[0]->total;
		}
		return false;
	}


	public function getOrderProducts($product_id,$feature_id,$price,$o_id)
	{
		$this->db->select('p.id,p.name,pf.image as image,pf.id as feature_id');
		$this->db->from('products p');
		$this->db->where('p.isdeleted',0);
		$this->db->join('product_features pf','pf.p_id = p.id','left');
		$this->db->where('pf.id',$feature_id);
		$q = $this->db->get();
		if ($q->num_rows() > 0 ) {
			$i = 0;
			foreach ($q->result() as $row ) {
				$data[$i] = $row;
				$data[$i]->price = $price;
				$data[$i]->review = $this->checkReview($o_id,$row->id,$row->feature_id);
				$data[$i]->delivered = $this->checkDelivered($o_id,$row->id,$row->feature_id);
				$i++;
			}
			return $data;
		}
		return false;
	}

	public function checkReview($o_id,$p_id,$pf_id)
	{
		$this->db->select('id');
		$this->db->from('order_reviews');
		$this->db->where('o_id',$o_id);
		$this->db->where('p_id',$p_id);
		$this->db->where('pf_id',$pf_id);
		$q = $this->db->get();
		if ($q->num_rows() > 0 ){
			return 0;
		}
		return 1;
	}

	public function checkDelivered($o_id)
	{
		$this->db->select('id');
		$this->db->from('orders');
		$this->db->where('id',$o_id);
		$this->db->where('delivered',0);
		$q = $this->db->get();
		if ($q->num_rows() > 0 ){
			return 0;
		}
		return 1;
	}
	public function getReviews($p_id,$pf_id)
	{
		$this->db->select('*');
		$this->db->from('order_reviews');
		$this->db->where('p_id',$p_id);
		if ($pf_id == 0) {
		$this->db->where('pf_id',$p_id);
		}else{
		$this->db->where('pf_id',$pf_id);
		}
		$this->db->order_by('id','DESC');
		$q = $this->db->get();
		if ($q->num_rows() > 0 ){
			return $q->result();
		}
		return false;
	}


	public function getCartProducts($user_id)

	{

		$this->db->select('p.id,p.u_id,p.b_id,p.pc_id,p.name,p.details,p.tax,p.price,pc.name as category,c.quantity as cartquantity,pf.image as image,pf_id as feature_id');

		$this->db->from('cart c');

		$this->db->join('products p','c.p_id = p.id');

		$this->db->join('product_categories pc','p.pc_id = pc.id');

		$this->db->join('product_features pf','pf.id = c.pf_id');

		$this->db->where('p.isdeleted',0);

		$this->db->where('c.u_id',$user_id);

		// $this->db->where('pf.u_id',$user_id);

		$q = $this->db->get();

		if ($q->num_rows() > 0 ) {

			$i = 0;

			foreach ($q->result() as $row ) {

				$data[$i] = $row;

				$data[$i]->CartItemTotal = $row->price * $row->cartquantity;

				$i++;

			}

			return $data;

		}

		return false;



	}



	public function getWishlistProducts($user_id)

	{

		$this->db->select('p.id,p.u_id,p.b_id,p.pc_id,p.name,p.details,p.tax,p.price,pc.name as category,pf.image as image,pf.id as feature_id');

		$this->db->from('wishlist w');

		$this->db->join('products p','w.p_id = p.id');

		$this->db->join('product_categories pc','p.pc_id = pc.id');

		$this->db->join('product_features pf','pf.id = w.pf_id');

		$this->db->where('p.isdeleted',0);

		$this->db->where('w.u_id',$user_id);

		$q = $this->db->get();

		if ($q->num_rows() > 0 ) {

			foreach ($q->result() as $row ) {

				$data[] = $row;

			}

			return $data;

		}

		return false;



	}



	public function getCounts($user_id)

	{

		$this->db->select('count(id) as totalCart');

		$this->db->from('cart');

		$this->db->where('u_id',$user_id);

		$q = $this->db->get();

		if ($q->num_rows() > 0 ) {

			foreach ($q->result() as $row ) {

				$data['counts'] = $row;

				$data['counts']->totalWishlist = $this->countWishlist($user_id);

			}

			return $data['counts'];

		}

		return false;



	}



	function countWishlist($user_id){

		$this->db->select('count(id) as totalWish');

		$this->db->from('wishlist');

		$this->db->where('u_id',$user_id);

		$q = $this->db->get();

		if ($q->num_rows() > 0 ) {

			return $q->result()[0]->totalWish;

		}

		return false;

	}



	public function getProductDetails($p_id,$pf_id = NULL)

	{

		$this->db->select('p.id as p_id,pf.id as pf_id,p.name,p.details,p.price,pf.image,pc.name as category,pf.color,pf.warranty,b.name as brand');

		$this->db->from('products p');

		$this->db->join('brands b','p.b_id = b.id');

		$this->db->join('product_categories pc','p.pc_id = pc.id','LEFT');

		$this->db->join('product_features pf','p.id = pf.p_id','LEFT');

		$this->db->where('p.id',$p_id);

		$this->db->where('p.isdeleted',0);

		if ($pf_id == null && empty($pf_id)) {
		$this->db->where('pf.p_id',$p_id);
		$this->db->limit(1);
		}else{
		$this->db->where('pf.id',$pf_id);
		$this->db->limit(1);
		}

		$q = $this->db->get();

		if ($q->num_rows() > 0 ) {
			$i = 0;
			foreach ($q->result() as $row ) {
				$data[$i] = $row;
				$data[$i]->variants = $this->getVariants($p_id);
				$i++;
			}
			return $data;
		}

		return false;



	}

	public function getFeaturesColors($id)
	{
		$this->db->select('id,color');
		$this->db->from('product_features');
		$this->db->where('p_id',$id);
		$q = $this->db->get();
		if ($q->num_rows() > 0 ) {
			foreach ($q->result() as $row ) {
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}



	public function checkSession($user_hash)

	{

		$this->db->where('user_hash',$user_hash);

		$q = $this->db->get('session_log');

		if ($q->num_rows() > 0 ) {

			foreach ($q->result() as $row ) {

				$data[] = $row;

			}

			return $data;

		}

		return false;



	}



	public function getBrand($user_id)

	{

		$this->db->select('*');

		$this->db->where('u_id',$user_id);

		$this->db->from('brands');

		$q = $this->db->get();

		if ($q->num_rows() > 0 ) {

			foreach ($q->result() as $row ) {

				$data[] = $row;

			}

			return $data;

		}

		return false;



	}



}



/* End of file Api_model.php */

/* Location: ./application/models/Api_model.php */