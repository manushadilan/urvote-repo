<?php
/**
 *   (C) Copyright 1997-2013 hSenid International (pvt) Limited.
 *   All Rights Reserved.
 *
 *   These materials are unpublished, proprietary, confidential source code of
 *   hSenid International (pvt) Limited and constitute a TRADE SECRET of hSenid
 *   International (pvt) Limited.
 *
 *   hSenid International (pvt) Limited retains all title to and intellectual
 *   property rights in these materials.
 */

include_once '../../lib/ussd/MoUssdReceiver.php';
include_once '../../lib/ussd/MtUssdSender.php';
include_once '../log.php';

$con=mysql_connect("localhost","root","")or die(mysql_error());
mysql_select_db("yrvotedb")or die(mysql_error());

ini_set('error_log', 'ussd-app-error.log');

$receiver = new MoUssdReceiver(); // Create the Receiver object

$receiverSessionId = $receiver->getSessionId();
session_id($receiverSessionId); //Use received session id to create a unique session
session_start();

$content = $receiver->getMessage(); // get the message content
$address = $receiver->getAddress(); // get the sender's address
$requestId = $receiver->getRequestID(); // get the request ID
$applicationId = $receiver->getApplicationId(); // get application ID
$encoding = $receiver->getEncoding(); // get the encoding value
$version = $receiver->getVersion(); // get the version
$sessionId = $receiver->getSessionId(); // get the session ID;
$ussdOperation = $receiver->getUssdOperation(); // get the ussd operation

logFile("[ content=$content, address=$address, requestId=$requestId, applicationId=$applicationId, encoding=$encoding, version=$version, sessionId=$sessionId, ussdOperation=$ussdOperation ]");

$tbl1="mbrndtbl";
$tbl2="mostbl";
$tbl3="politbl";

//your logic goes here......
$responseMsg = array(
    "main" => "Welcome to UrVote
					Select the Option
					1.Mobile phones
                    2.Political
                    99.Exit",
    "mobi" => "Select the Option:
                    1.OS
                    2.Brand
                    93.Back",
                    
    "polit" => "Do you agree with CEPA/ETCA
                    1.Yes
                    2.No
					3.Other
                    93.Back",
    "osm" => "Give your Vote:
					1.Android
					2.Apple
					3.Windows
					4.Other
                    93.Back",
    "brd" => "Give your Vote:
					1.Samsung
					2.HTC
					3.IPhone
					4.Other
                    93.Back",
	"ok" => "Your Vote has been Accepted.
					99.Exit"
											
);

