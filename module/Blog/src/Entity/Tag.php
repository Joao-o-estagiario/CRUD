<?php
namespace Blog\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="tag")
 */
class Tag
{
    /**
    * @ORM\Id
    * @ORM\GeneratedValue
    * @ORM\Column(name="id")
    */
    protected $id;

    /**
     * @ORM\Column(name="name")
     */
    protected $name;

    /**
     * @ORM\ManyToMany(targetEntity="\Blog\Entity\Post", mappedBy="tags")
     */
    protected $posts;

    //construtor
    public function __construct(){
        $this->posts = new ArrayCollection();
    }

    //retorna os posts associados com as tags
    public function getPosts(){
        return $this->posts;
    }

    //adiciona um post a uma coleção de posts relacionados a essa tag
    public function addPosts($post){
        $this->posts[] = $post;
    }

    public function getId(){
        return $this->id;
    }
    public function setId($id){
        $this->id = $id;
    }

    public function getName(){
        return $this->name;
    }
    public function SetName($name){
        $this->name = $name;
    }
}