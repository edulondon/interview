<?php    
	session_start();
require_once ("PHPMailer/class.phpmailer.php");
?>
<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
		
if ((isset($_POST['tag']) && $_POST['tag'] != '')||(isset($_GET['tag']) && $_GET['tag'] != '')){
	if(isset($_POST['tag'])){
		$tag = $_POST['tag'];
	}else if(isset($_GET['tag'])){
		$tag = $_GET['tag'];
	}
	require_once 'DBFunctions.php';
	$db = new DBFunctions();
	
	$response = array("tag" => $tag, "error" => FALSE);
	
	if($tag == 'login'){
		
		$json = file_get_contents('php://input');
		$params = json_decode($json);
		
		$user = $db->getUserByUsernameAndPassword($params->email, $params->password);
		if(isset($user)&& $user != false){
			$response["user"] = $user;
			$response["error"] = FALSE;
			$response["status"] = 200;				
			$_SESSION['username'] = $user['email'];
			$_SESSION['password'] = $user['password'];
			echo json_encode($response);
		}else{
			$response["error"] = TRUE;
			$response["error_msg"] = "Incorrect username or password";
			$response["status"] = 201;
			$response["result"] = $user;
			echo json_encode($response);
		}
	}

	
	else if($tag == 'createUser'){ 
		$passport = $_FILES['passport'];
		$resume = $_FILES['resume'];
		$first_name = $_POST['first_name'];
		$sur_name = $_POST['sur_name'];
		$email = $_POST['email'];
		$phone_number = $_POST['phone_number'];
		$cover_letter = $_POST['cover_letter'];
		
		
		if($db->doesUserExists($email)== true){
			$response["error"] = TRUE;
			$response["error_msg"] = "Candidate already exist";
			echo json_encode($response); 
		}
		else if($db->countRegisteredUser()== true){
			$response["error"] = TRUE;
			$response["error_msg"] = "Registration Closed";
			echo json_encode($response); 
		}
		else{  
			$user = $db->storeUser($first_name, $email, $sur_name, $phone_number, $cover_letter);
			
			if($user){
			$response["error"] = FALSE;
			$response["status"] = 200;
			$response["msg"] = $user;
			$uploadP = $db->uploadPassport($passport, $email);
			if($uploadP == true){
			$uploadR = $db->uploadResume($resume, $email);
			}
			echo json_encode($response);
			}
			else{
				$response["error"] = TRUE;
				$response["error_msg"] = "User creation failed. Try again! Please ensure all fields are filled";
				echo json_encode($response);
			} 
		}  
		
	}
	
else if($tag == 'upload'){ 
	if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			echo json_encode(array('status' => false));
			exit;
			}
			
			$path = 'upload/';
			
			if (isset($_FILES['file'])) {
			$originalName = $_FILES['file']['name'];
			$ext = '.'.pathinfo($originalName, PATHINFO_EXTENSION);
			$generatedName = md5($_FILES['file']['tmp_name']).$ext;
			$filePath = $path.$generatedName;
			
			if (!is_writable($path)) {
				echo json_encode(array(
				'status' => false,
				'msg'    => 'Destination directory not writable.'
				));
				exit;
			}
			
			if (move_uploaded_file($_FILES['file']['tmp_name'], $filePath)) {
				$id = $_GET['id'];
				$coy = $db->updateSponsorLogo($id, $generatedName);
				echo json_encode(array(
				'status'        => true,
				'originalName'  => $originalName,
				'generatedName' => $generatedName,
				'sponsor-id'	=> $id
				));
			}
		}
		else {
		echo json_encode(
			array('status' => false, 'msg' => 'No file uploaded.')
		);
		exit;
		}
	}
	else if($tag == 'upload2'){ 
	if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			echo json_encode(array('status' => false));
			exit;
			}
			
			$path = 'upload/';
			
			if (isset($_FILES['file'])) {
			$originalName = $_FILES['file']['name'];
			$ext = '.'.pathinfo($originalName, PATHINFO_EXTENSION);
			$generatedName = md5($_FILES['file']['tmp_name']).$ext;
			$filePath = $path.$generatedName;
			
			if (!is_writable($path)) {
				echo json_encode(array(
				'status' => false,
				'msg'    => 'Destination directory not writable.'
				));
				exit;
			}
			
			if (move_uploaded_file($_FILES['file']['tmp_name'], $filePath)) {
				$id = $_GET['id'];
				$coy = $db->updateSponsorAdvert($id, $generatedName);
				echo json_encode(array(
				'status'        => true,
				'originalName'  => $originalName,
				'generatedName' => $generatedName,
				'sponsor-id'	=> $id
				));
			}
		}
		else {
		echo json_encode(
			array('status' => false, 'msg' => 'No file uploaded.')
		);
		exit;
		}
	}

		else if($tag == 'getAllCandidates'){
		
		$users = $db->getAllCandidates();
		if($users != false){
			$response["error"] = FALSE;
			$response["status"] = 200;
			$response["users"] = $users;
		}else{
			$response["error"] = TRUE;
			$response["error_msg"] = "No data found";
		}

		echo json_encode($response);
	}
	
	}else{
	$response["error"] = TRUE;
	$response["error_msg"] = "Required parameter 'tag' is missing";
	
	echo json_encode($response);
}

?>