logFile("Previous Menu is := " . $_SESSION['menu-Opt']); //Get previous menu number
if (($receiver->getUssdOperation()) == "mo-init") { //Send the main menu
    loadUssdSender($sessionId, $responseMsg["main"]);
    if (!(isset($_SESSION['menu-Opt']))) {
        $_SESSION['menu-Opt'] = "main"; //Initialize main menu
    }

}
if (($receiver->getUssdOperation()) == "mo-cont") {
    $menuName = null;

    switch ($_SESSION['menu-Opt']) {
        case "main":
            switch ($receiver->getMessage()) {
                case "1":
                    $menuName = "mobi";
                    break;
                case "2":
                    $menuName = "polit";
                    break;
                default:
                    $menuName = "main";
                    break;
            }
            $_SESSION['menu-Opt'] = $menuName; //Assign session menu name
            break;
        case "mobi":
            $_SESSION['menu-Opt'] = "mob-hist"; //Set to mobile menu back
            switch ($receiver->getMessage()) {
                case "1":
                    $menuName = "osm";
                    break;
                case "2":
                    $menuName = "brd";
                    break;
                case "93":
                    $menuName = "main";
                    $_SESSION['menu-Opt'] = "main";
                    break;
                default:
                    $menuName = "main";
                    break;
            }
			$_SESSION['menu-Opt'] = $menuName; //Assign session menu name
            break;
        case "polit":
            $_SESSION['menu-Opt'] = "poli-hist"; //Set to political menu back
            switch ($receiver->getMessage()) {
                case "1":
					$query="INSERT INTO $tbl3 values('$address','$sessionId','yes')";
					mysql_query($query) or die(mysql_error());
					$menuName = "ok";
                    break;
                case "2":
					$query="INSERT INTO $tbl3 values('$address','$sessionId','no')";
					mysql_query($query) or die(mysql_error());
                    $menuName = "ok";
                    break;
				case "3":
					$query="INSERT INTO $tbl3 values('$address','$sessionId','other')";
					mysql_query($query) or die(mysql_error());
                    $menuName = "ok";
                    break;
                case "93":
                    $menuName = "main";
                    $_SESSION['menu-Opt'] = "main";
                    break;
                default:
                    $menuName = "main";
                    break;
            }
            break;
			
		case "osm":
            $_SESSION['menu-Opt'] = "osm-hist"; 
            switch ($receiver->getMessage()) {
                case "1":
					$query="INSERT INTO $tbl2 values('$address','$sessionId','android')";
					mysql_query($query) or die(mysql_error());
                    $menuName = "ok";
                    break;
                case "2":
					$query="INSERT INTO $tbl2 values('$address','$sessionId','apple')";
					mysql_query($query) or die(mysql_error());
                    $menuName = "ok";
                    break;
				case "3":
					$query="INSERT INTO $tbl2 values('$address','$sessionId','windows')";
					mysql_query($query) or die(mysql_error());
                    $menuName = "ok";
                    break;
				case "4":
					$query="INSERT INTO $tbl2 values('$address','$sessionId','other')";
					mysql_query($query) or die(mysql_error());
                    $menuName = "ok";
                    break;
                case "93":
                    $menuName = "main";
                    $_SESSION['menu-Opt'] = "main";
                    break;
                default:
                    $menuName = "main";
                    break;
            }
			$_SESSION['menu-Opt'] = $menuName; //Assign session menu name
            break;
			
			case "brd":
            $_SESSION['menu-Opt'] = "br-hist"; 
            switch ($receiver->getMessage()) {
                case "1":
					$query="INSERT INTO $tbl1 values('$address','$sessionId','samsung')";
					mysql_query($query) or die(mysql_error());
                    $menuName = "ok";
                    break;
                case "2":
					$query="INSERT INTO $tbl1 values('$address','$sessionId','htc')";
					mysql_query($query) or die(mysql_error());
                    $menuName = "ok";
                    break;
				case "3":
					$query="INSERT INTO $tbl1 values('$address','$sessionId','iphone')";
					mysql_query($query) or die(mysql_error());
                    $menuName = "ok";
                    break;
				case "4":
					$query="INSERT INTO $tbl1 values('$address','$sessionId','other')";
					mysql_query($query) or die(mysql_error());
                    $menuName = "ok";
                    break;
                case "93":
                    $menuName = "main";
                    $_SESSION['menu-Opt'] = "main";
                    break;
                default:
                    $menuName = "main";
                    break;
            }
			$_SESSION['menu-Opt'] = $menuName; //Assign session menu name
            break;
        case "mob-hist" || "poli-hist" || "osm-hist" || "br-hist":
            switch ($_SESSION['menu-Opt']) { //Execute menu back sessions
                case "mob-hist":
                    $menuName = "mobi";
                    break;
                case "poli-hist":
                    $menuName = "polit";
                    break;
				 case "osm-hist":
                    $menuName = "osm";
                    break;
                case "br-hist":
                    $menuName = "brd";
                    break; 
            }
            $_SESSION['menu-Opt'] = $menuName; //Assign previous session menu name
            break;
    }

    if ($receiver->getMessage() == "99") {
        $responseExitMsg = "Thank you for using UrVote.";
        $response = loadUssdSender($sessionId, $responseExitMsg);
        session_destroy();
		mysql_close($con);
    } else {
        logFile("Selected response message := " . $responseMsg[$menuName]);
        $response = loadUssdSender($sessionId, $responseMsg[$menuName]);
    }

}
/*
    Get the session id and Response message as parameter
    Create sender object and send ussd with appropriate parameters
**/

function loadUssdSender($sessionId, $responseMessage)
{
    $password = "password";
    $destinationAddress = "tel:94771122336";
    if ($responseMessage == "99") {
        $ussdOperation = "mt-fin";
    } else {
        $ussdOperation = "mt-cont";
    }
    $chargingAmount = "5";
    $applicationId = "APP_000001";
    $encoding = "440";
    $version = "1.0";

    try {
        // Create the sender object server url

//        $sender = new MtUssdSender("http://localhost:7000/ussd/send/");   // Application ussd-mt sending http url
        $sender = new MtUssdSender("https://localhost:7443/ussd/send/"); // Application ussd-mt sending https url
        $response = $sender->ussd($applicationId, $password, $version, $responseMessage,
            $sessionId, $ussdOperation, $destinationAddress, $encoding, $chargingAmount);
        return $response;
    } catch (UssdException $ex) {
        //throws when failed sending or receiving the ussd
        error_log("USSD ERROR: {$ex->getStatusCode()} | {$ex->getStatusMessage()}");
        return null;
    }
}

?>