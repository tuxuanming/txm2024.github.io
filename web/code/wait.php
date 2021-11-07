<?php
if(!isset($_GET['key']))
	die('Invalid key.');
$key=$_GET['key'];
if(strlen($key)!=32 || preg_match('/\W/',$key))
	die('Invalid key.');
$Title="Waiting";
?>
<!DOCTYPE html>
<html>
  <?php require('head.php'); ?>

  <body>
    <div class="container wait-page">
    	<div class="row">
        <div class="span8 offset2">
          <h3>Detailed Result</h3>
          <p class="muted tiny-font" style="margin-bottom:0">Please don't close or refresh this page, or you won't see your detailed result.</p>
          <p class="muted tiny-font">This page will be refreshed automatically.</p>
          <div class="row">
            <div class="span4 offset2">
              <div id="ele_queue" class="alert alert-info center"><strong><i class="icon-spinner icon-large icon-spin"></i> Queueing...</strong></div>
              <div id="ele_judge" class="hide alert alert-info center"><strong><i class="icon-spinner icon-spin"></i> Judging, please wait...</strong></div>
            </div>
          </div>
          <div class="hide well well-small margin-0" id="ele_table">
            <table class="table table-bordered result_table" style="margin-bottom:0">
              <thead>
                <tr><th>Case</th><th>Result</th><th>Time</th><th>Memory</th><th>Score</th></tr>
              </thead>
              <tbody style="color:white" id="ele_tbody"></tbody>
            </table>
          </div>
          <div class="hide" id="ele_finish" style="margin-top: 15px;">
            <p>Finished! You can go back to the problem or go to the record page.</p>
            <ul class="pager"><li class="previous"><a class="pager-pre-link shortcut-hint" title="Alt+P" id="btn_back" href="#"><i class="icon-angle-left"></i> Problem Page</a></li>
            <li class="next"><a class="pager-next-link shortcut-hint" title="Alt+R" href="record.php">Record Page <i class="icon-angle-right"></i></a></li></ul>
          </div>
        </div>
    	</div>
      <hr>
      <footer>
        <p>&copy; 2012-2014 Bashu Middle School</p>
      </footer>

    </div>

    <script src="../assets/js/jquery.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>

    <script type="text/javascript"> 
      res_tyep={"0":"Correct","2":"Time Out","3":"MLE","4":"Wrong Answer","5":"Runtime Error","99":"Validator Error"};
      function disp_CE(str){
        $("#ele_judge").hide();
        $('#ele_queue').hide();
        $('#ele_table').html('<h4>Compile Error</h4><p style="text-align:left;overflow-x:auto;">'+htmlEncode(str)+'</p>').show();
        $("#ele_finish").show();
      }
      function disp_SE(){
        $("#ele_judge").hide();
        $('#ele_queue').hide();
        $('#ele_table').removeClass().html('<div class="alert alert-error center"><p>Sorry, something went wrong. Your code is not recorded.<br>Please contact administrator.</p></div>').show();
        $("#ele_finish").show();
      }
      function htmlEncode(str) {
        var s = "";
        if (str.length == 0) return "";
        s = str.replace(/&/g, "&amp;");
        s = s.replace(/ /g, "&nbsp;");
        s = s.replace(/</g, "&lt;");
        s = s.replace(/>/g, "&gt;");  
        s = s.replace(/\'/g, "&#39;");
        s = s.replace(/\"/g, "&quot;");
        return s;
      }
      last_i=0;
      function load_progress(){
        var url='<?php echo "proxy.php?url=query_$key";?>';
        // alert(url);
        $.getJSON(url,function(obj){
          if(obj.state=="invalid"){
            $("#ele_judge").hide();
            $('#ele_queue').hide();
            $("#ele_finish").show();
          }else{
            var timeout=2500;
            if(obj.detail.length>1){
              var content="",record,i;
              if(obj.detail[0][0]==7)
                return disp_CE(obj.detail[0][3]);
              if(obj.detail[0][0]==100)
                return disp_SE(obj.detail[0][3]);
              for(i=0;obj.detail[i].length>0;i++){
                var record=obj.detail[i];
                content+='<tr id="line'+i+'" title="'+htmlEncode(record[3])+'" ';
                switch(record[0]){
                  case 0:
                  content+='class="res-good">';break;
                  case 2:
                  case 3:
                  content+='class="res-warning">';break;
                  case 4:
                  content+='class="res-error">';break;
                  case 5:
                  content+='class="res-info">';break;
                  case 99:
                  content+='class="res-inverse">';break;
                  default:
                  content+='>';break;
                }
                content+='<td>'+(i+1)+'</td><td>'+res_tyep[record[0]]+'</td><td>'+record[1]+' ms</td><td>'+record[2]+' KB</td><td>'+record[4]+'</td></tr>';
              }
              $('body>.tooltip').remove();
              $('#ele_tbody').empty().html(content);
              if(i-1-last_i==0)
                timeout=3600;
              else if(i-1-last_i>1)
                timeout=1000;
              last_i=i;
              for(--i;i>=0;--i)
                $('#line'+i).tooltip({});
              $('#ele_queue').hide();
              $('#ele_judge').show();
              $('#ele_table').show();
            }
            if(obj.state=='finish'){
              $('#ele_queue').hide();
              $("#ele_judge").hide();
              $("#ele_finish").show();
              return;
            }
            window.setTimeout(load_progress,timeout);
          }
        });
      }
      $(document).ready(function(){
        window.setTimeout(load_progress,2000);
        $('#btn_back').click(function(){
          history.go(-1);
          return false;
        });
      }).keydown(function(E){
        if(E.altKey && !E.metaKey){
          var key=E.keyCode;
          if(key==80)
            history.go(-1);
          else if(key==82)
            location.href="record.php";
          return false;
        }
      });
    </script>
  </body>
</html>
