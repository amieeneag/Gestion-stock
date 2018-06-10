<?php

namespace BackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MouvementProduit
 *
 * @ORM\Table(name="mouvement_produit")
 * @ORM\Entity(repositoryClass="BackBundle\Repository\MouvementProduitRepository")
 */
class MouvementProduit
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
     * @ORM\ManyToOne(targetEntity="StockBundle\Entity\Produit", inversedBy="produits" , cascade={"persist"})
     * @ORM\JoinColumn(name="produit_id", referencedColumnName="id")
     *@ORM\JoinColumn(nullable=false)
     */
    private $produit;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="statut", type="string", length=255)
     */
    private $statut;

    /**
     * @var int
     *
     * @ORM\Column(name="quantite", type="integer")
     */
    private $quantite;

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
     * @return MouvementProduit
     */
    public function setDate($date)
    {
        $this->date = $date;

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
     * Set statut
     *
     * @param string $statut
     * @return MouvementProduit
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * Get statut
     *
     * @return string 
     */
    public function getStatut()
    {
        return $this->statut;
    }


    /**
     * Set quantite
     *
     * @param integer $quantite
     * @return MouvementProduit
     */
    public function setQuantite($quantite)
    {
        $this->quantite = $quantite;

        return $this;
    }

    /**
     * Get quantite
     *
     * @return integer
     */
    public function getQuantite()
    {
        return $this->quantite;
    }


    /**
     * Set produit
     *
     * @param \StockBundle\Entity\Produit $produit
     * @return MouvementProduit
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
