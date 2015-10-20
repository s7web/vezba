<?php
namespace S7D\Core\Auth\Controller;

use GuzzleHttp\Client;
use S7D\Core\Auth\Entity\User;
use S7D\Core\Routing\Controller;

class UserController extends Controller {

	private function getUserRepo() {
		return $this->em->getRepository( 'S7D\Core\Auth\Entity\User' );
	}

	public function login() {
		if($this->request->isPost()) {
			$user = $this->getUserRepo()->findOneBy([
				'username' => $this->request->get('user'),
			]);
			$password = $this->request->get('password');
			if ( $user && $password && password_verify($password, $user->getPassword())) {
				$this->session->set('auth', $user->getId());
			} else {
				$this->session->setFlash('Invalid email and/or password.');
			}
			return $this->redirectBack();
		}
		return $this->render();
	}

	public function logout() {
		$this->session->remove('auth');
		return $this->redirectRoute('logout');
	}

	public function registration() {
		return $this->render([
			'captchaKey' => $this->parameters->get('captcha.siteKey')
		]);
	}

	public function verify() {

		$email = $this->request->get('email');
		$user = $this->getUserRepo()->findOneBy(['email' => $email]);
		if($user) {
			$this->session->setFlash(sprintf('Registration failed, email %s already taken.', $email));
			return $this->redirectBack();
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
			$url = $this->generateUrl('confirm', $token);
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
		return $this->redirectBack();
	}

	public function confirm($token) {
		$user = $this->getUserRepo()->findOneBy(['token' => $token]);
		if($user) {
			$user->setToken(null);
			$user->setStatus(1);
			$this->em->persist($user);
			$this->em->flush();
			$this->session->setFlash('Registration success.');
			if($vr = $this->parameters->get('verifyRedirect')) {
				$this->session->setAuth($user->getId());
				return $this->redirectRoute($vr, $user->getId());
			}
		} else {
			$this->session->setFlash('Invalid token.');
		}
		return $this->redirectRoute('logout');
	}

	private function insertUser($email, $password, $role, $meta = [], $status = 0, $token = null) {
		$userClass = 'S7D\App\\' . $this->parameters->get('app')  . '\Entity\ExtendedUser';
		$user = new $userClass;
		$user->setEmail($email);
		$user->setUsername($email);
		$password = password_hash($password, PASSWORD_DEFAULT);
		$user->setPassword($password);
		$user->setRoles([$role]);
		$user->setStatus($status);
		$user->setToken($token);
		$group = $this->getUserRepo()->createQueryBuilder('u');
		$group->select('MAX(u.user_group)');
		$newGroup = $group->getQuery()->getSingleScalarResult() + 1;
		$user->setUserGroup($newGroup);

		$user->setMeta($meta);
		$this->em->persist($user);
		$this->em->flush();

		return $user->getId();
	}
}