<?php
 
namespace App\Entity;
 
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
 
/**
 * @ORM\Entity(repositoryClass="App\Repository\AuteurRepository")
 * @ApiResource(
 *      attributes=
 *          {
 *          "order"= {"nom":"ASC"},
 *          "pagination_enabled"=false
 *          },
 *      collectionOperations={
 *          "get"={
 *              "method"="GET",
 *              "normalization_context"=
 *                  {
 *                      "groups"={"get_auteur_role_adherent"}
 *                  }
 *           },
 *          "post"={
 *              "method"="POST",
 *              "access_control"="is_granted('ROLE_ADMIN')",
 *              "access_control_message"="Vous n'avez pas les droits d'accéder à cette ressource",
 *              "denormalization_context"= {
 *                  "groups"={"put_role_manager"}
 *              }  
 *          }
 *      },
 *      itemOperations={
 *          "get"={
 *              "method"="GET",
 *              "normalization_context"={
 *                  "groups"={"get_auteur_role_adherent"}
 *              }
 *          },
 *          "put"={
 *              "method"="PUT",
 *              "access_control"="is_granted('ROLE_ADMIN')",
 *              "access_control_message"="Vous n'avez pas les droits d'accéder à cette ressource",
 *              "denormalization_context"= {
 *                  "groups"={"put_role_manager"}
 *              }  
 *          },
 *          "delete"={
 *              "method"="DELETE",
 *              "access_control"="is_granted('ROLE_ADMIN')",
 *              "access_control_message"="Vous n'avez pas les droits d'accéder à cette ressource"
 *          }        
 * }
 * )
 * @ApiFilter(
 *      SearchFilter::class,
 *      properties={
 *          "nom": "ipartial",
 *          "prenom": "ipartial",
 *          "nationalite" : "exact"
 *      }
 * )
 */
class Auteur
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"get_auteur_role_adherent"})
     */
    private $id;
 
    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"get"})
     * @Groups({"get_auteur_role_adherent","put_role_manager"})
     */
    private $nom;
 
    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"get"})
     * @Groups({"get_auteur_role_adherent","put_role_manager"})
     */
    private $prenom;
 
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Nationnalite", inversedBy="auteurs")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"get_auteur_role_adherent","put_role_manager"})
     */
    private $nationnalite;
 
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Livre", mappedBy="auteur")
     * @Groups({"get_auteur_role_adherent"})
     */
    private $livres;
 
    public function __construct()
    {
        $this->livres = new ArrayCollection();
    }
 
    public function getId(): ? int
    {
        return $this->id;
    }
 
    public function getNom(): ? string
    {
        return $this->nom;
    }
 
    public function setNom(string $nom): self
    {
        $this->nom = $nom;
 
        return $this;
    }
 
    public function getPrenom(): ? string
    {
        return $this->prenom;
    }
 
    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;
 
        return $this;
    }
 
    public function getNationnalite(): ? Nationnalite
    {
        return $this->nationnalite;
    }
 
    public function setNationnalite(? Nationnalite $nationnalite): self
    {
        $this->nationnalite = $nationnalite;
 
        return $this;
    }
 
    /**
     * @return Collection|Livre[]
     */
    public function getLivres(): Collection
    {
        return $this->livres;
    }
 
    public function addLivre(Livre $livre): self
    {
        if (!$this->livres->contains($livre)) {
            $this->livres[] = $livre;
            $livre->setAuteur($this);
        }
 
        return $this;
    }
 
    public function removeLivre(Livre $livre): self
    {
        if ($this->livres->contains($livre)) {
            $this->livres->removeElement($livre);
            // set the owning side to null (unless already changed)
            if ($livre->getAuteur() === $this) {
                $livre->setAuteur(null);
            }
        }
 
        return $this;
    }
 
    public function __toString()
    {
        return (string)$this->nom . " " . $this->prenom;
    }
}