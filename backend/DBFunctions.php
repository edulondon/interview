<?php
require_once ("PHPMailer/class.phpmailer.php");
class DBFunctions{
	
	
	function __construct(){
		
	}
	
	function __destruct(){
		
	}
//$params->fist_name, $params->email, $params->sur_name, $params->phone_number, $params->cover_letter, $params->passport,$params->resume);
	public function storeUser($first_name, $email, $sur_name, $phone_number, $cover_letter){
		require_once 'Config.php';
			try {
							$con = new PDO('mysql:host='.DB_HOST.';dbname='.DB_DATABASE.';charset=utf8', DB_USER, DB_PASSWORD, array(PDO::ATTR_EMULATE_PREPARES => false,PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
							$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
							$con->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
					}catch(Exception $e) {
					//echo $e->getMessage();
					echo "An error has occurred-". $e->getMessage();
					}	
		try{
			$passport = "passport.png";
			$resume = "default.pdf";
			$created_at = DATE('Y-m-d h:i:s');
			$stmt = $con->prepare("INSERT INTO register(firstName, surName,  phoneNumber, email, cover_letter, passport, resume, created_at)
								VALUES(:first_name, :sur_name, :phone_number, :email, :cover_letter, :passport, :resume, :created_at)");
			
			$stmt->execute(array(':first_name'=>$first_name,':sur_name'=>$sur_name,':phone_number'=>$phone_number, ':email'=>$email,':cover_letter'=>$cover_letter,':passport'=>$passport,':resume'=>$resume,':created_at'=>$created_at));
			return true;
		}
		catch(Exception $e) {
			echo $e->getMessage();
			return false;
		}
		
	} 
	public function getAllCandidates(){
		include_once ('DBConnect.php');
		
		$stmt = $con->prepare("SELECT * FROM register");
		$stmt->execute();
		if($stmt){
			$result = $stmt->fetchAll();
			return $result;
		}
		else{
			return false;
		} 
		}

	public function getUserByUsernameAndPassword($username, $password){
		include_once ('DBConnect.php');
		
		$stmt = $con->prepare("SELECT * FROM users WHERE email = ? AND password =? LIMIT 1");
		$stmt->bindParam(1,$username);
		$stmt->bindParam(2,$password);
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		return $result;

		}	

		
		public function doesUserExists($email){
			include_once ('DBConnect.php');
			$stmt = $con -> prepare("SELECT * from register WHERE email = ? ");
			$stmt -> bindParam(1, $email);
			$stmt->execute();
			$no_of_rows = $stmt->rowCount();
			if($no_of_rows > 0){
				return true;
			}else{
				return false;
			}
		}

	public function countRegisteredUser(){
		require_once 'Config.php';
		try {
						$con = new PDO('mysql:host='.DB_HOST.';dbname='.DB_DATABASE.';charset=utf8', DB_USER, DB_PASSWORD, array(PDO::ATTR_EMULATE_PREPARES => false,PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
						$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
						$con->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
				}catch(Exception $e) {
				//echo $e->getMessage();
				echo "An error has occurred-". $e->getMessage();
				}	
				try { 
							$stmt = $con -> prepare("SELECT * from register");
							$stmt->execute();
							$no_of_rows = $stmt->rowCount();
							if($no_of_rows > 3){
								return true;
							}else{
								return false;
							}
						}catch(Exception $e) {
							return false;
							}	
					}

	public function uploadPassport($passport, $email){
		require_once 'Config.php';
		try {
						$con = new PDO('mysql:host='.DB_HOST.';dbname='.DB_DATABASE.';charset=utf8', DB_USER, DB_PASSWORD, array(PDO::ATTR_EMULATE_PREPARES => false,PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
						$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
						$con->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
				}catch(Exception $e) {
				//echo $e->getMessage();
						return false;
					}	
		if(isset($passport)){
		  $pName = $passport['name'];
		  $result = move_uploaded_file($passport['tmp_name'], "upload/". $passport['name']);
			  if(isset($result)){
				try{	
					$stmt = $con->prepare("UPDATE register SET passport = :passport WHERE email = :email");
					
					$stmt->execute(array(':email'=> $email, ':passport'=>$pName));
					
					return true;
				} catch(Exception $e) {
						return false;
				}	
			  }
		}
		  
	  }
  
	  
	  public function uploadResume($resume, $email){ 
		require_once 'Config.php';
		try {
						$con = new PDO('mysql:host='.DB_HOST.';dbname='.DB_DATABASE.';charset=utf8', DB_USER, DB_PASSWORD, array(PDO::ATTR_EMULATE_PREPARES => false,PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
						$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
						$con->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
				}catch(Exception $e) {
				//echo $e->getMessage();
						return false;
					}	
		if(isset($resume, $email)){
		  $rName = $resume['name'];
		  $result = move_uploaded_file($resume['tmp_name'], "upload/". $resume['name']);
			  if(isset($result)){
				try{	
					$stmt = $con->prepare("UPDATE register SET resume = :resume WHERE email = :email");
					
					$stmt->execute(array(':email'=> $email, ':resume'=> $rName));
					
					return true;
				} catch(Exception $e) {
					return false;
				}
		}
		  
	  }
	

}
}	

?>