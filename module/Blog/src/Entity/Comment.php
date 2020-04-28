<?php
namespace Blog\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="comment")
 */
class Comment
{
    /**
     * @ORM\Entity
     * @ORM\Column(name="id")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(name="conten")
     */
    protected $conten;

    /**
     * @ORM\Column(name="author")
     */
    protected $author;

    /**
     * @ORM\Column(name="date_created")
     */
    protected $dateCreated;

    /**
     * @ORM\ManyToOne(targetEntity="\Blog\Entity\Post", inversedBy="comments")
     * @ORM\JoinColumn(name="post_id", referencedColumnName="id")
     */
    protected $post;

    /**
     * retorna o post associado
     * @retun \Blog\Entity\Post
     */
    public function getPost(){
        return $this->post;
    }

    /**
     * @param \Blog\Entity\Post $post
     */
    public function setPost($post){
        $this->post = $post;
        $post->addComment($this);
    }

    public function getId(){
        return $this->id;
    }
    public function setId($id){
        $this->id = $id;
    }

    public function getConten(){
        return $this->conten;
    }
    public function setConten($conten){
        $this->conten = $conten;
    }

    public function getAuthor(){
        return $this->author;
    }
    public function setAuthor($author){
        $this->author = $author;
    }

    public function getDateCreated(){
        return $this->dateCreated;
    }
    public function setDateCreated($dateCreated){
        $this->dateCreated = $dateCreated;
    }
}