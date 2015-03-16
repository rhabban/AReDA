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
  <script type="text/javascript">
    $(function() {
      var itemList;
      var categoryName;
      var id_item_List = [];
      var tmp_id_item = 0;
      $("#addItem").click(function(){
        alert('hey');
        tmp_id_item++;
        $(this).parent().before("<li class='form-inline'><div class='form-group'><label>Nom de l'élément</label><input type='text' name='name_item_" + tmp_id_item +"' class='form-control col-xs-3 name_item' placeholder='Nom de l&apos;élément'></div><div class='form-group'><label>Valeur de l'élément</label><input type='text' name='value_item_" + id_item +"' class='form-control value_item' placeholder='Valeur de l&apos;élément'></div></li>");
      });

      $(".editName_category").click(function(){
        var input_categoryName = "<input type='text' class='form-control' name='category' value='" +$(this).parent().children('span').html()+"'>";
        var that = $(this).parent().children('span').html(input_categoryName);
        $(that).children('input').focus();
        $(this).hide();
        var that = this;
        $(this).parent().children('span').children('input').blur(function(){
          var category_id = $(that).attr('id');
          var posting = $.ajax({
            type: "POST",
            url: $(that).attr('data-url'),
            data: "category=" + $(this).val() + "_" + category_id,
            dataType: 'json',
            success: function (data){
              $(that).parent().children('span').html(data.name_category);
              $(that).show();
              $(that).parent().parent().append('<div class="alert alert-success" role="alert">Le nom de la catégorie à été modifié</div>');
              setTimeout(function(){
                $('.alert').remove();
              }, 2000);
            }
          });
        });
      });

    $(".editName_item").click(function(){
      var input_itemName = "<input type='text' class='form-control' style='width:auto' name='name_id_item' value='" +$(this).parent().parent().children('label').text()+ "'>";
      var that = $(this).parent().parent();
      $(this).parent().hide();
      $(that).children('label').html(input_itemName);
      $(that).children('label').children('input').focus();
      $(that).children('label').children('input').blur(function(){
        var id_item = $(that).children('label').attr('for');
        var posting = $.ajax({
          type: "POST",
          url: "?action=formulaire&p=saveEdition",
          data: "item=" + $(this).val() + "_" + id_item,
          dataType: 'json',
          success: function (data){
            var newValue = $(that).children('label').children('input').val();
            $(that).children('label').children('input').remove();
            $(that).children().show();
            $(that).children('label').html(newValue);
            $(that).parent().parent().append('<div class="alert alert-success" role="alert">Le nom de la catégorie à été modifié</div>');
            setTimeout(function(){
              $('.alert').remove();
            }, 2000);
          }
        });
      });
    });
        

    $('.editModal').on('shown.bs.modal', function (e) {
      itemList= [];
      var toFind = $('input');
      $(this).find(toFind).each(function(){
        itemList.push($(this).val());
      });
    });

    $('.editModal').on('hide.bs.modal', function (e) {
      var i = 0;
      var toFind = $('input');
      $(this).find(toFind).each(function(){
        $(this).val(itemList[i]);
        i++;
      });
      for (var index= 0; index<id_item_List.length ; index++){
        $('.id_item_'+id_item_List[index]).remove();        
      }
      id_item_List = [];
    });

    $('.delete_item').click(function(){
      var item = $(this).parent().children('div').children('label');
      if (confirm("Voulez vous supprimer l'item " +item.html()+" ?")){
        var id_item = $(this).parent().find('input').attr('name');
        id_item = id_item.split('_')
        $(this).parent().parent().append('<div class="alert alert-success" role="alert">L\'item ' +item.html()+ ' a bien été supprimé !</div>');
        var that = $(this).parent()
        var posting = $.ajax({
          type: "POST",
          url: "?action=formulaire&p=deleteItem",
          data: "item=" + id_item[3],
          dataType: 'html',
          success: function (data){
            that.remove();
            id_item_List.push(id_item[3]);
            setTimeout(function(){
              $('.alert').remove();
            }, 2000);
          }
        });
      }
    });

    $('.newItem').click(function(){
      
    });


    $('[data-toggle="tooltip"]').tooltip();
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
