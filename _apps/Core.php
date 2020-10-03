<?php

//Write your custome class/methods here
namespace Apps;
use \Apps\Model;
//use \Apps\Session;

class Core extends Model{

	public $token = NULL;
	public $ngn = "&#x20A6;";
	
	public function __construct(){
		parent::__construct();
	}
	
	public function GenPassword($length=10) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
	
	public function GenOTP($length=10) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return strtoupper($randomString);
	}
	
	public function ToMoney($amount){
		$amount = number_format($amount,2,".",",");
		return "&#x20A6;" . $amount;
	}
	public function NGN(){
		return "&#x20A6;";
	}
	
	public function Monify($amount){
		$amount = number_format($amount,0,".",",");
		return "&#x20A6;" . $amount;
	}

	
	public function Passwordify($password) {
		$Passwordify = md5($password);
		return $Passwordify;
	}

	public function Cycle($time_ago){
	  $current_time    = time();
	  $time_difference = $current_time - $time_ago;
	  $seconds         = $time_difference;
	
	  $minutes = round($seconds / 60); // value 60 is seconds  
	  $hours   = round($seconds / 3600); //value 3600 is 60 minutes * 60 sec  
	  $days    = round($seconds / 86400); //86400 = 24 * 60 * 60;  
	  $weeks   = round($seconds / 604800); // 7*24*60*60;  
	  $months  = round($seconds / 2629440); //((365+365+365+365+366)/5/12)*24*60*60  
	  $years   = round($seconds / 31553280); //(365+365+365+365+366)/5 * 24 * 60 * 60
					
	  if ($seconds <= 60){
		return "Just Now";
	  } else if ($minutes <= 60){
		if ($minutes == 1){
		  return "one minute ago";
		} else {
		  return "$minutes minutes ago";
		}
	  } else if ($hours <= 24){
		if ($hours == 1){
		  return "an hour ago";
		} else {
		  return "$hours hrs ago";
		}
	  } else if ($days <= 7){
		if ($days == 1){
		  return "yesterday";
		} else {
		  return "$days days ago";
		}
	  } else if ($weeks <= 4.3){
		if ($weeks == 1){
		  return "a week ago";
		} else {
		  return "$weeks weeks ago";
		}
	  } else if ($months <= 12){
		if ($months == 1){
		  return "a month ago";
		} else {
		  return "$months months ago";
		}
	  } else {
		if ($years == 1){
		  return "one year ago";
		} else {
		  return "$years years ago";
		}
	  }
	}

	public function AddMinsToDate($mins) {
		$date = new \DateTime();
		$date->setTimezone(new \DateTimeZone('Africa/Lagos'));
		$date->modify("+{$mins} minutes"); 
		return $date->format('Y-m-d h:i:s');
	}	
	
	public function RemoveMinsToDate($mins) {
		$date = new \DateTime();
		$date->setTimezone(new \DateTimeZone('Africa/Lagos'));
		$date->modify("-{$mins} minutes"); 
		return $date->format('Y-m-d h:i:s');
	}	
	
	public function timepast($datetime, $full = false) {

		$now = new \DateTime;
		$ago = new \DateTime($datetime);
		
		$diff = $now->diff($ago);
	
		$diff->w = floor($diff->d / 7);
		$diff->d -= $diff->w * 7;
	
		$string = array(
			'y' => 'year',
			'm' => 'month',
			'w' => 'week',
			'd' => 'day',
			'h' => 'hour',
			'i' => 'minute',
			's' => 'second',
		);
		foreach ($string as $k => &$v) {
			if ($diff->$k) {
				$v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
			} else {
				unset($string[$k]);
			}
		}
	
		if (!$full) $string = array_slice($string, 0, 1);
		return $string ? implode(', ', $string) . ' ago' : 'just now';
	}	
	
	public function PosLogin($username,$password){
		$PosLogin = mysqli_query($this->dbCon,"SELECT * FROM igr_pos_users WHERE username='$username' AND password='$password'");
		$PosLogin = mysqli_fetch_object($PosLogin);
		return $PosLogin;
	}
	
	
	public function POSInfo($posid){
		$POSInfo = mysqli_query($this->dbCon,"SELECT * FROM igr_pos WHERE id='$posid'");
		$POSInfo = mysqli_fetch_object($POSInfo);
		return $POSInfo;
	}
	
	
	public function splitMDA($accountinfo){
		$split = explode("-",$accountinfo);
		$objSplit = new \stdClass;
		$objSplit->mda = $split[0];
		$objSplit->username = $split[1];
		return $objSplit;
	}
	
	
		
	public function POSPackages($pos){
		$POSPackages = mysqli_query($this->dbCon,"select * from `igr_mda_dnos_packages`");
		return $POSPackages;
	}

	public function POSUserInfo($accid){
		$POSUserInfo = mysqli_query($this->dbCon,"select * from igr_pos_users where accid='$accid'");
		$POSUserInfo = mysqli_fetch_object($POSUserInfo);
		return $POSUserInfo;
	}

	public function GetPackageTotal($packages = array()){
		$itemsTotal = 0;
		foreach($packages as $package){
			$pack = mysqli_query($this->dbCon,"select price from igr_mda_dnos_packages where id='$package'");
			$pack = mysqli_fetch_object($pack);
			$itemsTotal += $pack->price;
		}
		return $itemsTotal;
	}
			
			
	public function createDNOInvoice($title,$surname,$othernames,$address,$mobile,$email,$package){
		$sess = new Session;
		$created_by = $sess->data['accid'];

		$customer_id =  $this->createCustomer($title,$surname,$othernames,$address,$mobile,$email);
		$items_qty = count($package);
		$items_package = json_encode($package);

		$items_total = $this->GetPackageTotal($package);
		
		$createDNOInvoice = mysqli_query($this->dbCon,"INSERT INTO igr_dnos(customer_id,items_qty,items_total,items_packages,created_by) VALUES('$customer_id','$items_qty','$items_total','$items_package','$created_by')");
		$createDNOInvoice = mysqli_insert_id($this->dbCon);
	
		return $createDNOInvoice;

	}
	
	public function createCustomer($title,$surname,$othernames,$address,$mobile,$email){
		$createCustomer = mysqli_query($this->dbCon,"INSERT INTO igr_customers(title,surname,othernames,address,mobile,email) VALUES('$title','$surname','$othernames','$address','$mobile','$email')");
		$createCustomer = mysqli_insert_id($this->dbCon);
		return $createCustomer;
	}
		
	public function UserInfo($igr,$accid){
		if($igr=="pos"){
			$UserInfo = mysqli_query($this->dbCon,"select * from igr_pos_users where accid='$accid'");
			$UserInfo = mysqli_fetch_object($UserInfo);
		}elseif($igr=="admin"){
			
		}else{

		}
		return $UserInfo;
	}	
	
	public function DNOInfo($dnoid){
		$DNOInfo = mysqli_query($this->dbCon,"SELECT * FROM igr_dnos WHERE id='$dnoid'");
		$DNOInfo = mysqli_fetch_object($DNOInfo);
		return $DNOInfo;
	}

	public function SetDNOInfo($dnoid,$key,$val){
		$SetUserInfo = mysqli_query($this->dbCon,"UPDATE igr_dnos SET $key='$val' where id='$dnoid'");
		return mysqli_affected_rows($this->dbCon);
	}

	public function CustomerInfo($customer_id){
		$CustomerInfo = mysqli_query($this->dbCon,"SELECT * FROM igr_customers WHERE id='$customer_id'");
		$CustomerInfo = mysqli_fetch_object($CustomerInfo);
		return $CustomerInfo;
	}
	
	public function PackageInfo($package_id){
		$PackageInfo = mysqli_query($this->dbCon,"select * from igr_mda_dnos_packages where id='$package_id'");
		$PackageInfo = mysqli_fetch_object($PackageInfo);
		return $PackageInfo;
	}
	
		
	public function NDOOders($status="pending"){
		$NDOOders = mysqli_query($this->dbCon,"select * from `igr_dnos` where status='$status'");
		return $NDOOders;
	}
	
	public function newMDA($mda){
		$newMDA = mysqli_query($this->dbCon,"INSERT INTO igr_mdas(`mda`) VALUES('$mda')");
		$newMDA = mysqli_insert_id($this->dbCon);
		return $newMDA;
	}
		
	
	public function createPackage($mda,$package,$revcode){
		$createPackage = mysqli_query($this->dbCon,"INSERT INTO igr_mda_dnos_packages(`mda`,`name`,`revcode`) VALUES('$mda','$package','$revcode')");
		$createPackage = mysqli_insert_id($this->dbCon);
		return $createPackage;
	}
	
	public function getMDAname($mda){
		$getMDAname = mysqli_query($this->dbCon,"select mda from igr_mdas where id='$mda'");
		$getMDAname = mysqli_fetch_object($getMDAname);
		return $getMDAname->mda;
	}
	
		
	public function GenInvoice($dno){
		$GenInvoice = mysqli_query( $this->dbCon,"SELECT id FROM igr_dno_invoice");
		$GenInvoice = mysqli_num_rows($GenInvoice);
		return $GenInvoice;
	}
	
	public function GenerateDNOInvoice($dnoid,$customer_id){

		$invoicenumber = "";
		$transid ="";

		$createCustomer = mysqli_query($this->dbCon,"INSERT INTO igr_dno_invoices(dnoid,customer_id,invicenumber,transid) VALUES('$dnoid','$customer_id','$invoicenumber','$transid')");
		$createCustomer = mysqli_insert_id($this->dbCon);
		
		return $createCustomer;

	}	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	

	
	public function VerifyOTP($accid,$otp){
		$VerifyOTP = mysqli_query($this->dbCon,"select otp from ebsg_accounts where accid='$accid'");
		$VerifyOTP = mysqli_fetch_object($VerifyOTP);
		$VerifyOTP = $VerifyOTP->otp;
		if($VerifyOTP==$otp){
			return true;
		}else{
			return false;
		}
	}
	public function SetUserInfo($accid,$key,$val){
		$SetUserInfo = mysqli_query($this->dbCon,"UPDATE ebsg_accounts SET $key='$val' where accid='$accid'");
		return mysqli_affected_rows($this->dbCon);
	}

	public function DeleteUser($accid){
		$DeleteUser = mysqli_query($this->dbCon,"DELETE ebsg_accounts.* from ebsg_accounts WHERE accid='$accid'");
		return $DeleteUser;
	}

	public function BankerInfo($id){
		$BankerInfo = mysqli_query($this->dbCon,"select * from ebsg_bankers where id='$id'");
		$BankerInfo = mysqli_fetch_object($BankerInfo);
		return $BankerInfo;
	}
	
	public function BankerInfoToBank($id){
		$result = new \stdClass;
		
		$BankerInfo = mysqli_query($this->dbCon,"select * from ebsg_bankers where id='$id'");
		$BankerInfo = mysqli_fetch_object($BankerInfo);
		
		$MdaInfo = $this->MdaInfo($BankerInfo->mda);
		$result->mda = $MdaInfo->mda;
		
		$result->accountname = $BankerInfo->accountname;
		$result->accountnumber = $BankerInfo->accountnumber;
		
		$Banker = $this->BankInfo($BankerInfo->bankid);
		$result->bank = $Banker->bank;
		
		return $result;
	}
	
	public function Bankers(){
		$Bankers = mysqli_query($this->dbCon,"select * from ebsg_bankers");
		return $Bankers;
	}

	public function MdaBankers($mda){
		$MdaBankers = mysqli_query($this->dbCon,"select * from ebsg_bankers where mda='$mda'");
		return $MdaBankers;
	}

	public function Accounts(){
		$Accounts = mysqli_query($this->dbCon,"select * from ebsg_accounts");
		return $Accounts;
	}

	public function CountUsers(){
		$result = new \stdClass;
		
		//SELECT id, category_id, post_title FROM posts WHERE id IN(SELECT MAX(id) FROM posts GROUP BY category_id);
		$CountUsers = mysqli_query($this->dbCon,"select count(accid) as nusers from ebsg_accounts");
		$CountUsers = mysqli_fetch_object($CountUsers);
		$result->nusers = $CountUsers->nusers;
		
		$now_time = time();

		$CountOnlineUsers = mysqli_query($this->dbCon,"SELECT count(*) As ousers FROM `ebsg_accounts` WHERE `lastaction` >= DATE_SUB(NOW(), INTERVAL {$this->session_timout} MINUTE)");
		$CountOnlineUsers = mysqli_fetch_object($CountOnlineUsers);
		$result->ousers = $CountOnlineUsers->ousers;

		return $result;
	}

	public function Mdas(){
		$Mdas = mysqli_query($this->dbCon,"select * from ebsg_mdas");
		return $Mdas;
	}
	
	public function MdaInfo($mda){
		$MdaInfo = mysqli_query($this->dbCon,"select * from ebsg_mdas where id='$mda'");
		$MdaInfo = mysqli_fetch_object($MdaInfo);
		return $MdaInfo;
	}
	
	
	public function CreateMDA($parent,$category,$mda,$description){
		$query = mysqli_query($this->dbCon,"INSERT INTO ebsg_mdas(parent,category,mda,description) VALUES('$parent','$category','$mda','$description')");
		return (int)mysqli_insert_id($this->dbCon);
	}
	
	public function BankInfo($bankerid){
		$BankInfo = mysqli_query($this->dbCon,"select * from ebsg_banks where id='$bankerid'");
		$BankInfo = mysqli_fetch_object($BankInfo);
		return $BankInfo;
	}
	
	public function CreateAccount($category,$sn,$fn,$email,$mobile,$username,$password,$office){
		$roots = ($category=="admin")?"my":"user";
		$query = mysqli_query($this->dbCon,"INSERT INTO ebsg_accounts(category,roots,lastname,firstname,email,mobile,username,password,office) VALUES('$category','$roots','$sn','$fn','$email','$mobile','$username','$password','$office')");
		return (int)mysqli_insert_id($this->dbCon);
	}
	
	public function ListBankers(){
		$ListBankers = mysqli_query($this->dbCon,"select * from ebsg_banks");
		return $ListBankers;
	}
	
	public function CreateBank($mda,$banker,$branch,$accountname,$accountnumber){
		$query = mysqli_query($this->dbCon,"INSERT INTO ebsg_bankers(mda,bankid,branch,accountname,accountnumber) VALUES('$mda','$banker','$branch','$accountname','$accountnumber')");
		return (int)mysqli_insert_id($this->dbCon);
	}
	
	public function setConnection($accid,$ip,$os,$browser,$device){
		$query = mysqli_query($this->dbCon,"INSERT INTO ebsg_connections(accid,ip,os,browser,device) VALUES('$accid','$ip','$os','$browser','$device')");
		return (int)mysqli_insert_id($this->dbCon);
	}
	public function Connections(){
		$Connections = mysqli_query($this->dbCon,"select * from ebsg_connections ORDER BY created DESC");
		return $Connections;
	}
	
	public function ReportIn(
		$bankerid,
		$amount,
		$inflowdetails,
		$account_balance,
		$remarks
	){	
		$query = mysqli_query($this->dbCon,"INSERT INTO ebsg_reports(bankerid,type,amount,inflowdetails,remarks) VALUES('$bankerid','inflow','$amount','$inflowdetails','$remarks')");
		return (int)mysqli_insert_id($this->dbCon);
	}
	
	public function ReportOut(
		$bankerid,
		$originating_mda,
		$amount,
		$inflowdetails,
		$account_balance,
		$remarks,
		$request_for_payment,
		$govt_approved_number,
		$previous_approval,
		$total_previously_paid,
		$fresh_approval_request,
		$amount_currently_approved,
		$payments_to_date	
	){	
		$query = mysqli_query($this->dbCon,"INSERT INTO ebsg_reports(bankerid,type,originating_mda,amount,inflowdetails,account_balance,remarks,request_for_payment,govt_approved_number,previous_approval,total_previously_paid,fresh_approval_request,amount_currently_approved,payments_to_date) VALUES('$bankerid','outflow','$originating_mda','$amount','$inflowdetails','$account_balance','$remarks','$request_for_payment','$govt_approved_number','$previous_approval','$total_previously_paid','$fresh_approval_request','$amount_currently_approved','$payments_to_date')");
		return (int)mysqli_insert_id($this->dbCon);
	}
	
	public function PushOutBalance($bankerid,$account_balance,$project_number,$previous_approval){
		$query = mysqli_query($this->dbCon,"INSERT INTO ebsg_balances(bankerid,balance,projectcode,project,type) VALUES('$bankerid','$account_balance','$project_number','$previous_approval','outflow')");
		return (int)mysqli_insert_id($this->dbCon);
	}
	
	public function PushInBalance($bankerid,$account_balance){
		$query = mysqli_query($this->dbCon,"INSERT INTO ebsg_balances(bankerid,balance,type) VALUES('$bankerid','$account_balance','inflow')");
		return (int)mysqli_insert_id($this->dbCon);
	}

	public function LastBankerBalance($bankerid){
		$LastBankerBalance = mysqli_query($this->dbCon,"select balance from ebsg_balances where bankerid='$bankerid' ORDER BY id DESC LIMIT 1");
		$LastBankerBalance = mysqli_fetch_object($LastBankerBalance);
		if(isset($LastBankerBalance->balance)){
			return $LastBankerBalance->balance;
		}
		return 0;
	}
	
	//SELECT id, category_id, post_title FROM posts WHERE id IN(SELECT MAX(id) FROM posts GROUP BY category_id);

	public function AllBankerBalance(){
		$AllBankerBalance = mysqli_query($this->dbCon,"SELECT SUM(balance) AS tbal FROM ebsg_balances WHERE id IN(SELECT MAX(id) FROM ebsg_balances GROUP BY bankerid)");
		$AllBankerBalance = mysqli_fetch_object($AllBankerBalance);
		if(isset($AllBankerBalance->tbal)){
			return $AllBankerBalance->tbal;
		}
		return 0;
	}

	
	public function GetProjectInfoFromBudget($projectcode){

		$GetProjectInfo = mysqli_query($this->dbCon,"select * from ebsg_budget_items where procode = '$projectcode'");
		$GetProjectInfo = mysqli_fetch_object($GetProjectInfo);
		
		$GetProjectPreviousInfo = mysqli_query($this->dbCon,"SELECT SUM(amount) as p_pay FROM ebsg_reports WHERE projectcode='$projectcode'");
		$GetProjectPreviousInfo = mysqli_fetch_object($GetProjectPreviousInfo);
		
		$GetProjectPreviousTotalInfo = mysqli_query($this->dbCon,"SELECT id,amount FROM ebsg_reports WHERE projectcode='$projectcode' ORDER BY created DESC LIMIT 0,1");
		$GetProjectPreviousTotalInfo = mysqli_fetch_object($GetProjectPreviousTotalInfo);
		
		$project['project'] = $GetProjectInfo;
		$project['previouspay'] = $GetProjectPreviousInfo;
		$project['lastpreviouspay'] = $GetProjectPreviousTotalInfo;
		
		return $project;
		
	}
	
	public function CreateBudget($type,$year){
		$query = mysqli_query($this->dbCon,"INSERT INTO ebsg_budgets(type,year) VALUES('$type','$year')");
		return (int)mysqli_insert_id($this->dbCon);
	}

	public function AddBudgetItem($bid,$oepcode,$project,$estimated){
		$arroepcode = explode(" ",$oepcode);
		$cnt_oepcode = count($arroepcode);
		if($cnt_oepcode==3){
			$orgcode = $arroepcode[0];
			$ecocode = $arroepcode[1];
			$procode = $arroepcode[2];
			$query = mysqli_query($this->dbCon,"INSERT INTO ebsg_budget_items(bid,oepcode,orgcode,ecocode,procode,project,estimated) VALUES('$bid','$oepcode','$orgcode','$ecocode','$procode','$project','$estimated')");
		}else{
			$query = mysqli_query($this->dbCon,"INSERT INTO ebsg_budget_items(bid,oepcode,project,estimated) VALUES('$bid','$oepcode','$project','$estimated')");
		}
		return (int)mysqli_insert_id($this->dbCon);
	}

	public function BudgetItems($budget=0){
		if($budget > 0){
			$BudgetItems = mysqli_query($this->dbCon,"select * from ebsg_budget_items where bid='$budget'");
		}else{
			$BudgetItems = mysqli_query($this->dbCon,"select * from ebsg_budget_items");
		}
		return $BudgetItems;
	}
	
	
	public function Budgets(){
		$Budgets = mysqli_query($this->dbCon,"select * from ebsg_budgets");
		return $Budgets;
	}
	
	public function BudgetInfo($bgid){
		$BudgetInfo = mysqli_query($this->dbCon,"select * from ebsg_budgets where id='$bgid'");
		$BudgetInfo = mysqli_fetch_object($BudgetInfo);
		return $BudgetInfo;
	}
	
	public function BudgetMinYear(){
		$BudgetMinYear = mysqli_query($this->dbCon,"select max(year) as maxy from ebsg_budgets");
		$BudgetMinYear = mysqli_fetch_object($BudgetMinYear);
		return $BudgetMinYear->maxy;
	}
	
	public function CountMdaBankers($mda){
		$CountMdaBankers = mysqli_query($this->dbCon,"select count(id) AS bnum from ebsg_bankers where mda='$mda'");
		$CountMdaBankers = mysqli_fetch_object($CountMdaBankers);
		return $CountMdaBankers->bnum;
	}

	public function OEPCodes($data){
		$oepObj = new \stdClass;
		$ex_data = explode(" ",$data);
		$oepObj->orgcode =  preg_replace('/[^A-Za-z0-9\-]/', '', $ex_data[0]);
		$oepObj->ecocode =  preg_replace('/[^A-Za-z0-9\-]/', '', $ex_data[1]);
		$oepObj->procode =  preg_replace('/[^A-Za-z0-9\-]/', '', $ex_data[2]);
		return $oepObj;
	}


	public function Reports(){
		$Reports = mysqli_query($this->dbCon,"select * from ebsg_reports");
		return $Reports;
	}

	public function BankReports($bid){
		$BankReports = mysqli_query($this->dbCon,"select * from ebsg_reports where bankerid='$bid'");
		return $BankReports;
	}
	
	public function MyTalks($accid){
		$MyTalks = mysqli_query($this->dbCon,"select * from ebsg_talks WHERE talkfrom='$accid' OR talkto='$accid'");
		return $MyTalks;
	}

	public function PushTalk($accid,$users,$talk){
		$query = mysqli_query($this->dbCon,"INSERT INTO ebsg_talks(talkfrom,talkto,talk) VALUES('$accid','$users','$talk')");
		return (int)mysqli_insert_id($this->dbCon);
	}











}
?>