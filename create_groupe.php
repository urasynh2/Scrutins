<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if( isset($_POST['nomGroupe']) && isset($_POST['membres'])){
        $nomGroupe = $_POST['nomGroupe'];
        $membres = $_POST['membres'];

        $data = file_get_contents('profil.json');
        $users = json_decode($data, true);

        $users['groups'][$nomGroupe] =  $membres ;

        $data = json_encode($users, JSON_PRETTY_PRINT);
        file_put_contents('profil.json', $data);

        $answer = array(
            'groupe_created' => true,
            'message' => 'Groupe créé',
        ); 
        $jsonData = json_encode($answer);
        echo $jsonData;
        exit();

        
    }else{
        $answer = array(
            'groupe_created' => false,
            'message' => 'Tous les champs requis doivent être renseignés.',
        ); 
        $jsonData = json_encode($answer);
        echo $jsonData;
        exit();
    }
}else{
    $answer = array(
        'groupe_created' => false,
        'message' => 'Méthode non autorisée. Seules les requêtes POST sont autorisées.',
    );
    echo json_encode($answer);
    exit();
}
?>
