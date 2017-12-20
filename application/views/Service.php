<?php

require APPPATH.'/libraries/REST_Controller.php';



/**

 * Service

 * 

 * @package FTP

 * @author Punitha

 * @copyright 2017

 * @version 1

 * @access public

 */

class Service extends REST_Controller

{

       protected $profile_url = '';

        function __construct()

        {

            // Construct our parent class

            parent::__construct();

            

            $key  = $this->get('X-APP-KEY');

            $this->load->library('email');
            
            $config['charset']  = 'UTF-8';
            $config['wordwrap'] = TRUE;
            $config['mailtype'] = 'html';
            $config['newline']  = "\r\n";
            $this->email->initialize($config);

            $this->load->model(array("user_model","login_model","address_model","credit_card_model","checking_account_model","paypal_model","jobs_model","rating_model"));

            

        }

        

         //register

         function user_register_post() {

            

            if(!$this->post('email')){

    			return $this->response(array('status' => 'error','msg' => 'Required fields missing in your request','error_code' => "1"), 404);

    		}

             

            $username       = $this->post('username');

            $password       = $this->post('password');

            $email          = $this->post('email');

            $firstname      = $this->post('firstname');

            $lastname       = $this->post('lastname');

            $address        = $this->post('address');

            $city           = $this->post('city');

            $state          = $this->post('state');

            $zipcode        = $this->post('zipcode');

            $usertype       = $this->post('usertype');

            $devicetoken    = $this->post('devicetoken');

            

             if(!empty($username)) {

                  //username check if already exists or not

                  $user_res = $this->user_model->check_unique(array("username" => $username));

                  if(count($user_res)>0) {

                    return $this->response(array('status' => "error",'msg' => 'Username already in use.','error_code' => "2"), 404);

                  }

             } 

             

             //if(!empty($email)) {

//                  //Email check if already exists or not

//                  $email_res = $this->user_model->check_unique(array("email" => $email));

//                  if(count($email_res)>0) {

//                    return $this->response(array('status' => "error",'msg' => 'Email already exists.','error_code' => 2), 404);

//                  }

//             }

             

            

            $ins_data = array();

            $ins_data['username']       = $username;

            $ins_data['password']       = md5($password);

            $ins_data['email']          = $email;

            $ins_data['firstname']      = $firstname;

            $ins_data['lastname']       = $lastname;

            $ins_data['address']        = $address;

            $ins_data['city']           = $city;

            $ins_data['state']          = $state;

            $ins_data['zipcode']        = $zipcode;

            $ins_data['usertype']       = $usertype;

            $ins_data['devicetoken']    = $devicetoken;

                

            $user_id = $this->user_model->insert($ins_data,"user");   

            if(!empty($user_id)){

                $res = $email_res = $this->user_model->check_unique(array('id' => $user_id));

                return $this->response(array('status' =>'success','request_type' => 'create_account','user_data' => $res), 200);

            }    

            else

            {

                return $this->response(array('status' =>'error','request_type' => 'create_account', 'msg' => "User doesn't create!" ,'error_code' => "5"), 404);

            }

        }

        

        //check if email id already exists or not

        function user_email_check_post(){

            if(!$this->post('email')){

    			return $this->response(array('status' => 'error','msg' => 'Required fields missing in your request','error_code' => "1"), 404);

    		}

            $email = $this->post('email');

            

            if(!empty($email)) {

                  //Email check if already exists or not

                  $email_res = $this->user_model->check_unique(array("email" => $email));

                  if(count($email_res)>0) {

                    return $this->response(array('status' => "error",'msg' => 'Email already exists.','error_code' => "2"), 404);

                  }

             }

             

            return $this->response(array('status' => "success"), 200);

        }

        

        //user email update

        function user_email_update_post() {

            

            if(!$this->post('user_id') && !$this->post('email')){

    			return $this->response(array('status' => 'error','msg' => 'Required fields missing in your request','error_code' => "1"), 404);

    		}

             

            $email          = $this->post('email');

            $user_id        = $this->post('user_id');

            $res            = $this->user_model->check_unique(array('id' => $user_id));

            

             if(!empty($email)) {

                  //Email check if already exists or not

                  $email_res = $this->user_model->check_unique(array("email" => $email, 'id!=' => $user_id));

                  if(count($email_res)>0) {

                    return $this->response(array('status' => "error",'msg' => 'Email already exist.','error_code' => "2"), 404);

                  }

             } 

            

            

            if(count($res)>0){   

                $ins_data           = array();

                $ins_data['email']  = $email;  

                $affected           = $this->user_model->update(array("id" => $user_id),$ins_data,"user");

                $res = $this->user_model->check_unique(array('id' => $user_id));

                return $this->response(array('status' =>'success','request_type' => 'update_email','user_data' => $res), 200);

            }

            else

            {

                return $this->response(array('status' =>'error','request_type' => 'update_email', 'msg' => 'Email not updated', 'error_code' => "3"), 404);

            } 

        }

        

        //login

        function login_post()

        {

            if(!$this->post('username') & !$this->post('password') & !$this->post('devicetoken')){

    			return $this->response(array('status' => 'error','msg' => 'Required fields missing in your request','error_code' => "1"), 404);

    		}

      

            $username       = $this->post('username'); 

            $password       = $this->post('password');

            $devicetoken    = $this->post('devicetoken');

            $type           = $this->post("type");

            

            $result = $this->login_model->app_login($username,$password,$type);

           //print_r($result);exit;

            if(!empty($result)){

                //update user device token

                $device_token_update = $this->user_model->update(array("id" => $result['id']),array("devicetoken" => $devicetoken),"user");

                return $this->response(array('status' =>'success','request_type' => 'user_login','user_data' => $result), 200);

            }

            else

            {

                return $this->response(array('status' =>'error','request_type' => 'user_login', 'msg' => 'Invalid credentials', 'error_code' => "4"), 404);

            }

        }

        

        //user authentication update (username/password)

        function user_authentication_update_post()

        {

            if(!$this->post('user_id')){

    			return $this->response(array('status' => 'error','msg' => 'Required fields missing in your request','error_code' => "1"), 404);

    		}

            

            $username  = $this->post('username');

            $password  = $this->post('password');

            $user_id   = $this->post('user_id');

            

            $res       = $this->user_model->check_unique(array('id' => $user_id));

            $username  = (!empty($username))?$username:$res['username'];

            $password  = (!empty($password))?md5($password):$res['password'];

            

            if(!empty($username)) {

                  //Username check if already exists or not

                  $user_res = $this->user_model->check_unique(array("username" => $username, 'id!=' => $user_id));

                  if(count($user_res)>0) {

                    return $this->response(array('status' => "error",'msg' => 'Username already exist.','error_code' => "2"), 404);

                  }

             }

            

            if(count($res)>0){

                $up = array();

                $up['username'] = $username;

                $up['password'] = $password;

                $update = $this->user_model->update(array("id" => $res['id']),$up,"user");

                return $this->response(array('status' =>'success','request_type' => 'user_authenticate_update','user_data' => get_current_user_dt($user_id)), 200);

            }

            else

            {

                return $this->response(array('status' =>'error','request_type' => 'user_authenticate_update', 'msg' => 'Invalid User!', 'error_code' => "6"), 404);

            }

        }

        

        //add user address

        function user_address_post()

        {

            if(!$this->post('user_id')){

    			return $this->response(array('status' => 'error','msg' => 'Required fields missing in your request','error_code' => "1"), 404);

    		} 

            

            $address1       = $this->post('address1');

            $address2       = $this->post('address2');

            $city           = $this->post('city');

            $state          = $this->post('state');

            $zipcode        = $this->post('zipcode');

            $user_id        = $this->post('user_id');

            

            $ins_data = array();

            $ins_data['user_id']  = $user_id;

            $ins_data['address1'] = $address1;

            $ins_data['address2'] = $address2;

            $ins_data['city']     = $city;

            $ins_data['state']    = $state;

            $ins_data['zipcode']  = $zipcode;

            $address_id           = $this->address_model->insert($ins_data);

            if(!empty($address_id)){

                return $this->response(array('status' =>'success','request_type' => 'user_address_create','address_id' => $address_id), 200);

            }    

            else

            {

                return $this->response(array('status' =>'error','request_type' => 'user_address_create', 'msg' => "Address doesn't create!" ,'error_code' => "5"), 404);

            }

        } 

        

        //get user update email

        function get_user_email_post()

