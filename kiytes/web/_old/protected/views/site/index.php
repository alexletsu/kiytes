<header class="panel-heading text-center">
          <strong>Sign in</strong>
        </header>
		<div class="alert" style="display:none">
                    <button data-dismiss="alert" class="close" type="button">×</button>
                    <i class="fa fa-ok-sign"></i> <span id="messages"> </span>
                  </div>
        <form data-validate="parsley" class="panel-body wrapper-lg" id="formcpass" action="<?php echo Yii::app()->createUrl("site/login"); ?>" method="post">
          <div class="form-group">
            <label class="control-label">Email</label>
            <input type="email" placeholder="test@example.com" name= "email" data-type="email" data-required="true" class="form-control input-lg">
          </div>
          <div class="form-group">
            <label class="control-label">Password</label>
            <input type="password" id="inputPassword" placeholder="Password" name= "password" class="form-control input-lg" data-required="true">
          </div>
          <div class="checkbox">
            <label>
              <input type="checkbox" name="check"   > Keep me logged in
            </label>
          </div>
          <a href="#" class="pull-right m-t-xs"><small>Forgot password?</small></a>
          <button type="submit" class="login btn btn-primary">Sign in</button>
          <div class="line line-dashed"></div>
          <a href="#" class="btn btn-facebook btn-block m-b-sm"><i class="fa fa-facebook pull-left"></i>Sign in with Facebook</a>
          <a href="#" class="btn btn-twitter btn-block"><i class="fa fa-twitter pull-left"></i>Sign in with Twitter</a>
          <div class="line line-dashed"></div>
      <p class="text-muted text-center"><small>Do not have an account? &nbsp;<a href="<?php echo Yii::app()->createUrl("users/create"); ?>">Sign Up</a></small></p>
</form>


