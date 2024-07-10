<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UsuarioRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UsuarioRepository::class)]
class Usuario implements UserInterface, PasswordAuthenticatedUserInterface{
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    private ?InfoReparticion $reparticion = null;

    #[ORM\ManyToOne]
    private ?InfoDependencia $dependencia = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $cuil = null;

    #[ORM\Column]
    private ?int $dni = null;

    #[ORM\ManyToOne]
    private ?Genero $genero = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nombre = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $apellido = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $fechaAlta = null;

    #[ORM\Column(nullable: true)]
    private ?int $activo = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $ultimoAcceso = null;

    #[ORM\Column(nullable: true)]
    private ?int $borrado = null;


    #[ORM\JoinTable(name: 'usuario_rol')]
    #[ORM\JoinColumn(name: 'usuario_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'rol_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: Rol::class)]
    private Collection $rolesActivos;


    
    private  $rolesObject;

    public function __construct()
    {
        $this->rolesActivos = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Devuelve la reparticion del usuario
     *
     * @return InfoReparticion|null
     */
    public function getReparticion(): ?InfoReparticion
    {
        return $this->reparticion;
    }

    /**
     * Asigna una reparticion al usuario
     *
     * @param infoReparticion|null $reparticion
     * @return static
     */
    public function setReparticion(?infoReparticion $reparticion): static
    {
        $this->reparticion = $reparticion;

        return $this;
    }

    public function getDependencia(): ?InfoDependencia
    {
        return $this->dependencia;
    }

    public function setDependencia(?InfoDependencia $dependencia): static
    {
        $this->dependencia = $dependencia;

        return $this;
    }


    public function getGenero(): ?Genero
    {
        return $this->genero;
    }

    public function setGenero(?Genero $genero): static
    {
        $this->genero = $genero;

        return $this;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(?string $nombre): static
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getApellido(): ?string
    {
        return $this->apellido;
    }

    public function setApellido(?string $apellido): static
    {
        $this->apellido = $apellido;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
    * @see PasswordAuthenticatedUserInterface
    */
    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getFechaAlta(): ?\DateTimeInterface
    {
        return $this->fechaAlta;
    }

    public function setFechaAlta(\DateTimeInterface $fechaAlta): static
    {
        $this->fechaAlta = $fechaAlta;

        return $this;
    }

    public function getActivo(): ?int
    {
        return $this->activo;
    }

    public function setActivo(?int $activo): static
    {
        $this->activo = $activo;

        return $this;
    }

    public function getUltimoAcceso(): ?\DateTimeInterface
    {
        return $this->ultimoAcceso;
    }

    public function setUltimoAcceso(?\DateTimeInterface $ultimoAcceso): static
    {
        $this->ultimoAcceso = $ultimoAcceso;

        return $this;
    }

    public function getBorrado(): ?int
    {
        return $this->borrado;
    }

    public function setBorrado(?int $borrado): static
    {
        $this->borrado = $borrado;

        return $this;
    }

    
    public function getDni(): ?int
    {
        return $this->dni;
    }

    public function setDni(int $dni): static
    {
        $this->dni = $dni;

        return $this;
    }

    public function getCuil(): ?string
    {
        return $this->cuil;
    }

    public function setCuil(?string $cuil): static
    {
        $this->cuil = $cuil;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

   

    /**
     * @see UserInterface
     */
    public function getRoles(): array {   
            $roles = [];
            foreach ($this->rolesActivos->toArray() as $rol) {
                $roles[] = $rol->getNombre();
            }
            return array_unique($roles);
    }
    
    public function setRoles(array $roles): static
    {
        $this->rolesActivos = $roles;

        return $this;
    }
    

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    
    public function fetchRoles(){
        return $this->rolesActivos;
    }


    
    public function addRolesActivos(Collection $roles)
    {
        $this->rolesActivos = $roles;
        return $this;
    }

    public function removeRolesActivos(Rol $rol)
    {
        if ($this->rolesActivos->contains($rol))
        {
            $this->rolesActivos->removeElement($rol);
            //$rol->removeUsuario($this);
        }
        return $this;

    }

    public function setRolesActivos($rolesActivos): void {
        $this->rolesActivos = $rolesActivos;
    }
   
    /*
    * @return array de ids de roles dependientes
    */

    public function getRoleCollection(){
        if(is_array($this->rolesActivos)) {
            return $this->rolesActivos;
        } else if($this->rolesActivos && ($this->rolesActivos instanceof Collection)) {
            return $this->rolesActivos->toArray();
        } else {
            return [];
        }
    }
  
    public function getRolesActivos(): Collection
    {
        if(is_array($this->rolesActivos)) {
            return $this->rolesActivos;
        } else if($this->rolesActivos && ($this->rolesActivos instanceof Collection)) {
            return $this->rolesActivos;
        } else {
            return $empty = new ArrayCollection();
        }
    }
}