        {

            if(!$this->post('user_id')){

    			return $this->response(array('status' => 'error','msg' => 'Required fields missing in your request','error_code' => "1"), 404);

    		}

            

            $user_id   = $this->post('user_id');

            $res       = $this->user_model->check_unique(array('id' => $user_id));

            

            if(count($res)>0){

                return $this->response(array('status' =>'success','request_type' => 'get_user_email','email' => $res['email']), 200);

            }

            else

            {

                return $this->response(array('status' =>'error','request_type' => 'get_user_email', 'msg' => "User doesn't exists!" ,'error_code' => "6"), 404);

            }

        }

        

        //get user update username

        function get_username_post()

        {

            if(!$this->post('user_id')){

    			return $this->response(array('status' => 'error','msg' => 'Required fields missing in your request','error_code' => "1"), 404);

    		}

            

            $user_id   = $this->post('user_id');

            $res       = $this->user_model->check_unique(array('id' => $user_id));

            

            if(count($res)>0){

                return $this->response(array('status' =>'success','request_type' => 'get_username','username' => $res['username']), 200);

            }

            else

            {

                return $this->response(array('status' =>'error','request_type' => 'get_username', 'msg' => "User doesn't exists!" ,'error_code' => "6"), 404);

            }

        }

        

        //add credit card

        function add_credit_card_post()

        {

            if(!$this->post('name')){

    			return $this->response(array('status' => 'error','msg' => 'Required fields missing in your request','error_code' => "1"), 404);

    		} 

            

            $name           = $this->post('name');

            $card_number    = $this->post('card_number');

            $card_type      = $this->post('card_type');

            $exp_month      = $this->post('exp_month');

            $exp_year       = $this->post('exp_year');

            $cvv            = $this->post('cvv');

            $employer_id    = $this->post('employer_id');

            $address_card   = $this->post('address_card');

            $state          = $this->post('state');

            $city           = $this->post('city');

            $zipcode        = $this->post('zipcode');

            $default_card   = $this->post('default_card');

            $usertype       = $this->post('usertype');

            

             if(!empty($card_number)) {

                  //Card number check if already exists or not

                  $card_res = $this->credit_card_model->check_unique(array("card_number" => $card_number));

                  if(count($card_res)>0) {

                    return $this->response(array('status' => "error",'msg' => 'Card number already exists.','error_code' => "2"), 404);

                  }

             }

            

            $ins_data = array();

            $ins_data['name']        = $name;

            $ins_data['card_number'] = $card_number;

            $ins_data['card_type']   = $card_type;

            $ins_data['exp_month']   = $exp_month;

            $ins_data['exp_year']    = $exp_year;

            $ins_data['cvv']         = $cvv;

            $ins_data['address_card']= $address_card;

            $ins_data['state']       = $state;

            $ins_data['city']        = $city;

            $ins_data['zipcode']     = $zipcode;

            $ins_data['employer_id'] = $employer_id;

            $ins_data['default_card']= $default_card;

            $ins_data['created_date']= date("Y-m-d H:i:s");

            $ins_data['usertype']    = $usertype;

            $card_id                 = $this->credit_card_model->insert($ins_data);

            if(!empty($card_id)){

                return $this->response(array('status' =>'success','request_type' => 'add_credit_card','card_id' => $card_id), 200);

            }    

            else

            {

                return $this->response(array('status' =>'error','request_type' => 'add_credit_card', 'msg' => "Card details doesn't create!" ,'error_code' => "5"), 404);

            }

        }

        

        //user credit card update

        function credit_card_update_post() {

            

            if(!$this->post('card_id') && !$this->post('card_number')){

    			return $this->response(array('status' => 'error','msg' => 'Required fields missing in your request','error_code' => "1"), 404);

    		}

             

                $name           = $this->post('name');

                $card_number    = $this->post('card_number');

                $card_type      = $this->post('card_type');

                $exp_month      = $this->post('exp_month');

                $exp_year       = $this->post('exp_year');

                $cvv            = $this->post('cvv');

                $employer_id    = $this->post('employer_id');

                $address_card   = $this->post('address_card');

                $state          = $this->post('state');

                $city           = $this->post('city');

                $zipcode        = $this->post('zipcode');

                $card_id        = $this->post('card_id');

                $default_card   = $this->post('default_card');

                $usertype       = $this->post('usertype');

                

                //get card details

                $cd_res = $this->credit_card_model->check_unique(array('id' => $card_id));

                

                $ins_data = array();

                $ins_data['name']        = (!empty($name))?$name:$cd_res['name'];

                $ins_data['card_number'] = (!empty($card_number))?$card_number:$cd_res['card_number'];

                $ins_data['card_type']   = (!empty($card_type))?$card_type:$cd_res['card_type'];

                $ins_data['exp_month']   = (!empty($exp_month))?$exp_month:$cd_res['exp_month'];

                $ins_data['exp_year']    = (!empty($exp_year))?$exp_year:$cd_res['exp_year'];

                $ins_data['cvv']         = (!empty($cvv))?$cvv:$cd_res['cvv'];

                $ins_data['address_card']= (!empty($address_card))?$address_card:$cd_res['address_card'];

                $ins_data['state']       = (!empty($state))?$state:$cd_res['state'];

                $ins_data['city']        = (!empty($city))?$city:$cd_res['city'];

                $ins_data['zipcode']     = (!empty($zipcode))?$zipcode:$cd_res['zipcode'];

                $ins_data['employer_id'] = (!empty($employer_id))?$employer_id:$cd_res['employer_id'];

                $ins_data['default_card']= (!empty($default_card))?$default_card:$cd_res['default_card'];

                $ins_data['usertype']    = $usertype;

           

            $card_res = $this->credit_card_model->check_unique(array('id' => $card_id));

          

            if(count($card_res)==0){

                return $this->response(array('status' =>'error','request_type' => 'update_credit_card_details', 'msg' => "Credit Cart doesn't exists", 'error_code' => "3"), 404);

            } 

            

             if(!empty($card_number)) {

                  //Card number check if already exists or not

                  $card_res = $this->credit_card_model->check_unique(array("card_number" => $card_number, 'id!=' => $card_id));

                  if(count($card_res)>0) {

                    return $this->response(array('status' => "error",'msg' => 'Card number already exist.','error_code' => "2"), 404);

                  }

             } 

              

            $ins_data['updated_date'] = date("Y-m-d H:i:s");

            $affected                 = $this->credit_card_model->update(array("id" => $card_id),$ins_data);

            

            if(!empty($affected)){   

                $res = $this->credit_card_model->check_unique(array('id' => $card_id));

                return $this->response(array('status' =>'success','request_type' => 'update_credit_card_details','user_data' => $res), 200);

            }

            

        }

        

         //view credit card details

        function view_credit_card_post()

        {

            if(!$this->post('card_id')){

    			return $this->response(array('status' => 'error','msg' => 'Required fields missing in your request','error_code' => "1"), 404);

    		}

            

            $card_id   = $this->post('card_id');

            

            //Card number check if already exists or not

            $card_res  = $this->credit_card_model->check_unique(array('id' => $card_id));

            

            if(count($card_res)>0){

                 return $this->response(array('status' =>'success','request_type' => 'view_credit_card_details', 'card_data' => $card_res), 200);

            }

            else

            {

                return $this->response(array('status' =>'error','request_type' => 'view_credit_card_details', 'msg' => "Card doesn't exists!" ,'error_code' => "6"), 404);

            }

        } 

        

       

       //Delete credit card details

        function delete_credit_card_post()

        {

            if(!$this->post('card_id')){

    			return $this->response(array('status' => 'error','msg' => 'Required fields missing in your request','error_code' => "1"), 404);

    		}

            

            $card_id   = $this->post('card_id');

            $res       = $this->credit_card_model->delete(array('id' => $card_id));

            

            return $this->response(array('status' =>'success','request_type' => 'delete_credit_card', 'card_id' => $card_id), 200);

        } 

        

        //lists the employer cards

        function lists_employer_cards_post()

        {

            if(!$this->post('employer_id')){

    			return $this->response(array('status' => 'error','msg' => 'Required fields missing in your request','error_code' => "1"), 404);

    		}

            

            $employer_id= $this->post('employer_id');

            $card_res   = array();

            $card_res   = $this->credit_card_model->get_employer_cards(array('c.employer_id' => $employer_id));

            $check_res  = $this->checking_account_model->get_checking_account_data(array('c.employer_id' => $employer_id));

            $paypal_res = $this->paypal_model->get_paypal_user_info(array('p.user_id' => $employer_id));

            

            if((count($card_res)>0) || (count($check_res)>0) || (count($paypal_res)>0)){

                 return $this->response(array('status' =>'success','request_type' => 'employer_card_lists', 'card_data' => $card_res, 'checking_account_data' => $check_res, 'paypal_info' => $paypal_res), 200);

            }

            else

            {

                return $this->response(array('status' =>'error','request_type' => 'employer_card_lists', 'msg' => "No Cards Found!" ,'error_code' => "7"), 404);

            }

        }

        

