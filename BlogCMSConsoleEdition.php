<?php 

class Utilisateur{
    protected static int $counter = 1; 
    private int $id;
    private static array $usernames = [];
    protected string $username;
    private static array $emails = [];
    protected string $email;
    protected string $password;
    protected DateTime $createdAt; 
    protected ?DateTime $lastLogin=null; 


    public function __construct($username,$email,$password)
    {
        $this->id = self::$counter;
        self::$counter++;
            if (strlen($username) < 3 || strlen($username) > 50) {
        throw new Exception("Username est petit");
      }
              if (in_array($username, self::$usernames)) {
            throw new Exception("Username deja utilise");
        }
        self::$usernames[] = $username;
        $this->username = $username;
        $this->username=$username;
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Email invalide");
        }
        if (in_array($email, self::$emails)) {
            throw new Exception("Email deja utilise");
        }
        self::$emails[] = $email;
        $this->email = $email;
        $this->password = password_hash($password, PASSWORD_DEFAULT);
        $this->createdAt= new DateTime();
        $this->lastLogin=null;
    }
        public function getId(){
        return $this->id;
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
    public function getCreatedAt(): DateTime {
        return $this->createdAt;
    }


    public function setLastLogin(): void {
        $this->lastLogin = new DateTime(); 
    }
    public function getLastLogin(): ?DateTime {
        return $this->lastLogin;
    }

}


class Auteur extends Utilisateur {
    private string $bio;
    private array $articles=[];
    private array $commentaires=[];

    public function __construct($username, $email, $password,$bio)
    {   parent::__construct($username,$email,$password);
                if (strlen($bio) > 500) {
            throw new Exception("La biographie ne peut pas depasser 500 caractères.");
        }
        $this->bio=$bio;
        foreach($articles as $article){
            $this->articles[]=$article;
        }
        foreach($commentaires as $commentaire)
        {
            $this->commentaires[]=$commentaire;
        }
    } 
        public function getBio(): string {
        return $this->bio;
    }
    public function ajouterarticle(Article $article){
        $this->articles[]=$article;
    }
    public function getMyArticles(){
        return $this->articles;
    }
    
}
class Moderateur extends Utilisateur{

}
class Editeur extends Moderateur{
    private string $moderationLevel;
    
         public function __construct($username, $email, $password,$moderationLevel)
    {   parent::__construct($username,$email,$password);
        $allowed = ['junior', 'senior', 'chief'];
          if (!in_array($level, $allowed)) {
            throw new Exception("Niveau de modération invalide");
        }
        $this->moderationLevel=$moderationLevel;
    } 
        public function getLevel(): string {
        return $this->moderationLevel;
    }
}
class Administrateur extends Moderateur{
    private bool $isSuprAdmin;
        public function __construct($username, $email, $password, $createdAt,$isSuperAdmin = false)
    {   parent::__construct($username,$email,$password,$createdAt);
        $this->isSuprAdmin=$isSuprAdmin;
    } 
    
    public function getisSuperAdmin():bool{
        return $this->isSuperAdmin;
    }

    public function setSuperAdmin(bool $status):void{
        $this->isSuperAdmin = $status;
    }
}


class Article{

    protected static int $counter = 1; 
    private int $id;
    private string $title;
    private string $content;
    private string $excerpt;
    private Auteur $auteur;
    private string $status;
    private array $allowedStatus = ['draft', 'published', 'archived'];
    private DateTime $createdAt; 
    private ?DateTime $publishedAt=null;    
    private ?DateTime $updatedAt=null; 
    private array $commentaires=[];
    private array $categories=[]; 

    public function __construct($title,$content,$status='draft',Auteur $auteur)
    {
        $this->id=self::$counter;
        self::$counter++;
           if (strlen($title) < 2 || strlen($title) > 200) {
            throw new Exception("Le titre doit contenir entre 2 et 200 caractere");
        }
        $this->title=$title;
                if (strlen($content) > 10000) {
            throw new Exception("Le contenu ne peut pas depasser 10000 caractères.");
        }
        $this->content=$content;
        $this->excerpt=substr($content, 0, 150);
        $this->auteur=$auteur;
        $this->createdAt=new DateTime();
                if (!in_array($status, $this->allowedStatus)) {
            throw new Exception("Statut invalide");
        }
         $this->status = $status;
                if ($status === 'published') {
            $this->publishedAt = new DateTime(); 
        }

       $this->updatedAt = null; 

        foreach($commentaires as $commentaire)
        {
            $this->commentaires[]=$commentaire;
        }
        foreach($categories as $categorie)
        {
            $this->categories[]=$categorie;
        }
    }  
        public function getId(): int {
        return $this->id;
    }
        public function getTitle(): string {
        return $this->title;
    }
        public function getContent(): string {
        return $this->content;
    }
    public function getExcerpt(): string {
        return $this->excerpt;
    }
        public function getStatus(): string {
        return $this->status;
    }
        public function getAuthor(): Auteur {
        return $this->Auteur;
    }
        public function getCreatedAt(): DateTime {
        return $this->createdAt;
    }
        public function getPublishedAt(): ?DateTime {
        return $this->publishedAt;
    }
        public function setUpdatedAt(): void {
        $this->updatedAt = new DateTime();
    }
    public function getUpdatedAt(): ?DateTime {
        return $this->updatedAt;
    }
}
class Categories{
    protected static $counter;
    protected int $id;
    private static array $names = [];
    protected string $name;
    protected string $description;
    protected DateTime $createAt;

    public function __construct($name,$description){
        $this->id=self::$counter;
        self::$counter++;
        if(strlen($name)<2 || strlen($name)>50)
        {
            throw new Exception("le nom de categorie est petit");
        }
        else if(in_array($name,self::$names))
        {
            throw new Exception("le nom de categorie est deja existe");
        }
        $this->name=$name;
        self::$names[]=$name;
               if (strlen($description) > 255) {
            throw new Exception("La description ne peut pas depasser 255 caractere");
        }
        $this->description=$description;
        $this->createAt=new DateTime;
    }
        public function getNameCat(): string {
        return $this->name;
    }
        public function getDescription(): string {
        return $this->description;
    }
        public function getcreateAtCat(): DateTime {
        return $this->createAt;
    }
}
class Commentaires{
    protected static $counter;
    private int $id;
    private Utilisateur $Auteur;
    private string $content;
    private string $status;
    private array $allowedStatus = ['draft', 'published', 'archived'];
    private DateTime $createdAt; 
    private ?DateTime $publishedAt=null;    

    public function __construct(Utilisateur $Auteur,$content){
        $this->id=self::$counter;
        self::$counter;
        $this->Auteur=$Auteur;
        $this->content=$content;
        $this->publishedAt=$publishedAt;

    }
        public function getAuthor(): Auteur {
        return $this->Auteur;
    }
        public function getContent(): string {
        return $this->content;
    }
            public function getCreatedAt(): DateTime {
        return $this->createdAt;
    }
        public function getPublishedAt(): ?DateTime {
        return $this->publishedAt;
    }
                    if (!in_array($status, $this->allowedStatus)) {
            throw new Exception("Statut invalide");
        }
         $this->status = $status;
                if ($status === 'published') {
            $this->publishedAt = new DateTime(); 
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
                    {echo "\n--- Creation d'un Article ---\n";
                    $title = readline("Entrez le titre : ");
                    $contenu = readline("Entrez le contenu : ");
                    $newArticle= new Article ($title,$content,$auteur);

                    $auteur->ajouterarticle($newArticle);
                    echo "\nL'article a ajouté à votre liste .\n";

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








