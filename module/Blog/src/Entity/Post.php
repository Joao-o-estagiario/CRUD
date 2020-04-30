<?php
namespace Blog\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="post")
 */
class Post
{

    CONST STATUS_DRAFT     = 1;
    CONST STATUS_PUBLISHED = 2;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer",name="id")
     */
    protected $id;

    /**
     * @ORM\Column(name="title")
     */
    protected $title;

    /**
     * @ORM\Column(name="conten")
     */
    protected $conten;

    /**
     * @ORM\Column(name="status")
     */
    protected $status;

    /**
     * @ORM\Column(name="date_created")
     */
    protected $dateCreated;
    
    //construção do relacionamento entre tabelas POST <-> COMMENT
    /**
     * @ORM\OneToMany(targetEntity="\Blog\Entity\Comment", mappedBy="post")
     * @ORM\JoinColumn(name="id", referencedColumnName="post_id")
     */

    //construção do relacionamento entre tabelas POST <-> TAG
    /**
     * @ORM\ManyToMany(targetEntity="\Blog\Entity\Tag", inversedBy="posts")
     * @ORM\JoinTable(name="post_tag",
     *      joinColumns={@ORM\JoinColumn(name="post_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id")}
     * )
     */
    //FIM DO RELACIONAMENTO

    protected $tags;
    protected $comments;

    //construtor
    public function __construct(){
        $this->comments = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }
    /**
     * @return array
     */
    public function getComments(){
        return $this->comments;
    }

    /**
     * Adiciona um novo comentário nesse psot
     * @param $comment
     */
    public function addComment($comment){
        $this->comments[] = $comment;
    }
    
    public function getTags(){
        return $this->tags;
    }

    //adiciona uma nova tag a esse post
    public function addTag($tag){
        $this->tags[] = $tag;
    }

    //remove a associação que esse post tinha com a tag
    public function removeTagAssociation($tag){
        $this->tags->removeElement($tag);
    }

    //retorna o Id desse post
    public function getId(){
        return $this->id;
    }
    public function setId($id){
        $this->id = $id;
    }

    public function getTitle(){
        return $this->title;
    }
    public function setTitle($title){
        $this->title = $title;
    }

    public function getConten(){
        return $this->conten;
    }
    public function setConten($conten){
        $this->conten = $conten;
    }

    public function getStatus(){
        return $this->status;
    }
    public function setStatus($status){
        $this->status = $status;
    }

    public function getDateCreated(){
        return $this->dateCreated;
    }
    public function setDateCreated($dateCreated){
        $this->dateCreated = $dateCreated;
    }
}