       //update profile image & profile name  

        function update_profile_image_post()

        {

            if(!$this->post('user_id')){

    			return $this->response(array('status' => 'error','msg' => 'Required fields missing in your request','error_code' => "1"), 404);

    		}

            

            $profile_image = $this->post('profile_image');

            $profile_name  = $this->post('profile_name');

            $user_id       = $this->post("user_id"); 

            $res           = $this->user_model->check_unique(array('id' => $user_id));

            

            if(count($res)>0){

                 $ins_data   = array();

                 $ins_data['profile_image'] = (!empty($profile_image))?$profile_image:$res['profile_image'];

                 $ins_data['profile_name']  = (!empty($profile_name))?$profile_name:$res['profile_name']; 

                 $update = $this->user_model->update(array("id" => $res['id']),$ins_data,"user");

                 return $this->response(array('status' =>'success','request_type' => 'update_profile_section'), 200);

            }

            else

            {

                return $this->response(array('status' =>'error','request_type' => 'update_profile_section', 'msg' => "Profile image doesn't update" ,'error_code' => "7"), 404);

            }

        }

        

        //add checking account data

        function add_checking_account_post()

        {

            if(!$this->post('name')){

    			return $this->response(array('status' => 'error','msg' => 'Required fields missing in your request','error_code' => "1"), 404);

    		} 

            

            $name           = $this->post('name');

            $routing_number = $this->post('routing_number');

            $account_number = $this->post('account_number');

            $license_number = $this->post('license_number');

            $employer_id    = $this->post('employer_id');

            $default_account= $this->post('default_account');

            $state          = $this->post('state');

            //$check_accot_id = $this->post('checking_account_id');

            $status         = $this->post('status');

            $usertype       = $this->post('usertype');

            

             if(!empty($account_number)) {

                  //Account number check if already exists or not

                  $account_res = $this->checking_account_model->check_unique(array("account_number" => $account_number));

                  if(count($account_res)>0) {

                    return $this->response(array('status' => "error",'msg' => 'Account number already exists.','error_code' => "2"), 404);

                  }

             }

            

            $ins_data = array();

            $ins_data['name']           = $name;

            $ins_data['routing_number'] = $routing_number;

            $ins_data['account_number'] = $account_number;

            $ins_data['license_number'] = $license_number;

            $ins_data['state']          = $state;

            $ins_data['status']         = $status;

            $ins_data['employer_id']    = $employer_id;

            $ins_data['default_account']= $default_account;

            $ins_data['created_date']   = date("Y-m-d H:i:s");

            $ins_data['usertype']       = $usertype;

            

            $checking_account_id        = $this->checking_account_model->insert($ins_data);

            if(!empty($checking_account_id)){

                return $this->response(array('status' =>'success','request_type' => 'add_checking_account','checking_account_id' => $checking_account_id), 200);

            }    

            else

            {

                return $this->response(array('status' =>'error','request_type' => 'add_checking_account', 'msg' => "Checking Account details doesn't create!" ,'error_code' => "5"), 404);

            }

        }

        

        //user checking account update

        function checking_account_update_post() {

            

                if(!$this->post('checking_account_id') && !$this->post('account_number')){

        			return $this->response(array('status' => 'error','msg' => 'Required fields missing in your request','error_code' => "1"), 404);

        		}

                 

                $name           = $this->post('name');

                $routing_number = $this->post('routing_number');

                $account_number = $this->post('account_number');

                $license_number = $this->post('license_number');

                $default_account= $this->post('default_account');

                $employer_id    = $this->post('employer_id');

                $state          = $this->post('state');

                $status         = $this->post('status');

                $check_accot_id = $this->post('checking_account_id');

                $usertype       = $this->post('usertype');

                

                //get account details

                $cd_res = $this->checking_account_model->check_unique(array('id' => $check_accot_id));

                

                $ins_data = array();

                $ins_data['name']           = (!empty($name))?$name:$cd_res['name'];

                $ins_data['routing_number'] = (!empty($routing_number))?$routing_number:$cd_res['routing_number'];

                $ins_data['account_number'] = (!empty($account_number))?$account_number:$cd_res['account_number'];

                $ins_data['license_number'] = (!empty($license_number))?$license_number:$cd_res['license_number'];

                $ins_data['state']          = (!empty($state))?$state:$cd_res['state'];

                $ins_data['status']         = (!empty($status))?$status:$cd_res['status'];

                $ins_data['employer_id']    = (!empty($employer_id))?$employer_id:$cd_res['employer_id'];

                $ins_data['default_account']= (!empty($default_account))?$default_account:$cd_res['default_account'];

                $ins_data['usertype']       = $usertype;

                

                

           

             if(!empty($account_number)) {

                  //Account number check if already exists or not

                  $acc_res = $this->checking_account_model->check_unique(array("account_number" => $account_number, 'id!=' => $check_accot_id));

                  if(count($acc_res)>0) {

                    return $this->response(array('status' => "error",'msg' => 'Account number already exist.','error_code' => "2"), 404);

                  }

             } 

            $ins_data['updated_date']   = date("Y-m-d H:i:s");

            $affected  = $this->checking_account_model->update(array("id" => $check_accot_id),$ins_data);

            

            if(!empty($affected)){   

                $res = $this->checking_account_model->check_unique(array('id' => $check_accot_id));

                return $this->response(array('status' =>'success','request_type' => 'update_credit_card_details','account_data' => $res), 200);

            }

            else

            {

                return $this->response(array('status' =>'error','request_type' => 'update_credit_card_details', 'msg' => "Account doesn't updated", 'error_code' => "3"), 404);

            } 

        }

        

         //view checking account by through checking account id

        function view_checking_account_post()

        {

            if(!$this->post('checking_account_id')){

    			return $this->response(array('status' => 'error','msg' => 'Required fields missing in your request','error_code' => "1"), 404);

    		}

            $checking_account_id   = $this->post('checking_account_id');

            

            //Get checking account data

            $check_res  = $this->checking_account_model->check_unique(array('id' => $checking_account_id));

            

            if(count($check_res)>0){

                 return $this->response(array('status' =>'success','request_type' => 'view_checking_account', 'checking_data' => $check_res), 200);

            }

            else

            {

                return $this->response(array('status' =>'error','request_type' => 'view_checking_account', 'msg' => "Checking Account doesn't exists!" ,'error_code' => "6"), 404);

            }

        } 

        

        //Delete Checking Account details

        function delete_checking_account_post()

        {

            if(!$this->post('checking_account_id')){

    			return $this->response(array('status' => 'error','msg' => 'Required fields missing in your request','error_code' => "1"), 404);

    		}

            

            $checking_account_id   = $this->post('checking_account_id');

            $res                   = $this->checking_account_model->delete(array('id' => $checking_account_id));

            return $this->response(array('status' =>'success','request_type' => 'delete_checking_account', 'card_id' => $checking_account_id), 200);

        } 

        

        //get profile image & profile name  

        function get_profile_image_post()
        {

            if(!$this->post('user_id')){
    			return $this->response(array('status' => 'error','msg' => 'Required fields missing in your request','error_code' => "1"), 404);
    		}

            $user_id       = $this->post("user_id"); 
            $type          = $this->post("type");
            $res           = $this->user_model->check_unique(array('id' => $user_id));
            $employee_rating['cnt'] = '';
            $employer_rating['cnt'] = '';
            
            if(count($res)>0){
                 $profile_image      = (!empty($res['profile_image']))?site_url()."assets/images/uploads/profile/".$res['profile_image']:"";
                 $pending   = $this->db->query("select count(*) as cnt from notifications where user_id='".$user_id."' and type='notificationCountPending'")->row_array();
                 $posted    = $this->db->query("select count(*) as cnt from notifications where user_id='".$user_id."' and type='notificationCountPosted'")->row_array();
                 $active    = $this->db->query("select count(*) as cnt from notifications where user_id='".$user_id."' and type='notificationCountActive'")->row_array();
                 $history   = $this->db->query("select count(*) as cnt from notifications where user_id='".$user_id."' and type='notificationCountJobHistory'")->row_array();
                 
                 $pending    = (isset($pending['cnt']))?$pending['cnt']:"";
                 $posted     =  (isset($posted['cnt']))?$posted['cnt']:"";
                 $active     = (isset($active['cnt']))?$active['cnt']:"";
                 $history    = (isset($history['cnt']))?$history['cnt']:"";
                 
                 if($type == 'employee'){
                    $employee_rating = $this->db->query("select count(*) as cnt from rating where employee_id='".$user_id."'")->row_array();  
                }  
                else
                {
                    $employer_rating = $this->db->query("select count(*) as cnt from rating where employer_id='".$user_id."'")->row_array();
                }
                
                 return $this->response(array('status' =>'success','request_type' => 'get_profile_image_and_name', "profile_name" => $res['profile_name'], "profile_image" => $profile_image, "notificationCountPending" => $pending, "notificationCountPosted" => $posted, "notificationCountActive" => $active, "notificationCountJobHistory" => $history, 'employee_rating' => $employee_rating['cnt'], 'employer_rating' => $employer_rating['cnt']  ), 200);
            }
            else
            {
                return $this->response(array('status' =>'error','request_type' => 'get_profile_image_and_name', 'msg' => "Doesn't found user!" ,'error_code' => "7"), 404);
            }

        }

        

