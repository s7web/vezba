<?php
namespace S7D\Vendor\Auth\Controller;

use GuzzleHttp\Client;
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

	public function logout() {
		$this->session->remove('auth');
		Response::redirect('?login');
	}

	public function registration() {
		return $this->view('S7D\App\\' . $this->parameters->get('app') . '::registration.html.twig');
	}

	public function verify($token = '') {

		if($token) {
			$user = $this->em->getRepository( 'S7D\Vendor\Auth\Entity\User' )->findOneBy(['token' => $token]);
			if($user) {
				$user->setToken(null);
				$user->setStatus(1);
				$this->em->persist($user);
				$this->em->flush();
				Response::redirect('?login');
			} else {
				return new Response('Invalid token.');
			}
		}

		$client = new Client();
		$google = $client->request('POST', 'https://www.google.com/recaptcha/api/siteverify', [
			'form_params' => [
				'secret' => $this->parameters->get('google-recaptcha'),
				'response' => $this->request->get('g-recaptcha-response'),
			]
		]);
		$response = $google->getBody()->getContents();

		$response = json_decode($response);

		if($response->success) {
			$token = md5(uniqid());
			$url = $this->parameters->get('url') . '?verify/' . $token;
			$email = $this->request->get('email');
			mail($email,'registration','welcome <a href="' . $url . '">click</a>', 'Content-type: text/html');
			$this->insertUser($email, $this->request->get('password'), 'USER', [], 0, $token);
			return new Response('Check your email.');
		}
		Response::redirectBack();
	}

	private function insertUser($email, $password, $role, $meta = [], $status = 0, $token = null) {
		$user = new User();
		$user->setEmail($email);
		$user->setUsername($email);
		$password = password_hash($password, PASSWORD_DEFAULT);
		$user->setPassword($password);
		$user->setRoles([$role]);
		$user->setStatus($status);
		$user->setToken($token);
		$group = $this->em->getRepository( 'S7D\Vendor\Auth\Entity\User' )->createQueryBuilder('u');
		$group->select('MAX(u.user_group)');
		$newGroup = $group->getQuery()->getSingleScalarResult() + 1;
		$user->setUserGroup($newGroup);

		$user->setMeta($meta);
		$this->em->persist($user);
		$this->em->flush();

		return $user->getId();
	}

	public function register() {

		$email = $this->request->get('email');
		$user = $this->em->getRepository( 'S7D\Vendor\Auth\Entity\User' )->findOneBy(array(
			'email' => $email,
		));
		if($user) {
			$this->session->setFlash("Registration failed, email $email is alredy taken.");
		} else {
			$name = explode(' ', $this->request->get('name'));
			$firstName = $name[0];
			$lastName = isset($name[1]) ? $name[1] : '';
			$meta = [
				'first_name' => $firstName,
				'last_name' => $lastName,
			];
			$this->insertUser($email, $this->request->get('password'), 'ADMIN', $meta);
			$this->session->setFlash('You have been registered. Wait until administrator enable this account.');
		}

		return $this->view('S7D\App\\' . $this->parameters->get('app') . '::login.html.twig');
	}

	public function registered(){
		return $this->view('S7D\App\\' . $this->parameters->get('app') . '::registered.html.twig');
	}
}