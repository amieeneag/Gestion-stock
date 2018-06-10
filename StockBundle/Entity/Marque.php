<?php

namespace StockBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Marque
 *
 * @ORM\Table(name="marque")
 * @ORM\Entity(repositoryClass="StockBundle\Repository\MarqueRepository")
 */
class Marque
{


    /**
     * @ORM\ManyToOne(targetEntity="StockBundle\Entity\Categorie", inversedBy="marque" , cascade={"persist"})
     * @ORM\JoinColumn(name="categorie_id", referencedColumnName="id")
     * @ORM\JoinColumn(nullable=false)
     */
    private $categorie;

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
     * @ORM\Column(name="marque", type="string", length=255)
     */
    private $marque;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set marque
     *
     * @param string $marque
     * @return Marque
     */
    public function setMarque($marque)
    {
        $this->marque = $marque;

        return $this;
    }

    /**
     * Get marque
     *
     * @return string 
     */
    public function getMarque()
    {
        return $this->marque;
    }

    /**
     * Set marque
     *
     * @param string $marque
     * @return Marque
     */
    public function setcategorie(\StockBundle\Entity\Categorie $categorie)
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * Get marque
     *
     * @return string
     */
    public function getCategorie()
    {
        return $this->categorie;
    }



    public function __toString() {
        return $this->marque;
    }
}
