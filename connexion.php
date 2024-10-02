<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['cemail'];
    $password = $_POST['cpassword'];

    $data = file_get_contents('profil.json');
    $users = json_decode($data, true);

    foreach ($users['users'] as $user) {
            if ($user['email'] == $email) {
                if(password_verify($password,$user['password'])){
                    $data = array(
                        'registered' => true,
                        'email' => $email, 
                        'password' => true
                    );
                    $jsonData = json_encode($data);
                    echo $jsonData;
                    $_SESSION['email'] = $email;
                    // header("Location: acceuil.html");
                    exit();
                }else{
                    $data = array(
                        'registered' => true,
                        'email' => $email, 
                        'password' => false
                    );
                    $jsonData = json_encode($data);
                    echo $jsonData;
                    exit();
            }
        }
    }
    $data = array(
        'registered' => false,
        'email' => $email, 
        'password' => null
    );
    $jsonData = json_encode($data);
    echo $jsonData;
    exit();
}
?>