        //save payment information

        function save_payment_information_post()

        {

            if(!$this->post('user_id')){

    			return $this->response(array('status' => 'error','msg' => 'Required fields missing in your request','error_code' => "1"), 404);

    		}

            

            $user_id       = $this->post("user_id"); 

            

            $ins_data             = array();

            $ins_data['employer_id']= $user_id;

            $ins_data['email_id']   = $this->post('email');

            $ins_data['password']   = $this->post('password');

            $res                    = $this->paypal_model->insert($ins_data,'paypal');

            

            if(count($res)>0){

                 return $this->response(array('status' =>'success','request_type' => 'save_paypal_info' ), 200);

            }

            else

            {

                return $this->response(array('status' =>'error','request_type' => 'save_paypal_info', 'msg' => "Doesn't save paypal info!" ,'error_code' => "8"), 404);

            }

        }

        

        //get payment information

        function check_if_payment_mode_post()

        {

            if(!$this->post('user_id')){

    			return $this->response(array('status' => 'error','msg' => 'Required fields missing in your request','error_code' => "1"), 404);

    		}

            

            $user_id        = $this->post("user_id"); 

            $check_account  = $this->checking_account_model->check_unique(array('employer_id' => $user_id));

            $credit_account = $this->credit_card_model->check_unique(array('employer_id' => $user_id));

            $paypal         = $this->db->query("select * from paypal_user_info where user_id='".$user_id."'")->result_array();

            

            if((count($check_account)>0) || (count($credit_account)>0) || (count($paypal)>0)){

                 return $this->response(array('status' =>'success','request_type' => 'save_paypal_info', "payment" => 'yes' ), 200);

            }

            else

            {

                return $this->response(array('status' =>'error','request_type' => 'save_paypal_info', "payment" => "no" ,'error_code' => "9"), 404);

            }

        }

        

        //create job

        function create_job_post()

        {

            if(!$this->post('user_id')){

    			return $this->response(array('status' => 'error','msg' => 'Required fields missing in your request','error_code' => "1"), 404);

    		}

            

            $employer_id            = $this->post('user_id');

            $job_name               = $this->post('job_name');

            $job_category           = $this->post('job_category');

            $job_description        = $this->post('job_description');

            $job_date               = $this->post('job_date');

            $job_start_date         = $this->post('job_start_date');

            $job_end_date           = $this->post('job_end_date');

            $job_payment_amount     = $this->post('job_payment_amount');

            $job_payment_type       = $this->post('job_payment_type');

            $address                = $this->post('address');

            $city                   = $this->post('city');

            $state                  = $this->post('state');

            $zipcode                = $this->post('zipcode');

            $post_address           = $this->post('post_address');

            $start_time             = $this->post('start_time');

            $end_time               = $this->post('end_time');

            $lat                    = $this->post('lat');

            $lon                    = $this->post('lon');
            
            $currentlocation        = $this->post('currentlocation');

            

            $ins_data = array();

            $ins_data['employer_id']       = $employer_id;

            $ins_data['job_name']          = $job_name;

            $ins_data['job_category']      = $job_category;

            $ins_data['description']       = $job_description;

            $ins_data['job_date']          = $job_date;

            $ins_data['job_start_date']    = $job_start_date;

            $ins_data['job_end_date']      = $job_end_date;

            $ins_data['job_payment_amount']= $job_payment_amount;

            $ins_data['job_payment_type']  = $job_payment_type;

            $ins_data['address']           = $address;

            $ins_data['city']              = $city;

            $ins_data['state']             = $state;

            $ins_data['zipcode']           = $zipcode;

            $ins_data['post_address']      = $post_address;

            $ins_data['start_time']        = $start_time;

            $ins_data['end_time']          = $end_time;

            $ins_data['lat']               = $lat;

            $ins_data['lon']               = $lon;
            $ins_data['currentlocation']   = $currentlocation;
            
            $res                           =  $this->jobs_model->insert($ins_data,'jobs');

           // $notification = array();
//            $notification['message']  = '';
//            $notification['user_id']  = $employer_id;
//            $notification['is_view']  = 0;
//            $notification['type']     = 'notificationCountPosted';
//            $notification             =  $this->jobs_model->insert($notification,'notifications');
            
            update_notification_count($employer_id,'','notificationCountPosted',$res);
            
            if($res!=''){

                 return $this->response(array('status' =>'success','request_type' => 'create_job', 'job_id' => $res), 200);

            }

            else

            {

                return $this->response(array('status' =>'error','request_type' => 'create_job', 'msg' => "Doesn't create job!" ,'error_code' => "8"), 404);

            }

        }

        

        //edit job

        function edit_job_post()

        {

            if(!$this->post('job_id')){

    			return $this->response(array('status' => 'error','msg' => 'Required fields missing in your request','error_code' => "1"), 404);

    		}

            

            $id                     = $this->post('job_id');
            $employer_id            = $this->post('user_id');
            $job_name               = $this->post('job_name');
            $job_category           = $this->post('job_category');
            $job_description        = $this->post('job_description');
            $job_date               = $this->post('job_date');
            $job_start_date         = $this->post('job_start_date');
            $job_end_date           = $this->post('job_end_date');
            $job_payment_amount     = $this->post('job_payment_amount');
            $job_payment_type       = $this->post('job_payment_type');
            $address                = $this->post('address');
            $city                   = $this->post('city');
            $state                  = $this->post('state');
            $zipcode                = $this->post('zipcode');
            $post_address           = $this->post('post_address');
            $start_time             = $this->post('start_time');
            $end_time               = $this->post('end_time');
            $lat                    = $this->post('lat');
            $lon                    = $this->post('lon');
            $currentlocation        = $this->post('currentlocation');

            

            $ins_data = array();
            $ins_data['employer_id']       = $employer_id;
            $ins_data['job_name']          = $job_name;
            $ins_data['job_category']      = $job_category;
            $ins_data['description']       = $job_description;
            $ins_data['job_date']          = $job_date;
            $ins_data['job_start_date']    = $job_start_date;
            $ins_data['job_end_date']      = $job_end_date;
            $ins_data['job_payment_amount']= $job_payment_amount;
            $ins_data['job_payment_type']  = $job_payment_type;
            $ins_data['address']           = $address;
            $ins_data['city']              = $city;
            $ins_data['state']             = $state;
            $ins_data['zipcode']           = $zipcode;
            $ins_data['start_time']        = $start_time;
            $ins_data['end_time']          = $end_time;
            $ins_data['lat']               = $lat;
            $ins_data['lon']               = $lon;
            $ins_data['currentlocation']  = $currentlocation;
            $res                           = $this->jobs_model->update(array("id" => $id),$ins_data,'jobs');

            

            if($res){

                 return $this->response(array('status' =>'success','request_type' => 'update_job', 'job_id' => $id, 'query' => $this->db->last_query()), 200);

            }

            else

            {

                return $this->response(array('status' =>'error','request_type' => 'update_job', 'msg' => "Doesn't edit job!" ,'error_code' => "8"), 404);

            }

        }

        

        //display jobs

        function job_lists_post()
        {

            //if(!$this->post('user_id')){

//    			return $this->response(array('status' => 'error','msg' => 'Required fields missing in your request','error_code' => "1"), 404);
//    		}

            $employer_id = '';

            $employer_id = $this->post('user_id');
            $type        = $this->post('type');
            
            if($type == 'posted'){
              $job_lists   = $this->jobs_model->job_lists($employer_id);
            } 
            else if($type == 'applied')
            {
              $job_lists   = $this->jobs_model->applied_job_lists($em-ployer_id);  
            } 
            
            $jobs_data   = array();

            foreach($job_lists as $jkey => $jvalue){

                $no_of_applicants_applied = $this->db->query("select count(*) as cnt from applied_jobs where job_id='".$jvalue['job_id']."'")->row_array();

                $job_lists[$jkey]['no_of_applicants_applied'] = $no_of_applicants_applied['cnt'];

                $job_lists[$jkey]['profile_image']            = (!empty($jvalue['profile_image']))?site_url()."assets/images/uploads/profile/".$jvalue['profile_image']:"";
                
               
            }

            if(count($job_lists)>0){
                 return $this->response(array('status' =>'success','request_type' => 'job_lists', 'job_lists' => $job_lists), 200);
            }
            else
            {
                return $this->response(array('status' =>'error','request_type' => 'job_lists', 'msg' => "No Jobs Found" ,'error_code' => "8"), 404);
            }

        }

        

