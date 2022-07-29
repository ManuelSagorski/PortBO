<div class="ui raised green segment" id="loginBody">
	<h1>Backoffice</h1>
	<form class="ui form login" id="loginForm" action="index.php" method="post">
    	<div class="field">
    		<div class="ui icon input">
        		<input type="text" name="username" placeholder="Username...">
        		<i class="user icon"></i>
        	</div>
    	</div>
    	<div class="field">
    		<div class="ui icon input">
	        	<input type="password" name="secret" placeholder="Password...">
	        	<i class="key icon"></i>
	        </div>
    	</div>

		<div class="ui error message"></div>

		<?php echo (isset($result['message']['info']))?'<div class="msgForm">' . $result['message']['info'] . '</div>':''; ?>
		
		<button class="ui primary button">Login</button>
	</form>
</div>

<?php if(isset($result['message']['error'])) { ?>
<script>
	$('#loginForm').addClass("error");
	$('.ui.error.message').html('<?php echo $result['message']['error']; ?>');
</script>
<?php } ?>