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
        $this->username = $username;
                self::$usernames[] = $username;

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Email invalide");
        }
        if (in_array($email, self::$emails)) {
            throw new Exception("Email deja utilise");
        }
        
        $this->email = $email;
        self::$emails[] = $email;

        $this->password = password_hash($password, PASSWORD_DEFAULT);
        $this->createdAt= new DateTime();
        $this->lastLogin=null;
    }
        public function getId():int{
        return $this->id;
    }
        public function getusername(): string {
    return $this->username;
}
        public function getemail(): string {
    return $this->email;
}
public function getpassword(): string {
    return $this->password;
}
    public function getCreatedAt(): DateTime {
        return $this->createdAt;
    }
    public function getLastLogin(): ?DateTime {
        return $this->lastLogin;
    }

    public function setLastLogin(): void {
        $this->lastLogin = new DateTime(); 
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
    } 
        public function getBio(): string {
        return $this->bio;
    }
    public function ajouterarticle(Article $article):void{
        $this->articles[]=$article;
    }
    public function getMyArticles():array{
        return $this->articles;
    }
public function supprimerArticle(int $id) {
    foreach ($this->articles as $index => $article) {
        if ($article->getId() === $id) {
            unset($this->articles[$index]);
            $this->articles = array_values($this->articles); 
            return true;
        }
    }
    return false;
}
    
}
class Moderateur extends Utilisateur{

}
class Editeur extends Moderateur{
    private string $moderationLevel;

         public function __construct($username, $email, $password,$moderationLevel)
    {   parent::__construct($username,$email,$password);
        $Levels = ['junior', 'senior', 'chief'];
          if (!in_array($moderationLevel, $Levels)) {
            throw new Exception("Niveau de modération invalide");
        }
        $this->moderationLevel=$moderationLevel;
    } 
        public function getLevel(): string {
        return $this->moderationLevel;
    }
}
class Administrateur extends Moderateur{
    private bool $isSuperAdmin;
public function __construct($username, $email, $password, $isSuperAdmin=false)

    {   parent::__construct($username,$email,$password);
$this->isSuperAdmin = $isSuperAdmin;
    } 
    
public function getisSuperAdmin(): bool {
    return $this->isSuperAdmin;
}

public function setSuperAdmin(bool $status): void {
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
    private DateTime $createdAt; 
    private ?DateTime $publishedAt=null;    
    private ?DateTime $updatedAt=null; 
    private array $commentaires=[];
    private array $categories=[]; 

    public function __construct($title,$content,Auteur $auteur,$status='draft')
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
                $allowedStatus = ['draft', 'published', 'archived'];
                if (!in_array($status, $allowedStatus)) {
            throw new Exception("Statut invalide");
        }
         $this->status = $status;
          if ($status === 'published') {
            $this->publishedAt = new DateTime(); 
        }
        $this->auteur=$auteur;
        $this->createdAt=new DateTime();
       $this->updatedAt = null; 

    }  
        public function getId(): int {
        return $this->id;
    }
        public function getTitle(): string {
        return $this->title;
    }
            public function setTitle($title)  {
                 $this->title = $title;
                 }
        public function getContent(): string {
        return $this->content;
    }   
     public function setContent($content)
      { $this->content = $content; }

    public function getExcerpt(): string {
        return $this->excerpt;
    }
        public function getStatus(): string {
        return $this->status;
    }
public function getAuthor(): Auteur {
    return $this->auteur;
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
    public function ajouterCommentaire(Commentaires $commentaire): void {
    $this->commentaires[] = $commentaire;
}

public function getCommentaires(): array {
    return $this->commentaires;
}
public function AuteurArticleTitle($title) {
    foreach ($this->articles as $article) {
        if ($article->getTitle() == $title) {
            return $article;
        }
    }
    return null;
}
}
class Categories{
    protected static int $counter = 1;
    protected int $id;
    private static array $names = [];
    protected string $name;
    protected string $description;
    protected DateTime $createdAt;

    public function __construct($name,$description){
        $this->id=self::$counter;
        self::$counter++;
        if(strlen($name)<2 || strlen($name)>50)
        {
            throw new Exception("le nom de categorie est petit");
        }
        if(in_array($name,self::$names))
        {
            throw new Exception("le nom de categorie est deja existe");
        }
        $this->name=$name;
        self::$names[]=$name;
               if (strlen($description) > 255) {
            throw new Exception("La description ne peut pas depasser 255 caractere");
        }
        $this->description=$description;
        $this->createdAt=new DateTime;
    }
        public function getNameCat(): string {
        return $this->name;
    }
        public function getDescription(): string {
        return $this->description;
    }
        public function getcreateAtCat(): DateTime {
        return $this->createdAt;
    }
}
class Commentaires{
    protected static int $counter = 1;
    private int $id;
    private ?Auteur $Auteur;
    private string $content;
    private string $status;
    private DateTime $createdAt; 
    private ?DateTime $publishedAt=null;    

    public function __construct(?Auteur $Auteur=null,$content,$status = 'draft'){
        $this->id=self::$counter;
        self::$counter++;
        $this->Auteur=$Auteur;
        $this->content=$content;
         $allowedStatus = ['draft', 'published', 'archived'];
        if (!in_array($status, $allowedStatus)) {
            throw new Exception("Statut invalide");
        }
         $this->status = $status;
         $this->createdAt=new DateTime();
        if ($status === 'published') {
            $this->publishedAt = new DateTime(); 
        }

    }
        public function getAuthor(): string {
                if ($this->Auteur !== null) {
            return $this->Auteur->getUsername();
        }
        return 'Visiteur';
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
                    
}

class Collection {
    private static $obj = null;
    private array $Users = [];
    private array $Catgs=[];
    private ?Utilisateur $current_user=null;

