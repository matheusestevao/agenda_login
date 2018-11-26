<?php

class NewUserController extends MainController
{

	public $loginRequired = true;
	public $permissionRequired = 'userRegister';
	public $formMsg;

	public function index ()
	{

		$this->title = 'Novo Usuário';

		$parameters = (func_num_args() >= 1) ? func_get_arg(0) : array();

		$model = $this->loadModel('new-user-model');


		require BASEDIR.'/views/newUser/newUser.php';

	}

	public function save ()
	{

		$this->formData = $_POST;

		/*VALIDATION - START*/
		if (checkArray($this->formData, 'name') == '' && checkArray($this->formData, 'email') == '' && checkArray($this->formData, 'password') == '' && checkArray($this->formData, 'city') == '' && checkArray($this->formData, 'state') == '') {

			$this->formMsg = '<p>Os campos <b><i>Nome</i></b>, <b><i>E-mail</i></b>, <b><i>Senha</i></b>, <b><i>Cidade</i></b> e <b><i>Estado</i></b> é obrigatório.';
			$this->index();

			return;

		} else if (checkArray($this->formData, 'name') == '') {

			$this->formMsg = '<p>O campo <b><i>Nome</i></b> é obrigatório.';
			$this->index();

			return;

		} else if (checkArray($this->formData, 'email') == '') {

			$this->formMsg = '<p>O campo <b><i>E-mail</i></b> é obrigatório.';
			$this->index();

			return;

		} else if (checkArray($this->formData, 'password') == '') {

			$this->formMsg = '<p>O campo <b><i>Senha</i></b> é obrigatório.';
			$this->index();

			return;

		} else if (checkArray($this->formData, 'city') == '') {

			$this->formMsg = '<p>O campo <b><i>Cidade</i></b> é obrigatório.';
			$this->index();

			return;

		} else if (checkArray($this->formData, 'state') == '') {

			$this->formMsg = '<p>O campo <b><i>Estado</i></b> é obrigatório.';
			$this->index();

			return;

		}

		/*VERIFY USER DUPLICITY*/
		$checkEmail = $this->queryCheckEmail(checkArray($this->formData, 'email'));
		
		if ($checkEmail) {

			$this->formMsg = '<p>E-mail já está sendo utilizado.';
			$this->index();

			return;

		}

		/*VALIDATION - END*/

		$passwordHash = new PasswordHash(8, FALSE);
		$password = $passwordHash->HashPassword($this->formData['password']);

		$insertUser = $this->db->insert('pessoa', array(
			'nome'  	  => checkArray($this->formData, 'name'),
			'email' 	  => checkArray($this->formData, 'email'),
			'senha' 	  => $password,
			'cep'   	  => checkArray($this->formData, 'zipCode'),
			'logradouro'  => checkArray($this->formData, 'address'),
			'numero'   	  => checkArray($this->formData, 'number'),
			'complemento' => checkArray($this->formData, 'complement'),
			'bairro'      => checkArray($this->formData, 'neighborhood'),
			'cidade'      => checkArray($this->formData, 'city'),
			'estado'      => checkArray($this->formData, 'state'),
			'id_perfil'   => checkArray($this->formData, 'perfil'),
			'created_at'  => date('Y-m-d H:i:s'),
		));

		if ($insertUser) {

			$page = HOME_SYS.'/login';

			echo '<meta http-equiv="Refresh" content="0; url='.$page.'">';
			echo '<script type="text/javascript">window.location.href="'.$page.'";</script>';

			return;

		} else {

			$this->formMsg = '<p>Algo inexperado aconteceu. Favor, tente novamente.';


			$this->index();

		}

	}

	public function queryCheckEmail ($email)
	{

		if (!is_array($email)) {

			$email = array($email);

		}

		$queryCheck = $this->db->query('SELECT email FROM `pessoa` WHERE `email` = ?', $email);
		$emailCheck = $queryCheck->fetchAll();

		if (count($emailCheck) > 0){
			
			if ($emailCheck[0]['email'] === $email[0]) {

				return 1;

			} else {

				return 0;

			}

		}

	}

	public function ajaxZipCodeAutocomplete ($numCep)
	{

		$reg = simplexml_load_file("http://viacep.com.br/ws/".$numCep[0]."/xml");

		if (!isset($reg->erro)) {
            $dados['success']      = 1; // SUCCESS
            $dados['address']      = (string) $reg->logradouro;
            $dados['neighborhood'] = (string) $reg->bairro;
            $dados['city']         = (string) $reg->ibge;
            $dados['state']        = (string) substr($reg->ibge, 0,2);
            echo json_encode($dados);
            exit;
        } else {
            $dados['success'] = 0; // ERROR
            echo json_encode($dados);
            exit;
        }


	}

	public function ajaxCityState($parameters)
	{
		
		$idState = array($parameters[0]);
		$idCity  = $parameters[1];

		$query = $this->db->query('SELECT * FROM `cidade` WHERE `id_estado` = ? ORDER BY nome ASC', $idState);
		$citys = $query->fetchAll();

		ob_start();?>
		<label>Cidade:</label>
		<select class="form-control city" id="city" name="city" required>
			<option value="">Selecione...</option>
			<?php
				foreach ($citys as $city) {?>
					<option value="<?php echo $city['id']?>" <?php echo $idCity === $city['id'] ? 'selected' : '' ?> ><?php echo $city['nome']?></option>
			<?php
				}
			?>
		</select>

		<?php
		$selectCidades = ob_get_clean();

		echo $selectCidades;

	}

}