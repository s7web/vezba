<?php
namespace S7D\Vendor\Auth\Controller;

use S7D\Vendor\Auth\Entity\User;
use S7D\Vendor\HTTP\Response;
use S7D\Vendor\Routing\Controller;

class UserController extends Controller {

	public function login() {
		if($this->user->getId()) {
			Response::redirect($this->parameters->get('landing')[$this->user->getRoles()[0]]);
		}
		return $this->view('S7D\App\\' . $this->parameters->get('app') . '::login.html.twig');
	}

	public function logout(){
		$this->session->set('auth', null);
		Response::redirect('?login');
	}

	public function register() {

		$email = $this->request->get('email');
		$user = $this->em->getRepository( 'S7D\Vendor\Auth\Entity\User' )->findOneBy(array(
			'email' => $email,
		));

		if($user) {
			$this->session->setFlash("Registration failed, email $email is alredy taken.");
		} else {
			$user = new User();
			$user->setEmail($email);
			$user->setUsername($email);
			$password = password_hash($this->request->get('password'), PASSWORD_DEFAULT);
			$user->setPassword($password);
			$user->setRoles(['ADMIN']);
			$user->setStatus(0);
			$group = $this->em->getRepository( 'S7D\Vendor\Auth\Entity\User' )->createQueryBuilder('u');
			$group->select('MAX(u.user_group)');
			$newGroup = $group->getQuery()->getSingleScalarResult() + 1;
			$user->setUserGroup($newGroup);
			$name = explode(' ', $this->request->get('name'));
			$firstName = $name[0];
			$lastName = isset($name[1]) ? $name[1] : '';
			$user->setMeta([
				'first_name' => $firstName,
				'last_name' => $lastName,
			]);
			$this->em->persist($user);
			$this->em->flush();
			$this->session->setFlash('You have been registered. Wait until administrator enable this account.');
		}

		return $this->view('S7D\App\\' . $this->parameters->get('app') . '::login.html.twig');
	}

	public function registered(){
		return $this->view('S7D\App\\' . $this->parameters->get('app') . '::registered.html.twig');
	}
}