        //update usertype

        function update_usertype_post()

        {

            if(!$this->post('user_id') && !$this->post('usertype')){

    			return $this->response(array('status' => 'error','msg' => 'Required fields missing in your request','error_code' => "1"), 404);

    		}

            

            $employer_id = $this->post('user_id');

            $usertype    = $this->post('usertype');

            $res         = $this->user_model->check_unique(array('id' => $employer_id));

            

            if(count($res)>0){

                 $ins_data = array();

                 $ins_data['usertype'] = $usertype;

                 $update   = $this->user_model->update(array("id" => $employer_id),$ins_data,"user");

                 return $this->response(array('status' =>'success','request_type' => 'usertype_update'), 200);

            }

            else

            {

                return $this->response(array('status' =>'error','request_type' => 'usertype_update', 'msg' => "Doesn't update usertype!" ,'error_code' => "8"), 404);

            }

        }

        

        //job category lists

        function job_category_lists_post()

        {

            

            $job_category_lists = $this->db->query("select * from job_category where 1=1")->result_array();

            

            if(count($job_category_lists)>0){

                 return $this->response(array('status' =>'success','request_type' => 'job_category_lists', 'categories' => $job_category_lists), 200);

            }

            else

            {

                return $this->response(array('status' =>'error','request_type' => 'job_category_lists', 'msg' => "Doesn't found categories!" ,'error_code' => "8"), 404);

            }

        }

        

         //job detail view

        function job_detail_view_post()

        {

            if(!$this->post('job_id')){

    			return $this->response(array('status' => 'error','msg' => 'Required fields missing in your request','error_code' => "1"), 404);

    		}

            

            $job_id = $this->post('job_id');

            $job    = $this->db->query("select j.*,u.*,j.id as job_id,j.address as job_address,j.state as job_state,j.city as job_city,j.zipcode as job_zipcode from jobs j inner join user u on u.id=j.employer_id where j.id='".$job_id."'")->row_array();

            $job['profile_image']    = (!empty($job['profile_image']))?site_url()."assets/images/uploads/profile/".$job['profile_image']:""; 

            

            if(count($job)>0){

                 return $this->response(array('status' =>'success','request_type' => 'job_detail', 'job_data' => $job), 200);

            }

            else

            {

                return $this->response(array('status' =>'error','request_type' => 'job_detail', 'msg' => "Doesn't found job!" ,'error_code' => "8"), 404);

            }

        }

        

        //forgot password 

        function forget_password_post()

        {

            if(!$this->post('email')){

    			return $this->response(array('status' => 'error','msg' => 'Required fields missing in your request','error_code' => "1"), 404);

    		}

            

            $email = $this->post('email');

            $res   = $this->user_model->check_unique(array("email" => $email));

            

            if(count($res)>0){

                 $user_id = $res['id'];

                 $username= $res['username'];

                 
                 $current_time = strtotime(date("Y-m-d H:i:s"));

                 $fpwd_url = site_url()."auth/changepassword?id=$user_id";

                 $message  = "<html>";

                 $message .= "<body>";

                 $message .= "<p>Hi $username,</p><br/>";

                 $message .= "<p>Please click below link to reset your password.</p><br/>";

                 $message .= "<p><a href='".$fpwd_url."' title='Reset Your Password'>Click Here</a></p><br/><br/>";

                 $message .= "<p>Thanks,<p>";

                 $message .= "<p>HandzforHire</p>"; 

                 $message .= "</body></html>";

    

                 $this->email->from('allivizhi@izaaptech.in','Allivizhi');

                 $this->email->to($email);

                 $this->email->subject('Forgot Password');

                 $this->email->message($message);

                 $this->email->send();

                 return $this->response(array('status' =>'success','request_type' => 'forget_password'), 200);

           }

           else

           {

              return $this->response(array('status' =>'error','request_type' => 'forget_password', 'msg' => "Doesn't found user!" ,'error_code' => "8"), 404);

           }         

        }

        

        //job search

        function job_search_post()

        {

            if(!$this->post('search_type')){

    			return $this->response(array('status' => 'error','msg' => 'Required fields missing in your request','error_code' => "1"), 404);

    		}

            

            $search_type  = $this->post('search_type');

            $category     = $this->post('category');

            $zipcode      = $this->post('zipcode');

            $all_jobs     = ($search_type == 'display_all')?"yes":"no";

            

            if($search_type == 'location'){

                $lat        = $this->post('lat');

                $lon        = $this->post('lon');

                $job_lists  = $this->jobs_model->get_location_jobs($lat,$lon,$category,$zipcode);

            }

            else

            {

                $job_lists  = $this->jobs_model->job_search($category,$zipcode);

            }

            

            foreach($job_lists as $jkey => $jvalue){

                $job_lists[$jkey]['profile_image'] = (!empty($jvalue['profile_image']))?site_url()."assets/images/uploads/profile/".$jvalue['profile_image']:"";

            }

            

            if(count($job_lists)>0){

                 return $this->response(array('status' =>'success','request_type' => 'job_lists', 'job_lists' => $job_lists,"all_jobs" => $all_jobs), 200);

            }

            else

            {

                 return $this->response(array('status' =>'error','request_type' => 'job_lists', 'msg' => "No Jobs Found" ,'error_code' => "8"), 404);

            }

        }

        

        //apply job

        function apply_jobs_post()

        {

            if(!$this->post('employer_id') && !$this->post('employee_id') && !$this->post('job_id')){

    			return $this->response(array('status' => 'error','msg' => 'Required fields missing in your request','error_code' => "1"), 404);

    		}

            $employer_id  = $this->post('employer_id');
            $employee_id  = $this->post('employee_id');
            $job_id       = $this->post('job_id');            
            $comments     = $this->post('comments');

            if($employer_id == $employee_id){
    			return $this->response(array('status' => 'error','msg' => 'You are not allowed to apply for the job','error_code' => "12"), 404);
    		}

            $ins_data = array();

            $ins_data['employer_id'] = $employer_id;

            $ins_data['employee_id'] = $employee_id;

            $ins_data['job_id']      = $job_id;

            $ins_data['comments']    = $comments;
            $ins_data['status']      = 'Hold';

            

            $apply_job    = $this->jobs_model->insert($ins_data,"applied_jobs");

            $device_token = $this->user_model->check_unique(array("id" => $employer_id));

            //$notification = array();
//            $notification['message']  = '';
//            $notification['user_id']  = $employee_id;
//            $notification['is_view']  = 0;
//            $notification['type']     = 'notificationCountPending';
//            $notification             =  $this->jobs_model->insert($notification,'notifications');sfef
            
            update_notification_count($employee_id,'','notificationCountPending',$apply_job);
            
            //get employee data

            $employee_data= $this->user_model->check_unique(array("id" => $employee_id));

            

            //get job data

            $job_data     = $this->jobs_model->check_unique(array("id" => $job_id));

            

            if(!empty($apply_job)){

                if(!empty($device_token['devicetoken'])){

                 // $message = ucfirst($employee_data['firstname'].''.$employee_data['lastname'])." is applied for ".$job_data['job_name'];
                 
                    $message = 'You have received a new job application'; 

                  $params  = $this->db->query("select * from notifications where user_id='".$employer_id."'")->result_array();

                  sendNotification($device_token['devicetoken'],$message,count($params),$employer_id,'applyjob');

                  update_notification_count($employer_id,$message,'applyjob',$job_id);

                }

                return $this->response(array('status' => 'success','request_type' => 'apply_job'), 200);

            }

            else

            {

                 return $this->response(array('status' =>'error','request_type' => 'apply_job', 'msg' => "Doesn't apply job" ,'error_code' => "8"), 404);

            }

        }

        

        //view applied job

