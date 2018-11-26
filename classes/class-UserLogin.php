<?php

class UserLogin
{

	public $loggedIn;
	public $userData;
	public $loginError;

	public function checkUserLogin ()
	{

		if (isset($_SESSION['userData']) && !empty($_SESSION['userData']) && is_array($_SESSION['userData']) && !isset($_POST['userData'])) {

			$userData = $_SESSION['userData'];

			$userData['post'] = false;

		}

		if (isset($_POST['userData']) && empty($_SESSION['userData']) && is_array($_POST['userData'])) {

			$userData = $_POST['userData'];

			$userData['post'] = true;

		}

		if (!isset($userData) || !is_array($userData)) {

			$this->logout();

			return;

		}
		
		if ($userData['post'] === true) {

			$post = true;

		} else {

			$post = false;

		}

		unset($userData['post']);

		if (empty($userData)) {
			
			$this->loggedIn = false;
			$this->loginError = null;

			$this->logout();

			return;

		}

		extract($userData);
		
		if (!isset($email) || !isset($password)) {

			$this->loggedIn = false;
			$this->loginError = null;

			$this->logout();

			return;

		}

		$query = $this->db->query('SELECT * FROM pessoa WHERE email = ? LIMIT 1', array($email));

		if (!$query) {

			$this->loggedIn = false;
			$this->loginError = 'Erro Interno.';

			$this->logout();

			return;

		}

		$fetch = $query->fetch(PDO::FETCH_ASSOC);

		$userId = (int)$fetch['id'];
		
		if (empty($userId)) {

			$this->loggedIn = false;
			$this->loginError = 'Usuário não existe.';

			$this->logout();

			return;

		}

		if ($this->phpass->CheckPassword($password, $fetch['senha'])) {

			if (session_id() != $fetch['session_id'] && !$post) {

				$this->loggedIn = false;
				$this->loginError = 'ID da sessão errada.';

				$this->logout();

				return;

			}

			if ($post) {

				session_regenerate_id();
				$sessionId = session_id();

				$_SESSION['userData'] = $fetch;
				$_SESSION['userData']['password'] = $password;
				$_SESSION['userData']['session_id'] = $sessionId;

				$query = $this->db->query('UPDATE pessoa SET session_id = ? WHERE id = ?', array($sessionId, $userId));

			}

			//$_SESSION['userData']['userPermissions'] = unserialize($fetch['userPermissions']);

			$this->loggedIn = true;
			$this->userData = $_SESSION['userData'];

			if (isset($_SESSION['gotoUrl'])) {

				$gotoUrl = urldecode($_SESSION['gotoUrl']);

				unset($_SESSION['gotoUrl']);

				//REDIRECT PAGE
				echo '<meta http-equiv="Refresh" content="0; url='.$gotoUrl.'">';
				echo '<script type="text/javascript">window.location.href="'.$gotoUrl.'";</script>';

			} else {
				
				//REDIRECT PAGE
				echo '<meta http-equiv="Refresh" content="0; url='.HOME_SYS.'/dashboard">';
				echo '<script type="text/javascript">window.location.href="'.HOME_SYS.'/dashboard";</script>';

			}

			return;

		} else {

			$this->loggedIn = false;

			$this->loginError = 'Senha errada. Favor verifique.';

			$this->logout();

			return;

		}

	}

	protected function logout ($redirect = false)
	{

		$_SESSION['userData'] = Array();

		unset($_SESSION['userData']);

		session_regenerate_id();

		if ($redirect === true) {

			$this->gotoLogin();

		}

	}

	protected function gotoLogin ()
	{
		
		if (defined('HOME_SYS')) {

			$loginUri = HOME_SYS.'/login/';

			$_SESSION['gotoUrl'] = urlencode($_SERVER['REQUEST_URI']);

			echo '<meta http-equiv="Refresh" content="; url='.$loginUri.'">';
			echo '<script type="text/javascript">window.location.href = "' . $loginUri . '";</script>';

		}

		return;

	}

	final protected function gotoPage ($pageUi = null)
	{

		if (isset($_GET['url']) && !empty($_GET['url']) && !$pageUri) {

			$pageUri = urldecode($_GET['url']);

		}

		if ($pageUri) {

			echo '<meta http-equiv="Refresh" content="0; url=' . $pageUri . '">';
			echo '<script type="text/javascript">window.location.href = "' . $pageUri . '";</script>';
			
			return;

		}

	}

	final protected function checkPermissions ($required = 'any', $userPermissions = array('any'))
	{

		if (!is_array($userPermissions)) {
		
			return;
		
		}

		if (!in_array($required, $userPermissions)) {
			
			return false;
			
		} else {
			
			return true;
			
		}

	}

}