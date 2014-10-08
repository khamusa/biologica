<? 
function showOption($privOption, $userpriv, $privnames, $selected) {
	if($userpriv > $privOption) {
		
		$title = $privnames[$privOption];
		if($selected == $privOption)
		echo('<option value='.$privOption.' selected="selected">'.$title.'</option>');
		else
		echo('<option value='.$privOption.'>'.$title.'</option>');
		
	}
}

$end = json_decode($row->dados_endereco);
$contatos = json_decode($row->dados_contatos);
?>
<div id="usereditform">
<?=form_open('user/edit',array('id' => 'form_useredit'));?>
	
                                                                  		<label for="priv">Privil&eacute;gios: </label>
                               		<br />
                                    <select name="priv" class="logininput" id="priv">
                                    <? //showOption(1, $priv, 'Usu&aacute;rios Abertos'); ?>
                                    
                                    <? showOption(1, $priv, $privnames, $row->priv); ?>
                                    <? //showOption(2, $priv, '&nbsp;&nbsp;|- Redator'); ?>
                                    <? //showOption(3, $priv, '&nbsp;&nbsp;|- Editor'); ?>
                                    <? //showOption(50, $priv, 'Administra&ccedil;&atilde;o'); ?>
                                    <? showOption(50, $priv, $privnames, $row->priv); ?>
                                    <? showOption(60, $priv, $privnames, $row->priv); ?>
                                    <? //showOption(90, $priv, '&nbsp;&nbsp;|- Super Administrador'); ?>
                               		</select>              
                                     <br />                        
                                     
                                   
   
                                     <label for="dado_cnpj">CNPJ:</label>
                                     <br />
                                    <input name="dado_cnpj" type="text" class="logininput" id="dado_cnpj" size="30" value="<?=$row->dado_cnpj;?>"/>       
                                     <br />
                                      <label for="telefone">Telefone (s):</label>
                                     <br />
                                    <input name="dado_telefone" type="text" class="logininput" id="dado_telefone" value="<?=$row->dado_telefone;?>" size="30" />       
                                     <br />
                                    <label for="UF">UF: </label>&nbsp;
  							<select name="uf" class="logininput" id="uf">
                                    <option value="AL" <? if($end->{'uf'} == 'AL') echo("selected='selected'");?>>AL</option>
                                    <option value="BA" <? if($end->{'uf'} == 'BA') echo("selected='selected'");?>>BA</option>
                                    <option value="CE" <? if($end->{'uf'} == 'CE') echo("selected='selected'");?>>CE</option>
                                    <option value="DF" <? if($end->{'uf'} == 'DF') echo("selected='selected'");?>>DF</option>
                                    <option value="ES" <? if($end->{'uf'} == 'ES') echo("selected='selected'");?>>ES</option>
                                    <option value="GO" <? if($end->{'uf'} == 'GO') echo("selected='selected'");?>>GO</option>
                                    <option value="MG" <? if($end->{'uf'} == 'MG') echo("selected='selected'");?>>MG</option>
                                    <option value="MS" <? if($end->{'uf'} == 'MS') echo("selected='selected'");?>>MS</option>
                                    <option value="MT" <? if($end->{'uf'} == 'MT') echo("selected='selected'");?>>MT</option>
                                    <option value="PB" <? if($end->{'uf'} == 'PB') echo("selected='selected'");?>>PB</option>
                                    <option value="PE" <? if($end->{'uf'} == 'PE') echo("selected='selected'");?>>PE</option>
                                    <option value="PR" <? if($end->{'uf'} == 'PR') echo("selected='selected'");?>>PR</option>
                                    <option value="RJ" <? if($end->{'uf'} == 'RJ') echo("selected='selected'");?>>RJ</option>
                                    <option value="RN" <? if($end->{'uf'} == 'RN') echo("selected='selected'");?>>RN</option>
                                    <option value="SC" <? if($end->{'uf'} == 'SC') echo("selected='selected'");?>>SC</option>
                                    <option value="SE" <? if($end->{'uf'} == 'SE') echo("selected='selected'");?>>SE</option>
                                    <option value="SP" <? if($end->{'uf'} == 'SP') echo("selected='selected'");?>>SP</option>
                                    <option value="TO" <? if($end->{'uf'} == 'TO') echo("selected='selected'");?>>TO</option>    
                                    </select>
                                    <br /> 
                                 
                                    <label for="cidade">Cidade:</label>
                                     <br />
                                    <input name="cidade" type="text" class="logininput" id="cidade" size="30" value="<?=$end->{'cidade'};?>"/>       
                                    <Br />
                                     <label for="logradouro">Logradouro:</label><br />
                                    <input name="logradouro" type="text" class="logininput" id="logradouro" size="30" value="<?=$end->{'logradouro'};?>"/>       
                                      <br />
                                     <label for="numero">N&uacute;mero:</label><br />
                                    <input name="numero" type="text" class="logininput" id="numero" size="30" value="<?=$end->{'numero'};?>"/>       
                                      <br />
                                    <label for="complemento">Complemento:</label><br />
                                    <input name="complemento" type="text" class="logininput" id="complemento" size="30" value="<?=$end->{'complemento'};?>"/>       
                                      <br />
                                      <label for="bairro">Bairro:</label><br />
                                    <input name="bairro" type="text" class="logininput" id="bairro" size="30" value="<?=$end->{'bairro'};?>"/>       
                                      <br />
                                      <label for="CEP">CEP:</label><br />
                                    <input name="cep" type="text" class="logininput" id="cep" size="30" value="<?=$end->{'cep'};?>"/>       
                                      <br />                   
                                     <input type="hidden" name="uid" value="<?=$uid?>" />
									<input class="loginsubmit" type="submit" value="Atualizar" />
                                <?=form_close();?>

</div>