        function applied_job_detailed_view_post()
        {
            if(!$this->post('job_id') && !$this->post('employer_id')){
    			return $this->response(array('status' => 'error','msg' => 'Required fields missing in your request','error_code' => "1"), 404);
    		}


            $job_id      = $this->post('job_id');

            $employer_id = $this->post('employer_id');

            $employees   = $this->db->query("select u.*,a.* from applied_jobs a inner join user u on u.id=a.employee_id where a.job_id='".$job_id."' and a.employer_id='".$employer_id."'")->result_array();

            
            foreach($employees as $ekey => $evalue){
                $employees[$ekey]['profile_image']  = (!empty($evalue['profile_image']))?site_url()."assets/images/uploads/profile/".$evalue['profile_image']:"";
            }  

            

            if(count($employees)>0){
                 return $this->response(array('status' =>'success','request_type' => 'employee_detail', 'emp_data' => $employees), 200);
            }
            else
            {

                return $this->response(array('status' =>'error','request_type' => 'employee_detail', 'msg' => "Doesn't found job!" ,'error_code' => "8"), 404);

            }

        }

        

        //paypal user info add

        function paypal_user_info_add_post()
        {

            if(!$this->post('user_id')){
    			return $this->response(array('status' => 'error','msg' => 'Required fields missing in your request','error_code' => "1"), 404);
    		}

            

            $user_id                = $this->post('user_id');

            $usertype               = $this->post('usertype');

            $address                = $this->post('address');

            $email                  = $this->post('email');

            $phonenumber            = $this->post('phonenumber');

            $email_verified         = $this->post('email_verified');

            $user_verified          = $this->post('user_verified');

            

            $ins_data = array();

            $ins_data['user_id']           = $user_id;

            $ins_data['usertype']          = $usertype;

            $ins_data['address']           = $address;

            $ins_data['email']             = $email;

            $ins_data['phonenumber']       = $phonenumber;

            $ins_data['email_verified']    = $email_verified;

            $ins_data['user_verified']     = $user_verified;

            $res                           =  $this->jobs_model->insert($ins_data,'paypal_user_info');

            

            if($res!=''){

                return $this->response(array('status' =>'success','request_type' => 'paypal_user_info', 'paypal_user_info_id' => $res), 200);

            }

            else

            {

                return $this->response(array('status' =>'error','request_type' => 'paypal_user_info', 'msg' => "Doesn't create user info!" ,'error_code' => "8"), 404);

            }

        }

        

        //Delete Checking Account details

        function delete_paypal_user_post()
        {
            if(!$this->post('user_id')){
    			return $this->response(array('status' => 'error','msg' => 'Required fields missing in your request','error_code' => "1"), 404);
    		}

            $user_id   = $this->post('user_id');
            $res       = $this->jobs_model->delete(array('id' => $user_id),"paypal_user_info");
            return $this->response(array('status' =>'success','request_type' => 'delete_paypal_account', 'user_id' => $user_id), 200);
        }

        
       function logout_post()
       {
           if(!$this->post('user_id')){
    			return $this->response(array('status' => 'error','msg' => 'Required fields missing in your request','error_code' => "1"), 404);
    		}
            
            $user_id   = $this->post('user_id');
            
            $ins_data                = array();
            $ins_data['devicetoken'] = '';
            
            $affected  = $this->user_model->update(array("id" => $user_id),$ins_data,"user");
            return $this->response(array('status' =>'success','request_type' => 'logout_user', 'user_id' => $user_id), 200);
       }
     
      function hire_job_post()
      {
            if(!$this->post('job_id') && !$this->post('job_name') && !$this->post('employer_id') && !$this->post('employee_id')){
    			return $this->response(array('status' => 'error','msg' => 'Required fields missing in your request','error_code' => "1"), 404);
    		}
            
            $job_id      = $this->post('job_id');
            $job_name    = $this->post('job_name');
            $employer_id = $this->post('employer_id');
            $employee_id = $this->post('employee_id');
            
            $ins_data = array();
            $ins_data['job_id']      = $job_id;
            $ins_data['job_name']    = $job_name;
            $ins_data['employer_id'] = $employer_id;
            $ins_data['employee_id'] = $employee_id;
            $res                     =  $this->jobs_model->insert($ins_data,'hire_jobs');
            
            $this->db->query("update applied_jobs set status='Hired' where job_id='".$job_id."' and employer_id='".$employer_id."' and employee_id='".$employee_id."'");
           // $this->db->query("delete from applied_jobs where job_id='".$job_id."' and employer_id='".$employer_id."' and  employee_id='".$employee_id."'");
            
            //send notification to employee
            $employee = $this->user_model->check_unique(array("id" => $employee_id));
            $params   = $this->db->query("select * from notifications where user_id='".$employee_id."'")->result_array();
            //$message  = "You are hired for ".ucfirst($job_name);
            $message = 'Congratulations! You’ve been hired for the'.ucfirst($job_name). 'job';
            
            if(count($employee)){
                sendNotification($employee['devicetoken'],$message,count($params),$employee_id,'hirejob');
            }    
            update_notification_count($employee_id,$message,'hirejob',$job_id);
                
           return $this->response(array('status' =>'success','request_type' => 'hire_jobs'), 200);     
      }   
      
        //display active jobs
        function active_job_lists_post()
        {
            $user_id = $this->post('user_id');
            $type    = $this->post('type');
            
            $job_lists   = $this->jobs_model->active_job_lists($user_id, $type);  
            $jobs_data   = array();

            foreach($job_lists as $jkey => $jvalue){
                $job_lists[$jkey]['channel']        = $jvalue['employer_id'].$jvalue['employee_id'];
                $job_lists[$jkey]['firstname']      = $jvalue['employer_id'].$jvalue['firstname'];
                $job_lists[$jkey]['profile_image']  = (!empty($jvalue['profile_image']))?site_url()."assets/images/uploads/profile/".$jvalue['profile_image']:"";
                
                if($type == 'employee'){
                  $udata =   $this->user_model->check_unique(array('id' => $jvalue['employer_id'] ));
                  $job_lists[$jkey]['username'] = $udata['username'];
                  $job_lists[$jkey]['profile_name'] = $udata['profile_name'];
                  $job_lists[$jkey]['profile_image']= (!empty($udata['profile_image']))?site_url()."assets/images/uploads/profile/".$udata['profile_image']:"";;
                }
                if($type == 'employer'){
                   $udata =   $this->user_model->check_unique(array('id' => $jvalue['employee_id'] ));
                   $job_lists[$jkey]['username']     = $udata['username'];
                   $job_lists[$jkey]['profile_name'] = $udata['profile_name']; 
                   $job_lists[$jkey]['profile_image']= (!empty($udata['profile_image']))?site_url()."assets/images/uploads/profile/".$udata['profile_image']:"";;
                }  
                $job_lists[$jkey]['notificationCountMessage']     = get_notification_count('',$jvalue['id'],'notificationCountMessage');
                $job_lists[$jkey]['notificationCountMakePayment'] = get_notification_count('',$jvalue['id'],'notificationCountMakePayment');
                $job_lists[$jkey]['notificationCountMsgActive']   = get_notification_count('',$jvalue['id'],'notificationCountMsgActive');   
            }
                  
            if(count($job_lists)>0){
                 return $this->response(array('status' =>'success','request_type' => 'active_job_lists', 'job_lists' => $job_lists), 200);
            }
            else
            {
                return $this->response(array('status' =>'error','request_type' => 'active_job_lists', 'msg' => "No Active Jobs Found" ,'error_code' => "8"), 404);
            }
        }
        
         //refuse active jobs
        function refuse_jobs_post()
        {
             if(!$this->post('job_id') && !$this->post('employer_id') && !$this->post('employee_id')){
    			return $this->response(array('status' => 'error','msg' => 'Required fields missing in your request','error_code' => "1"), 404);
    		}
            
            $job_id      = $this->post('job_id');
            $employer_id = $this->post('employer_id');
            $employee_id = $this->post('employee_id');
            
            $this->db->query("update applied_jobs set status='Refused' where job_id='".$job_id."' and employer_id='".$employer_id."' and employee_id='".$employee_id."'");
            
           // $this->db->query("delete from applied_jobs where job_id='".$job_id."' and employer_id='".$employer_id."' and  employee_id='".$employee_id."'");
            //send notification to employee
            $employee = $this->user_model->check_unique(array("id" => $employee_id));
            $params   = $this->db->query("select * from notifications where user_id='".$employee_id."'")->result_array();
            $message  = "You are refused";
            
            if(count($employee)){
                sendNotification($employee['devicetoken'],$message,count($params),$employee_id,'refusejob');
            }    
             update_notification_count($employee_id,$message,'refusejob',$job_id);
            return $this->response(array('status' =>'success','request_type' => 'refuse_jobs'), 200);      
        }
        
