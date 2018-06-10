<?php

namespace VenteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LigneVente
 *
 * @ORM\Table(name="ligne_vente")
 * @ORM\Entity(repositoryClass="VenteBundle\Repository\LigneVenteRepository")
 */
class LigneVente
{

    /**
     * @ORM\ManyToOne(targetEntity="StockBundle\Entity\Produit", inversedBy="produits" , cascade={"persist"})
     * @ORM\JoinColumn(name="produit_id", referencedColumnName="id")
     *@ORM\JoinColumn(nullable=false)
     */
    private $produit;

    /**
     * @ORM\ManyToOne(targetEntity="VenteBundle\Entity\CommandeVente", inversedBy="commandes" , cascade={"persist"})
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
     * @ORM\Column(name="tva", type="decimal", precision=10, scale=2)
     */
    private $tva;


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
     * @return LigneVente
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
     * @return LigneVente
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
     * Set tva
     *
     * @param string $tva
     * @return LigneVente
     */
    public function setTva($tva)
    {
        $this->tva = $tva;
            return $this;
    }

    /**
     * Get tva
     *
     * @return string
     */
    public function getTva()
    {
        return $this->tva;
    }


    /**
     * Set sousTotal
     *
     * @param string $sousTotal
     * @return LigneVente
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
     * @return LigneVente
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
     * @param \VenteBundle\Entity\CommandeVente $commande
     * @return LigneVente
     */
    public function setCommande(\VenteBundle\Entity\CommandeVente $commande = null)
    {
        $this->commande = $commande;

        return $this;
    }

    /**
     * Get commande
     *
     * @return \VenteBundle\Entity\CommandeVente 
     */
    public function getCommande()
    {
        return $this->commande;
    }
}
