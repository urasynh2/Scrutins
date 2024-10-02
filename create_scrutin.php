<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if( isset($_POST['question']) && isset($_POST['options']) && isset($_POST['votants'])){
        if (isset($_SESSION['email'])) {
            $question = $_POST['question'];
            $options = $_POST['options']; 
            $votants = $_POST['votants'];
            $nbVotes = $_POST['nbVotes'];

            $data = file_get_contents('scrutins.json');
            $scrutins = json_decode($data, true);

            $new_scrutin = array(
                'id' => uniqid('scrutin_'),
                'organisateur' => $_SESSION['email'],
                'question' => $question,
                'options' => $options,
                'total_votes' => intval($nbVotes),
                'votes_comptes' => 0,
                'votants' => $votants,
                'actif' => true,
            );

            foreach ($new_scrutin['options'] as $option => &$value) {
                $value = intval($value);
            }
            
            foreach ($new_scrutin['votants'] as $votant => &$procurations) {
                $procurations = intval($procurations);
            }

            $scrutins[] = $new_scrutin;
            $data = json_encode($scrutins, JSON_PRETTY_PRINT);
            file_put_contents('scrutins.json', $data); 

            $answer = array(
                'scrutin_created' => true,
                'message' => '',
            );
            $jsonData = json_encode($answer);
            echo $jsonData;
            exit();
        }else{
            $answer = array(
                'scrutin_created' => false,
                'message' => "Veuillez vous connecter.",
            );
            $jsonData = json_encode($answer);
            echo $jsonData;
            exit();
        }
    }else{
        $answer = array(
            'scrutin_created' => false,
            'message' => 'Tous les champs requis doivent être renseignés.',
        ); 
        $jsonData = json_encode($answer);
        echo $jsonData;
        exit();
    }
}else{
    $answer = array(
        'scrutin_created' => false,
        'message' => 'Méthode non autorisée. Seules les requêtes POST sont autorisées.',
    );
    echo json_encode($answer);
    exit();
}
?>
