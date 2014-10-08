<div id="content">
<? if($query->num_rows() > 0)
		{ ?>
            <? foreach($query->result() as $row): ?>
                <div id="img_<?=$row->id?>" style="text-align:center; margin-right: 2px; width:405px; height:220px; float:left; margin-right: 10px;">
                <a href="<?=base_url()."uploads/promocoes/".$row->arquivo?>" rel="imagebox" title="Promo&ccedil;&atilde;o"><img src="<?=base_url()."uploads/promocoes/thumbs/".$row->arquivo?>" ></a></div>		
            <? endforeach; ?>
     <? } else {  ?>
     Desculpe, mas no momento n&atilde;o existem promo&ccedil;&otilde;es em vigor, em breve traremos novidades.
     <? }?>
</div>
