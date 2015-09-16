<?php

namespace app\models;

use app\models\RelUserAddress;
use app\models\Car;
use app\models\Rate;

use yii\db\Query;

class User extends \yii\db\ActiveRecord
{
    public static $_TYPE_CUSTOMER = 0;
    public static $_TYPE_DRIVER = 1;
    
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Comments the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
 
    /**
     * @return string the associated database table name
     */
    public static function tableName()
    {
        return 'users';
    }
 
    /**
     * @return array primary key of the table
     **/     
    public static function primaryKey()
    {
        return array('id');
    }
 
    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',

            'user_type' => 'User Type',
            'is_complete' => 'Is Profile Complete',

            'first_name' => 'First Name',
            'last_name' => 'Last Name',

            'email' => 'Email address',
            'email_verified' => 'Is Email Verified',
            'email_acttoken' => 'Email Activation Token',

            'phone' => 'Phone Number',
            'phone_verified' => 'Is Phone Verified',
            'phone_acttoken' => 'Phone Activation Token',

            'photo' => 'Photo',
            
            'license_photo' => 'Driver License Photo',
            'insurance_photo' => 'Driver Insurance Photo',
            
            'ccard_info' => 'Credit Card Info',

            'created_at' => 'Created At'
        );
    }
    
    /**
     * @param string $password Unencrypted password
     * @return string Encrypted password
     */
    public static function encryptPassword($password) {
        return crypt($password);
        
        // php 5.5 required 
        //return password_hash($password, PASSWORD_DEFAULT);
    }
    
    /**
     * Creates new user in database
     * 
     * @param bool $userType User type : 0 - customer, 1 - driver
     * 
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     * @param string $password
     * @param string $phone
     * @param string $cCardInfo
     * @return bool whether the saving succeeded
     */
    public static function createUser(
            $userType,
            $firstName,
            $lastName,
            $email,
            $password,
            $phone,
            $cCardInfo
        ) {
        
        $user = new User;
        $user->user_type = ('1' === $userType ? 1 : 0);
            
        $user->first_name = $firstName;
        $user->last_name = $lastName;
            
        $user->email = $email;
        $user->email_verified = 0;
        $user->email_acttoken = uniqid();
            
        $user->phone = $phone;
        $user->phone_verified = 0;
        $user->generatePhoneToken();
        
        $user->password = User::encryptPassword($password);
                
        if ( (null !== $cCardInfo) && (0 < strlen($cCardInfo)) ) {
            $user->ccard_info = $cCardInfo;
        }
        
        return $user->save();
    }
    
    public function generatePhoneToken() {
        $this->phone_acttoken = uniqid();
    }
    
    public function beforeSave($insert) {
        $blnMakeSave = parent::beforeSave($insert);
        
        /** @TODO: add checking on profile completion **/
        if (!$insert) {
            $blnIsComplete = true;
            
            $rua = RelUserAddress::findOne(['user_id' => $this->id]);
            if ( 
                    !$rua
                    || ( 0 == strlen($this->first_name) )
                    || ( 0 == strlen($this->last_name) )
                    
                    || ( 0 == strlen($this->email) )
                    
                    || ( 0 == strlen($this->phone) )
                    || ( 0 == $this->phone_verified )
                    
                    || ( 0 == strlen($this->photo) )
                    || ( 0 == strlen($this->ccard_info) )) {
                
                $blnIsComplete = false;
            }
            
            if ( $blnIsComplete && (User::$_TYPE_DRIVER === $this->user_type) ) {
                $_car = Car::findOne(['driver_id' => $this->id]);
                if ( 
                        ( 0 == strlen($this->license_photo) )
                        || ( 0 == strlen($this->insurance_photo) )
                        
                        || !$_car
                        || ( 0 == strlen($_car->make) )
                        || ( 0 == strlen($_car->model) )
                        || ( 0 == strlen($_car->year) )
                        || ( 0 == strlen($_car->license_plate) )
                        || ( 0 == strlen($_car->car_photo) )
                        || ( 0 == strlen($_car->price_mile) ) ) {
                    
                    $blnIsComplete = false;
                }
            }
            
            $this->is_complete = ($blnIsComplete ? 1 : 0);
        }
        
        return $blnMakeSave;
    }
    
    public static function validatePhone($phone) {
        if ( !is_string($phone) ) {
            return "Phone expected to be string";
        } elseif( 0 === strlen($phone) ) {
            return "Phone can't be empty";
        } elseif( 12 !== strlen($phone) ) { 
            return "Phone must be in a specific format, e.g. +16175551212";
        } elseif ( 1 !== preg_match('/^\+[0-9]{11}/', $phone) ) { 
            return "Phone must be in a specific format, e.g. +16175551212";
        }
        
        return true;
    }
    
    public function getRate() {
        $res = null;
        if ( !$this->isNewRecord ) {
            $query = new Query;
            $query->select('avg(r.rate) as rate')
                    ->from('rates r')
                    ->groupBy('r.rated_id')
                    ->where('r.rated_id = :uid', [':uid'=>$this->id])
                    ->limit(1);
            $res = $query->one();
        }
        return ( $res ? sprintf("%.2f", $res['rate']) : null );
    }
    
    public function isRatedBy($user_id) {
        $rater = User::findOne(['id' => $user_id]);
        
        if ( $rater ) {
            $rate = Rate::findOne([
                'rated_id' => $this->id,
                'rater_id' => intval($user_id),
            ]);

            if ( $rate ) {
                return true;
            }
        }
        
        return false;
    }
    
    public function rateByUser($user_id, $rate_score) {
        $rater = User::findOne(['id' => $user_id]);
        
        if ( $rater ) {
            $rate = Rate::findOne([
                'rated_id' => $this->id,
                'rater_id' => intval($user_id),
            ]);
            $rate_score = floatval($rate_score);
            if ( !$rate && !$this->isNewRecord && (0 < $rate_score) && (5 >= $rate_score) ) {
                $rate = new Rate;

                $rate->rated_id = $this->id;
                $rate->rater_id = $rater->id;
                $rate->rate = $rate_score;
                
                $rate->save();
            }
        }
        
        return false;
    }
}
