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
 * @ORM\Entity(repositoryClass="App\Repository\NationaliteRepository")
 * @ApiResource(
 *      attributes={
 *          "order"= {
 *              "libelle":"ASC"
 *           }
 * })
 * @ApiFilter(
 *      SearchFilter::class,
 *      properties={
 *          "libelle": "ipartial"
 *      }
 * )
 */
class Nationnalite
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
     * @Groups({"get_auteur_role_adherent"})
     */
    private $libelle;
 
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Auteur", mappedBy="nationalite")
     */
    private $auteurs;
 
    public function __construct()
    {
        $this->auteurs = new ArrayCollection();
    }
 
    public function getId(): ? int
    {
        return $this->id;
    }
 
    public function getLibelle(): ? string
    {
        return $this->libelle;
    }
 
    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;
 
        return $this;
    }
 
    /**
     * @return Collection|Auteur[]
     */
    public function getAuteurs(): Collection
    {
        return $this->auteurs;
    }
 
    public function addAuteur(Auteur $auteur): self
    {
        if (!$this->auteurs->contains($auteur)) {
            $this->auteurs[] = $auteur;
            $auteur->setNationnalite($this);
        }
 
        return $this;
    }
 
    public function removeAuteur(Auteur $auteur): self
    {
        if ($this->auteurs->contains($auteur)) {
            $this->auteurs->removeElement($auteur);
            // set the owning side to null (unless already changed)
            if ($auteur->getNationnalite() === $this) {
                $auteur->setNationnalite(null);
            }
        }
 
        return $this;
    }
 
    public function __toString()
    {
        return (string)$this->libelle;
    }
}