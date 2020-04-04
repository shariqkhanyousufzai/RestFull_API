<?php

require APPPATH . 'libraries/REST_Controller.php';

     

class Rashanwalay extends REST_Controller {

    

    /**

     * Get All Data from this method.

     *

     * @return Response

    */

    public function __construct() {

       parent::__construct();

       $this->load->helper('language');

       $this->load->model('Api_model');

       $this->load->library('form_validation');

       $this->load->helper('string');

       $this->load->database();

    }

       

    /**

     * Get All Data from this method.

     *

     * @return Response

    */



 // Creating User 

  public function signup_post() 

  {

    $this->form_validation->set_rules('token', 'Token', 'trim|required|callback_checkToken[token]');

    $this->form_validation->set_rules('cnic', 'CNIC', 'trim|required|is_unique[users.cnic]');

    $this->form_validation->set_rules('name', 'Name', 'trim|required');

    $this->form_validation->set_rules('phone', 'Phone', 'trim|required');

    $data = array(

      'status' => 0,

      'code' => -1,

      'msg' => 'Bad Request',

      'data' => null

    );

    if($this->form_validation->run() == TRUE )

      {
        $identity = "PK-".random_string('alnum', 16);
        $ip_address = $_SERVER['REMOTE_ADDR'];
        $userInfo = array(

          'cnic' => $this->input->post('cnic'),

          'ip_address' => $ip_address,

          'name' => $this->input->post('name'),

          'phone' => $this->input->post('phone'),

          'created' => date("Y-m-d H:i:s"),

          'identity' => $identity,

        ); 

        $createUser = $this->Api_model->createUser($userInfo);

        if ($createUser === TRUE) {

          $data = array(

            'status' => 1,

            'code' => 1,

            'msg' => 'success',

            'data' => 'User created'

          );

        }else{

          $data = array(

            'status' => 0,

            'code' => -2,

            'msg' => 'Something Went Wrong',

            'data' => 'Failed to create user'

          );

        }

      }else

      {

        $data['code'] = -1;

        $data['msg'] = $this->form_validation->error_array();

      }

      $this->response($data, REST_Controller::HTTP_OK);

    }



  // Login User 

  public function login_post()

  {

    $this->form_validation->set_rules('token', 'Token', 'trim|required|callback_checkToken[token]');

    $this->form_validation->set_rules('cnic', 'CNIC', 'trim|required');

    $data = array(

      'status' => 0,

      'code' => -1,

      'msg' => 'Bad Request',

      'data' => null

    );

    if($this->form_validation->run() == TRUE )

      {

        $cnic = $this->input->post('cnic');

        $checkUser = $this->Api_model->checkUserExist($cnic);

        if (isset($checkUser) && !empty($checkUser)) {

          $data = array(

            'status' => 1,

            'code' => 1,

            'msg' => 'success',

            'data' => $checkUser

          );

        }else{

          $data = array(

            'status' => 0,

            'code' => -1,

            'msg' => 'Invalid CNIC Number',

            'data' => null

          );

        }

      }else

      {

        $data['msg'] = validation_errors();

      }

      $this->response($data, REST_Controller::HTTP_OK);

    }

  // Login User End 



  //GET PRODUCTS 

  public function productsfilter_get()

  {

    // $token = $_SERVER['HTTP_TOKEN'];

    $category_id = $this->input->get('category_id');

    $min = $this->input->get('minprice');

    $max = $this->input->get('maxprice');

    $brand = $this->input->get('brand');

    $type = $this->input->get('type');

    $color = $this->input->get('color');

    $material = $this->input->get('material');

    $data = array(

      'status' => 0,

      'code' => -1,

      'msg' => 'Bad Request',

      'data' => null

    );

    $getProductsFilter = $this->Api_model->getProductsFilter($category_id,$min,$max,$brand,$type,$color,$material);

    if (isset($getProductsFilter)) {

      $data = array(

        'status' => 1,

        'code' => 1,

        'msg' => 'success',

        'data' => $getProductsFilter

      );

    }else{

      $data = array(

        'status' => 1,

        'code' => 1,

        'msg' => 'success',

        'data' => null

      );

    }

      $this->response($data, REST_Controller::HTTP_OK);

  }

