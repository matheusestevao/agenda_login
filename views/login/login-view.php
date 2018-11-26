<?php 
    if (!defined('BASEDIR')){
        exit;
    } 
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="agenda, contatos, agenda telefonica, telefonica">
        <meta name="author" content="Matheus Estevao / matheus@skinnysoft.com.br">

        <title>Login Agenda</title>

        <!-- Bootstrap core CSS -->
        <link href="<?php echo HOME_SYS?>/bootstrap/css/bootstrap.min.css" rel="stylesheet">

        <!--FONT AWESOME-->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">

        <!-- Custom styles for this template -->
        <link href="<?php echo HOME_SYS?>/dist/css/login.css" rel="stylesheet">
    </head>

    <body class="text-center">
        <?php
            if ($this->formMsg != '') {?>
                <div class="alert alert-success alert-dismissable">
                    <strong><?php echo $this->formMsg?></strong>
                    <?php echo $this->formMsg = ''?>                      
                </div>
            <?php
            }?>
        <form class="form-signin" method="POST">
            <img class="mb-4" src="<?php echo HOME_SYS?>/img/phone-square-solid.svg" alt="" width="72" height="72">
            <h1 class="h3 mb-3 font-weight-normal"></h1>
            
            <label for="email" class="sr-only">E-mail</label>
            <input type="email" id="email" class="form-control" placeholder="E-mail" required autofocus name="userData[email]">
           
            <label for="password" class="sr-only">Senha</label>
            <input type="password" id="password" class="form-control" placeholder="Senha" required name="userData[password]">
            <?php
                if ($this->loginError) {
                    echo $this->loginError;
                }
            ?>
            <div class="checkbox mb-3">
                <label>
                    <a href="<?php echo HOME_SYS?>/new-user"> Cadastrar-se</a>
                </label>
            </div>
            <button class="btn btn-lg btn-primary btn-block mt-3" type="submit" value="Enter">Entrar</button>
        </form>
    </body>
</html>