    private function __construct() {
        $this->Users = [
            new Auteur('Alice', 'alice@blog.com','$2y$10$fJT01j42gnc7vpHQ9mAghusaM8seR8VXYk88WRNadxbWmOTJDlE3u','my bio'),
            new Auteur('Bob', 'bob@blog.com','$2y$10$fJT01j42gnc7vpHQ9mAghusaM8seR8VXYk88WRNadxbWmOTJDlE3u','My bio'),
            new Editeur('Charlie', 'charlie@blog.com','$2y$10$fJT01j42gnc7vpHQ9mAghusaM8seR8VXYk88WRNadxbWmOTJDlE3u','chief'),
            new Administrateur('Admin', 'admin@blog.com','$2y$10$fJT01j42gnc7vpHQ9mAghusaM8seR8VXYk88WRNadxbWmOTJDlE3u',true)
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
        public function login($name,$password):bool{
            foreach($this->Users as $user)
            {
                if($user->getusername()===$name || $user->getemail()===$name)
                {
              if(password_verify($password, $user->getpassword()))
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
    $user=$data->get_current_user();
if($user=== null)
    {
    echo "\n------MENUE------\n";
    echo "1 login \n";
    echo "2. Afficher tous les articles\n";
    echo "3. quitter \n";
    $choix=readline("choisir une option :");
    if($choix=="1")
        {
        $name=readline("\nEmail ou Username : ");
        $pass=readline("\nPassword : ");

            if (!$data->login($name,$pass))
            {
            echo "erreur : les identifiants incorrects.\n";
            }
             else
            {   
           $user=$data->get_current_user();
           while ($user !== null)
             {
            echo "\n bonjour ".$data->get_current_user()->getusername();
            echo "\n------MENUE------\n";
            echo "1. Afficher tous les articles\n";
            if ($user instanceof Auteur) 
        {
            echo "2. Ajouter Mon Article\n";
            echo "3. Modifier Mes Article\n";
            echo "4. Supprimer Mes Article\n";
            echo "5. Creé commentaire\n"; 
            $choixauteur=readline("choisir une option :");
            if( $choixauteur==2)
            {
                        echo "\n--- Creation d'un Article ---\n";
                    $title = readline("Entrez le titre : ");
                    $content = readline("Entrez le contenu : ");
                    $newArticle= new Article ($title,$content,$user);
                    $user->ajouterarticle($newArticle);
                    echo "\nL'article a ajouté à votre liste .\n";
            }
           if( $choixauteur==3){
echo "\n--- MODIFIER UN ARTICLE ---\n"; 
        $mesArticles = $user->getMyArticles();
        if (!$mesArticles) {
            echo "Vous n'avez aucun article  modifier.\n";
        } else {
            foreach ($mesArticles as $art) {
                echo "title: " . $art->getTitle() . " | contenue: " . $art->getContent() . "\n";
            }
            $mod = readline("Entrez le title de l'article pour le modifier: ");
            $article = $user->AuteurArticleTitle($mod);

            if ($article) {
                $newTitle = readline("Nouveau titre : ");
                $newContent = readline("Nouveau contenu : ");

                if (!empty($newTitle)) 
                    $article->setTitle($newTitle);
                if (!empty($newContent)) 
                    $article->setContent($newContent);

                echo "Article modifié \n";
            } else {
                echo " Erreur: Article non trouvé dans votre liste.\n";
            } } }
if ($choixauteur == "4") {
    echo "\n--- SUPPRIMER UN ARTICLE ---\n";
    $mesArticles = $User->getMyArticles();
    if (empty($mesArticles)) {
        echo "Vous n'avez aucun article à supprimer.\n";
    } else {
        foreach ($mesArticles as $art) {
                echo "title: " . $art->getTitle() . " | contenue: " . $art->getContent() . "\n";
        }
        $idASupprimer =  readline("Entrez l'ID de l'article à supprimer : ");

        if ($User->supprimerArticle($idASupprimer)) {
            echo "L'article a été supprimé.\n";
        } else {
            echo "Article introuvable.\n";
        }
    }
}

        }

        else if ($user instanceof Administrateur) {
            echo "2. Publier Articles signer\n";
            echo "3. Depublier Articles Signeé\n";
            echo "4. Modifier Article singneé\n";
            echo "5. Supprimer Articles signeé\n";
            echo "6. Creé Catégories\n";
            echo "7. Modiffier Catégories\n";
            echo "8. Supprimer Catégories\n";
            echo "9. Ajouter User\n";
            echo "10. Liste des User\n";
            echo "11. Modifier User\n";
            echo "12. Supprimer User\n"; 
            echo "13. changerRole User\n"; 
            echo "14. view Dashboard User\n"; 
        } 
        else if($user instanceof Editeur) {
            echo "2. Publier Articles signer\n";
            echo "3. Depublier Articles Signeé\n";
            echo "4. Modifier Article singneé\n";
            echo "5. Supprimer Articles signeé\n";
            echo "6. Creé Catégories\n";
            echo "7. Modiffier Catégories\n";
            echo "8. Supprimer Catégories\n";
            echo "9. accepter Commentaires\n";
            echo "10. supprimer Commentaires\n";            
        } 
        echo "0 Quitter\n"; 
        $choixUser = readline("Choisir : ");

     }

         }
    }
    }
  
if($choix=="3"){
    break;
}
}








