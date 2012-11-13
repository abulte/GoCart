<?php

class Order extends Front_Controller { 
    
    var $customer;

    function __construct()
    {
        parent::__construct();    
        force_ssl();
        $this->lang->load('order');        
        $this->customer = $this->go_cart->customer();
    }


    function index()
    {
        show_404();
    }

    function view($id)
    {

        if (! $this->Customer_model->is_logged_in(false, false)){
            redirect('secure/login');
        }

        $this->load->model('Gift_card_model');
                
        $message = $this->session->flashdata('message');
        
        $data['page_title'] = lang('view_order') . " #$id";
        $data['order']      = $this->Order_model->get_order($id);

        if($data['order']->customer_id != $this->customer['id']){
            $this->session->set_flashdata('error', lang('not_allowed'));
            redirect('secure/my_account');
        }

        if (sizeof($data['order']) == 0){
            show_404();
        }
        
        // we need to see if any items are gift cards, so we can generate an activation link
        foreach($data['order']->contents as $orderkey=>$product)
        {
            if(isset($product['is_gc']) && (bool)$product['is_gc'])
            {
                if($this->Gift_card_model->is_active($product['sku']))
                {
                    $data['order']->contents[$orderkey]['gc_status'] = '[ '.lang('giftcard_is_active').' ]';
                } else {
                    $data['order']->contents[$orderkey]['gc_status'] = ' [ <a href="'. base_url() . $this->config->item('admin_folder').'/giftcards/activate/'. $product['code'].'">'.lang('activate').'</a> ]';
                }
            }
        }
        
        $this->load->view('order', $data);
        
    }
}
