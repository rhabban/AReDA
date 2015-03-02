<!DOCTYPE html PUBLIC
"-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <link rel="stylesheet" type="text/css" href="skin/minimalsite.css" />
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.3/jquery-ui.js"></script>

  <script src="http://netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">
  <link rel="http://vitalets.github.io/x-editable/assets/x-editable/bootstrap3-editable/css/bootstrap-editable.css">
  <script src="http://vitalets.github.io/x-editable/assets/x-editable/bootstrap3-editable/js/bootstrap-editable.js"></script>
  <script type="text/javascript">
  	$(function() {
  		$("#edit").click(function(){
  			//Edition Mode
  			if ($(this).hasClass("active") ){
  				$(this).removeClass("active");
  				$(".value_item").editable('disable');
  			} else {
  				$(this).addClass("active");
  				$(".value_item").editable('enable');
  			}
  		});

      $("#addItem").click(function(){
        id_item++;
        $(this).parent().before("<li class='form-inline'><div class='form-group'><label>Nom de l'élément</label><input type='text' name='name_item_" + id_item +"' class='form-control col-xs-3 name_item' placeholder='Nom de l&apos;élément'></div><div class='form-group'><label>Valeur de l'élément</label><input type='text' name='value_item_" + id_item +"' class='form-control value_item' placeholder='Valeur de l&apos;élément'></div></li>");
      });

  		$("#save").click(function(){
        console.log($('.value_item.editable-unsaved').editable('submit'));
      });

  		$('[data-toggle="tooltip"]').tooltip();
  		$(function(){
        $('.value_item a').editable({
        });
      });

      var id_item = 0;
      $.fn.editable.defaults.mode = 'inline';
 	});
  </script>	
  <title>Site web minimal</title>
</head>

<body>
	<div class="header">
		<?php echo $this->makeMenu(); ?>
	</div>
	<div class="container">
		<?php //echo $this->makeBreadcrumbs(); ?>
		<div class="content">
			<?php echo $content; ?>
		</div>

		<div class="feedback">
			<?php echo $feedback; ?>
		</div>

		<div class="logBox">
			<?php echo $logBox; ?>
		</div>
	</div>

</body>

</html>
