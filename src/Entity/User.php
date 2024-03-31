<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use App\Core\Database\Connector\DoctrineConnector;

#[ORM\Entity]
#[ORM\Table(name: 'users')]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: Types::INTEGER)]
    private int $id;

    #[ORM\Column(type: Types::STRING, nullable: false)]
    private string $login;

    #[ORM\Column(type: Types::STRING, nullable: false)]
    private string $email;

    #[ORM\Column(type: Types::STRING, nullable: false)]
    private string $password;

    #[ORM\OneToMany(targetEntity: Usermeta::class, mappedBy: 'user', cascade: ['persist'])]
    private Collection $usermetas;

    #[ORM\Column(name: 'created_at', type: Types::DATETIME_IMMUTABLE, nullable: false)]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(name: 'updated_at', type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(name: '2fa_token', type: Types::STRING, nullable: true)]
    private ?string $twoFaToken = null;

    #[ORM\ManyToMany(targetEntity: Role::class)]
    #[ORM\JoinTable(name: 'user_roles')]
    private Collection $roles;

    #[ORM\OneToMany(targetEntity: Post::class, mappedBy: 'author', cascade: ['persist'])]
    private Collection $posts;

    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'author', cascade: ['persist'])]
    private Collection $comments;

    #[ORM\OneToMany(targetEntity: Message::class, mappedBy: 'author', cascade: ['persist'])]
    private Collection $receivedMessages;

    #[ORM\OneToMany(targetEntity: Message::class, mappedBy: 'recipient', cascade: ['persist'])]
    private Collection $sentMessages;

    public function __construct()
    {
        $this->usermetas = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->roles = new ArrayCollection();
        $this->posts = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->receivedMessages = new ArrayCollection();
        $this->sentMessages = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getUsermetas(): Collection
    {
        return $this->usermetas;
    }

    public function addUsermeta(Usermeta $usermeta): self
    {
        if (!$this->usermetas->contains($usermeta)) {
            $this->usermetas[] = $usermeta;
            $usermeta->setUser($this);
        }

        return $this;
    }

    public function removeUsermeta(Usermeta $usermeta): self
    {
        if ($this->usermetas->removeElement($usermeta)
            && $usermeta->getUser() === $this) {
            $usermeta->setUser(null);
        }

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->getMetaValueByKey(USERMETA_FIRST_NAME);
    }

    public function setFirstName(?string $firstName): self
    {
        $this->setMetaValueByKey(USERMETA_FIRST_NAME, $firstName);

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->getMetaValueByKey(USERMETA_LAST_NAME);
    }

    public function setLastName(?string $lastName): self
    {
        $this->setMetaValueByKey(USERMETA_LAST_NAME, $lastName);

        return $this;
    }

    public function getFullName(): string
    {
        return $this->getFirstName() . ' ' . $this->getLastName();
    }

    public function getBirthdate(): ?string
    {
        return $this->getMetaValueByKey(USERMETA_BIRTHDATE);
    }

    public function setBirthdate(?\DateTimeImmutable $birthdate): self
    {
        $this->setMetaValueByKey(USERMETA_BIRTHDATE, $birthdate?->format('Y-m-d H:i:s'));

        return $this;
    }

    public function getAge(): ?int
    {
        $birthdate = $this->getBirthdate();

        if ($birthdate === null) {
            return null;
        }

        $now = new \DateTimeImmutable();

        return $now->diff(new \DateTimeImmutable($birthdate))->y;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getLastLogin(): ?string
    {
        return $this->getMetaValueByKey(USERMETA_LAST_LOGIN);
    }

    public function setLastLogin(?\DateTimeImmutable $lastLogin): self
    {
        $this->setMetaValueByKey(USERMETA_LAST_LOGIN, $lastLogin?->format('Y-m-d H:i:s'));

        return $this;
    }

    public function getTwoFaToken(): ?string
    {
        return $this->twoFaToken;
    }

    public function setTwoFaToken(?string $twoFaToken): self
    {
        $this->twoFaToken = $twoFaToken;

        return $this;
    }

    public function getRoles(): Collection
    {
        return $this->roles;
    }

    public function addRole(Role $role): self
    {
        if (!$this->roles->contains($role)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    public function removeRole(Role $role): self
    {
        $this->roles->removeElement($role);

        return $this;
    }

    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts->add($post);
            $post->setAuthor($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        $this->posts->removeElement($post);

        return $this;
    }

    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setAuthor($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        $this->comments->removeElement($comment);

        return $this;
    }

    public function getReceivedMessages(): Collection
    {
        return $this->receivedMessages;
    }

    public function removeReceivedMessage(Message $message): self
    {
        $this->receivedMessages->removeElement($message);

        return $this;
    }

    public function getSentMessages(): Collection
    {
        return $this->sentMessages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->sentMessages->contains($message)) {
            $this->sentMessages->add($message);
            $message->setAuthor($this);
        }

        return $this;
    }

    public function removeSentMessage(Message $message): self
    {
        $this->sentMessages->removeElement($message);

        return $this;
    }

    public function getMessages(): Collection
    {
        return new ArrayCollection(array_merge($this->receivedMessages->toArray(), $this->sentMessages->toArray()));
    }

    public function userData(): array
    {
        return [
            'id' => $this->getId(),
            'login' => $this->getLogin(),
            'email' => $this->getEmail(),
            'first_name' => $this->getFirstName(),
            'last_name' => $this->getLastName(),
            'created_at' => $this->getCreatedAt(),
            'updated_at' => $this->getUpdatedAt(),
            'last_login' => $this->getLastLogin(),
            'age' => $this->getAge(),
        ];
    }

    private function getMetaValueByKey(string $key): ?string
    {
        foreach ($this->usermetas as $usermeta) {
            if ($usermeta->getMetaKey() === $key) {
                return $usermeta->getMetaValue();
            }
        }

        return null;
    }

    private function setMetaValueByKey(string $key, string $value): void
    {
        $manager = DoctrineConnector::getEntityManager();
        $usermeta = $manager
            ->getRepository(Usermeta::class)
            ->findOneBy(['user' => $this, 'metaKey' => $key]);

        if ($usermeta) {
            $usermeta->setMetaValue($value);
            try {
                $manager->persist($usermeta);
                $manager->flush();
            } catch (ORMException $e) {
                echo $e->getMessage();
            }
        } else {
            $usermeta = new Usermeta();
            $usermeta
                ->setUser($this)
                ?->setMetaKey($key)
                ->setMetaValue($value);
            $this->addUsermeta($usermeta);
        }
    }
}
