<?php
///////////////////////////////////////////////////////////////////////////////////
// Email Class
// Site: postcode.auspost.com.au
// Version 0.0.1
// Author: Gordon MacK
// Copyright Owner: Raremedia Pty Ltd (Andrew Davidson)
///////////////////////////////////////////////////////////////////////////////////

class email
{

    //generate product cross sell markup
    private function make_cross_sell($id)
    {
        global $product;

        $return = '';

        if(!isset($id) || empty($id)){
            return $return;
        }

        //get cross sell data
        $cross_sell = $product->get_cross_sell($id);
        if($cross_sell['boolean']){

            //start ugly email markup
            $return .= '
    			<tr>
    				<td class="bg-blue text-center" style="text-align:center;padding-top:14px;padding-bottom:14px;background-color:#e2f7f5;">
    					<table border="0" cellpadding="0" cellspacing="0" width="100%" align="center">
    						<tr style="text-align:center;"><td style="text-align:center;"><p>You may also be interested in some<br />of our other data products</p></td></tr>
            ';

            //loop through each cross sell product found
            foreach($cross_sell['content'] as $cs){

                //set cross sell product row
                $return .= '
                    <tr style="text-align:center; padding: 14px 0;">
                        <td>
                            <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" style="background-color:#e2f7f5;">
                                <tbody>
                                    <tr>
                                        <td class="" height="10">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <table width="60%" border="0" cellspacing="5" cellpadding="0" align="center" style="background-color:#26bfa2;border-radius:9px;overflow:hidden;">
                                <tbody>
                                    <tr>
                                        <td class="" height="42" style="padding-left:20px;padding-right:20px;font-family:Helvetica Neue,Arial,sans-serif;font-size:14px;text-align:center;color:white;font-weight:bold">
                                            <a href="'.PROTOCOL.'://'.$_SERVER['HTTP_HOST'].'/product_display.html?id='.$cs['product_id'].'" title="'.$cs['name'].'" style="display:block;width:100%;margin:0;color:white;text-decoration:none;font-family:Arial,sans-serif;font-weight:400;line-height:1.2;font-size:15px;" target="_blank">
                                                '.$cs['name'].'&nbsp;&nbsp; <span>&rsaquo;</span>
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" style="background-color:#e2f7f5;">
                                <tbody>
                                    <tr>
                                        <td class="" height="10">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                ';
            }

            //end ugly email markup
            $return .= '
                        </table>
                    </td>
                </tr>
            ';
        }

        return $return;
    }


    //send tax invoice to subscriber
    public function send_invoice($subscriber_data, $product_data, $alt_email = '')
    {
        global $product;

        //make sure required parameters are set
        if (
            empty($subscriber_data) ||
            empty($product_data) ||
            !isset($subscriber_data['delivery_email']) ||
            empty($subscriber_data['delivery_email'])
        ) {
            return false;
        }

        //set container for email content parameters
        $content_params = array();
        $content_params['contact_name'] = $subscriber_data['contact_name'];
        $content_params['product_name'] = $product_data['name'];
        $content_params['product_sku'] = $product_data['sku'];
        $content_params['subscription_term'] = $product_data['subscription_term'];
        $content_params['delivery_interval'] = ucwords($subscriber_data['product_delivery_interval']);
        $content_params['data_filter'] = ucwords($subscriber_data['product_data_filter']);
        $content_params['data_needle'] = implode(' / ', (array)json_decode($subscriber_data['product_data_needle']));
        $content_params['subscriber_id'] = $subscriber_data['subscriber_id'];
        $content_params['paid_total'] = '$'.number_format($subscriber_data['paid_total'], 2);
        $content_params['business_name'] = $subscriber_data['business_name'];
        $content_params['business_address'] = $subscriber_data['business_address'];
        $content_params['business_suburb'] = $subscriber_data['business_suburb'];
        $content_params['business_state'] = $subscriber_data['business_state'];
        $content_params['business_postcode'] = $subscriber_data['business_postcode'];
        $content_params['business_department'] = $subscriber_data['business_department'];
        $content_params['contact_phone'] = $subscriber_data['contact_phone'];
        $content_params['delivery_email'] = $subscriber_data['delivery_email'];
        $content_params['support_email'] = $subscriber_data['support_email'];
        $content_params['business_abn'] = $subscriber_data['business_abn'];

        //generate cross-sell content
        $content_params['cross_sell'] = $this->make_cross_sell($product_data['product_id']);

        //load email template
        $content = file_get_contents(ROOT . 'secure/email/invoice.html');

        //replace keys in template with content parameters
        foreach ($content_params as $k => $v) {

            //replace template keys with content values
            $content = str_replace('{{'.$k.'}}', $v, $content);
        }

        //instantiate AWS SES
        include_once(LOCAL.'secure/class/SES.php');
        $ses = new SimpleEmailService(IAM_KEY_ID, IAM_KEY_SECRET);
        $m = new SimpleEmailServiceMessage();

        //alter delivery email if an alternate email is set
        if(!empty($alt_email)){
            $m->addTo($alt_email);
        }else{
            $m->addTo($subscriber_data['delivery_email']);
        }

        //set the rest of the email parameters
        $m->setFrom('Australia Post <no-reply@postcode.auspost.com.au>');
        $m->setSubject($product_data['name'].' Tax Invoice - Australia Post');
        $m->setMessageFromString('This is an email from Australia Post. HTML is required to view this email', $content);
        $ses->sendEmail($m);

        return true;
    }


