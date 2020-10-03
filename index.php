<?php


define('DOT','.');
require_once( DOT . "/bootstrap.php");
	
//Home page//
$Route->add('/ebsgigr2/',function(){
	$Core = new Apps\Core;
	$Template = new Apps\Template;
	$Template->assign("title","IGR.Demo");
	
	$Template->addheader("layouts.header");
	$Template->addfooter("layouts.footer");
	
	$Template->render("home");
	
},'GET');
//Home page//


$Route->add('/ebsgigr2/pos/uploadit/upload',function(){
	$Core = new Apps\Core;
	$Template = new Apps\Template;

	$fileName = $_FILES["uploaded"]["tmp_name"];
	$handle = fopen($fileName, "r");
	while (($Data = fgetcsv($handle, 1000, ",")) !== FALSE){
		$mda = $Core->newMDA($Data[0]);
		if( $mda ){
			$Core->createPackage($mda,$Data[1],$Data[2]);
		}
	}
	fclose($handle);	
	$Core->debug(1);

},'POST');

$Route->add('/ebsgigr2/pos',function(){
	$Core = new Apps\Core;
	$Template = new Apps\Template("/ebsgigr2/pos/login");
	$Template->assign("title","EBSG.POS");
	$Template->addheader("layouts.dashboard.header");
	$Template->addfooter("layouts.dashboard.footer");

	$Template->addcss(array("./templates/assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css"));
	$Template->addjs(array("./templates/assets/plugins/datatables/jquery.dataTables.min.js"));
	$Template->addjs(array("./templates/assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"));

	$Template->assign("PendingDNOPackages",$Core->NDOOders("pending"));

	$Template->assign("IGR","pos");
	$Template->render("igr.pos.dashboard");

},'GET');

$Route->add('/ebsgigr2/{igr}/login',function($igr){
	$Core = new Apps\Core;
	$Template = new Apps\Template;
	$Template->assign("title","EBSG.IGR");
	$Template->addheader("layouts.login.header");
	$Template->addfooter("layouts.login.footer");
	$Template->render("igr.{$igr}.home");
},'GET');


$Route->add('/ebsgigr2/pos/do/{page}',function($page){
	$Core = new Apps\Core;
	$Template = new Apps\Template("/ebsgigr2/pos/login");
	$Template->assign("title","EBSG.POS");	
	$Template->addheader("layouts.dashboard.header");
	$Template->addfooter("layouts.dashboard.footer");
	$Template->addcss(array("./templates/assets/dashboard/css/all-skins.min.css"));
	$Template->assign("IGR","pos");
	if($page=="create-dno"){
		$Template->assign("POSPackages",$Core->POSPackages($Template->data['pos']));	
	}elseif($page=="dno"){
	}elseif($page=="dnos"){
	}	
	$Template->render("igr.pos.{$page}");

},'GET');



$Route->add('/ebsgigr2/dno',function(){
	$Core = new Apps\Core;
	$Template = new Apps\Template("/ebsgigr2/dno/login");
	$Template->assign("title","EBSG.DNO");
	$Template->addheader("layouts.login.header");
	$Template->addfooter("layouts.login.footer");
	$Template->render("igr.dno.dashboard");
},'GET');
$Route->add('/ebsgigr2/mda',function(){
	$Core = new Apps\Core;
	$Template = new Apps\Template("/ebsgigr2/mda/login");
	$Template->assign("title","EBSG.MDA");
	$Template->addheader("layouts.login.header");
	$Template->addfooter("layouts.login.footer");
	$Template->render("igr.mda.dashboard");
},'GET');
$Route->add('/ebsgigr2/merchant',function(){
	$Core = new Apps\Core;
	$Template = new Apps\Template("/ebsgigr2/merchant/login");
	$Template->assign("title","EBSG.Merchant");
	$Template->addheader("layouts.login.header");
	$Template->addfooter("layouts.login.footer");
	$Template->render("igr.merchant.dashboard");
},'GET');
$Route->add('/ebsgigr2/admin',function(){
	$Core = new Apps\Core;
	$Template = new Apps\Template("/ebsgigr2/admin/login");
	$Template->assign("title","EBSG.Admin");
	$Template->addheader("layouts.login.header");
	$Template->addfooter("layouts.login.footer");
	$Template->render("igr.admin.dashboard");
},'GET');



