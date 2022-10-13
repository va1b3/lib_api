<?php

namespace App\Service;

use App\Entity\User;
use App\Exception\FieldsErrorException;
use App\Exception\LoginFailedException;
use App\Exception\UserAlreadyExistException;
use App\Exception\UserNotFoundException;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Http\Authentication\AuthenticationSuccessHandler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    public function __construct(
        private readonly UserPasswordHasherInterface $hasher,
        private readonly UserRepository $userRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly AuthenticationSuccessHandler $successHandler
    ) {
    }

    public function signUp(array $data): array
    {
        try {
            $email    = $data['email'];
            $password = $data['password'];
        } catch (Exception $exception) {
            throw new FieldsErrorException();
        }

        if ($this->userRepository->isExistByEmail($email)) {
            throw new UserAlreadyExistException();
        }

        $user = (new User())->setEmail($email);
        $user->setPassword($this->hasher->hashPassword($user, $password));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return ['success' => true];
    }

    public function getToken(array $data): Response
    {
        try {
            $email    = $data['email'];
            $password = $data['password'];
        } catch (Exception $exception) {
            throw new FieldsErrorException();
        }

        if ( ! $this->userRepository->isExistByEmail($email)) {
            throw new UserNotFoundException();
        }

        $user = $this->userRepository->findOneBy(['email' => $email]);

        if ( ! $this->hasher->isPasswordValid($user, $password)) {
            throw new LoginFailedException();
        }

        return $this->successHandler->handleAuthenticationSuccess($user);
    }
}