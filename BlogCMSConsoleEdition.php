<?php 
Utilisateur=[

    { id:1,
        

    }
]
class Utilisateur{
    protected int $id;
    protected string $username;
    protected string $email;
    protected string $password;
    protected Datetime $createdAt; 
    protected ?Datetime $lastLogin; 

    public function __construct($id,$username,$email,$password,$createAt)
    {
        $this->id=$id;
        $this->username=$username;
        $this->email=$email;
        $this->password=$password;
    }

}
class Auteur extends Utilisateur {
    private string $bio;
     private array $articles;

    public function __construct($id, $username, $email, $password, $createdAt,$bio,$articles)
    {
        $this->bio=$bio;
        foreach($articles as $article){
            $this->$articles[]=$article;
        }

    } 
}
class modirateur extends Utilisateur{

}
class Administrateur extends modirateur{
    private bool $isSuprAdmin;
        public function __construct($id, $username, $email, $password, $createdAt,$isSuprAdmin)
    {
        $this->isSuprAdmin=$isSuprAdmin;
    } 
}
class Editeur extends modirateur{
    private string $moderationLevel;
            public function __construct($id, $username, $email, $password, $createdAt,$moderationLevel)
    {
        $this->moderationLevel=$moderationLevel;
    } 
}

class Article{

    private int $id;
    private string $title;
    private string $content;
    private string $excerpt;
    private string $status;
    private Datetime $createdAt; 
    private Datetime $publishedAt;    
    private Datetime $updatedAt; 
    private array $commentaires;
    private array $Categories; 

    public function __construct($id,$title,$content,$excerpt,$status,$createdAt,$publishedAt,$updatedAt,$commentaires=[],$Categories=[])
    {
        $this->id=$id;
        $this->title=$title;
        $this->content=$content;
        $this->excerpt=$excerpt;
        $this->status=$status;
        $this->createdAt=$createdAt;
        $this->publishedAt=$publishedAt;
        $this->updatedAt=$updatedAt;
        $this->Auteur=$Auteur;
        foreach($commentaires as $commentaire)
        {
            $this->commentaires[]=$commentaire;
        }
        foreach($Categories as $Categorie)
        {
            $this->Categories[]=$Categorie;
        }
    }  
}
class Categories{
    protected int $id;
    protected string $name;
    protected string $description;
    protected DateTime $createAt;
    private array $Categorie;

    public function __construct($id,$name,$description,$createAt,$Categorie=[]){
        $this->id=$id;
        $this->name=$name;
        $this->description=$description;
        $this->createAt=$createAt;
        foreach ($Categories as $Categorie){
            $this->categories[]=$Categorie;
        }
    }

}
class commentaires{
    private int $id;
    private $Auteur;
    private string $content;
    private datetime $publishedAt;

    public function __construct($id,$Auteur,$content,$publishedAt){

        $this->id=$id;
        $this->auteur=$Auteur;
        $this->content=$content;
        $this->publishedAt=$publishedAt;

    }
}