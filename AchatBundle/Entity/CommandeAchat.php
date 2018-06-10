<?php

namespace AchatBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CommandeAchat
 *
 * @ORM\Table(name="commande_achat")
 * @ORM\Entity(repositoryClass="AchatBundle\Repository\CommandeAchatRepository")
 */
class CommandeAchat
{


    /**
     * @ORM\ManyToOne(targetEntity="BackBundle\Entity\Utilisateur", inversedBy="utilisateurs" , cascade={"persist"})
     * @ORM\JoinColumn(name="utilisateur_id", referencedColumnName="id")
     *@ORM\JoinColumn(nullable=false)
     */
     private $utilisateur;

    /**
     * @ORM\ManyToOne(targetEntity="AchatBundle\Entity\Fournisseur", inversedBy="fournisseurs" , cascade={"persist"})
     * @ORM\JoinColumn(name="fournisseur_id", referencedColumnName="id")
     *@ORM\JoinColumn(nullable=false)
     */
    private $fournisseur;



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
     * @return CommandeAchat
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
     * @return CommandeAchat
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
     * Set etat
     *
     * @param string $etat
     * @return CommandeAchat
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
     * Set utilisateur
     *
     * @param \BackBundle\Entity\Utilisateur $utilisateur
     * @return CommandeAchat
     */
    public function setUtilisateur(\BackBundle\Entity\Utilisateur $utilisateur)
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

    /**
     * Set fournisseur
     *
     * @param \AchatBundle\Entity\Fournisseur $fournisseur
     * @return CommandeAchat
     */
    public function setFournisseur(\AchatBundle\Entity\Fournisseur $fournisseur )
    {
        $this->fournisseur = $fournisseur;

        return $this;
    }

    /**
     * Get fournisseur
     *
     * @return \AchatBundle\Entity\Fournisseur 
     */
    public function getFournisseur()
    {
        return $this->fournisseur;
    }
}
