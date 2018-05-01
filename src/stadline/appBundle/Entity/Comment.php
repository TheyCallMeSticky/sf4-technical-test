<?php

namespace stadline\appBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Comment
 *
 * @ORM\Table(name="comment")
 * @ORM\Entity(repositoryClass="stadline\appBundle\Repository\CommentRepository")
 */
class Comment {

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255)
     */
    private $username;

    /**
     * @var int
     *
     * @ORM\Column(name="repository_id", type="integer")
     */
    private $repositoryId;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="text")
     */
    private $comment;

    /**
     *  @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $created_at;

    /**
     *  @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updated_at;

    public function __construct() {
        $this->created_at = new \DateTime();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set username
     *
     * @param string $username
     *
     * @return Comment
     */
    public function setUsername($username) {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * Set repositoryId
     *
     * @param integer $repositoryId
     *
     * @return Comment
     */
    public function setRepositoryId($repositoryId) {
        $this->repositoryId = $repositoryId;

        return $this;
    }

    /**
     * Get repositoryId
     *
     * @return int
     */
    public function getRepositoryId() {
        return $this->repositoryId;
    }

    function getComment() {
        return $this->comment;
    }

    function getCreatedAt() {
        return $this->created_at;
    }

    function getUpdatedAt() {
        return $this->updated_at;
    }

    function setComment($comment) {
        $this->comment = $comment;
    }

    function setCreatedAt(\DateTime $created_at) {
        $this->created_at = $created_at;
    }

    function setUpdatedAt(\DateTime $updated_at) {
        $this->updated_at = $updated_at;
    }

}
