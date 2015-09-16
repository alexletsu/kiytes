<?php

namespace app\models;

use yii\base\Model;
use app\models\User;
/**
 * SignupForm is the model behind the login form.
 */
class SignupForm extends Model
{
    public $firstName;
    public $lastName;
    public $userType;
    public $email;
    public $phone;
    public $password;
    public $password2;
    public $creditCardInfo;
    public $termsAgreement = false;

    private $_user = false;


    /**
     * @return array attribute labels
     */
    public function attributeLabels()
    {
        return [
            'firstName' => "First Name", 
            'lastName' => "Last Name", 
            'userType' => "User Type",
            'email' => "Email", 
            'phone' => "Phone Number", 
            'password' => "Password", 
            'password2' => "Confirm Password",
            'termsAgreement' => "Terms And Policy",
            'creditCardInfo' => "Credit Card Information"
        ];
    }
    
    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['firstName', 'lastName', 'email', 'phone', 'password', 'password2', 'userType'], 'required'],
            [['firstName', 'lastName'], 'string', 'length' => [3, 200]],
            [['email', 'password', 'password2'], 'string', 'length' => [6, 200]],
            
            ['email', 'email'],
            ['termsAgreement','boolean', 'trueValue' => 1, 'falseValue' => 0, 'strict' => 0],
            
            ['password2', 'compare','compareAttribute'=>'password','message'=>'Passwords must match'],
            
            ['userType','in','range'=>['0','1'], 'message' => 'Invalid user type'],

            ['phone', 'validPhoneFormat'],
            [['email', 'phone'], 'uniqueInDatabase'],
            
            ['termsAgreement', 'compare', 'compareValue' => 1, 'message'=>'You must accept terms and policy'],
        ];
    }

    public function validPhoneFormat($attribute, $params) {
        $err = User::validatePhone($this->phone);
        
        if ( true !== $err ) {
            $this->addError('phone', $err);
            return false;
        }
        
        return true;
    }
    
    public function uniqueInDatabase($attribute, $params) {
        if ( YII_DEBUG ) {error_log("[SignupForm::uniqueInDatabase]");}
        if ( YII_DEBUG ) {error_log("[SignupForm::uniqueInDatabase] attribute : {$attribute}");}
        if ( YII_DEBUG ) {error_log("[SignupForm::uniqueInDatabase] params : {$params}");}
        
        if ( in_array($attribute, ['email', 'phone']) ) {
            $user = User::findOne([$attribute => $this->{$attribute}]);
            if ( null === $user ) {
                return true;
            }
            $this->addError($attribute, $this->attributeLabels()[$attribute] . " already exists");
        }
        return false;
    }
    
    /**
     * Creates new user
     * @return boolean whether the user is created successfully
     */
    public function signup()
    {
        if ( YII_DEBUG ) {error_log("[SignupForm::Signup]");}
        if ( YII_DEBUG ) {error_log("[SignupForm::Signup] attributes : " . json_encode($this->getAttributes()));}
        
        if ($this->validate()) {
            if ( YII_DEBUG ) {error_log("[SignupForm::Signup] valid data");}
            
            try {
                return User::createUser(
                        $this->userType, 
                        $this->firstName, 
                        $this->lastName, 
                        $this->email, 
                        $this->password,
                        $this->phone, 
                        $this->creditCardInfo
                    );
            } catch (Exception $ex) {
                if ( YII_DEBUG ) {error_log("[SignupForm::Signup] error [" . get_class($ex) . "}] : {$ex->getMessage()} [{$ex->getCode()}]");}
            }
            //$user = User;
            //return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
        } else {
            if ( YII_DEBUG ) {error_log("[SignupForm::Signup] !invalid! data");}
        }
        
        return false;
    }
}
