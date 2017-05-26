<?php
// Connexion à la base de données
try
{
    $bdd = new PDO('mysql:host=localhost;dbname=mon_blog', 'root', 'root');
}
catch(Exception $e)
{
        die('Erreur : '.$e->getMessage());
}
if(isset($_POST['identifiant']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['password-confirm']))
    {
        // Sécurité
        $identifiant = addslashes(htmlspecialchars(htmlentities(trim($_POST['identifiant']))));
        $email = addslashes(htmlspecialchars(htmlentities(trim($_POST['email']))));
        $password = sha1($_POST['password']);
        $passwordConfirm = sha1($_POST['password-confirm']);
        if(preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $email)) // on vérifie si l'email à un format valide.
        {
            if($password == $passwordConfirm) // on vérifie que les deux mots de passe soient identique.
            {
                $req = $db->query("SELECT pseudo FROM membre WHERE pseudo = '$identifiant'"); // On séléctionne le champ (identifiant) dans notre table membre où identifiant est égale au champ identifiant rentré par l'utilisateur
                $count = $req->rowCount(); // on rowCount() la requete, donc rowcount retournera une valeur si il trouve.
                if($count == 0) // si il ne trouve pas une valeur, alors c'est bon
                {
                    $req = $db->prepare("INSERT INTO membre(pseudo, pass, email) VALUES('$identifiant', '$password', '$email')");
                    $req->execute(array(
                        'pseudo' => $identifiant,
                        'pass' => $password,
                        'email' => $email
                    ));
                    header('Location: login.php');
                }else{
                    $message = 'Cette adresse e-mail est déjà utilisé.';
                }
            }else{
                $message = 'Cet identifiant est déjà utilisé.';
            }
        }else{
            $message = 'Vos mots de passe ne sont pas identique.';
        }
    }else{
        $message = 'Votre adresse e-mail n\'est pas valide.';
    }
?>

	<?= $message; ?>
		<form action="" method="post">
			<div class="form-group">
				<label for="identifiant">
            Votre identifiant
        </label>
				<input type="text" name="identifiant" id="identifiant" class="form-control">
			</div>
			<div class="form-group">
				<label for="email">
            Votre adresse e-mail
        </label>
				<input type="text" name="email" id="email" class="form-control">
			</div>
			<div class="form-group">
				<label for="password">
            Votre mot de passe
        </label>
				<input type="password" name="password" id="password" class="form-control">
			</div>
			<div class="form-group">
				<label for="password-confirm">
            Confirmez votre mot de passe
        </label>
				<input type="password" name="password-confirm" id="password-confirm" class="form-control">
			</div>
			<button type="submit" class="btn btn-default btn-lg btn-block">
        S'inscrire
    </button>
		</form>