  //GET PRODUCTS  END

  //GET PRODUCTS 

  public function products_get()

  {

    // $token = $_SERVER['HTTP_TOKEN'];


    $data = array(

      'status' => 0,

      'code' => -1,

      'msg' => 'Bad Request',

      'data' => null

    );

    $getProducts = $this->Api_model->getProducts();

    if (isset($getProducts)) {

      $data = array(

        'status' => 1,

        'code' => 1,

        'msg' => 'success',

        'data' => $getProducts

      );

    }else{

      $data = array(

        'status' => 1,

        'code' => 1,

        'msg' => 'success',

        'data' => null

      );

    }

      $this->response($data, REST_Controller::HTTP_OK);

  }

  //GET PRODUCTS  END



  //GET FEATURES Variants End

    public function variants_get()

  {

    $p_id = $this->input->get('p_id');

    $data = array(

      'status' => 0,

      'code' => -1,

      'msg' => 'Bad Request',

      'data' => null

    );

    $getVariants = $this->Api_model->getVariants($p_id);

    if (isset($getVariants)) {

      $data = array(

        'status' => 1,

        'code' => 1,

        'msg' => 'success',

        'data' => $getVariants

      );

    }else{

      $data = array(

        'status' => 1,

        'code' => 1,

        'msg' => 'success',

        'data' => null

      );

    }

      $this->response($data, REST_Controller::HTTP_OK);

  }

  //GET FEATURES Variants



  //GET Cart PRODUCTS 

  public function cartProducts_get()

  {

    // $token = $_SERVER['HTTP_TOKEN'];

    $user_id = $this->input->get('user_id');

    $data = array(

      'status' => 0,

      'code' => -1,

      'msg' => 'Bad Request',

      'data' => null

    );

    $getCartProducts = $this->Api_model->getCartProducts($user_id);

    if (isset($getCartProducts)) {

      $data = array(

        'status' => 1,

        'code' => 1,

        'msg' => 'success',

        'data' => $getCartProducts

      );

    }else{

      $data = array(

        'status' => 1,

        'code' => 1,

        'msg' => 'success',

        'data' => null

      );

    }

      $this->response($data, REST_Controller::HTTP_OK);

  }

  //GET Cart PRODUCTS  END



  //GET Wishlist PRODUCTS 

  public function wishlistProducts_get()

  {

    // $token = $_SERVER['HTTP_TOKEN'];

    $user_id = $this->input->get('user_id');

    $data = array(

      'status' => 0,

      'code' => -1,

      'msg' => 'Bad Request',

      'data' => null

    );

    $getWishlistProducts = $this->Api_model->getWishlistProducts($user_id);

    if (isset($getWishlistProducts)) {

      $data = array(

        'status' => 1,

        'code' => 1,

        'msg' => 'success',

        'data' => $getWishlistProducts

      );

    }else{

      $data = array(

        'status' => 1,

        'code' => 1,

        'msg' => 'success',

        'data' => null

      );

    }

      $this->response($data, REST_Controller::HTTP_OK);

  }

  //GET Cart PRODUCTS  END

  //GET Reviews

  public function reviews_get()
  {

    // $token = $_SERVER['HTTP_TOKEN'];

    $p_id = $this->input->get('p_id');
    $pf_id = $this->input->get('pf_id');

    $data = array(

      'status' => 0,

      'code' => -1,

      'msg' => 'Bad Request',

      'data' => null

    );

    $getReviews = $this->Api_model->getReviews($p_id,$pf_id);

    if (isset($getReviews)) {

      $data = array(

        'status' => 1,

        'code' => 1,

        'msg' => 'success',

        'data' => $getReviews

      );

    }else{

      $data = array(

        'status' => 1,

        'code' => 1,

        'msg' => 'success',

        'data' => null

      );

    }

      $this->response($data, REST_Controller::HTTP_OK);

  }

  //GET Reviews End


  //GET Counts

  public function counts_get()

