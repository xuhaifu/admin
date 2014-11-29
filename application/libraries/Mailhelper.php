<?php
require_once APPPATH.'third_party/PHPMailer/class.phpmailer.php';
require_once APPPATH.'third_party/PHPMailer/class.smtp.php';

class Mailhelper {
	
	protected $ci;
	
	public $mail;
	
	public function __construct($email_config = 'email') {
		$this->ci = & get_instance ();
		$this->ci->config->load ($email_config);
		$this->mail = new PHPMailer();
		$this->mail->CharSet = $this->ci->config->item('charset');
		$this->mail->Timeout = $this->ci->config->item('timeout');
		$this->mail->IsSMTP();
		$this->mail->SMTPAuth = $this->ci->config->item('smtp_auth');
		$this->mail->SMTPSecure = $this->ci->config->item('smtp_secure');
		$this->mail->Host = $this->ci->config->item('smtp_host');
		$this->mail->Port = $this->ci->config->item('smtp_port');
		$this->mail->Username = $this->ci->config->item('smtp_user');
		$this->mail->Password = $this->ci->config->item('smtp_pass');
		$this->mail->From = $this->ci->config->item('smtp_from');
		$this->mail->FromName = $this->ci->config->item('smtp_from_name');
		$this->mail->IsHTML(true);
	}
	
	public function send($to, $subject, $message) {
		if(is_array($to) && count($to) > 0) {
			foreach($to as $val) {
				$this->mail->AddAddress(trim($val));
			}
		} else {
			$this->mail->AddAddress(trim($to));
		}
		
		$this->mail->Subject = $subject;
		$this->mail->MsgHTML($message);
		if ($this->mail->Send()) {
			return true;
		}
		return false;
	}
}