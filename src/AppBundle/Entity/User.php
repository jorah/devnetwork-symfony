<?php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;

/**
 * User
 */
class User extends BaseUser
{
    /**
     * @var int
     */
    protected $id;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

    /**
     * @var string
     */
    private $firstname;

    /**
     * @var string
     */
    private $lastname;

    /**
     * @var array
     */
    private $job;

    /**
     * @var string
     */
    private $bio;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @var string
     */
    private $theme;

    /**
     * @var string
     */
    private $img;

    /**
     * @var boolean
     */
    private $status;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $skills;


    /**
     * Set firstname
     *
     * @param string $firstname
     *
     * @return User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     *
     * @return User
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set job
     *
     * @param array $job
     *
     * @return User
     */
    public function setJob($job)
    {
        $this->job = $job;

        return $this;
    }

    /**
     * Get job
     *
     * @return array
     */
    public function getJob()
    {
        return $this->job;
    }

    /**
     * Set bio
     *
     * @param string $bio
     *
     * @return User
     */
    public function setBio($bio)
    {
        $this->bio = $bio;

        return $this;
    }

    /**
     * Get bio
     *
     * @return string
     */
    public function getBio()
    {
        return $this->bio;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return User
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return User
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set theme
     *
     * @param string $theme
     *
     * @return User
     */
    public function setTheme($theme)
    {
        $this->theme = $theme;

        return $this;
    }

    /**
     * Get theme
     *
     * @return string
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * Set img
     *
     * @param string $img
     *
     * @return User
     */
    public function setImg($img)
    {
        $this->img = $img;

        return $this;
    }

    /**
     * Get img
     *
     * @return string
     */
    public function getImg()
    {
        return $this->img;
    }

    /**
     * Set status
     *
     * @param boolean $status
     *
     * @return User
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return boolean
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Add skill
     *
     * @param \AppBundle\Entity\Skill $skill
     *
     * @return User
     */
    public function addSkill(\AppBundle\Entity\Skill $skill)
    {
        $this->skills[] = $skill;

        return $this;
    }

    /**
     * Remove skill
     *
     * @param \AppBundle\Entity\Skill $skill
     */
    public function removeSkill(\AppBundle\Entity\Skill $skill)
    {
        $this->skills->removeElement($skill);
    }

    /**
     * Get skills
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSkills()
    {
        return $this->skills;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $favCode;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $favPost;


    /**
     * Add favCode
     *
     * @param \AppBundle\Entity\Code $favCode
     *
     * @return User
     */
    public function addFavCode(\AppBundle\Entity\Code $favCode)
    {
        $this->favCode[] = $favCode;

        return $this;
    }

    /**
     * Remove favCode
     *
     * @param \AppBundle\Entity\Code $favCode
     */
    public function removeFavCode(\AppBundle\Entity\Code $favCode)
    {
        $this->favCode->removeElement($favCode);
    }

    /**
     * Get favCode
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFavCode()
    {
        return $this->favCode;
    }

    /**
     * Add favPost
     *
     * @param \AppBundle\Entity\Post $favPost
     *
     * @return User
     */
    public function addFavPost(\AppBundle\Entity\Post $favPost)
    {
        $this->favPost[] = $favPost;

        return $this;
    }

    /**
     * Remove favPost
     *
     * @param \AppBundle\Entity\Post $favPost
     */
    public function removeFavPost(\AppBundle\Entity\Post $favPost)
    {
        $this->favPost->removeElement($favPost);
    }

    /**
     * Get favPost
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFavPost()
    {
        return $this->favPost;
    }
}
