<?php

namespace VenteBundle\Entity;

use BackBundle\BackBundle;
use BackBundle\Entity\Utilisateur;
use Doctrine\ORM\Mapping as ORM;

/**
 * CommandeVente
 *
 * @ORM\Table(name="commande_vente")
 * @ORM\Entity(repositoryClass="VenteBundle\Repository\CommandeVenteRepository")
 */
class CommandeVente
{


    /**
     * @ORM\ManyToOne(targetEntity="BackBundle\Entity\Utilisateur", inversedBy="utilisateur" , cascade={"persist"})
     * @ORM\JoinColumn(name="utilisateur_id", referencedColumnName="id")
     *@ORM\JoinColumn(nullable=false)
     */
    private $utilisateur;

    /**
     * @ORM\ManyToOne(targetEntity="VenteBundle\Entity\Client", inversedBy="client" , cascade={"persist"})
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id")
     *@ORM\JoinColumn(nullable=false)
     */
    private $client;


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
     * @return CommandeVente
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
     * @return CommandeVente
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
     * @return CommandeVente
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
     * @param BackBundle\Entity\Utilisateur $utilisateur
     * @return CommandeVente
     */
    public function setUtilisateur(Utilisateur $utilisateur = null)
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    /**
     * Get utilisateur
     *
     * @return BackBundle\Entity\Utilisateur
     */
    public function getUtilisateur()
    {
        return $this->utilisateur;
    }

    /**
     * Set client
     *
     * @param \VenteBundle\Entity\Client $client
     * @return CommandeVente
     */
    public function setClient(\VenteBundle\Entity\Client $client = null)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client
     *
     * @return \VenteBundle\Entity\Client 
     */
    public function getClient()
    {
        return $this->client;
    }
    public function __toString()
    {

      return "Num".$this->getId();
    }
}
