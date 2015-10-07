<?php
namespace S7D\Vendor\Auth\Controller;

use GuzzleHttp\Client;
use S7D\Vendor\Auth\Entity\User;
use S7D\Vendor\HTTP\Response;
use S7D\Vendor\Routing\Controller;

class UserController extends Controller {

	public function login() {
		if($this->request->isPost()) {
			$user = $this->em->getRepository( 'S7D\Vendor\Auth\Entity\User' )->findOneBy([
				'username' => $this->request->get('user'),
			]);
			$password = $this->request->get('password');
			if ( $user && $password && password_verify($password, $user->getPassword())) {
				$this->session->set('auth', $user->getId());
			} else {
				$this->session->setFlash('Invalid email and/or password.');
			}
			Response::redirectBack();
		}
		return $this->render();
	}

	public function logout() {
		$this->session->remove('auth');
		Response::redirect('?login');
	}

	public function registration() {
		return $this->render([
			'captchaKey' => $this->parameters->get('captcha.siteKey')
		]);
	}

	public function verify() {

		$email = $this->request->get('email');
		$user = $this->em->getRepository( 'S7D\Vendor\Auth\Entity\User' )->findOneBy(['email' => $email]);
		if($user) {
			$this->session->setFlash(sprintf('Registration failed, email %s already taken.', $email));
			Response::redirectBack();
		}
		$client = new Client();
		$captcha = $client->request('POST', $this->parameters->get('captcha.url'), [
			'form_params' => [
				'secret' => $this->parameters->get('captcha.secret'),
				'response' => $this->request->get('g-recaptcha-response'),
			]
		]);
		$response = $captcha->getBody()->getContents();

		$response = json_decode($response);

		if($response->success) {
			$app = $this->parameters->get('app');
			$token = md5(uniqid());
			$url = $this->parameters->get('url') . '?confirm/' . $token;
			mail(
				$email,
				$app . ' activation',
				sprintf('To activate your account on %s follow this <a href="%s">link</a>.', $app, $url),
				'Content-type: text/html'
			);
			$this->insertUser($email, $this->request->get('password'), 'USER', [], 0, $token);
			return $this->render();
		}
		$this->session->setFlash('Something went wrong.');
		Response::redirectBack();
	}

	public function confirm($token) {
		$user = $this->em->getRepository( 'S7D\Vendor\Auth\Entity\User' )->findOneBy(['token' => $token]);
		if($user) {
			$user->setToken(null);
			$user->setStatus(1);
			$this->em->persist($user);
			$this->em->flush();
			$this->session->setFlash('Registration success.');
		} else {
			$this->session->setFlash('Invalid token.');
		}
		Response::redirect('?login');
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
}