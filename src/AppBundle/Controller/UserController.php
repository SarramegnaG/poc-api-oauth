<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\UserEditType;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use Swagger\Annotations as SWG;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class UserController extends FOSRestController
{
    /**
     * List the users.
     *
     * @Rest\Get(
     *     path = "/users"
     * )
     * @Rest\View
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the users"
     * )
     * @SWG\Tag(name="users")
     *
     * @return array
     */
    public function getUsersAction()
    {
        return $this->getDoctrine()->getRepository(User::class)->findAll();
    }

    /**
     * Search the users.
     *
     * @Rest\Get(
     *     path = "/users/search"
     * )
     * @Rest\View
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the users"
     * )
     * @SWG\Tag(name="users")
     *
     * @Rest\QueryParam(name="username", description="Username of the user")
     *
     * @param ParamFetcher $paramFetcher
     *
     * @return array
     */
    public function getUsersSearchAction(ParamFetcher $paramFetcher)
    {
        return $this->getDoctrine()->getRepository(User::class)->searchBy($paramFetcher->all());
    }

    /**
     * Show a user.
     *
     * @Rest\Get(
     *     path = "/users/{id}"
     * )
     * @Rest\View
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns a user"
     * )
     * @SWG\Tag(name="users")
     *
     * @param User $user
     *
     * @return User
     */
    public function getUserAction(User $user)
    {
        return $user;
    }

    /**
     * Create a user.
     *
     * @Rest\Post(
     *     path = "/users"
     * )
     * @Rest\View
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns a user"
     * )
     * @SWG\Tag(name="users")
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     *
     * @return User|FormInterface
     */
    public function postUsersAction(Request $request, EntityManagerInterface $em)
    {
        $user = new User();
        $form = $this->createForm(UserEditType::class, $user);

        $form->submit($request->request->all());

        if (!$form->isValid()) {
            return $form;
        }

        $user->setPassword('test');
        $em->persist($user);
        $em->flush();

        return $user;
    }

    /**
     * Update a user.
     *
     * @Rest\Put(
     *     path = "/users/{id}"
     * )
     * @Rest\View
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the user"
     * )
     * @SWG\Tag(name="users")
     *
     * @param User $user
     * @param Request $request
     * @param EntityManagerInterface $em
     *
     * @return User|FormInterface
     */
    public function putUserAction(User $user, Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(UserEditType::class, $user);

        $form->submit($request->request->all());

        if (!$form->isValid()) {
            return $form;
        }

        $em->flush();

        return $user;
    }
}