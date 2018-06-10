<?php

namespace StockBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Produit
 *
 * @ORM\Table(name="produit")
 * @ORM\Entity(repositoryClass="StockBundle\Repository\ProduitRepository")
 */
class Produit
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
     * @ORM\ManyToOne(targetEntity="StockBundle\Entity\Categorie", inversedBy="produits" , cascade={"persist"})
     * @ORM\JoinColumn(name="categorie_id", referencedColumnName="id")
     * @ORM\JoinColumn(nullable=false)
     */
    private $categorie;

    /**
     * @ORM\ManyToOne(targetEntity="StockBundle\Entity\Marque", inversedBy="marques" , cascade={"persist"})
     * @ORM\JoinColumn(name="marque_id", referencedColumnName="id")
     * @ORM\JoinColumn(nullable=false)
     */
    private $marque;


    /**
     * @var string
     *
     * @ORM\Column(name="designation", type="string", length=255)
     */
    private $designation;

    /**
     * @var float
     *
     * @ORM\Column(name="prix_achat", type="decimal", precision=10, scale=2)
     */
    private $prixAchat ;

    /**
     * @var float
     *
     * @ORM\Column(name="prix_vente", type="decimal", precision=10, scale=2)
     */
    private $prixVente = 0;
    /**
     * @var float
     *
     * @ORM\Column(name="tva", type="decimal", precision=10, scale=2)
     */
    private $tva = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255)
     */
    private $image;

    /**
     * @var int
     *
     * @ORM\Column(name="marge", type="integer")
     */
    private $marge=5;

    /**
     * @var int
     *
     * @ORM\Column(name="quantiteStock", type="integer")
     */
    private $quantiteStock;

    /**
     * @var int
     *
     * @ORM\Column(name="quantiteSeuil", type="integer")
     */
    private $quantiteSeuil=10;



    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active = true ;

    private $file=null;

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
     * Set designation
     *
     * @param string $designation
     * @return Produit
     */
    public function setDesignation($designation)
    {
        $this->designation = $designation;

        return $this;
    }

    /**
     * Get designation
     *
     * @return string 
     */
    public function getDesignation()
    {
        return $this->designation;
    }

    /**
     * Set prixAchat
     *
     * @param string $prixAchat
     * @return Produit
     */
    public function setPrixAchat($prixAchat)
    {
        $this->prixAchat = $prixAchat;

        return $this;
    }

    /**
     * Get prixAchat
     *
     * @return string 
     */
    public function getPrixAchat()
    {
        return $this->prixAchat;
    }

    /**
     * Set prixVente
     *
     * @param string $prixVente
     * @return Produit
     */
    public function setPrixVente($prixVente)
    {
        $this->prixVente =$prixVente;

        return $this;
    }

    /**
     * Get prixVente
     *
     * @return string 
     */
    public function getPrixVente()
    {
        return $this->prixVente;
    }


    /**
     * Set tva
     *
     * @param string $tva
     * @return Produit
     */
    public function setTva($tva)
    {
        $this->tva =$tva;

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
     * Set image
     *
     * @param string $image
     * @return Produit
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string 
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set marge
     *
     * @param integer $marge
     * @return Produit
     */
    public function setMarge($marge)
    {
        $this->marque = $marge;

        return $this;
    }

    /**
     * Get marge
     *
     * @return integer 
     */
    public function getmarge()
    {
        return $this->marge;
    }

    /**
     * Set quantiteStock
     *
     * @param integer $quantiteStock
     * @return Produit
     */
    public function setQuantiteStock($quantiteStock)
    {
        $this->quantiteStock = $quantiteStock;

        return $this;
    }

    /**
     * Get quantiteStock
     *
     * @return integer 
     */
    public function getQuantiteStock()
    {
        return $this->quantiteStock;
    }

    /**
     * Set quantiteSeuil
     *
     * @param integer $quantiteSeuil
     * @return Produit
     */
    public function setQuantiteSeuil($quantiteSeuil)
    {
        $this->quantiteSeuil = $quantiteSeuil;

        return $this;
    }

    /**
     * Get quantiteSeuil
     *
     * @return integer 
     */
    public function getQuantiteSeuil()
    {
        return $this->quantiteSeuil;
    }



    /**
     * Set description
     *
     * @param string $description
     * @return Produit
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }


    /**
     * Set active
     *
     * @param boolean $active
     * @return Produit
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set category
     *
     * @param \StockBundle\Entity\Categorie $category
     * @return Produit
     */
    public function setCategorie(\StockBundle\Entity\Categorie $categorie )
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * Get category
     *
     * @return \StockBundle\Categorie 
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * Set marque
     *
     * @param \StockBundle\Entity\Marque $marque
     * @return Produit
     */
    public function setMarque(\StockBundle\Entity\Marque $marque )
    {
        $this->marque = $marque;

        return $this;
    }

    /**
     * Get marque
     *
     * @return \StockBundle\Entity\Marque
     */
    public function getMarque()
    {
        return $this->marque;
    }
    public function getFile()
    {
        return $this->file;
    }

    public function setFile( $file )
    {
        $this->file = $file;

        return $this;
    }


}
