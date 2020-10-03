<?php

namespace Apps;

class Emailer{

	public $toEmail = NULL;
	public $toName = NULL;
	public $fromEmail = "info@crowdcob.com";
	public $fromName = "Info@CrowdCOB";
	public $replyEmail = "info@crowdcob.com";
	public $replyName = "Info@CrowdCOB";
	public $subject = "Crowd Confederation Of Businesses";
	
	var $recipients = array();
    var $EmailTemplate;
    var $EmailContents;

    public function __construct($to = false){
        if($to !== false){
            if(is_array($to)){
                foreach($to as $_to){
					$this->recipients[$_to] = $_to;
				}
            }else{
                $this->recipients[$to] = $to;
            }
        }
    }

    public function __set($key,$val){
        $this->variables[$key] = $val;
    }

    function SetTemplate(EmailTemplate $EmailTemplate){
        $this->EmailTemplate = $EmailTemplate;            
    }

    function send() {
        $html = $this->EmailTemplate->compile();
		try{
			$PHPmailer = new PHPMailer(true);
			$PHPmailer->AddAddress($this->toEmail,$this->toName);
			$PHPmailer->setFrom( $this->fromEmail,$this->fromName );
			$PHPmailer->AddReplyTo( $this->replyEmail,$this->replyName );
			$PHPmailer->Sender = "bounced@crowdcob.com";
			$PHPmailer->Subject = $this->subject;
			
			//$PHPmailer->DKIM_domain = 'crowdcob.com';
			//$PHPmailer->DKIM_private = ROOT . "/etc/_DKIM/private.key";
			//$PHPmailer->DKIM_selector = '1513355176.vetuweb';
			//$PHPmailer->DKIM_passphrase = '';
			//$PHPmailer->DKIM_identity = $PHPmailer->From;
			
			$PHPmailer->isHTML(true);
			$PHPmailer->MsgHTML($html);
			$PHPmailer->Encoding = "base64";
	
			$sent = $PHPmailer->Send();
			return $sent;
			
		}catch(phpmailerException $e){
				return $sent;
		}catch (Exception $e) {
				return $sent;
		}
    }



	
}
	
	

	
	
?>