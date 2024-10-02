<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['iemail'];
    $password = $_POST['ipassword'];
    $password2 = $_POST['ipassword2'];

    $data = file_get_contents('profil.json');
    $users = json_decode($data, true);

    foreach ($users['users'] as $user) {
        if ($user['email'] == $email) {

            $data = array(
                'exists' => true,
                'email' => $email, 
                'passwords_match' => null
            );
            
            $jsonData = json_encode($data);

            echo $jsonData;

            exit();
        }
    }

    if ($password !== $password2) {
        $data = array(
            'exists' => false,
            'email' => $email, 
            'passwords_match' => false
        );
        
        $jsonData = json_encode($data);

        echo $jsonData;

        exit();
    }

    $new_user = array(
        'email' => $email,
        'password' => password_hash($password, PASSWORD_DEFAULT),
    );
    
    $users['users'][] = $new_user;
    $data = json_encode($users, JSON_PRETTY_PRINT);
    file_put_contents('profil.json', $data); 

    $data = array(
        'exists' => false,
        'email' => $email, 
        'passwords_match' => true
    );
    
    $jsonData = json_encode($data);

    echo $jsonData;

    exit();
}
?>