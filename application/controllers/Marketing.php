<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
include(APPPATH . '/libraries/koolreport/core/autoload.php');

require APPPATH . '/libraries/BaseControllerWeb.php';

use \koolreport\drilldown\DrillDown;
use \koolreport\widgets\google\ColumnChart;
use \koolreport\clients\Bootstrap;


class Marketing extends BaseControllerWeb
{
	private $vendor_id;
	public function __construct()
	{
		parent::__construct();
		$this->load->model('marketing_model');
		$this->load->library('language', array('controller' => $this->router->class));
		$this->isLoggedIn();
		$this->vendor_id = $this->session->userdata("userId");
	}

	public function index()
	{ 
		$data['title'] = 'invoices';
		$vendor_id = $this->vendor_id;
		$this->global['pageTitle'] = 'TIQS: Invoices';
		$this->loadViews("marketing/reports", $this->global, $data, 'footerbusiness', 'headerbusiness');
	}

	public function get_marketing_data(){
		$context = stream_context_create([
            "http" => [
                "header" => "authtoken:eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyIjoidGlxc3dlYiIsIm5hbWUiOiJ0aXFzd2ViIiwicGFzc3dvcmQiOm51bGwsIkFQSV9USU1FIjoxNTgyNTQ2NTc1fQ.q7ssJqcwsXhuNVDyspGYh_KV7_JsbwS8vq2TT9R-MGk"
                ]
            ]);
            $data = file_get_contents("http://tiqs.com/backoffice/admin/api/invoice/data/47", false, $context );
            echo $data;
		
	}


}