  {

    // $token = $_SERVER['HTTP_TOKEN'];

    $user_id = $this->input->get('user_id');

    $data = array(

      'status' => 0,

      'code' => -1,

      'msg' => 'Bad Request',

      'data' => null

    );

    $getCounts = $this->Api_model->getCounts($user_id);

    if (isset($getCounts)) {

      $data = array(

        'status' => 1,

        'code' => 1,

        'msg' => 'success',

        'data' => $getCounts

      );

    }else{

      $data = array(

        'status' => 1,

        'code' => 1,

        'msg' => 'success',

        'data' => null

      );

    }

      $this->response($data, REST_Controller::HTTP_OK);

  }

  //GET Counts End



   //GET PRODUCTS Details

  public function productsdetails_get()

  {

    // $token = $_SERVER['HTTP_TOKEN'];

    $p_id = $this->input->get('p_id');

    $pf_id = $this->input->get('pf_id');

    $data = array(

      'status' => 0,

      'code' => -1,

      'msg' => 'Bad Request',

      'data' => null

    );

      $getProductDetails = $this->Api_model->getProductDetails($p_id,$pf_id);

      if (isset($getProductDetails)) {

        $data = array(

          'status' => 1,

          'code' => 1,

          'msg' => 'success',

          'data' => $getProductDetails

        );

      }else{

        $data = array(

          'status' => 1,

          'code' => 1,

          'msg' => 'success',

          'data' => null

        );

      }

      $this->response($data, REST_Controller::HTTP_OK);

    }

  //GET PRODUCTS Details END

  public function productsfeatures_get()

  {

    // $token = $_SERVER['HTTP_TOKEN'];

    $p_id = $this->input->get('p_id');

    $data = array(

      'status' => 0,

      'code' => -1,

      'msg' => 'Bad Request',

      'data' => null

    );

      $getFeaturesColors = $this->Api_model->getFeaturesColors($p_id);

      if (isset($getFeaturesColors)) {

        $data = array(

          'status' => 1,

          'code' => 1,

          'msg' => 'success',

          'data' => $getFeaturesColors

        );

      }else{

        $data = array(

          'status' => 1,

          'code' => 1,

          'msg' => 'success',

          'data' => null

        );

      }

      $this->response($data, REST_Controller::HTTP_OK);

    }



  // Add to Cart

  public function cart_post()

  {

    $this->form_validation->set_rules('token', 'Token', 'trim|required|callback_checkToken[token]');

    $this->form_validation->set_rules('product_id', 'Product Id', 'trim|required');

    $this->form_validation->set_rules('user_id', 'User Id', 'trim|required');

    $this->form_validation->set_rules('feature_id', 'Feature Id', 'trim|required');

    $data = array(

      'status' => 0,

      'code' => -1,

      'msg' => 'Bad Request',

      'data' => null

    );

    if($this->form_validation->run() == TRUE )

      {

         $product_id = $this->input->post('product_id');

         $user_id = $this->input->post('user_id');

         $feature_id = $this->input->post('feature_id');

         $checkCart = $this->Api_model->checkCart($product_id,$user_id,$feature_id);

          if ($checkCart) {

             $data = array(

                'status' => 1,

                'code' => 1,

                'msg' => 'success',

                'data' => "Already In Cart"

              );

          }else{

            $cartInfo = array(

            'p_id' => $product_id,

            'u_id' => $user_id,

            'pf_id' => $feature_id,

            'created' => date('Y-m-d H:i:s'),

            );

            $createCart = $this->Api_model->createCart($cartInfo);

            if ($createCart === TRUE) {

              $data = array(

                'status' => 1,

                'code' => 1,

                'msg' => 'success',

                'data' => "Added To Cart"

              );

            }else{

              $data = array(

                'status' => 0,

                'code' => -2,

                'msg' => 'Something Went Wrong',

                'data' => 'Failed Add To Cart'

              );

            }

          }

      }else

      {

        $data['code'] = -1;

        // $data['msg'] = strip_tags(validation_errors());

        $data['msg'] = $this->form_validation->error_array();

      }

      $this->response($data, REST_Controller::HTTP_OK);

    }

