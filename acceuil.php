<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VOTEZ</title>
    <link rel="stylesheet" href="style.css">
</head>


<body>
    <div class="navbar">
        <a style='float: left' onclick='openTab("section_organisateur")' >Organisateur</a>
        <a style='float: left' onclick='openTab("section_votant")'>Votant</a>
        <?php

        
        session_start();
        if (isset($_SESSION['email'])) {
            echo '<a href="#">' . $_SESSION['email'] . '</a>';
            echo '<a  href="deconnexion.php">Déconnexion</a>';
        } else {
            echo '<a href="conn_inscr.html">Connexion/Inscription</a>';    }
        ?>
    </div>

    <br>


    <div class='section' id='section_organisateur'> <!-- classe section pour ouvrir et fermer -->

        <div class="tab-container"> 
            
            <div class='tab' id="creation_scrutin" >
                <label for='creation_scrutin'><h3 class='titre'> Créer un scrutin  </h3></label>

                <?php

                if(isset($_SESSION['email'])){
                    echo '
                    <div id="message"></div>
                    <label for="question">Question:</label><br>
                    <input type="text" id="question" name="question"  placeholder="Playsation ou Xbox ?" required><br>
                
                    <label>Choix de réponses :</label><br>
                    <div id="options">
                        <input type="text" name="option" placeholder="Playsation "required><br>
                        <input type="text" name="option" placeholder="Xbox"required><br>
                    </div>
                    <button onclick="ajouterOption()">+</button><br>

                        
                    <label for="listeVotants">Liste de votants:</label><br>


                    <input type="checkbox" id="switch" class="checkbox" />
                    <label for="switch" class="toggle"> </label>
            
                    <div id="legende">Choisir un groupe &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp Personnalisé</div>


                    <div id="listeVotants"></div>';

                    $jsonString = file_get_contents('profil.json');
                    $users = json_decode($jsonString, true);
                    foreach ($users['users'] as $user) {
                        echo "<input type='checkbox' name='votant' value='" . $user['email'] . "'>". $user['email'] ."> | nombre de procurations ";
                        echo '<input type=\'number\' min=0 max=2 value=0 id="votes_'.$user['email'].'"> <br>';
                    
                    }
                    echo '<button onclick="create_scrutin()">Créer un scrutin</button><br>';
                }else{
                    echo "<div id='message' style='color:red'> Vous n'êtes pas connecté </div>";
                }
                
                ?>
                <script>
                    function ajouterOption() {
                        var divOptions = document.getElementById("options");
                        var input = document.createElement("input");
                        input.type = "text";
                        input.name = "option";
                        input.required = true;
                        divOptions.appendChild(input);
                        divOptions.appendChild(document.createElement("br"));
                    }
                </script>
            </div>  

            <div class='tab' id='scrutin_orga'>
                <label for='scrutin_orga'><h3 class='titre'> Scrutins dont vous êtes l'organisateur.  </h3></label> <br>
                <div class="liste" id="liste_pour_organisateur" >

                    
                    <?php
                    $jsonString = file_get_contents('scrutins.json');
                    $scrutins = json_decode($jsonString, true);

                    if(isset($_SESSION['email'])){
                        $userEmail =  $_SESSION['email'];
                        foreach ($scrutins as $scrutin) {
                            if ($scrutin['organisateur'] == $userEmail) {
                                echo "<div class='scrutin' id='".$scrutin['id']."_orga'>

                                <h4>" . $scrutin['question'] . " </h4> ";

                                echo "Options:<div class='options-container'>"; 
                                foreach ($scrutin['options'] as $option => $pourcentage) {
                                    echo "<div class='option'>".$option."</div>";
                                }

                                if ($scrutin['votes_comptes'] > 0) {
                                    $pourcentage = round($scrutin['votes_comptes'] / $scrutin['total_votes'] * 100);
                                    
                                } else {
                                    $pourcentage = 0; // Évite une division par zéro
                                }
                                echo '<br><br><label for="barre_de"> Taux de participation:</label>';
                                echo '</div> <div class="barre_de">';
                                echo '<div class="progression" style="width:'.$pourcentage.'%">'.$pourcentage.'%</div></div>';
                        
                                if($scrutin['actif']){
                                    echo "<br> <button style='color:red' onclick='close_scrutin(\"".$scrutin['id']."\",\"".$userEmail."\")'>Clore le scrutin</button>";

                                }else{
                                    echo " <p style=\"color:red\"> Vous avez clos ce scrutin <p>";
                                }
                                echo "</div>"; 
                            }
                        }
                    }else{

                        echo "<div id='message' style='color:red'> Vous n'êtes pas connecté </div>";


                    }

                    ?>


                
                </div>
            </div> 

            <div class='tab' id='groupes'>

                <label for='groupes'><h3 class='titre'> Groupes</h3></label> <br>
                <?php

                if(isset($_SESSION['email'])){
                    echo "

                    <div class='tab2' id='creationGroupes'>
                        <label for='creationGroupes'><h4 class='titre'> Création de groupe</h4></label>

                        <label for='question'>Nom du grroupe:</label><br>
                        <input type='text' id='nomGroupe' placeholder='L3/Groupe 3/ProgWeb' required><br>";
                    
                            $jsonString = file_get_contents('profil.json');
                            $users = json_decode($jsonString, true);
                            foreach ($users['users'] as $user) {
                                echo "<input type='checkbox' name='membreGroupe' value='" . $user['email'] . "'>". $user['email'] ."> <br>";
                            
                            }
                            echo "<button onclick='create_groupe()'>Créer un groupe</button><br>

                            <div id='gmessage'>...</div>

                     </div> ";

                            echo "<div class='tab2' id='afficheGroupe'>
                            <label for='afficheGroupe'><h4 class='titre'> Liste des groupes</h4></label>";
                            
                                foreach ($users['groups'] as $groupName => $list) {
                                    echo "<div class='groupe'>
                                    Nom du groupe:" . $groupName ."<br>
                                    Membres du groupe: <br>";
    
                                    foreach ($list as $member) {
                                        echo "- ".$member."<br>";
    
                                    }
                                    echo "</div>";
                                }
    
    
    
                        echo "</div>";


                    }else{
                        echo "<div id='message' style='color:red'> Vous n'êtes pas connecté </div>";
                    }
                    
                    ?>


                    

                    <br>

                </div>

                </div>    
    
    
                </div> 
                <div class='section' id='section_votant'> <!-- classe section pour ouvrir et fermer -->
                       <div class='tab' id='scrutin_votant'>
            <label for='scrutin_orga'><h3 class='titre'> Scrutins auxquels vous participez en tant que votant.  </h3> <br></label>
            <div class="liste" id="liste_pour_votant" >
            
            <?php
            $jsonString = file_get_contents('scrutins.json');
            $scrutins = json_decode($jsonString, true);

            if(isset($_SESSION['email'])){

                $userEmail =  $_SESSION['email'];
                foreach ($scrutins as $scrutin) {
                    if (isset($scrutin['votants'][$userEmail])) {
                        echo "<div class='scrutin' id='".$scrutin['id']."_votant'>";    //div du scrutin
                        echo "<h4>" . $scrutin['question'] . " </h4>";  // Question/Titre
                        echo "Organisateur: " . $scrutin['organisateur'];  //Organisateur

                        if ($scrutin['actif']){ //Si le scrutin est encore actif
                            echo " <br><br> Options:<br>";  //Les options en boutons
                            foreach ($scrutin['options'] as $option => $pourcentage) {
                                echo "<button onclick=\"vote('" . $userEmail . "', '" . $scrutin['id'] . "', '" . $option . "')\">" . $option . "</button> ";
                            }
                            if($scrutin['votants'][$userEmail]>0) {   //et le nombre de votes que la personne peut encore soumettre
                                echo "<div id='message'> Vous avez encore ".$scrutin['votants'][$userEmail]." votes. </div>" ;  
                            }else{ 
                                echo "<div id='message'> Vous avez déja voté </div>";
                            }
                            echo "<br><br>";

                            if ($scrutin['votes_comptes'] > 0) {
                                $pourcentage = round($scrutin['votes_comptes'] / $scrutin['total_votes'] * 100);
                            } else {
                                $pourcentage = 0; // Évite une division par zéro
                            }
                            echo '<label for="barre_de"> Taux de participation:</label>';
                            echo '<div class="barre_de">';
                            echo '<div class="progression" style="width:'.$pourcentage.'%">'.$pourcentage.'%</div></div>';

                        }else{
                            echo "<br><br>Options:<div class='options-container'>"; 
                            foreach ($scrutin['options'] as $option => $pourcentage) {
                                echo "<div class='option'>".$option."</div>";
                            }    
                            echo "</div> <p style=\"color:red\" > Ce scrutin est clos</p>  ";

                            echo "<div class='resultat'> Résultats des votes: <br>";
                            foreach ($scrutin['options'] as $option => $votes) {
                                if ($scrutin['votes_comptes']) {
                                    $pourcentage = round($votes/$scrutin['votes_comptes'] * 100);
                                } else {
                                    $pourcentage = 0; // Évite une division par zéro
                                }
                                echo $option.":".$pourcentage."% <br>";
                            }
                            echo '</div>';

                            
                            if ($scrutin['votes_comptes'] > 0) {
                                $participation = round($scrutin['votes_comptes'] / $scrutin['total_votes'] * 100);
                            } else {
                                $participation = 0; // Évite une division par zéro
                            }
                            echo '<br><label for="participation"> Taux de participation:</label>';
                            echo '<div class="participation">'.$participation.'%</div>';
                        
                        }
                        
                        echo '</div><br>';
                    }
                }
            }else{
                echo "<div id='message' style='color:red'> Vous n'êtes pas connecté </div>";
            }
            ?>



            </div> 
        </div>
    </div> 


    <script>
    function openTab(section) {
        var i, tabcontent;
        tabcontent = document.getElementsByClassName("section");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        document.getElementById(section).style.display = "block";
    }

    window.onload = function() {
        openTab('section_organisateur');
    };

    function refresh(d){
        var tmp = $("<div>");
        tmp.load(window.location.href + " #"+d, function() {
        $("#"+d).replaceWith(tmp.find("#"+d));
        });
    }
    </script>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>  
    <script src="create_scrutin.js"></script>
    <script src="close_scrutin.js"></script>
    <script src="vote.js"></script>
    <script src="create_groupe.js"></script>



</body>
</html>
