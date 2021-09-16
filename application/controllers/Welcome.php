<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

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
	public function index()
	{
		$data['ip']=$this->input->ip_address();
		$data['add']=false;
		$data['message']="";
		$insert=false;
		$descr=array();
		$this->load->helper('iprange');
		$private = 
			!checkiprange($this->input->ip_address(), '0.0.0.0', '0.0.0.0') &&
			!checkiprange($this->input->ip_address(), '14.0.0.0', '14.255.255.255') &&
			!checkiprange($this->input->ip_address(), '127.0.0.0', '127.255.255.255') &&
			!checkiprange($this->input->ip_address(), '169.254.0.0', '169.254.255.255') &&
			!checkiprange($this->input->ip_address(), '192.0.2.0', '192.0.2.254') &&
			!checkiprange($this->input->ip_address(), '224.0.0.0', '239.255.255.255') &&
			!checkiprange($this->input->ip_address(), '10.0.0.0', '10.255.255.255') &&
			!checkiprange($this->input->ip_address(), '172.16.0.0', '172.31.255.255') &&
			!checkiprange($this->input->ip_address(), '192.168.0.0', '192.168.255.255');

		if($this->input->method() === 'post')
		{
			if(isset($_POST['g-recaptcha-response'])){
	 			$recaptcha = json_decode(file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$this->config->item('recap_secret').'&response=' . $_POST['g-recaptcha-response']));
                        	if($recaptcha->{'success'} == 'true'){
					        include_once( APPPATH . 'third_party/phpwhois/src/whois.main.php');
					        //include_once( APPPATH . 'third_party/phpwhois/src/whois.utils.php');

					        $whois = new Whois();
						//$utils = new Utils;

					        // Set to true if you want to allow proxy requests
					        $allowproxy = false;

					        // get faster but less acurate results^M
					        $whois->deep_whois = true;

					        // Comment the following line to disable support for non ICANN tld's
					        $whois->non_icann = true;

					        $result = $whois->Lookup($data['ip']);

						if(!isset($result['rawdata'][0])){
							$whois->deep_whois = false;
							$result = $whois->Lookup($data['ip']);
						}
					        //echo "<pre>";
 						//print_r($result['rawdata']);
						//die();
						foreach($result['rawdata'] as $num => $line){
							if(strpos($line, 'NetRange:')!== false OR strpos($line, 'range')!== false OR strpos($line, 'inetnum:')!== false){
								$NetRange=explode(":", $line);
							}
							if(strpos($line, 'CIDR:')!== false OR strpos($line, 'route:')!== false){
                                                                $CIDR=explode(":", $line);
                                                        }
							if(strpos($line, 'country')!== false OR strpos($line, 'Country')!== false){
                                                                $zem=explode(":", $line);
                                                        }
							if(strpos($line, 'org-name')!== false OR strpos($line, 'descr')!== false OR strpos($line, 'Organization')!== false){
                                                                $descr[]=explode(":", $line);
                                                        }

						}
						$range=explode("-", trim($NetRange[1]));
						$min=trim($range[0]);
						$max=trim($range[1]);
						$cidr=trim($CIDR[1]);
						$country=trim($zem[1]);
						
						$des="";
						foreach ($descr as $n => $desc){
							$des.=trim($desc[1].",");
						}
						$des=rtrim($des,",");
						
						
						//echo "Min: ". $min . " Max: ". $max . " CIDR:".$cidr;
						//die();

					$this->db->insert('IMAPwhiteList', array('ip' => $data['ip'], 'network' => $cidr, 'rangeBegin' => $min, 'rangeEnd' => $max, 'comment' => $country.", ".$des ));
					if($this->db->affected_rows()==1){
 						$data['message'].="<div class=\"green\">Adresa vlozena do DB!</div>";
						$insert=true;
					}else{
 						$data['message'].="<span class=\"red\">Nezdařilo se uložení do DB! Nahlaš chybu dík.</span>";
					}
				}else{
					$data['message'].="CHYBA! reCaptcha selhala zkus to znova :'(";
				}
			}else{
 					$data['message'].="CHYBA! Formulář nelze odeslat ihned po kliknutí na nejsem robot, je třeba počkat až se zeleně zafajfkuje :'(";
			}

		}

		if ($private){
$sql="SELECT * FROM `IMAPwhiteList`
WHERE
(substring_index(substring_index('".$data['ip']."', '.', 1), '.', -1) BETWEEN substring_index(substring_index(rangeBegin, '.', 1), '.', -1)-1 AND substring_index(substring_index(rangeEnd, '.', 1), '.', -1)+1)
AND
(substring_index(substring_index('".$data['ip']."', '.', 2), '.', -1) BETWEEN substring_index(substring_index(rangeBegin, '.', 2), '.', -1)-1 AND substring_index(substring_index(rangeEnd, '.', 2), '.', -1)+1)
AND
(substring_index(substring_index('".$data['ip']."', '.', 3), '.', -1) BETWEEN substring_index(substring_index(rangeBegin, '.', 3), '.', -1)-1 AND substring_index(substring_index(rangeEnd, '.', 3), '.', -1)+1)
AND
(substring_index(substring_index('".$data['ip']."', '.', 4), '.', -1) BETWEEN substring_index(substring_index(rangeBegin, '.', 4), '.', -1)-1 AND substring_index(substring_index(rangeEnd, '.', 4), '.', -1)+1)";

			$query = $this->db->query($sql);
			//echo $this->db->last_query();
			$row = $query->row();
			if($query->num_rows()){
				if(!$insert){
					$data['message'].="Addresa (blok) je na whitelistu.<br />";
				}
				if(!$row->active){
					$data['message'].="<span class=\"red\">Vyčkejte na aktivaci .. <span id=\"timer\"></span></span> (max. 60 vteřin).";
					$data['reload']=true;
				}else{
					$data['message'].="<span class=\"green\">Adresa je aktivovana!</span>";
				}
				
				
			}else{
			    $data['message'].='<form action="/" method="POST"><div class="g-recaptcha" data-sitekey="'.$this->config->item('recap_site_key').'" data-theme="dark"></div><br/>
					<input type="hidden" name="ip" value=' . $data["ip"] . ' />
					<input type="submit" value="OK" class="btn btn-primary" /></form>';
			    $data['add']=true;
			}
			
			#$indb
		}else{
			$data['message']="Adresa nelze přidat.";
		}
		$data['allAllowIP']=0;
		$sql2="SELECT * FROM IMAPwhiteList WHERE active = 1";
		$query2 = $this->db->query($sql2);
		$row2 = $query2->row();
		if($query2->num_rows()!=0){
			foreach($query2->result_array() as $row2){
				$data['allAllowIP']+=ip_range($row2['rangeBegin'], $row2['rangeEnd']);
			}
		}
		

		$this->load->view('welcome_message', $data);
	}
}