    // Add to Cart End



    // Add to Wishlist

  public function wishlist_post()

  {

    $this->form_validation->set_rules('token', 'Token', 'trim|required|callback_checkToken[token]');

    $this->form_validation->set_rules('product_id', 'Product Id', 'trim|required');

    $this->form_validation->set_rules('user_id', 'User Id', 'trim|required');

    $this->form_validation->set_rules('feature_id', 'Feature Id', 'trim|required');

    $data = array(

      'status' => 0,

      'code' => -1,

      'msg' => 'Bad Request',

      'data' => null

    );

    if($this->form_validation->run() == TRUE )

      {

         $product_id = $this->input->post('product_id');

         $user_id = $this->input->post('user_id');

         $feature_id = $this->input->post('feature_id');

         $checkWishlist = $this->Api_model->checkWishlist($product_id,$user_id,$feature_id);

          if ($checkWishlist) {

             $data = array(

                'status' => 1,

                'code' => 1,

                'msg' => 'success',

                'data' => "Already In Wishlist"

              );

          }else{

            $wishlistInfo = array(

            'p_id' => $product_id,

            'u_id' => $user_id,

            'pf_id' => $feature_id,

            'created' => date('Y-m-d H:i:s'),

            );

            $createWishlist = $this->Api_model->createWishlist($wishlistInfo);

            if ($createWishlist === TRUE) {

              $data = array(

                'status' => 1,

                'code' => 1,

                'msg' => 'success',

                'data' => "Added To Wishlist"

              );

            }else{

              $data = array(

                'status' => 0,

                'code' => -2,

                'msg' => 'Something Went Wrong',

                'data' => 'Failed Add To Wishlist'

              );

            }

          }

      }else

      {

        $data['code'] = -1;

        // $data['msg'] = strip_tags(validation_errors());

        $data['msg'] = $this->form_validation->error_array();

      }

      $this->response($data, REST_Controller::HTTP_OK);

    }

    // Add to Wishlist End
    // Get Order Details
    public function getordersdetails_post()
    {
      $this->form_validation->set_rules('token', 'Token', 'trim|required|callback_checkToken[token]');
      $this->form_validation->set_rules('o_id', 'Users', 'trim|required');
      $data = array(

        'status' => 0,

        'code' => -1,

        'msg' => 'Bad Request',

        'data' => null

      );
      if($this->form_validation->run() == TRUE )

      {
      	$o_id = $this->input->post('o_id');
      	$dataOrders = Array();
        $getOrdersDetails = $this->Api_model->getOrdersDetails($o_id);
        $getTotal = $this->Api_model->getTotal($o_id);
        $dataOrders['items'] = $getOrdersDetails;
        $dataOrders['total'] = $getTotal;
        
        // Getting ORDER Details 

        if (isset($dataOrders)) {

              $data = array(

                'status' => 1,

                'code' => 1,

                'msg' => 'success',

                'data' => $dataOrders,

              );

            }else{

              $data = array(

                'status' => 0,

                'code' => -2,

                'msg' => 'Something Went Wrong',

                'data' => 'No Order Found'

              );

            }

        }else
        {
          $data['code'] = -1;
          // $data['msg'] = strip_tags(validation_errors());
          $data['msg'] = $this->form_validation->error_array();
         }
         $this->response($data, REST_Controller::HTTP_OK);
    }
    // Get Order Details End
    // Get Orders

    public function getorders_post()

    {

      $this->form_validation->set_rules('token', 'Token', 'trim|required|callback_checkToken[token]');

      $this->form_validation->set_rules('user_id', 'Users', 'trim|required');

      $data = array(

        'status' => 0,

        'code' => -1,

        'msg' => 'Bad Request',

        'data' => null

      );

      if($this->form_validation->run() == TRUE )

      {

        $user_id = $this->input->post('user_id');

        $getOrders = $this->Api_model->getOrders($user_id);

        // Adding Details End

        if (isset($getOrders)) {

              $data = array(

                'status' => 1,

                'code' => 1,

                'msg' => 'success',

                'data' => $getOrders

              );

            }else{

              $data = array(

                'status' => 0,

                'code' => -2,

                'msg' => 'Something Went Wrong',

                'data' => 'No Order Found'

              );

            }

        }else

        {

          $data['code'] = -1;

          // $data['msg'] = strip_tags(validation_errors());

          $data['msg'] = $this->form_validation->error_array();
         }
         $this->response($data, REST_Controller::HTTP_OK);

      }

