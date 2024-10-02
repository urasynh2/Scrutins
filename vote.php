<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $votant = $_POST['votant'];
    $id = $_POST['id'];
    $option = $_POST['option'];

    
    $data = file_get_contents('scrutins.json');
    $scrutins = json_decode($data, true);

    foreach($scrutins as &$scrutin){
        if($scrutin['id']==$id){
            if($scrutin['votants'][$votant]>0){
                $scrutin['options'][$option]++;
                $scrutin['votants'][$votant]--;
                $scrutin['votes_comptes']++;
            
                $data = json_encode($scrutins, JSON_PRETTY_PRINT);
                file_put_contents('scrutins.json', $data); 
                echo '1';
                exit();
            }else{
                echo '0';
                exit();
            }
        }
    }





    }
?>