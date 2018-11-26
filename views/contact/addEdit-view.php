
<?php
    if (defined('BASEDIR')) {
        exit;
    }
    
    require BASEDIR.'/views/layout/header.php';
    require BASEDIR.'/views/layout/menu.php';
?>
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Contatos</h1>                    
        </div>
    </div>

    <div class="row">
        <form class="form-contact" method="POST" id="form-contact">
            <input type="hidden" name="idPeople" >
            <div class="col-lg-5">
                <div class="form-group">
                    <label>Nome:</label>
                    <input type="text" class="form-control" name="name" />
                </div>
            </div>

            <div class="col-lg-12">
                <div class="form-group">
                    <a class="btn btn-success btn-phone" onclick="insertPhone()">Inserir Telefone</a>
                    <table class="table table-striped table-bordered" id="boxModel" style="display: none">
                        <thead>    
                            <tr>
                                <th>Tipo Contato</th>
                                <th>Número Contato</th>
                                <th>Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <input type="hidden" name="insertPhonePeople_idComponent[X]" value="<??>">
                                <td>
                                    <select name="insertPhonePeople_typePhone[X]" class="form-control">
                                        <option value='casa'>Casa</option>
                                        <option value='celular'>Celular</option>
                                        <option value='recado'>Recado</option>
                                        <option value='servico'>Serviço</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="tel" name="insertPhonePeople_numberPhone[X]" placeholder="(99) 9999-9999" class="form-control phone_number">
                                </td>
                                <td>
                                    <a class="btn btn-danger btn-remove" onclick="removePhone(this)">(x) Remover</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="table table-striped table-bordered" id="boxView">
                        <thead>    
                            <tr>
                                <th>Tipo Contato</th>
                                <th>Número Contato</th>
                                <th>Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $count = 0;
                            if(isset($infoPhone)) : foreach ($infoPhone as $infoPhone):?>
                                <tr>
                                    <input type="hidden" name="insertPhonePeople_idComponent[<?=$count?>]" value="<?=$infoPhone->id_phone?>">
                                    <td>
                                        <select name="insertPhonePeople_typePhone[<?=$count?>]" class="form-control">
                                            <option value='casa' <?=($infoPhone->type_phone == 'casa') ? 'selected' : ''?> >Casa</option>
                                            <option value='celular' <?=($infoPhone->type_phone == 'celular') ? 'selected' : ''?>>Celular</option>
                                            <option value='recado' <?=($infoPhone->type_phone == 'recado') ? 'selected' : ''?> >Recado</option>
                                            <option value='servico' <?=$infoPhone->type_phone == 'servico' ? 'selected' : ''?>>Serviço</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="tel" name="insertPhonePeople_numberPhone[<?=$count?>]" placeholder="(99) 9999-9999" class="form-control phone_number" value="<?=$infoPhone->phone?>">
                                    </td>
                                    <td>
                                        <a class="btn btn-danger btn-remove" onclick="removePhone(this)">(x) Remover</a>
                                    </td>
                                </tr>
                            <?php
                            $count++;
                            endforeach; endif;?>
                        </tbody>
                    </table>

                </div>
            </div>
        
            <div class="col-lg-6 col-xs-6">
                <a href="<?php HOME_SYS?>" name="back" class="btn btn-warning"><i class="fa fa-chevron-left fa-fw"></i> Voltar</a>
            </div>

            <div class="col-lg-6 text-right col-xs-6">
                <button type="submit" name="send-contact" value="0" class="btn btn-success"><i class="fa fa-save fa-fw"></i> Cadastrar</button>
            </div>

        </form>
    </div>
</div>

<script>
    var idenRegistro = '<?php $count?>';
    function insertPhone()
    {
        var clone = $("#boxModel tbody").html();
        clone= clone.replace(/\[X\]/g, "["+idenRegistro+"]");
        clone= clone.replace(/__X/g, "__"+idenRegistro);

        $("#boxView tbody").append(clone);
        
        idenRegistro++;
    };

    function removePhone(o)
    {
        $(o.parentNode.parentNode).remove();
    };

    $(document).ready(function(){

        $(function(){
            var phone, element;  
            
            element = $('.phone_number');  
            element.unmask();  
            phone = element.val().replace(/\D/g, '');

            if(phone.length > 10) {  
                element.mask("(99) 99999-9999");  
            } else {  
                element.mask("(99) 9999-99999");  
            }
        }).trigger('focusout');


        $("#form-contact").validate({
            //DEFINING THE FORM VALIDATION RULES
            rules:{
                name:{required: true, minlength: 5},
            }
        });

    });
</script>