    // Add Order

    public function placeorder_post()

    {


      $this->form_validation->set_rules('token', 'Token', 'trim|required|callback_checkToken[token]');

      $this->form_validation->set_rules('name', 'Name', 'trim|required');

      $this->form_validation->set_rules('address', 'Address', 'trim|required');

      $this->form_validation->set_rules('phone', 'Phone', 'trim|required');

      $this->form_validation->set_rules('total', 'Total', 'trim|required');

      $data = array(

      'status' => 0,

      'code' => -1,

      'msg' => 'Bad Request',

      'data' => null

    );


    if($this->form_validation->run() == TRUE )

      {


        $name = $this->input->post('name');

        $address = $this->input->post('address');

        $phone = $this->input->post('phone');

        $user_id = $this->input->post('user_id');

        $payment_type = $this->input->post('payment_type');

        $total = $this->input->post('total');

        $randomOrderNo = "AR-".random_string('alnum', 5);

        $date = date('Y-m-d H:i:s');

        $orderData = array(

          'u_id' => $user_id,

          'name' => $name,

          'total' => $total,

          'order_no' => $randomOrderNo,

          'address' => $address,

          'phone' => $phone,

          'date' => $date,

        );

        $addOrder = $this->Api_model->addOrder($orderData);

       
        // Adding Payment

        $paymentData = array(

          'o_id' => $addOrder,

          'pt_id' => $payment_type,

          'date' => $date,

        );

        $payment = $this->Api_model->addPayments($paymentData);

        // Adding Payment End

        // Adding Details

        $getAllCartDetails = $this->Api_model->getAllCartDetails($user_id);
        $time = date("Y-m-d H:i:s");
        foreach ($getAllCartDetails as $getAllCartDetail) {
        	$cartData = array(
        		'o_id' => $addOrder,
        		'p_id' => $getAllCartDetail->p_id,
        		'u_id' => $getAllCartDetail->u_id,
        		'pf_id' => $getAllCartDetail->pf_id,
        		'quantity' => $getAllCartDetail->quantity,
        		'price' => $getAllCartDetail->price,
        		'created' => $time,
        	);
        	$this->Api_model->createCartDetails($cartData);
        }


        $deleteCartItems = $this->Api_model->deleteCartItems($user_id);
        // Adding Details End

        if (isset($addOrder)) {

              $data = array(

                'status' => 1,

                'code' => 1,

                'msg' => 'success',

                'data' => "Order Placed"

              );

            }else{

              $data = array(

                'status' => 0,

                'code' => -2,

                'msg' => 'Something Went Wrong',

                'data' => 'Failed To Place Order'

              );

            }

        }else

        {

          $data['code'] = -1;

          // $data['msg'] = strip_tags(validation_errors());

          $data['msg'] = $this->form_validation->error_array();

        }

        $this->response($data, REST_Controller::HTTP_OK);

    }

    // Add Order End

    // Remove From Cart 

    public function cartremove_post()

