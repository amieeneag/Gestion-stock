<?php

namespace FactureBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FactureVente
 *
 * @ORM\Table(name="facture_vente")
 * @ORM\Entity(repositoryClass="FactureBundle\Repository\FactureVenteRepository")
 */
class FactureVente
{


    /**
     * @ORM\OneToOne(targetEntity="VenteBundle\Entity\CommandeVente" , cascade={"persist"})
     * @ORM\JoinColumn(name="commandeVente_id", referencedColumnName="id")
     *@ORM\JoinColumn(nullable=false)
     */
    private $commandeVente;

    /**
     * @ORM\ManyToOne(targetEntity="BackBundle\Entity\Utilisateur", inversedBy="utilisateurs" , cascade={"persist"})
     * @ORM\JoinColumn(name="utilisateur_id", referencedColumnName="id")
     *@ORM\JoinColumn(nullable=false)
     */
    private $utilisateur;


    /**
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
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="total", type="decimal", precision=10, scale=2)
     */
    private $total;

    /**
     * @var string
     *
     * @ORM\Column(name="modePaiement", type="string", length=255)
     */
    private $modePaiement;

    /**
     * @var int
     *
     * @ORM\Column(name="remise", type="integer")
     */
    private $remise;



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
     * @return FactureVente
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
     * Set total
     *
     * @param string $total
     * @return FactureVente
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total
     *
     * @return string 
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set modePaiement
     *
     * @param string $modePaiement
     * @return FactureVente
     */
    public function setModePaiement($modePaiement)
    {
        $this->modePaiement = $modePaiement;

        return $this;
    }

    /**
     * Get modePaiement
     *
     * @return string 
     */
    public function getModePaiement()
    {
        return $this->modePaiement;
    }

    /**
     * Set remise
     *
     * @param integer $remise
     * @return FactureVente
     */
    public function setRemise($remise)
    {
        $this->remise = $remise;

        return $this;
    }

    /**
     * Get remise
     *
     * @return integer 
     */
    public function getRemise()
    {
        return $this->remise;
    }


    /**
     * Set commandeVente
     *
     * @param \VenteBundle\Entity\CommandeVente $commandeVente
     * @return FactureVente
     */
    public function setCommandeVente(\VenteBundle\Entity\CommandeVente $commandeVente = null)
    {
        $this->commandeVente = $commandeVente;

        return $this;
    }

    /**
     * Get commandeVente
     *
     * @return \VenteBundle\Entity\CommandeVente 
     */
    public function getCommandeVente()
    {
        return $this->commandeVente;
    }

    /**
     * Set utilisateur
     *
     * @param \BackBundle\Entity\Utilisateur $utilisateur
     * @return FactureVente
     */
    public function setUtilisateur(\BackBundle\Entity\Utilisateur $utilisateur = null)
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    /**
     * Get utilisateur
     *
     * @return \BackBundle\Entity\Utilisateur
     */
    public function getUtilisateur()
    {
        return $this->utilisateur;
    }
}
