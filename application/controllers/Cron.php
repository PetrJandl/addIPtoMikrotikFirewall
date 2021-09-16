<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		// Your own constructor code
		if(!is_cli()){ die("nope"); }
	}

	public function index()
	{
		$this->load->helper('iprange');
		//$this->config->load('routeros');
		//vytahneme neaktivovane zaznamy
		$query = $this->db->get_where('IMAPwhiteList', array('active' => 0));
		foreach ($query->result() as $i=>$row)
		{
			$data[$i]['id']=$row->ip;
			if($row->rangeBegin!="" AND $row->rangeEnd!=""){
				if($row->network!=iprange2cidr($row->rangeBegin, $row->rangeEnd)){
					$row->network=iprange2cidr($row->rangeBegin, $row->rangeEnd);
					$this->db->set('network', $row->network);
                                        $this->db->where('ip', $data[$i]['id']);
                                        $this->db->update('IMAPwhiteList');
				}
			}
			if($row->network==""){
				if($row->rangeBegin!="" AND $row->rangeEnd!=""){
					$data[$i]['ip']=iprange2cidr($row->rangeBegin, $row->rangeEnd);
					$data[$i]['comment']=$row->comment;
				}else{
					$data[$i]['ip']=$row->ip;
					$data[$i]['comment']=$row->comment;

					
					$this->load->library('email');
					$config['protocol'] = 'mail';
					$this->email->initialize($config);
					$this->email->from($this->config->item('whitelist_mail_from'), 'IP White List');
					$this->email->to($this->config->item('whitelist_mail_to'));
					$this->email->subject('Samostatná IP!');
					$this->email->message('Nenalezen blok, přidána 1 IP addresa! 
https://www.lupa.cz/nastroje/whois/?qs='.$row->ip.'&kde=obecny&hledat=Hledat');
					$this->email->send();
					
					echo 'Nenalezen blok, přidána 1 IP addresa! https://www.lupa.cz/nastroje/whois/?qs='.$row->ip.'&kde=obecny&hledat=Hledat
';
				}
			}else{
				$data[$i]['ip']=$row->network;
				$data[$i]['comment']=$row->comment;
			}
		}
//		print_r($data);
		if(isset($data[0])){
//			print_r($data);
			require( APPPATH . 'third_party/routeros_api.class.php');
	
			$API = new RouterosAPI();
			$API->debug = false;
			if ($API->connect( $this->config->item('routeros_ip'), $this->config->item('routeros_user'), $this->config->item('routeros_pass') )) {
			$API->write('/ip/firewall/address-list/getall
?list=IMAPwhaitList');
			$READ = $API->read(false);
			$ARRAY = $API->parseResponse($READ);
//			print_r($ARRAY);
			foreach($ARRAY as $i=>$record){
				foreach($data as $i2=>$new){
					if($record['address']==$new['ip']){
							$black[]=$new['ip'];
							$data[$i2]['inserted']="OK";
					}
				}
			}
			$black=array_unique($black);
			if(isset($black[0])){
				echo "Vkladani existujici/ch adres/y! ";
				print_r($black);
			}
			foreach($data as $i=>$new){
				if(!isset($new['inserted'])){
					$API->comm("/ip/firewall/address-list/add", array(
						"list"		=> "IMAPwhaitList",
						"address"	=> $new['ip'],
						"comment"	=> $new['comment']
					));
					// otestovani vlozeni do MK
					$API->write('/ip/firewall/address-list/getall
?list=IMAPwhaitList');
					$READ = $API->read(false);
                			$ARRAY = $API->parseResponse($READ);
					//print_r($ARRAY);
					foreach($ARRAY as $i=>$record){
                		                foreach($data as $i2=>$new){
                                		        if($record['address']==$new['ip']){
		                                        	$data[$i2]['inserted']="OK";
		                                        }
                		                }
		                        }

				}
			}
		

			$API->disconnect();	
		}
//		print_r($data);
		//vlozit do db ze se podvedlo vlozeni do MK
		foreach($data as $i=>$line){
			if($line['inserted']){
				$this->db->set('active', 1);
				$this->db->where('ip', $line['id']);
				$this->db->update('IMAPwhiteList');
/*
				echo $this->db->last_query()."
";
*/
			}
		}


		}
	}
}
