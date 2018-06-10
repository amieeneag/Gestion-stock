<?php

namespace StockBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SousCategorie
 *
 * @ORM\Table(name="sous_categorie")
 * @ORM\Entity(repositoryClass="StockBundle\Repository\SousCategorieRepository")
 */
class SousCategorie
{
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
     * @ORM\Column(name="SousCat", type="string", length=255)
     */
    private $sousCat;


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
     * Set sousCat
     *
     * @param string $sousCat
     * @return SousCategorie
     */
    public function setSousCat($sousCat)
    {
        $this->sousCat = $sousCat;

        return $this;
    }

    /**
     * Get sousCat
     *
     * @return string 
     */
    public function getSousCat()
    {
        return $this->sousCat;
    }
}