          //payment_service
        function payment_service_post()
        {
            if(!$this->post('job_id') && !$this->post('employer_id') && !$this->post('employee_id')){
    			return $this->response(array('status' => 'error','msg' => 'Required fields missing in your request','error_code' => "1"), 404);
    		}
            
            $job_id           = $this->post('job_id');
            $employer_id      = $this->post('employer_id');
            $employee_id      = $this->post('employee_id');
            $tip              = $this->post('tip');
            $pay_method       = $this->post('payment_method');
            $job_name         = $this->post('job_name');
            $payment_amount   = $this->post('payment_amount');
            $total_payment    = $this->post('total_payment');
            $transaction_date = $this->post('transaction_date');
            
            $this->db->query("delete from hire_jobs where job_id='".$job_id."' and employer_id='".$employer_id."' and  employee_id='".$employee_id."'");
            
            $ins_data = array();
            $ins_data['job_id']          = $job_id;
            $ins_data['job_name']        = $job_name;
            $ins_data['employer_id']     = $employer_id;
            $ins_data['employee_id']     = $employee_id;
            $ins_data['tip']             = $tip;
            $ins_data['payment_method']  = $pay_method;
            $ins_data['payment_amount']  = $payment_amount;
            $ins_data['total_payment']   = $total_payment;
            $ins_data['transaction_date']= $transaction_date;
            $res                         =  $this->jobs_model->insert($ins_data,'jobs_history');
            
            //send notification to employee
            $employee = $this->user_model->check_unique(array("id" => $employee_id));
            $employer = $this->user_model->check_unique(array("id" => $employer_id));
            $params   = $this->db->query("select * from notifications where user_id='".$employee_id."'")->result_array();
            $message  = "Payment is made for".ucfirst($job_name).", provide star ratings";
            
            if(count($employee)){
                sendNotification($employee['devicetoken'],$message,count($params),$employee_id,'paymentcompleted');
                sendNotification($employer['devicetoken'],$message,count($params),$employer_id,'paymentcompleted');
            }    
           update_notification_count($employee_id,$message,'paymentcompleted',$job_id);
           update_notification_count($employer_id,$message,'paymentcompleted',$job_id); 
           update_notification_count($employer_id,$message,'notificationCountStarRating',$job_id);
           update_notification_count($employee_id,$message,'notificationCountStarRating',$job_id);
           
           return $this->response(array('status' =>'success','request_type' => 'payment_service'), 200);      
        }
        
        //job history listing service
        function job_history_listing_post()
        {
            if(!$this->post('user_id')){
    			return $this->response(array('status' => 'error','msg' => 'Required fields missing in your request','error_code' => "1"), 404);
    		}
            
            $user_id = $this->post('user_id');
            $type    = $this->post('type');
            
            $job_lists   = $this->jobs_model->job_history_lists($user_id, $type);  
            $jobs_data   = array();

            foreach($job_lists as $jkey => $jvalue){
                $job_lists[$jkey]['channel']        = $jvalue['employer_id'].$jvalue['employee_id']; 
                $job_lists[$jkey]['profile_image']  = (!empty($jvalue['profile_image']))?site_url()."assets/images/uploads/profile/".$jvalue['profile_image']:"";
                
                if($type == 'employee'){
                  $udata =   $this->user_model->check_unique(array('id' => $jvalue['employer_id'] ));
                  $job_lists[$jkey]['username']     = $udata['username'];
                  $job_lists[$jkey]['profile_name'] = $udata['profile_name'];
                  $job_lists[$jkey]['profile_image'] = (!empty($udata['profile_image']))?site_url()."assets/images/uploads/profile/".$udata['profile_image']:"";
                  $job_lists[$jkey]['employee_notificationCountMsgJobhistory'] = get_notification_count($user_id,$jvalue['id'],'notificationCountMsgJobhistory');
                  $job_lists[$jkey]['employee_notificationCountStarRating'] = get_notification_count($user_id,$jvalue['id'],'notificationCountStarRating');
                }
                if($type == 'employer'){
                   $udata =   $this->user_model->check_unique(array('id' => $jvalue['employee_id'] ));
                   $job_lists[$jkey]['username']      = $udata['username'];
                   $job_lists[$jkey]['profile_name']  =  $udata['profile_name'];
                   $job_lists[$jkey]['profile_image'] = (!empty($udata['profile_image']))?site_url()."assets/images/uploads/profile/".$udata['profile_image']:"";
                   $job_lists[$jkey]['employer_notificationCountMsgJobhistory'] = get_notification_count($user_id,$jvalue['id'],'notificationCountMsgJobhistory');
                   $job_lists[$jkey]['employer_notificationCountStarRating'] = get_notification_count($user_id,$jvalue['id'],'notificationCountStarRating');
                }
                  
            }

            if(count($job_lists)>0){
                 return $this->response(array('status' =>'success','request_type' => 'job_history_lists', 'job_lists' => $job_lists), 200);
            }
            else
            {
                return $this->response(array('status' =>'error','request_type' => 'active_job_lists', 'msg' => "No Active Jobs Found" ,'error_code' => "8"), 404);
            }
              
        }
        
        //rating section
        function add_rating_post()
        {
            if(!$this->post('job_id') && !$this->post('employer_id') && !$this->post('employee_id')){
    			return $this->response(array('status' => 'error','msg' => 'Required fields missing in your request','error_code' => "1"), 404);
    		}
            
            $job_id      = $this->post('job_id');
            $employer_id = $this->post('employer_id');
            $employee_id = $this->post('employee_id');
            $rating      = $this->post('rating');
            $comments    = $this->post('comments');
            //$date        = $this->post('date');
            
            $ins_data = array();
            $ins_data['job_id']        = $job_id;
            $ins_data['employer_id']   = $employer_id;
            $ins_data['employee_id']   = $employee_id;
            $ins_data['rating']        = $rating;
            $ins_data['comments']      = $comments;
            $rating_id                 =  $this->jobs_model->insert($ins_data,'rating');
            
            if(!empty($rating_id)){
              return $this->response(array('status' =>'success','request_type' => 'add_rating_service'), 200);
            }
            else
            {
               return $this->response(array('status' =>'error','request_type' => 'add_rating_service', 'msg' => "Network Error!" ,'error_code' => "8"), 404); 
            }      
        }
        
        function review_rating_post()
        {
            if(!$this->post('user_id')){
    			return $this->response(array('status' => 'error','msg' => 'Required fields missing in your request','error_code' => "1"), 404);
    		}
            
            $user_id = $this->post('user_id');
            $type    = $this->post('type');
            
            $job_lists   = $this->rating_model->rating_lists($user_id, $type);  
            $jobs_data   = array();
            
            if($type == 'employee'){
                $rating = $this->db->query("select * from rating where employee_id='".$user_id."'")->result_array();
            }  
            else
            {
                $rating = $this->db->query("select * from rating where employer_id='".$user_id."'")->result_array();
            }
                $rt = '';            
            foreach($rating as $rkey => $rvalue){
                $rt += $rvalue['rating'];
            }    
                $ratio = $rt/count($rating);
            foreach($job_lists as $jkey => $jvalue){
                $job_lists[$jkey]['profile_image']   = (!empty($jvalue['profile_image']))?site_url()."assets/images/uploads/profile/".$jvalue['profile_image']:"";
                $job_lists[$jkey]['employee_rating'] = 0;
                $job_lists[$jkey]['employer_rating'] = 0;
                $job_lists[$jkey]['date']            = $jvalue['created_date'];
                $job_lists[$jkey]['comments']        = $jvalue['comments'];
                $job_lists[$jkey]['average_rating']  = $ratio;
                if($type == 'employer'){
                    $empdata  =   $this->user_model->check_unique(array('id' => $jvalue['employee_id'] ));
                    $job_lists[$jkey]['employee']['username']     = $empdata['username'];
                    $job_lists[$jkey]['employee']['profile_name'] = $empdata['profile_name'];
                    $job_lists[$jkey]['employee']['profile_image']= (!empty($empdata['profile_image']))?site_url()."assets/images/uploads/profile/".$empdata['profile_image']:"";
                }
                else
                {
                    $emludata  =   $this->user_model->check_unique(array('id' => $jvalue['employer_id'] ));
                    $job_lists[$jkey]['employer']['username']     = $emludata['username'];
                    $job_lists[$jkey]['employer']['profile_name'] = $emludata['profile_name'];
                    $job_lists[$jkey]['employer']['profile_image']= (!empty($emludata['profile_image']))?site_url()."assets/images/uploads/profile/".$emludata['profile_image']:"";
                }
            }

            if(count($job_lists)>0){
                 return $this->response(array('status' =>'success','request_type' => 'review_rating_lists', 'rating_lists' => $job_lists), 200);
            }
            else
            {
                return $this->response(array('status' =>'error','request_type' => 'review_rating_lists', 'msg' => "No Ratings Found" ,'error_code' => "8"), 404);
            }     
        }
        
