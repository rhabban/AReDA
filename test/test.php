<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>X-editable PHP backend sample</title>
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.3/jquery-ui.js"></script>

  <script src="http://netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">
  <link rel="http://vitalets.github.io/x-editable/assets/x-editable/bootstrap3-editable/css/bootstrap-editable.css">
  <script src="http://vitalets.github.io/x-editable/assets/x-editable/bootstrap3-editable/js/bootstrap-editable.js"></script>
    </head>
    <body style="padding: 100px 100px">
         
      <?php
      /*
      Let's assume we have loaded record from database 
      */
      $user = array(
      'user_id' => 1,
      'user_name' => 'Awesome',
      'group_id' => 3
      );
      
      /*
      Render user_name and group as editable links
      */
      echo '<div id="user">';
      echo 'Username: <a href="#" id="user_name" data-type="text" data-pk="'.$user['user_id'].'" title="Enter username">'.$user['user_name'].'</a><br>';
      echo '</div>';
      ?>
                         
     <?
      /*
       In javascript apply $().editable() to both links on DOM ready
      */
     ?>
     <script>   
       $(function(){
          $('#user a').editable({
             url: 'post.php' 
          });
       });
     </script>
        
    </body>
</html>