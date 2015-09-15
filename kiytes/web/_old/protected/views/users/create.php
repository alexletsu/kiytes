    <header class="panel-heading text-center">
          <strong>Sign up</strong>
        </header>
        <form  class="panel-body wrapper-lg" data-validate="parsley" action="<?php echo Yii::app()->createUrl("users/create"); ?>" method="post">
          <div class="form-group">
            <label class="control-label">Name</label>
            <input type="text" placeholder="eg. Your name or company" class="form-control input-lg"  data-required="true" onkeyup="this.value=this.value.replace(/[^a-z]/g,'');" onblur="this.value=this.value.replace(/[^a-z]/g,'');" name="Users[Name]">
          </div>
          <div class="form-group">
            <label class="control-label">Email</label>
            <input type="email" placeholder="test@example.com" class="form-control input-lg" data-type="email" data-required="true" name="Users[Email]">
          </div>
          <div class="form-group">
            <label class="control-label">Password</label>
            <input type="password" id="inputPassword" placeholder="Type a password" class="form-control input-lg" data-required="true" name="Users[Password]">
          </div>
		  <div class="form-group">
            <label class="control-label">Confirm Password</label>
            <input type="password" id="cinputPassword" placeholder="Re-Type a password" data-equalto="#inputPassword" class="form-control input-lg" data-required="true">
          </div>
		  
          <div class="checkbox">
            <label>
              <input type="checkbox" name="agree" data-required="true"> Agree the <a href="#">terms and policy</a>
            </label>
          </div>
          <button type="submit" class="login btn btn-primary">Sign up</button>
          <div class="line line-dashed"></div>
          <p class="text-muted text-center"><small>Already have an account? &nbsp;</small> <a href="<?php echo Yii::app()->createUrl("site/login"); ?>">Sign in</a></p>
         
        </form>
     