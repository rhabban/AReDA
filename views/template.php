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
  				$("#editionMode").hide();
  			} else {
  				$(this).addClass("active");
  				$(".value_item").editable('enable');
  				$("#editionMode").show();
  			}
  		});

  		$("#save").click(function(){
  			res=$(".value_item").editable('getValue');
  			foreach(res)
  		});

  		$('[data-toggle="tooltip"]').tooltip();
  		$("#editionMode").hide();
  		$(function(){
          $('.value_item a').editable({
             url: 'post.php' 
          });
       });
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
