<?php

namespace AchatBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LigneAchat
 *
 * @ORM\Table(name="ligne_achat")
 * @ORM\Entity(repositoryClass="AchatBundle\Repository\LigneAchatRepository")
 */
class LigneAchat
{

    /**
     * @ORM\ManyToOne(targetEntity="StockBundle\Entity\Produit", inversedBy="produits" , cascade={"persist"})
     * @ORM\JoinColumn(name="produit_id", referencedColumnName="id")
     *@ORM\JoinColumn(nullable=false)
     */
    private $produit;

    /**
     * @ORM\ManyToOne(targetEntity="AchatBundle\Entity\CommandeAchat", inversedBy="commandes" , cascade={"persist"})
     * @ORM\JoinColumn(name="commande_id", referencedColumnName="id")
     *@ORM\JoinColumn(nullable=false)
     */
    private $commande;



    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="quantite", type="integer")
     */
    private $quantite;

    /**
     * @var string
     *
     * @ORM\Column(name="prixUnitaire", type="decimal", precision=10, scale=2)
     */
    private $prixUnitaire;

    /**
     * @var string
     *
     * @ORM\Column(name="sousTotal", type="decimal", precision=10, scale=2)
     */
    private $sousTotal;


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
     * Set quantite
     *
     * @param integer $quantite
     * @return LigneAchat
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
     * Set prixUnitaire
     *
     * @param string $prixUnitaire
     * @return LigneAchat
     */
    public function setPrixUnitaire($prixUnitaire)
    {
        $this->prixUnitaire = $prixUnitaire;

        return $this;
    }

    /**
     * Get prixUnitaire
     *
     * @return string 
     */
    public function getPrixUnitaire()
    {
        return $this->prixUnitaire;
    }

    /**
     * Set sousTotal
     *
     * @param string $sousTotal
     * @return LigneAchat
     */
    public function setSousTotal($sousTotal)
    {
        $this->sousTotal = $sousTotal;

        return $this;
    }

    /**
     * Get sousTotal
     *
     * @return string 
     */
    public function getSousTotal()
    {
        return $this->sousTotal;
    }

    /**
     * Set produit
     *
     * @param \StockBundle\Entity\Produit $produit
     * @return LigneAchat
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

    /**
     * Set commande
     *
     * @param \AchatBundle\Entity\CommandeAchat $commande
     * @return LigneAchat
     */
    public function setCommande(\AchatBundle\Entity\CommandeAchat $commande = null)
    {
        $this->commande = $commande;

        return $this;
    }

    /**
     * Get commande
     *
     * @return \AchatBundle\Entity\CommandeAchat 
     */
    public function getCommande()
    {
        return $this->commande;
    }
}