$Route->add('/pos/createdno/new',function(){
	$Core = new Apps\Core;
	$Template = new Apps\Template("/ebsgigr2/pos/login");
	$data = $Core->post($_POST);
	$title = $data->title;
	$surname = $data->surname;
	$othernames = $data->othernames;
	$address = $data->address;
	$mobile = $data->mobile;
	$email = $data->email;
	$package = $data->package;
	$dnoid = $Core->createDNOInvoice($title,$surname,$othernames,$address,$mobile,$email,$package);
	if(!$dnoid){
		$Template->redirect("/pos/do/create-dno");
	}
	$Template->redirect("/pos/do/{$dnoid}/checkout");	
},'POST');


$Route->add('/pos/checkoutdno/new',function(){
	
	$Core = new Apps\Core;
	$Template = new Apps\Template("/pos/login");
	$data = $Core->post($_POST);

	$dnoid = $data->dnoid;
	$DNOInfo = $Core->DNOInfo($dnoid);
	

	$set_recurrent_year = 0;
	$set_time_bound = 0;
	$set_tax_category = 0;
	
	if( isset($data->set_recurrent_year) ){
		$set_recurrent_year = 1;
		$Core->SetDNOInfo($dnoid,"is_recurring",1);
		$recurrent_year = $data->recurrent_year;
	}	
	if( isset($data->set_time_bound) ){
		$set_time_bound = 1;
		$Core->SetDNOInfo($dnoid,"is_time_bound",1);
		$time_bound = $data->time_bound;
	}	
	if( isset($data->set_tax_category) ){
		$set_tax_category = 1;
		$tax_category = $data->tax_category;
		$Core->SetDNOInfo($dnoid,"tax_on_checkout",1);
	}
	
	$Core->SetDNOInfo($dnoid,"status","published");

	$cnt = $Core->GenInvoice($dnoid);
	$Core->debug($cnt);

	$trans = $Core->GenerateDNOInvoice($dnoid,$DNOInfo->customer_id);


	if(!$dnoid){
		$Template->redirect("/pos/do/create-dno");
	}
	$Template->redirect("/pos/do/{$dnoid}/checkout");	

},'POST');










$Route->add('/pos/do/{dnoid}/checkout',function($dnoid){
	$Core = new Apps\Core;
	$Template = new Apps\Template("/admin/login");

	$Template->assign("title","EBSG.Admin");
	$Template->addheader("layouts.dashboard.header");
	$Template->addfooter("layouts.dashboard.footer");

	$dnoInfo = $Core->DNOInfo($dnoid);
	$Template->assign("dnoInfo",$dnoInfo);

	$DnoPackages = json_decode($dnoInfo->items_packages);
	$Template->assign("DnoPackages",$DnoPackages);

	$customerInfo = $Core->CustomerInfo($dnoInfo->customer_id);
	$Template->assign("customerInfo",$customerInfo);

	$Template->render("igr.pos.checkout");

},'GET');

$Route->add('/ebsgigr2/{igr}/form/{action}',function($igr,$action){
	$Core = new Apps\Core;
	$Template = new Apps\Template;
	$data = $Core->post($_POST);
	
	switch($igr){
		case "admin":
		break;
		case "dno":
		break;
		case "jtb":
		break;
		case "mda":
		break;
		case "merchant":
		break;
		case "pos":
			if($action=="login"){
				$username = $data->username;
				$password = $data->password;
				$Login = $Core->PosLogin($username,$password);
				if($Login->accid){
					$POSInfo = $Core->POSInfo($Login->pos);
					if($POSInfo->id){
						$Template->data['accid'] = $Login->accid;
						$Template->data['igr'] = "pos";
						$Template->data['pos'] = $POSInfo->id;
						$Template->data['mda'] = $POSInfo->mda;
						$Template->data[auth_session_key] = true;
						$Template->save();
						$Template->redirect("/ebsgigr2/pos");
					}
				}
			}
		break;
	}	
},'POST');



$Route->add('ebsgigr2/ajax/pos/{action}',function($action){
	$Core = new Apps\Core;
	$Template = new Apps\Template("ebsgigr2/pos/login");
},'POST');

$Route->add('/ebsgigr2/{igr}/app/exit',function($igr){
	$Core = new Apps\Core;
	$Template = new Apps\Template("ebsgigr2/{$igr}/login");
	$Template->expire();
	$Template->redirect("ebsgigr2/{$igr}");
},'GET');

$Route->run('/');

?>