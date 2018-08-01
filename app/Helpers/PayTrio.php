<?php

namespace Helpers;

class PayTrio
{
    protected $URL = 'https://tip.pay-trio.com/ru';
    protected $SHOP_ID = '';
    protected $SHOP_SECRET = '';

    public function __construct($shop_id, $secret)
    {
        $this->SHOP_ID = $shop_id;
        $this->SHOP_SECRET = $secret;
    }

    public function send($form_data)
    {
        $data = $this->create_tip_params($this->SHOP_ID, $this->SHOP_SECRET, $form_data['amount'], $form_data['currency'], $form_data['shop_invoice_id'], $form_data['description']);

        return $data;
    }

    protected function create_tip_params($shop_id, $secret, $amount, $currency, $shop_order_id, $description)
    {
        $request = [
            "shop_id" => $shop_id,
            "amount" => $amount,
            "currency" => $currency,
            "shop_invoice_id" => $shop_order_id
        ];

        $sign = $this->get_signature($request, $secret);
        $request["sign"] = $sign;

        $request["description"] = $description;
        // here can add additional params
        // $request["phone"] = "1234567";

//        print_r($request);

        // for sending get request
         $get_url = $this->URL."?".http_build_query($request);

        // POST request for sending to TIP url
//        return $request;
        return ['url' => $get_url, 'params' => $request];
    }

    protected function get_signature($request_sorted, $secret)
    {
        ksort($request_sorted);
        $vals = array_values($request_sorted);
        $joined = join(":", $vals);

        return md5($joined.$secret);
    }

    protected function form($request)
    {
        $form = "<form id='my_form' action='" . $this->URL . "' method='post'>
                    <input type='hidden' name='shop_id' value='" . $request['shop_id'] . "' />
                    <input type='hidden' name='amount' value='" . $request['amount'] . "' />
                    <input type='hidden' name='currency' value='" . $request['currency'] . "' />
                    <input type='hidden' name='shop_invoice_id' value='" . $request['shop_invoice_id'] . "' />
                    <input type='submit' name='submission_button' value='Click here if the site is taking too long to redirect!' />
                </form>
                <script type='text/javascript'>
                    document.getElementById('my_form').submit();
                </script>";

        return $form;
    }

    public function check()
    {

    }
}