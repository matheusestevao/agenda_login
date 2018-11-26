<?php
	if (!defined('BASEDIR')) {
		exit;
	}

	/*loading model*/
	$model->validateRegisterForm();
	$model->getRegisterForm(checkArray($parameters, 1 ));
	$model->delUser($parameters);
	$states = $model->estadosModelUser();

	require BASEDIR.'/views/layout/header.php';
	require BASEDIR.'/views/layout/menu-login.php';

?>
			<div class="row">
				<div class="col-md-12">
					<div class="card-header text-center">
						<h5>Novo Usuário</h5>
					</div>
					<?php
					if ($this->formMsg != '') {?>
					
						<div class="alert alert-danger alert-dismissable">
		        			<strong><?php echo $this->formMsg?></strong>
		        			<?php echo $this->formMsg = ''?>                      
		        		</div>
					<?php
					}?>
					
					<form role="form" method="post" id="cad_usuario" action="<?php echo HOME_SYS?>/new-user/save">
						<div class="row">
							<div class="col-md-6 col-lg-6 col-sm-6">
								<div class="form-group">
									<label for="name">Nome:</label>
									<input type="text" class="form-control" name="name" autofocus required>
								</div>
							</div>

							<div class="col-md-6 col-lg-6 col-sm-6">
								<div class="form-group">
									<label for="email">E-mail:</label>
									<input type="email" class="form-control email" name="email" id="email" required>                                
								</div>
								<input type="hidden" name="perfil" value="2">
							</div>

							<div class="col-md-3">
								<div class="form-group">
									<label>CEP:</label>
									<input type="text" name="zipCode" class="form-control zipCode" id="zipCode">
								</div>
							</div>

							<div class="col-md-6">
								<div class="form-group">
									<label>Logradouro:</label>
									<input type="text" name="address" class="form-control address" id="address">
								</div>
							</div>

							<div class="col-md-1">
								<div class="form-group">
									<label>Número:</label>
									<input type="text" name="number" class="form-control number" id="number">
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label>Complemento:</label>
									<input type="text" name="complement" class="form-control complement" id="complement">
								</div>
							</div>

							<div class="col-md-4">
								<div class="form-group">
									<label>Bairro:</label>
									<input type="text" name="neighborhood" class="form-control neighborhood" id="neighborhood">
								</div>
							</div>

								<div class="col-md-4">
									<div class="form-group div-state">
										<label>Estado:</label>
										<select class="form-control state" id="state" name="state" required>
											<option value="">Selecione...</option>
											<?php
												foreach ($states as $state) {?>
													<option value="<?php echo $state['id']?>"><?php echo $state['nome']?></option>
											<?php
												}
											?>
										</select>
									</div>
								</div>

								<div class="col-md-4 label-city">
									<input type="hidden" id="cityCep" value="0">
									<div class="form-group div-city" >
										<label>Cidade:</label>
										<select class="form-control city" id="city" name="city" required>
											<option value="">Selecione...</option>
										</select>
									</div>
								</div>

							<div class="col-md-4">
								<div class="form-group">
									<label for=>Senha:</label>
									<input type="password" class="form-control" name="password" id="password" required>      
								</div>
							</div>

							<div class="col-md-12 text-center col-xs-12">
								<button type="submit" name="envia_usuario" class="btn btn-success"><i class="fa fa-save fa-fw"></i> Registrar-se</button>
							</div>
						</div>
					</form>
				</div>
			</div>
			<!-- /.row -->


			<script>
				
				$(document).ready(function () {

					$("#zipCode").change(function () {

						$.ajax({
							url: '<?php echo HOME_SYS?>/new-user/ajaxZipCodeAutocomplete/'+$(this).val(),
							dataType: 'json',
							success: function (infAddress) {

								if (infAddress.success === 1) {

									$("#cityCep").val(infAddress.city);
									$("#address").val(infAddress.address);
									$("#neighborhood").val(infAddress.neighborhood);
									$("#state").val(infAddress.state).trigger('change');	

									$("#number").focus();

								}

							}
						})

					});

					$('#email').blur(function () {

						if ($(this).val() != '') {

							$.ajax({
								url: '<?php echo HOME_SYS?>/new-user/queryCheckEmail/'+$(this).val(),
								success: function (check) {
									console.log(check);
									if (check) {

										$("#email").val('');
										alert('E-mail informado já esta sendo usado no sistema. Favor, informe outro e-mail');
										$("#email").focus();

									}

								}
								
							});

						}

					});

					$("#state").change(function () {

						let cityCep = $('#cityCep').val();

						if ($(this).val() != '') {

							$.ajax({
								url: '<?php echo HOME_SYS?>/new-user/ajaxCityState/'+$(this).val()+'/'+cityCep,
								
								success: function (data) {

									$(".div-city").html('')
									$(".div-city").append(data);

								}
							});

						}	

					}).trigger('change');


				})

			</script>
			