    //send product download link to subscriber
    public function send_link_download($subscriber_data, $product_data, $bookmark, $alt_email = '')
    {

        //make sure required parameters are set
        if (
            empty($subscriber_data) ||
            empty($product_data) ||
            !isset($subscriber_data['delivery_email']) ||
            empty($subscriber_data['delivery_email']) ||
            !isset($bookmark) ||
            empty($bookmark)
        ) {
            return false;
        }

        //set container for email content parameters
        $content_params = array();
        $content_params['contact_name'] = $subscriber_data['contact_name'];
        $content_params['product_name'] = $product_data['name'];
        $content_params['product_sku'] = $product_data['sku'];
        $content_params['subscription_term'] = $product_data['subscription_term'];
        $content_params['delivery_interval'] = ucwords($subscriber_data['product_delivery_interval']);
        $content_params['data_filter'] = ucwords($subscriber_data['product_data_filter']);
        $content_params['data_needle'] = implode(' / ', (array)json_decode($subscriber_data['product_data_needle']));
        $content_params['subscriber_id'] = $subscriber_data['subscriber_id'];
        $content_params['link'] = PROTOCOL.'://'.$_SERVER['HTTP_HOST'].'/product_download.html?bookmark='.$bookmark;

        //generate cross-sell content
        $content_params['cross_sell'] = $this->make_cross_sell($product_data['product_id']);

        //load email template
        $content = file_get_contents(ROOT . 'secure/email/link_deliver.html');

        //replace keys in template with content parameters
        foreach ($content_params as $k => $v) {

            //replace template keys with content values
            $content = str_replace('{{'.$k.'}}', $v, $content);
        }

        //instantiate AWS SES
        include_once(LOCAL.'secure/class/SES.php');
        $ses = new SimpleEmailService(IAM_KEY_ID, IAM_KEY_SECRET);
        $m = new SimpleEmailServiceMessage();


        //alter delivery email if an alternate email is set
        if(!empty($alt_email)){
            $m->addTo($alt_email);
        }else{
            $m->addTo($subscriber_data['delivery_email']);
        }

        //set the rest of the email parameters
        $m->setFrom('Australia Post <no-reply@postcode.auspost.com.au>');
        $m->setSubject($product_data['name'].' Delivery - Australia Post');
        $m->setMessageFromString('This is an email from Australia Post. HTML is required to view this email', $content);
        $ses->sendEmail($m);

        return true;
    }


    //send product to subscriber as attachment
    public function send_attachment($subscriber_data, $product_data, $file_content, $alt_email = '')
    {

        //make sure required parameters are set
        if (
            empty($subscriber_data) ||
            empty($product_data) ||
            !isset($subscriber_data['delivery_email']) ||
            empty($subscriber_data['delivery_email']) ||
            !isset($file_content) ||
            empty($file_content)
        ) {
            return false;
        }

        //set container for email content parameters
        $content_params = array();
        $content_params['contact_name'] = $subscriber_data['contact_name'];
        $content_params['product_name'] = $product_data['name'];
        $content_params['product_sku'] = $product_data['sku'];
        $content_params['subscription_term'] = $product_data['subscription_term'];
        $content_params['delivery_interval'] = ucwords($subscriber_data['product_delivery_interval']);
        $content_params['data_filter'] = ucwords($subscriber_data['product_data_filter']);
        $content_params['data_needle'] = implode(' / ', (array)json_decode($subscriber_data['product_data_needle']));
        $content_params['subscriber_id'] = $subscriber_data['subscriber_id'];

        //generate cross-sell content
        $content_params['cross_sell'] = $this->make_cross_sell($product_data['product_id']);

        //load email template
        $content = file_get_contents(ROOT . 'secure/email/attach_deliver.html');

        //replace keys in template with content parameters
        foreach ($content_params as $k => $v) {

            //replace template keys with content values
            $content = str_replace('{{'.$k.'}}', $v, $content);
        }

        //instantiate AWS SES
        include_once(LOCAL.'secure/class/SES.php');
        $ses = new SimpleEmailService(IAM_KEY_ID, IAM_KEY_SECRET);
        $m = new SimpleEmailServiceMessage();

        //alter delivery email if an alternate email is set
        if(!empty($alt_email)){
            $m->addTo($alt_email);
        }else{
            $m->addTo($subscriber_data['delivery_email']);
        }

        //set filename
        $file_name = trim(str_replace(' ', '_', strtolower($product_data['name']))).'_'.trim(str_replace(' ', '_', strtolower($product_data['sku']))).'_' . date('dmY') . '.csv';

        //set the rest of the email parameters
        $m->setFrom('Australia Post <no-reply@postcode.auspost.com.au>');
        $m->setSubject($product_data['name'].' Delivery - Australia Post');
        $m->setMessageFromString('This is an email from Australia Post. HTML is required to view this email', $content);
        $m->addAttachmentFromData($file_name, $file_content, 'text/csv');
        $ses->sendEmail($m);

        return true;
    }
}
