<?php
namespace S7D\Core\Auth\Controller;

use GuzzleHttp\Client;
use S7D\Core\Auth\Entity\Role;
use S7D\Core\Auth\Entity\User;
use S7D\Core\HTTP\Cookie;
use S7D\Core\Routing\Controller;

class UserController extends Controller {

	protected function getUserRepo() {
		return $this->em->getRepository( 'S7D\Core\Auth\Entity\User' );
	}

	public function login() {
		if($this->user) {
			return $this->redirectRoute($this->parameters->get('landing')[$this->user->getRoles()[0]]);
		}
		if($this->request->isPost()) {
			$user = $this->getUserRepo()->findOneBy([
				'username' => $this->request->get('user'),
			]);
			$password   = $this->request->get('password');
			$rememberMe = $this->request->get('remember_me');
			if ( $user && $password && password_verify($password, $user->getPassword())) {
				if($rememberMe){
					$this->session->set('auth', $user->getId());
					setcookie('authState', $user->getId(), time() + (86400 * 30), null, null, null, true);
				}else{
					$this->session->set('auth', $user->getId());
				}
			} else {
				$this->session->setFlash('Invalid email and/or password.');
				return $this->redirectBack();
			}
			return $this->redirectRoute($this->parameters->get('landing')[$user->getRoles()[0]]);
		}
		return $this->render();
	}

	public function logout() {
		$this->session->destroy();
		if(Cookie::getCookieByName('authState', false)){
			setcookie('authState', "", time()-3600);
			unset($_COOKIE['authState']);
		}
		return $this->redirectRoute('login');
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
			$this->session->setFlash(sprintf($this->translate('emailTaken'), $email));
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
			$token = md5(uniqid());
			$url = $this->generateUrl('confirm', $token);
			$message = $this->getMessage($token);
			$message->setTo($email);
			$this->mailer->send($message);
			$role = $this->em->getRepository('S7D\Core\Auth\Entity\Role')->findOneBy(['name' => 'USER']);
			/** @var \S7D\Core\Auth\Repository\UserRepository $userRepo */
			$userRepo = $this->em->getRepository('S7D\Core\Auth\Entity\User');
			$userRepo->insert($email, $this->request->get('password'), [$role], [], 0, $token);
			return $this->render(['email' => $email]);
		}
		$this->session->setFlash('Something went wrong.');
		return $this->redirectBack();
	}

	private function getMessage($token) {

		$app = $this->parameters->get('app');
		$url = $this->generateUrl('confirm', $token);
		return \Swift_Message::newInstance(sprintf($this->translate('emailConfirmSubject'), $app))
		  ->setFrom($this->parameters->get('email.emailExplicit'), $app)
		  ->setBody(sprintf($this->translate('emailConfirm'), $url))
		  ->setContentType('text/html');
	}

	public function resendToken($email) {

		$user = $this->getUserRepo()->findOneBy(['email' => $email]);
		if($user) {
			$message = $this->getMessage($user->getToken());
			$message->setTo($email);
			$this->mailer->send($message);
		}
		return $this->view('User/verify.html.twig', ['email' => $email]);
	}

	public function confirm($token) {
		$this->session->setAuth(null);
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
}