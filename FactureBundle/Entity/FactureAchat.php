<?php

namespace FactureBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FactureAchat
 *
 * @ORM\Table(name="facture_achat")
 * @ORM\Entity(repositoryClass="FactureBundle\Repository\FactureAchatRepository")
 */
class FactureAchat
{


    /**
     * @ORM\OneToOne(targetEntity="AchatBundle\Entity\CommandeAchat" , cascade={"persist"})
     * @ORM\JoinColumn(name="commandeAchat_id", referencedColumnName="id")
     *@ORM\JoinColumn(nullable=false)
     */
    private $commandeAchat;

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
     * @var string
     *
     * @ORM\Column(name="total", type="decimal", precision=10, scale=2)
     */
    private $total;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;









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
     * Set total
     *
     * @param string $total
     * @return FactureAchat
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
     * Set date
     *
     * @param \DateTime $date
     * @return FactureAchat
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
     * Set commandeAchat
     *
     * @param \AchatBundle\Entity\CommandeAchat $commandeAchat
     * @return FactureAchat
     */
    public function setCommandeAchat(\AchatBundle\Entity\CommandeAchat $commandeAchat = null)
    {
        $this->commandeAchat = $commandeAchat;

        return $this;
    }

    /**
     * Get commandeAchat
     *
     * @return \AchatBundle\Entity\CommandeAchat 
     */
    public function getCommandeAchat()
    {
        return $this->commandeAchat;
    }

    /**
     * Set utilisateur
     *
     * @param \BackBundle\Entity\Utilisateur $utilisateur
     * @return FactureAchat
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