  {

    $this->form_validation->set_rules('token', 'Token', 'trim|required|callback_checkToken[token]');

    $this->form_validation->set_rules('product_id', 'Product Id', 'trim|required');

    $this->form_validation->set_rules('user_id', 'User Id', 'trim|required');

    $this->form_validation->set_rules('feature_id', 'Feature Id', 'trim|required');

    $data = array(

      'status' => 0,

      'code' => -1,

      'msg' => 'Bad Request',

      'data' => null

    );

    if($this->form_validation->run() == TRUE )

      {

         $product_id = $this->input->post('product_id');

         $user_id = $this->input->post('user_id');

         $feature_id = $this->input->post('feature_id');

         $removeCart = $this->Api_model->removeCart($product_id,$user_id,$feature_id);

          if ($removeCart === TRUE) {

            $data = array(

              'status' => 1,

              'code' => 1,

              'msg' => 'success',

              'data' => "Removed From Cart"

            );

          }else{

            $data = array(

              'status' => 0,

              'code' => -2,

              'msg' => 'Something Went Wrong',

              'data' => 'Failed To Removed From Cart'

            );

          }

      }else

      {

        $data['code'] = -1;

        // $data['msg'] = strip_tags(validation_errors());

        $data['msg'] = $this->form_validation->error_array();

      }

      $this->response($data, REST_Controller::HTTP_OK);

    }

    // Remove From Cart End 

    // Add Review

    public function review_post()

  {

    $this->form_validation->set_rules('token', 'Token', 'trim|required|callback_checkToken[token]');

    $this->form_validation->set_rules('p_id', 'Product Id', 'trim|required');

    $this->form_validation->set_rules('u_id', 'User Id', 'trim|required');

    $this->form_validation->set_rules('o_id', 'User Id', 'trim|required');

    $this->form_validation->set_rules('pf_id', 'Feature Id', 'trim|required');

    $this->form_validation->set_rules('name', 'Feature Id', 'trim|required');

    $this->form_validation->set_rules('review', 'Feature Id', 'trim|required');

    $data = array(

      'status' => 0,

      'code' => -1,

      'msg' => 'Bad Request',

      'data' => null

    );

    if($this->form_validation->run() == TRUE )

      {

         $p_id = $this->input->post('p_id');
         $u_id = $this->input->post('u_id');
         $o_id = $this->input->post('o_id');
         $pf_id = $this->input->post('pf_id');
         $name = $this->input->post('name');
         $review = $this->input->post('review');

         $data = array(
          'p_id' => $p_id,
          'u_id' => $u_id,
          'o_id' => $o_id,
          'pf_id' => $pf_id,
          'name' => $name,
          'review' => $review
         );

         $addReview = $this->Api_model->addReview($data);

          if ($addReview === TRUE) {

            $data = array(

              'status' => 1,

              'code' => 1,

              'msg' => 'success',

              'data' => "Reveiw Has Been Added"

            );

          }else{

            $data = array(

              'status' => 0,

              'code' => -2,

              'msg' => 'Something Went Wrong',

              'data' => 'Reveiw Has Not Been Added'

            );

          }

      }else

      {

        $data['code'] = -1;

        // $data['msg'] = strip_tags(validation_errors());

        $data['msg'] = $this->form_validation->error_array();

      }

      $this->response($data, REST_Controller::HTTP_OK);

    }

    // Remove From Cart End 

       // Add Review

    public function help_post()

  {

    $this->form_validation->set_rules('token', 'Token', 'trim|required|callback_checkToken[token]');

    $this->form_validation->set_rules('u_id', 'User Id', 'trim|required');

    $this->form_validation->set_rules('name', 'Name', 'trim|required');

    $this->form_validation->set_rules('phone', 'Phone', 'trim|required');

    $this->form_validation->set_rules('email', 'Email', 'trim|required');

    $this->form_validation->set_rules('message', 'Message', 'trim|required');
    
    $this->form_validation->set_rules('subject', 'Message', 'trim|required');

    $data = array(

      'status' => 0,

      'code' => -1,

      'msg' => 'Bad Request',

      'data' => null

    );

    if($this->form_validation->run() == TRUE )

      {

         $u_id = $this->input->post('u_id');
         $name = $this->input->post('name');
         $phone = $this->input->post('phone');
         $email = $this->input->post('email');
         $message = $this->input->post('message');
         $subject = $this->input->post('subject');

         $data = array(
          'u_id' => $u_id,
          'name' => $name,
          'phone' => $phone,
          'email' => $email,
          'message' => $message,
          'subject' => $subject,
         );

         $addHelp = $this->Api_model->addHelp($data);

          if ($addHelp === TRUE) {

            $data = array(

              'status' => 1,

              'code' => 1,

              'msg' => 'success',

              'data' => "Thankyou For Your Query"

            );

          }else{

            $data = array(

              'status' => 0,

              'code' => -2,

              'msg' => 'Something Went Wrong',

              'data' => 'Failed To Send Your Query'

            );

          }

      }else

      {

        $data['code'] = -1;

        // $data['msg'] = strip_tags(validation_errors());

        $data['msg'] = $this->form_validation->error_array();

      }

      $this->response($data, REST_Controller::HTTP_OK);

    }

