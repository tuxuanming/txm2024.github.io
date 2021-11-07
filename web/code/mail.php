<?php
require('inc/checklogin.php');
require('inc/mail_flags.php');

if(isset($_GET['start_id']))
	$page=intval($_GET['start_id']);
else
	$page=0;

if(isset($_GET['starred']))
	$cond_starred='and (flags &'.MAIL_FLAG_STAR.')';
else
	$cond_starred='';

if(!isset($_SESSION['user']))
	$info = 'Not logged in.';
else{
	require('inc/database.php');
	$user_id=$_SESSION['user'];
	$result=mysql_query("select mail_id,title,from_user,new_mail,in_date,flags from mail where to_user='$user_id' and UPPER(defunct)='N' $cond_starred order by mail_id desc limit $page,20");
}
$Title="Mail List";
?>
<!DOCTYPE html>
<html>
	<?php require('head.php'); ?>  

	<body>
		<?php require('page_header.php'); ?>
		<div class="container-fluid">
			<?php 
			if(isset($info)){
				echo '<div class="center">',$info,'</div>';
			}else{
			?>
			<div class="row-fluid">
				<div class="span2 offset2 form-inline">
					<span id="sendnew" style="margin:5px" class="btn btn-small shortcut-hint" title="Alt+N"><i class="icon-inbox"></i> COMPOSE </span>
		            <label class="checkbox"><input <?php if(isset($_GET['starred']))echo 'checked'?>  id="chk_starred" type="checkbox" name="star">Starred</label>
				</div>
			</div>
			<div class="row-fluid">
				<div class="span8 offset2" id="maillist">
						<ul class="unstyled">
						<?php
						while($row=mysql_fetch_row($result)){
							echo '<li class="mail-item" ',($row[3] ? 'style="background-color: #FCF8E3;"' : ''),' id="mail',$row[0],'">';
						?>
								<div class="mail-container">
									<div class="mail-title">
										<a href="#star" style="text-decoration:none">
											<i class="<?php echo ($row[5]&MAIL_FLAG_STAR)?'icon-star':'icon-star-empty'?> icon-large text-warning"></i>
										</a>
										<?php 
										echo '<a href="#title">',htmlspecialchars($row[1]),'</a>';
										?>
									</div>
									<div class="mail-info"><?php echo $row[2],' at ',substr($row[4],0,10);?></div>
									<div style="clear:both"></div>
									<div class="mail-content">
										<div class="mail-op">
											Received at <?php echo substr($row[4],-8)?>
											<a href="#del" class="btn btn-mini btn-danger">
												<i class="icon-trash icon-white"></i>
												Delete
											</a>
											<a href="#rep" class="btn btn-mini btn-primary">
												<i class="icon-share-alt icon-white"></i>
												Reply
											</a>
										</div>
										<pre></pre>
									</div>
								</div>
							</li>
						<?php } ?>
						</ul>
				</div>
			</div>  
			<div class="row-fluid">
				<ul class="pager">
					<li>
						<a class="pager-pre-link shortcut-hint" title="Alt+A" href="#" id="btn-pre"><i class="icon-angle-left"></i> Previous</a>
					</li>
					<li>
						<a class="pager-next-link shortcut-hint" title="Alt+D" href="#" id="btn-next">Next <i class="icon-angle-right"></i></a>
					</li>
				</ul>
			</div>  

			<div class="modal hide" id="MailModal">
				<div class="modal-header">
					<a class="close" data-dismiss="modal">×</a>
					<h4>New Message</h4>
				</div>
				<div class="modal-body">
					<form class="form-horizontal" id="send_form">
						<fieldset>
							<div class="control-group">
								<label class="control-label" for="to_input">To</label>
								<div class="controls">
									<input type="text" class="input-medium" id="to_input" name="touser">
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="title_input">Subject</label>
								<div class="controls">
									<input type="text" style="width: 445px;" id="title_input" name="title">
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="detail_input">Content</label>
								<div class="controls">
									<textarea style="width: 445px;" id="detail_input" rows="10" name="detail"></textarea>
								</div>
							</div>
						</fieldset>
					</form>
					<div class="alert alert-error hide margin-0" id="send_result"></div>
				</div>
				<div class="modal-footer">
					<a href="#" class="btn btn-primary shortcut-hint" title="Alt+S" id="send_btn">Send</a>
					<a href="#" class="btn" data-dismiss="modal">Close</a>
				</div>
			</div>
			<?php } ?>
			<hr>
			<footer>
				<p>&copy; 2012-2014 Bashu Middle School</p>
			</footer>

		</div><!--/.container-->
		<script src="../assets/js/jquery.js"></script>
	    <script src="../assets/js/bootstrap.min.js"></script>
		<script src="../assets/js/common.js"></script>

		<script type="text/javascript"> 
			$(document).ready(function(){
				var cur=<?php echo $page?>;
				$('#ret_url').val("mail.php");
				$('#btn-next').click(function(){
					var url_parm=GetUrlParms();
					url_parm['start_id']=cur+20;
					location.href='mail.php'+BuildUrlParms(url_parm);
					return false;
				});
				$('#btn-pre').click(function(){
					if(cur-20>=0){
						var url_parm=GetUrlParms();
						url_parm['start_id']=cur-20;
						location.href='mail.php'+BuildUrlParms(url_parm);
					}
					return false;
				});
				$('#maillist').click(function(E){
					var $a;
					if($(E.target).is('i'))
						$a=$(E.target).parent();
					else if(typeof(E.target.href)!='undefined')
						$a=$(E.target);
					else
						return;
					var j=$a.attr('href'),k,content,mailid;
					switch(j.substr(j.lastIndexOf('#')+1)){
						case 'title':
							k=$a.parents('.mail-container'); 
							mailid=k.parent().css('background-color','').attr('id').substr(4);
							content=k.children('.mail-content');
							if(content.is(":hidden")){
								$.get('ajax_mailfunc.php?op=show&mail_id='+mailid,function(data){
									if(typeof(window.fix_ie_pre)!='undefined')
										data=encode_space(data);
									content.children('pre').html(data);
								});
								content.show();
							}else{
								content.hide();
							}
							break;
						case 'del':
							k=$a.parents('li');
							$.ajax('ajax_mailfunc.php?op=delete&mail_id='+k.attr('id').substr(4));
							k.remove();
							break;
						case 'rep':
							k=$a.parents('.mail-container');
							content=k.children('.mail-info').html();
							$('#to_input').val(content.substr(0,content.indexOf(' ')));
							$('#title_input').val('Re:'+k.find('a[href="#title"]').html());
							$('#send_result').hide();
							$('#MailModal').modal('show');
							break;
						case 'star':
							k=$a.parents('li');
							$.ajax('ajax_mailfunc.php?op=star&mail_id='+k.attr('id').substr(4));
							$a.find('i').toggleClass('icon-star').toggleClass('icon-star-empty');
							break;
					}
					return false;
				});
				$('#send_btn').click(function(){
					$.ajax({
						type:"POST",
						url:"ajax_mailfunc.php?op=send",
						data:$('#send_form').serialize(),
						success:function(msg){
							if(msg.indexOf('__OK__')!=-1){
								location.reload();
								return;
							}
							$('#send_result').html(msg).show();
						}
					});
				});
				$('#sendnew').click(function(){
					$('#send_result').hide();
					$('#MailModal').modal('show');
					$('#to_input').focus();
				});
				$('#chk_starred').change(function(E){
					var url_parm=GetUrlParms();
					url_parm['start_id']=0;
					if(E.target.checked)
						url_parm['starred']=1;
					else
						delete url_parm.starred;
					location.href='mail.php'+BuildUrlParms(url_parm);
				});
				reg_hotkey(78,function(){$('#sendnew').click()}); //Alt+N
		        reg_hotkey(83,function(){$('#send_btn').click()}); //Alt+S
			}); 
		</script>
	</body>
</html>
