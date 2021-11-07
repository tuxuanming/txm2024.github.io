<?php 
require('inc/checklogin.php');
$Title="Sign Up";
?>
<!DOCTYPE html>
<html>
  <?php require('head.php'); ?>

  <body>
    <?php require('page_header.php'); ?>  
          
    <div class="container-fluid">
      <div class="row-fluid">
      <?php 
      if(isset($_SESSION['user'])){
        echo '<div style="text-align: center">Please sign out first.</div>';
      }else{
      ?>
        <div class="span8 offset3">
          <form class="form-horizontal" id="form_profile" action="#" method="post">
            <input type="hidden" value="reg" name="type">
            <fieldset>
              <div class="control-group" id="userid_ctl">
                <label class="control-label">User Name</label>
                <div class="controls">
                  <input class="input-xlarge" type="text" name="userid" id="input_userid">
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="input_nick">Nick Name</label>
                <div class="controls">
                  <input class="input-xlarge" type="text" name="nick" id="input_nick">
                </div>
              </div>
              <div class="control-group" id="newpwd_ctl">
                <label class="control-label" for="input_newpwd">Password</label>
                <div class="controls">
                  <input class="input-xlarge" type="password" id="input_newpwd" name="newpwd">
                </div>
              </div>
              <div class="control-group" id="reppwd_ctl">
                <label class="control-label" for="input_reppwd">Repeat Password</label>
                <div class="controls">
                  <input class="input-xlarge" type="password" id="input_reppwd">
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="input_email">E-Mail</label>
                <div class="controls">
                  <input class="input-xlarge" type="text" name="email" id="input_email">
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="input_school">School</label>
                <div class="controls">
                  <input class="input-xlarge" type="text" name="school" id="input_school">
                </div>
              </div>
              <div class="row-fluid">
                <div class="span8 center">
                  <span id="ajax_result" class="alert hide"></span>
                </div>
              </div>
              <div class="span3 offset3">
                <span id="save_btn" class="btn btn-primary">Submit</span>
              </div>
            </fieldset>
          </form>
        </div>
      <?php } ?>
      </div>
      <hr>
      <footer>
        <p>&copy; 2012-2014 Bashu Middle School</p>
      </footer>
    </div>

    <script src="../assets/js/jquery.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>
    <script src="../assets/js/common.js"></script>

    <script type="text/javascript"> 
      $(document).ready(function(){
        $('#save_btn').click(function(){
          var b=false,pwd;
          if(!$.trim($('#input_userid').val())) {
            $('#userid_ctl').addClass('error');
            b=true;
          }else{
            $('#userid_ctl').removeClass('error');
          }
          pwd=$('#input_newpwd').val();
          if(pwd!='' && $('#input_reppwd').val()!=pwd){
            b=true;
            $('#newpwd_ctl').addClass('error');
            $('#reppwd_ctl').addClass('error');
          }else{
            $('#newpwd_ctl').removeClass('error');
            $('#reppwd_ctl').removeClass('error');
          }
          if(!b){
            $.ajax({
              type:"POST",
              url:"ajax_profile.php",
              data:$('#form_profile').serialize(),
              success:function(msg){
                $('#ajax_result').html(msg).show();
                if(/created/.test(msg)){
                  $('#ajax_result').addClass('alert-success');
                  setTimeout(function(){location.href="index.php";},700);
                }
              }
            });
          }
        });
        $('#ret_url').val("index.php");
      });
    </script>
  </body>
</html>