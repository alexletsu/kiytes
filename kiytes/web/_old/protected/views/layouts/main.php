<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html lang="en" class="bg-dark">
<head>
  <meta charset="utf-8" />
  <title>Kytes | Web Application</title>
  <meta name="description" content="app, web app, responsive, admin dashboard, admin, flat, flat ui, ui kit, off screen nav" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" /> 
  <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap.css" type="text/css" />
  <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/animate.css" type="text/css" />
  <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/font-awesome.min.css" type="text/css" />
  <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/font.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/app.css" type="text/css" />
  <!--[if lt IE 9]>
    <script src="js/ie/html5shiv.js"></script>
    <script src="js/ie/respond.min.js"></script>
    <script src="js/ie/excanvas.js"></script>
  <![endif]-->
</head>
<body>
  <section id="content" class="m-t-lg wrapper-md animated fadeInUp">
    <div class="container aside-xxl">
         <a class="navbar-brand block logo" href="#">KYTES</a>
      <section class="panel panel-default m-t-lg bg-white">
	  	<?php echo $content; ?>
     </section>
    </div>
  </section>
  <!-- footer -->
  <footer id="footer">
    <div class="text-center padder clearfix">
      <p>
        <small> &copy; 2013</small>
      </p>
    </div>
  </footer>
  <!-- / footer -->
  <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.min.js"></script>
  <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/parsley/parsley.min.js"></script>
  <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/parsley/parsley.extend.js"></script> 
  <script>
    $(function() { 
    $('#formcpass').submit(function(e) { 
        e.preventDefault();
        if ( $(this).parsley().isValid() ) {
          
$.ajax({
	url:"<?php echo Yii::app()->request->baseUrl; ?>/index.php/users/login",
	data:$(this).serialize(),
	type:"post",
	success:function(data){
		//alert(data)
		if(data==1){
			$('#messages').text("Login succesfully")
			$('.alert').removeClass('alert-danger');
			$('.alert').addClass('alert-success');
			
			$('.alert').show();
			location.href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/users/loged"
		}else{
			$('#messages').text("Please enter correct username or password")
			$('.alert').removeClass('alert-success');
			$('.alert').addClass('alert-danger');
			$('.alert').show();
			
		}
	}
})			
        }
    });
}); 
  </script>
</body>
</html>