    // Remove From Cart End 

     // Remove From Wishlist 

    public function wishremove_post()

  {

    $this->form_validation->set_rules('token', 'Token', 'trim|required|callback_checkToken[token]');

    $this->form_validation->set_rules('product_id', 'Product Id', 'trim|required');

    $this->form_validation->set_rules('user_id', 'User Id', 'trim|required');

    $this->form_validation->set_rules('feature_id', 'Feature Id', 'trim|required');

    $data = array(

      'status' => 0,

      'code' => -1,

      'msg' => 'Bad Request',

      'data' => null

    );

    if($this->form_validation->run() == TRUE )

      {

         $product_id = $this->input->post('product_id');

         $user_id = $this->input->post('user_id');

         $feature_id = $this->input->post('feature_id');

         $removeWish = $this->Api_model->removeWish($product_id,$user_id,$feature_id);

          if ($removeWish === TRUE) {

            $data = array(

              'status' => 1,

              'code' => 1,

              'msg' => 'success',

              'data' => "Removed From Wishlist"

            );

          }else{

            $data = array(

              'status' => 0,

              'code' => -2,

              'msg' => 'Something Went Wrong',

              'data' => 'Failed To Removed From Wishlist'

            );

          }

      }else

      {

        $data['code'] = -1;

        // $data['msg'] = strip_tags(validation_errors());

        $data['msg'] = $this->form_validation->error_array();

      }

      $this->response($data, REST_Controller::HTTP_OK);

    }

    // Remove From Cart End 

    

    // Add Quantity

     public function quantity_post()

  {

    $this->form_validation->set_rules('token', 'Token', 'trim|required|callback_checkToken[token]');

    $this->form_validation->set_rules('product_id', 'Product Id', 'trim|required');

    $this->form_validation->set_rules('user_id', 'User Id', 'trim|required');

    $this->form_validation->set_rules('quantity', 'Quantity', 'trim|required');

    $data = array(

      'status' => 0,

      'code' => -1,

      'msg' => 'Bad Request',

      'data' => null

    );

    if($this->form_validation->run() == TRUE )

      {

         $product_id = $this->input->post('product_id');

         $user_id = $this->input->post('user_id');

         $quantity = $this->input->post('quantity');

         $updateCartQty = $this->Api_model->updateCartQty($product_id,$user_id,$quantity);

            if ($updateCartQty === TRUE) {

              $data = array(

                'status' => 1,

                'code' => 1,

                'msg' => 'success',

                'data' => "Quantity Updated"

              );

            }else{

              $data = array(

                'status' => 0,

                'code' => -2,

                'msg' => 'Something Went Wrong',

                'data' => 'Failed Quantity Updated'

              );

            }

      }else

      {

        $data['code'] = -1;

        // $data['msg'] = strip_tags(validation_errors());

        $data['msg'] = $this->form_validation->error_array();

      }

      $this->response($data, REST_Controller::HTTP_OK);

    }

    // Add Quantity End



    // Checking The Token If Valid

    public function checkToken($token)

    {

      if ($token == SECRET_TOKEN) {

        return true;

      }

      return false;

    }

    // Checking The Token If Valid End



    //Validation For User Password

    public function _userPassword($password) {

      if (preg_match('/^(?=.*[\w\d]).+/', $password ) ) 

      {

        return TRUE;

      } 

      else 

      {

        $this->form_validation->set_message('_userPassword', 'Password Must Contain Atleast One Alphabet');

        return FALSE;

      }

    }

    //Validation For User Password End

      

}