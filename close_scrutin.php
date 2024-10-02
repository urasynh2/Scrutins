<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $id = $_POST['id'];

    
    $data = file_get_contents('scrutins.json');
    $scrutins = json_decode($data, true);

    foreach($scrutins as &$scrutin){
        if($scrutin['id']==$id){
            $scrutin['actif'] = false;
        
            $data = json_encode($scrutins, JSON_PRETTY_PRINT);
            file_put_contents('scrutins.json', $data); 

            echo '1';
            exit();
        }
    }

    echo '0';
    exit();
}
?>
