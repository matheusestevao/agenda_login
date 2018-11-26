<?php
	
class NewUserModel extends MainModel
{

	public $formData;
	public $formMsg;
	public $db;
	
	function __construct($db= false)
	{
		
		$this->db = $db;

	}

	public function validateRegisterForm ()
	{	

		$this->formData = array();

		if ('POST' == $_SERVER['REQUEST_METHOD'] && !empty($_POST)) {

			foreach ($_POST as $key => $value) {
				
				$this->formData[$key] = $value;

				if (empty($value)) {

					$this->formMsg = '<p class="formError">Preencha todos os campos.</p>';

					return;

				}

			}

		} else {

			return;

		}

		if (empty($this->formData)) {

			return;

		}

		$dbCheckUser = $this->db->query('SELECT * FROM pessoa WHERE email = ?', array(checkArray($this->formData, 'email')));

		if (!$dbCheckUser) {

			$this->formMsg = '<p class="formError">Erro Interno</p>';
			return;

		}

		$fetchUser = $dbCheckUser->fetch();

		$userId = $fetchUser['id'];

		$passwordHash = new PasswordHash(8, FALSE);
		$password = $passwordHash->HashPassword($this->formData['userPassword']);

		if (preg_match( '/[^0-9A-Za-z,.-_s ]/is', $this->formData['userPermissions'])) {

			$this->formMsg = '<p class="form_error">Use apenas letras, números e uma vírgula para permissões.</p>';
			return;

		}

		$permissions = array_map('trim', explode(',', $this->formData['userPermissions']));
		$permissions = array_unique($permissions);
		$permissions = array_filter($permissions);
		$permissions = serialize($permissions);
		
		if (!empty($userId)) {

			$query = $this->db->update('pessoa', 'id', $userId, array(
				'senha' => $password, 
				'nome' => checkArray($this->formData, 'nome'), 
				'session_id' => md5(time()), 
				'permissoes' => $permissions, 
			));
			
			
			if (!$query) {

				$this->formMsg = '<p class="form_error">Erro ao enviar as informações.</p>';
				
				return;

			} else {

				$this->formMsg = '<p class="form_success">Usuário Atualizado com Sucesso.</p>';
				
				return;

			}
		
		} else {
		
			$query = $this->db->insert('pessoa', array(
				'email' => checkArray( $this->formData, 'email'), 
				'senha' => $password, 
				'nome' => checkArray( $this->formData, 'nome'), 
				'session_id' => md5(time()), 
				'permissions' => $permissions, 
			));
			
			
			if (!$query) {

				$this->formMsg = '<p class="form_error">Erro ao enviar as informações.</p>';
				
				return;

			} else {

				$this->form_msg = '<p class="form_success">Uuário Registrado com Sucesso.</p>';
				
				
				return;

			}
		}
	}

	public function getRegisterForm ($userId = false)
	{
	
		$sUserId = false;
		
		if (!empty($userId) ) {

			$sUserId = $userId;

		}
		
		if (empty($sUserId)) {

			return;

		}
		
		$query = $this->db->query('SELECT * FROM `pessoa` WHERE `id` = ?', array( $sUserId ));
		
		if (!$query) {

			$this->formMsg = '<p class="form_error">Usuário não existe.</p>';
			
			return;

		}
		
		$fetchUserdata = $query->fetch();
		
		if (empty($fetchUserdata)) {
			$this->formMsg = '<p class="form_error">User do not exists.</p>';
			return;
		}
		
		foreach ($fetchUserdata as $key => $value ) {

			$this->formData[$key] = $value;

		}
		
		$this->formData['userPassword'] = null;
		
		$this->formData['userPermissions'] = unserialize($this->formData['userPermissions']);
		
		$this->formData['userPermissions'] = implode(',', $this->formData['userPermissions']);
	}
	
	public function delUser ($parameters = array())
	{

		$userId = null;
		
		if (checkArray($parameters, 0) == 'del' ) {

			// CONFIRM DEL
			echo '<p class="alert">Tem certeza que deseja apagar este usuário?</p>';
			echo '<p><a href="' . $_SERVER['REQUEST_URI'] . '/confirma">Sim</a> | 
			<a href="' . HOME_SYS . '/user-list">Não</a> </p>';
			
			if (is_numeric(checkArray($parameters, 1)) && checkArray($parameters, 2) == 'confirma' ) {
				
				$userId = checkArray($parameters, 1);

			}

		}
		
		if (!empty($userId)) {
		
			$userId = $userId;
			
			// USER DEL
			$query = $this->db->delete('pessoa', 'id', $userId);
			
			echo '<meta http-equiv="Refresh" content="0; url=' . HOME_SYS . '/user-list/">';
			echo '<script type="text/javascript">window.location.href = "' . HOME_SYS . '/user-list/";</script>';
			return;
		}
	}	
	
	public function getUserList()
	{
	
		$query = $this->db->query('SELECT * FROM `pessoa` ORDER BY id DESC');
		
		if (!$query) {

			return array();

		}

		
		return $query->fetchAll();

	}

	public function estadosModelUser ()
	{

		$query = $this->db->query('SELECT * FROM `estado` ORDER BY nome ASC');

		return $query->fetchAll();

	}

}