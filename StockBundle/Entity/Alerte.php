<?php

namespace StockBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * Alerte
 *
 * @ORM\Table(name="alerte")
 * @ORM\Entity(repositoryClass="StockBundle\Repository\AlerteRepository")
 */
class Alerte
{

    /**
     * @ORM\ManyToOne(targetEntity="StockBundle\Entity\Produit", inversedBy="alert" , cascade={"persist"})
     * @ORM\JoinColumn(name="produit_id", referencedColumnName="id")
     * @ORM\JoinColumn(nullable=false)
     */
    private $produit;


    /**
     *
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date ;



    /**
     * @var string
     *
     * @ORM\Column(name="etat", type="string", length=255)
     */
    private $etat;


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
     * Set date
     *
     * @param \DateTime $date
     * @return Alerte
     */
    public function setDate($date)
    {
        $this->date =$date ;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }



    /**
     * Set etat
     *
     * @param string $etat
     * @return Alerte
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * Get etat
     *
     * @return string 
     */
    public function getEtat()
    {
        return $this->etat;
    }



    /**
     * Set produit
     *
     * @param \StockBundle\Entity\Produit $produit
     * @return Alerte
     */
    public function setProduit(\StockBundle\Entity\Produit $produit = null)
    {
        $this->produit = $produit;

        return $this;
    }

    /**
     * Get produit
     *
     * @return \StockBundle\Entity\Produit
     */
    public function getProduit()
    {
        return $this->produit;
    }


}
