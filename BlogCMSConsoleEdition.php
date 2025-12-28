<?php 

class Utilisateur{
    protected int $id;
    protected string $username;
    protected string $email;
    protected string $password;
    protected DateTime $createdAt; 
    protected ?DateTime $lastLogin; 

    public function __construct($id,$username,$email,$password,$createdAt)
    {
        $this->id=$id;
        $this->username=$username;
        $this->email=$email;
        $this->password=password_hash($password, PASSWORD_DEFAULT);
        $this->createdAt=$createdAt;
        $this->lastLogin=null;
    }
        public function getusername(){
    return $this->username;
}
        public function getemail(){
    return $this->email;
}
public function getpassword(){
    return $this->password;
}


}


class Auteur extends Utilisateur {
    private string $bio;
    private array $articles=[];
    private array $commentaires=[];

    public function __construct($id, $username, $email, $password, $createdAt,$bio,$articles=[],$commentaires=[])
    {   parent::__construct($id,$username,$email,$password,$createdAt);
        $this->bio=$bio;
        foreach($articles as $article){
            $this->articles[]=$article;
        }
        foreach($commentaires as $commentaire)
        {
            $this->commentaires[]=$commentaire;
        }
    } 
    public function ajouterarticle(Article $article){
        $this->articles=$article;
    }
    public function getMyArticles(){
        return $this->articles;
    }
}
class Moderateur extends Utilisateur{

}
class Administrateur extends Moderateur{
    private bool $isSuprAdmin;
        public function __construct($id, $username, $email, $password, $createdAt,$isSuprAdmin)
    {   parent::__construct($id,$username,$email,$password,$createdAt);
        $this->isSuprAdmin=$isSuprAdmin;
    } 
}
class Editeur extends Moderateur{
    private string $moderationLevel;
            public function __construct($id, $username, $email, $password, $createdAt,$moderationLevel)
    {   parent::__construct($id,$username,$email,$password,$createdAt);
        $this->moderationLevel=$moderationLevel;
    } 
}

class Article{

    private int $id;
    private string $title;
    private string $content;
    private Auteur $auteur;
    private string $excerpt;
    private string $status;
    private DateTime $createdAt; 
    private DateTime $publishedAt;    
    private DateTime $updatedAt; 
    private array $commentaires=[];
    private array $categories=[]; 

    public function __construct($id,$title,$content,Auteur $auteur,$excerpt,$status,$createdAt,$publishedAt,$updatedAt,$commentaires=[],$categories=[])
    {
        $this->id=$id;
        $this->title=$title;
        $this->content=$content;
        $this->auteur=$auteur;
        $this->excerpt=$excerpt;
        $this->status="Brouillon";
        $this->createdAt=new DateTime();
        $this->publishedAt=$publishedAt;
        $this->updatedAt=$updatedAt;

        foreach($commentaires as $commentaire)
        {
            $this->commentaires[]=$commentaire;
        }
        foreach($categories as $categorie)
        {
            $this->categories[]=$categorie;
        }
    }  
    public function getTitle(){
        return $this->title;
    }
}
class Categories{
    protected int $id;
    protected string $name;
    protected string $description;
    protected DateTime $createAt;

    public function __construct($id,$name,$description,$createAt){
        $this->id=$id;
        $this->name=$name;
        $this->description=$description;
        $this->createAt=$createAt;
    }

}
class Commentaires{
    private int $id;
    private Utilisateur $Auteur;
    private string $content;
    private DateTime $publishedAt;

    public function __construct($id,Utilisateur $Auteur,$content,$publishedAt){
        $this->id=$id;
        $this->Auteur=$Auteur;
        $this->content=$content;
        $this->publishedAt=$publishedAt;

    }
}

class Collection {
    private static $obj = null;
    private array $Users = [];
    private array $Catgs=[];
    private ?Utilisateur $current_user=null;

    private function __construct() {
        $this->Users = [
            new Auteur(1,'Alice', 'alice@blog.com','123',new DateTime(),'my bio'),
            new Auteur(2,'Bob', 'bob@blog.com','123', new DateTime(), 'My bio'),
            new Editeur(3,'Charlie', 'charlie@blog.com','123', new DateTime(), 'chief'),
            new Administrateur(4,'Admin', 'admin@blog.com','123', new DateTime(),true)
        ];
        $this->Catgs=[];

    }

    public static function getInstance() {
        if (self::$obj === null) {
            self::$obj = new self();
        }
        return self::$obj;
    }
    
        public function getUtilisateurs(){
            return $this->Users;
        }
        public function login($username,$email,$password):bool{
            foreach($this->Users as $user)
            {
                if($user->getusername()===$username || $user->getemail()===$email)
                {
                    if(password_verify(($password,$user->getpassword())))
                    {
                    $this->current_user=$user;
                    return true;
                    }
                }
            }
            return false;
        }
        public function get_current_user(){
            return  $this->current_user;
        }
      public function logout(){
        $this->current_user=null;
    }
   
}

$data=Collection::getInstance();

while(true){
    $User=$data->get_current_user();
if($User=== null)
    {
    echo "<br>------MENUE------<br>";
    echo "1 login <br>";
    echo "2 quitter <br>";
    echo "3. Afficher tous les articles\n";
    $choix=readline("choisir une option :");
    if($choix=="1")
        {
        $name=readline("<br>Email ou Username : ");
        $pass=readline("<br>Password : ");

         if (!$data->login($name,$pass)){
            echo "erreur : les identifiants incorrects.\n";
            }

         else{
            echo "<br> bonjour ".$data->get_current_user()->getusername();

            echo "<br>------MENUE------<br>";
            echo "1. Afficher tous les articles\n";

            if ($user instanceof Auteur) {
            echo "2. Ajouter Article<br>";
            echo "3. Modifier Mes Article<br>";
            echo "4. Supprimer Mes Article<br>";
            echo "5. Creé commentaire<br>"; 
            $choixauteur=readline("choisir une option :");
            if( $choixauteur==2)
            {echo "\n--- CRÉATION D'UN ARTICLE ---\n";
            $title = readline("Entrez le titre : ");
            $contenu = readline("Entrez le contenu : ");
            $newArticle= new Article ($title,$)

            }
        }

        else if ($user instanceof Administrateur) {
            echo "2. Publier Articles signer<br>";
            echo "3. Depublier Articles Signeé<br>";
            echo "4. Modifier Article singneé<br>";
            echo "5. Supprimer Articles signeé<br>";
            echo "6. Creé Catégories<br>";
            echo "7. Modiffier Catégories<br>";
            echo "8. Supprimer Catégories<br>";
            echo "9. Ajouter User<br>";
            echo "10. Liste des User<br>";
            echo "11. Modifier User<br>";
            echo "12. Supprimer User<br>"; 
            echo "13. changerRole User<br>"; 
            echo "14. view Dashboard User<br>"; 
        } 
        else if($user instanceof Editeur) {
            echo "2. Publier Articles signer<br>";
            echo "3. Depublier Articles Signeé<br>";
            echo "4. Modifier Article singneé<br>";
            echo "5. Supprimer Articles signeé<br>";
            echo "6. Creé Catégories<br>";
            echo "7. Modiffier Catégories<br>";
            echo "8. Supprimer Catégories<br>";
            echo "9. accepter Commentaires<br>";
            echo "10. supprimer Commentaires<br>";            
        } 
        echo "0 Quitter<br>"; 

            }

         }
    }

}








