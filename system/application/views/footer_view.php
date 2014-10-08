</div>
<? if($index == false) { ?>
<MAP NAME="mapfooter">
  <AREA
   HREF="<?=site_url()?>/servicos/residuos" ALT="Resíduos Sólidos" TITLE="Institucional"
   SHAPE=RECT COORDS="0,0,150,27">
  <AREA
   HREF="<?=site_url()?>/servicos/agua" ALT="Água" TITLE="Água"
   SHAPE=RECT COORDS="160,0,300,27">
  <AREA
   HREF="<?=site_url()?>/servicos/ecotoxicidade" ALT="Ecotoxicidade" TITLE="Ecotoxicidade"
   SHAPE=RECT COORDS="310,0,450,27">
  <AREA
   HREF="<?=site_url()?>/servicos/solo" ALT="Solo" TITLE="Solo"
   SHAPE=RECT COORDS="460,0,600,27">
  <AREA
   HREF="<?=site_url()?>/servicos/ar" ALT="Ar" TITLE="Ar"
   SHAPE=RECT COORDS="630,0,770,27">
  <AREA
   HREF="<?=site_url()?>/servicos/coleta" ALT="Coleta" TITLE="Coleta"
   SHAPE=RECT COORDS="780,0,940,27">

</MAP>
<div id="footer_boxes"><img src="<?=base_url()?>/images/template/botoes_rodape.jpg" usemap="#mapfooter"/></div>
<? } ?>
<div id="a4_boxfooter">
  <div>Rua Pouso&nbsp;Alegre, 458 - Floresta - Belo Horizonte/MG - CEP 31 110-010 - Tel.: (31) 3423-0188</div>
  <div></div>
</div>
</div>
<div id="debuginfo"></div>
</body>
</html>