        function help_post()
        {
            if(!$this->post('email') && !$this->post('user_id') && !$this->post('query')){
    			return $this->response(array('status' => 'error','msg' => 'Required fields missing in your request','error_code' => "1"), 404);
    		}
            
            $email       = $this->post('email');
            $user_id     = $this->post('user_id');
            $query       = $this->post('query');
            
             //userdata
             $userdata = $this->user_model->check_unique(array("id" => $user_id));
             $message  = "<html>";
             $message .= "<body>";
             $message .= "<p>Hi ".$userdata['firstname'].",</p><br/>";
             $message .= $query;
             $message .= "<p>Thanks,<p>";
             $message .= "<p>HandzforHire</p>"; 
             $message .= "</body></html>";
    
             $this->email->from('support@handzforhire.com','Support');
             $this->email->to($email);
             $this->email->subject('Help');
             $this->email->message($message);
             $this->email->send();
             
             return $this->response(array('status' =>'success','request_type' => 'add_rating_service'), 200);
                 
        }
        
        function notification_count_post()
        {
            if(!$this->post('user_id')){
    			return $this->response(array('status' => 'error','msg' => 'Required fields missing in your request','error_code' => "1"), 404);
    		}
            
            $user_id     = $this->post('user_id');
            $notification_count = $this->db->query("select count(*) as cnt from notifications where user_id='".$user_id."'")->row_array();
            
            if(count($notification_count)){
                return $this->response(array('status' =>'success','request_type' => 'notification_count',"notification_count" => $notification_count['cnt']), 200);
            }
            else
            {
                return $this->response(array('status' => 'error', "msg" => "No notification count" ), 404);
            }
        }
        
        function send_message_post()
        {
            if(!$this->post('sender_id') && !$this->post('message') && !$this->post('receiver_id')){
    			return $this->response(array('status' => 'error','msg' => 'Required fields missing in your request','error_code' => "1"), 404);
    		}
            
            $user_id    = $this->post('receiver_id');
            $sender_id  = $this->post('sender_id');
            $job_id     = $this->post('job_id');
            $message    = $this->post('message');
            $userdata   = $this->user_model->check_unique(array("id" => $user_id));
            $senderdata = $this->user_model->check_unique(array("id" => $sender_id));
            $params     = $this->db->query("select * from notifications where user_id='".$user_id."'")->result_array();
            //$message    = ucfirst($senderdaa['firstname']).$message;
            $message    = 'You have received a message from '.ucfirst($senderdata['firstname']);            
            if(count($userdata)){
               sendNotification($userdata['devicetoken'],$message,count($params),$user_id,'notificationCountMessage');
            }   
            update_notification_count($user_id,$message,'notificationCountMessage',$job_id);
            update_notification_count($user_id,$message,'notificationCountMsgActive',$job_id);
            update_notification_count($user_id,$message,'notificaitonCountMsgJobHistory',$job_id);
            
            
            return $this->response(array('status' =>'success','request_type' => 'send_message'), 200);   
        }
        
        function employee_accept_post()
        {
            if(!$this->post('job_id') && !$this->post('employer_id') && !$this->post('employee_id')){
    			return $this->response(array('status' => 'error','msg' => 'Required fields missing in your request','error_code' => "1"), 404);
    		}
            
            $job_id      = $this->post('job_id');
            $employer_id = $this->post('employer_id');
            $employee_id = $this->post('employee_id');
            
            //job move to active job
            $this->db->query("update jobs set active_job='yes' where id='".$job_id."'");
            
            $userdata   = $this->user_model->check_unique(array("id" => $employer_id));
            $params     = $this->db->query("select * from notifications where user_id='".$employer_id."'")->result_array();
            $message    = "Job Accepted";
            
            update_notification_count($employer_id,'','notificationCountActive',$job_id);
            update_notification_count($employee_id,'','notificationCountActive',$job_id);
            
            if(count($userdata)){
               sendNotification($userdata['devicetoken'],$message,count($params),$employer_id,'notificationCountActive');
            }   
            
            return $this->response(array('status' =>'success','request_type' => 'employee_accept'), 200); 
        }
        
        function employee_reject_post()
        {
            if(!$this->post('job_id') && !$this->post('employer_id') && !$this->post('employee_id')){
    			return $this->response(array('status' => 'error','msg' => 'Required fields missing in your request','error_code' => "1"), 404);
    		}
            
            $job_id      = $this->post('job_id');
            $employer_id = $this->post('employer_id');
            $employee_id = $this->post('employee_id');
            
            $this->db->query("delete from applied_jobs where job_id='".$job_id."' and employer_id='".$employer_id."' and  employee_id='".$employee_id."'");
            return $this->response(array('status' =>'success','request_type' => 'employee_reject'), 200);
        }
        
        function request_payment_notification_post()
        {
            if(!$this->post('employer_id') && !$this->post('job_id')){
    			return $this->response(array('status' => 'error','msg' => 'Required fields missing in your request','error_code' => "1"), 404);
    		}
            
            $employer_id = $this->post('employer_id');
            $job_id      = $this->post('job_id');
            
            $userdata    = $this->user_model->check_unique(array("id" => $employer_id));
            $job         = $this->jobs_model->check_unique(array("id" => $job_id));
            $params      = $this->db->query("select * from notifications where user_id='".$employer_id."'")->result_array();
            $message     = 'Just a reminder that payment has not been completed on this job '.$job['job_name'];
            
            if(count($userdata)){
               sendNotification($userdata['devicetoken'],$message,count($params),$employer_id,'notificationCountMakePayment');
            }   
            
            update_notification_count($employer_id,'','notificationCountMakePayment',$job_id);
            return $this->response(array('status' =>'success','request_type' => 'send_message'), 200); 
        }
        
        function view_count_post()
        {
             if(!$this->post('user_id')){
    			return $this->response(array('status' => 'error','msg' => 'Required fields missing in your request','error_code' => "1"), 404);
    		 }
            
            $user_id = $this->post('user_id');
            $job_id  = $this->post('job_id');
            $type    = $this->post('type');
            
            $this->db->query("delete from notifications where user_id='".$user_id."' and notify_id='".$job_id."' and type='".$type."'");
            $notification_ct = get_notification_count($user_id,$job_id,$type);
            return $this->response(array('status' =>'success','request_type' => 'view_count', 'count' => $notification_ct), 200);
        }
           
         function job_canceled_post()
        {
            if(!$this->post('job_id') && !$this->post('employer_id') && !$this->post('employee_id')){
    			return $this->response(array('status' => 'error','msg' => 'Required fields missing in your request','error_code' => "1"), 404);
    		}
            
            $job_id      = $this->post('job_id');
            $employer_id = $this->post('employer_id');
            $employee_id = $this->post('employee_id');
            
            $employerdata= $this->user_model->check_unique(array("id" => $employer_id));
            $employeedata= $this->user_model->check_unique(array("id" => $employee_id));
            $job         = $this->jobs_model->check_unique(array("id" => $job_id));
            $employer_params = $this->db->query("select * from notifications where user_id='".$employer_id."'")->result_array();
            $employee_params = $this->db->query("select * from notifications where user_id='".$employee_id."'")->result_array();
            $message     = 'Star Notification for '.$job['job_name'];
            
             if(count($employerdata) && count($employeedata)){
               sendNotification($employerdata['devicetoken'],$message,count($employer_params),$employer_id,'jobcanceled');
               sendNotification($employeedata['devicetoken'],$message,count($employee_params),$employee_id,'jobcanceled');
               update_notification_count($employer_id,$message,'jobcanceled',$job_id);
               update_notification_count($employee_id,$message,'jobcanceled',$job_id);
               update_notification_count($employee_id,$message,'notificationCountStarRating',$job_id); 
               update_notification_count($employer_id,$message,'notificationCountStarRating',$job_id);
             }
            
            return $this->response(array('status' =>'success','request_type' => 'job_canceled'), 200);
        }
        
        function get_average_rating_post()
        {
            if(!$this->post('user_id') && !$this->post('type')){
    			return $this->response(array('status' => 'error','msg' => 'Required fields missing in your request','error_code' => "1"), 404);
    		}
            
            $type    = $this->post('type');
            $user_id = $this->post('user_id');
            
            if($type == 'employee'){
                $rating = $this->db->query("select * from rating where employee_id='".$user_id."'")->result_array();
            }  
            else
            {
                $rating = $this->db->query("select * from rating where employer_id='".$user_id."'")->result_array();
            }
                $rt = '';            
            foreach($rating as $rkey => $rvalue){
                $rt += $rvalue['rating'];
            }    
            $ratio = $rt/count($rating);
            return $this->response(array('status' =>'success','request_type' => 'get_average_rating','average_rating' => $ratio), 200);    
        }   
}

?>

