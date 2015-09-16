<?php

namespace app\models;

Use Yii;
use yii\base\Model;

use app\models\User;
use app\models\Address;
use app\models\RelUserAddress;
use app\models\Car;

use yii\web\UploadedFile;
use yii\validators\EmailValidator;

/**
 * ProfileForm is the model behind the login form.
 */
class ProfileForm extends Model
{
    public $isExists;
    
    public $isEditable;
    
    public $isComplete;
    
    public $userType;
    
    public $firstName;
    public $lastName;
    
    public $email;
    
    public $phone;
    public $phoneVerified;
    
    public $photo;
    public $license_photo;
    public $insurance_photo;
    
    public $address;
    public $creditCardInfo;
    
    public $car_photo;
    public $priceMile;
    public $carMake;
    public $carModel;
    public $carYear;
    public $licensePlateNumber;
    
    public $rate;
    
    public $createdAt;

    private $_user = null;
    private $_car = null;


    public function __construct($config = array()) {
        if ( YII_DEBUG ) {error_log("[ProfileForm::__construct]");}
        parent::__construct($config);
        
        $this->loadData();
    }
    
    private function loadData() {
        $userId = Yii::$app->request->get('id');
        if ( null === $userId ) {
            $userId = Yii::$app->user->id;
        }
        
        $this->_user = User::findOne(['id' => $userId]);
        if ( $this->_user ) {
            $this->isExists = true;
            
            if ( $this->_user->id === Yii::$app->user->id ) {
                $this->isEditable = true;
            } else {
                $this->isEditable = false;
            }
        
            /* fields for form */
            $this->userType = $this->_user->user_type;
            $this->isComplete = $this->_user->is_complete;
            
            $this->firstName = $this->_user->first_name;
            $this->lastName = $this->_user->last_name;
            
            $this->email = $this->_user->email;
            $this->phone = $this->_user->phone;
            $this->phoneVerified = $this->_user->phone_verified;
            
            $this->photo = $this->_user->photo;
            
            $this->creditCardInfo = $this->_user->ccard_info;
            $this->createdAt = new \DateTime($this->_user->created_at);

            $this->address = "";
            $this->priceMile = "";
            
            $this->rate = $this->_user->getRate();
            
            if ( User::$_TYPE_CUSTOMER === $this->_user->user_type) {
                $this->priceMile = "";
                
                $this->address = [];
                
                $arrRua = RelUserAddress::findAll(['user_id' => $this->_user->id]);
                if ( $arrRua ) {
                    foreach($arrRua as $rua) {
                        $addr = Address::findOne($rua->address_id);
                        if ( $addr ) {
                            $this->address[] = $addr->address;
                        }
                    }
                }
            } else {
                $rua = RelUserAddress::findOne(['user_id' => $this->_user->id]);
                if ( $rua ) {
                    $addr = Address::findOne($rua->address_id);
                    if ( $addr ) {
                        $this->address = $addr->address;
                    }
                }
                
                if ( User::$_TYPE_DRIVER === $this->_user->user_type ) {
                    $this->_car = Car::findOne(['driver_id' => $this->_user->id]);
                    if ( !$this->_car ) {
                        $this->_car = new Car();
                        $this->_car->driver_id = $this->_user->id;

                        $this->_car->save();
                    }

                    $this->car_photo = $this->_car->car_photo;
                    $this->license_photo = $this->_user->license_photo;
                    $this->insurance_photo = $this->_user->insurance_photo;

                    $this->priceMile = $this->_car->price_mile;
                    $this->carMake = $this->_car->make;
                    $this->carModel = $this->_car->model;
                    $this->carYear = $this->_car->year;
                    $this->licensePlateNumber = $this->_car->license_plate;
                }
            }
            
            if ( YII_DEBUG ) {error_log("[ProfileForm::__construct] attributes : " . json_encode($this->getAttributes()));}
        } else {
            $this->isExists = false;
        }
    }
    
    public function processFileField($formAttribute, $dbObject) {
        if ( YII_DEBUG ) {error_log("[ProfileForm::processFileField] formAttribute : {$formAttribute}");}
        if ( YII_DEBUG ) {error_log("[ProfileForm::processFileField] for object : " . get_class($dbObject));}
        
        $file = UploadedFile::getInstance($this,$formAttribute);

        if ( $file && $this->validateFile($file, $formAttribute) ) {
            $filename = ProfileForm::getUniqueFileName("{$formAttribute}_", $file->extension);
            if ( YII_DEBUG ) {error_log("[ProfileForm::processFileField] name for new file : {$filename}");}
            
            if ( !$file->saveAs( Yii::getAlias('@app/web/uploads/') . $filename ) ) {
                Yii::$app->session->setFlash($formAttribute, 'Problems due file uploading.. Try another one.');
            } else {
                if ( (0 < strlen($dbObject->$formAttribute)) && file_exists(Yii::getAlias('@app/web/uploads/') . $dbObject->$formAttribute) ) {
                    if ( YII_DEBUG ) {error_log("[ProfileForm::processFileField] unlinking old file : " . $dbObject->$formAttribute);}
                    unlink(Yii::getAlias('@app/web/uploads/') . $dbObject->$formAttribute);
                }

                if ( YII_DEBUG ) {error_log("[ProfileForm::processFileField] set new filename for user : {$filename}");}
                $dbObject->$formAttribute = $filename;
                if ( YII_DEBUG ) {error_log("[ProfileForm::processFileField] user {$formAttribute} : {$dbObject->$formAttribute}");}
            }
        } else {
            if ( YII_DEBUG ) {error_log("[ProfileForm::processFileField] file was not uploaded or not valid");}
        }
    }
    
    public static function getUniqueFileName($prefix, $extension) {
        do {
            $filename = $prefix . rand(10000000000, 100000000000) . ".{$extension}";
        } while(is_file(Yii::getAlias('@app/web/uploads/') . $filename));
        
        return $filename;
    }
    
    public function validateFile(UploadedFile $file, $attribute) {
        if ($file->error !== UPLOAD_ERR_OK ) {
            Yii::$app->session->setFlash($attribute, 'Problems due file uploading.. Try another one.');
            return false;
        }
        
        if ( !in_array($file->extension, ['jpg', 'png', 'jpeg', 'img']) ) {
            Yii::$app->session->setFlash($attribute, 'Bad format, only jpg, png, jpeg and img are allowed');
            return false;
        }
        
        if ( $file->size >= 5 * 1024 * 1000) { /* 5 mb limit */
            Yii::$app->session->setFlash($attribute, 'File too large, 5 mb limit.');
            return false;
        }
        
        return true;
    }
    
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
            'creditCardInfo' => "Credit Card Information",
            
            'priceMile' => "Price/Mile",
            'carMake' => "Car Make",
            'carModel' => "Car Model",
            'carYear' => "Car Year",
            'licensePlateNumber' => "Car License Plate Number",
        ];
    }
    
    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
        ];
    }

    /**
     * Save user profile
     * @return boolean whether the user is created successfully
     */
    public function saveProfile()
    {
        if ( YII_DEBUG ) {error_log("[ProfileForm::saveProfile]");}
        if ( YII_DEBUG ) {error_log("[ProfileForm::saveProfile] attributes : " . json_encode($this->getAttributes()));}
        
        if ( $this->isEditable ) {
            $formData = Yii::$app->request->post('ProfileForm');
            if ( $formData ) {
                $this->processFileField('photo', $this->_user);

                $_email = (isset($formData['email']) ? strval($formData['email']) : null);
                if ( $_email && $this->_user->email !== $_email ) {
                    $v = new EmailValidator();
                    $err = null;

                    if ($v->validate($_email)) {
                        $u = User::findOne(['email' => $_email]);
                        if ( null !== $u ) {
                            $err = 'Email already exists';
                        } else {
                            $this->_user->email = $_email;
                        }
                    } else {
                        $err = 'Not a valid email address';
                    }

                    if ( $err ) {
                        Yii::$app->session->addFlash('email', $err);
                    }
                }

                $_phone = (isset($formData['phone']) ? strval($formData['phone']) : null);
                if ( $_phone && (0 == $this->_user->phone_verified) && ($this->_user->phone !== $_phone) ) {
                    $err = User::validatePhone($_phone);

                    if ( true === $err ) {
                        $u = User::findOne(['phone' => $_phone]);
                        if ( null !== $u ) {
                            $err = 'Phone already exists';
                        } else {
                            $this->_user->phone = $_phone;
                            $this->_user->phone_verified = 0;
                            $this->_user->generatePhoneToken();
                        }
                    }

                    if ( is_string($err) ) {
                        Yii::$app->session->addFlash('phone', $err);
                    }
                }

                $_address = (isset($formData['address']) ? strval($formData['address']) : null);
                if ( $_address && (0 < strlen($_address)) ) {
                    $addr = Address::findOne(['address' => $_address]);
                    if ( !$addr ) {
                        $addr = new Address();
                        $addr->address = $_address;
                        $addr->save();
                    }

                    if ( User::$_TYPE_CUSTOMER === $this->_user->user_type ) {
                        $rua = RelUserAddress::findOne([
                            'user_id' => $this->_user->id,
                            'address_id' => $addr->id]);

                        if ( !$rua ) {
                            $rua = new RelUserAddress();
                            $rua->user_id = $this->_user->id;
                            $rua->address_id = $addr->id;
                            $rua->save();
                        }
                    } else {
                        $rua = RelUserAddress::findOne(['user_id' => $this->_user->id]);

                        if ( !$rua ) {
                            $rua = new RelUserAddress();
                            $rua->user_id = $this->_user->id;
                        }
                        $rua->address_id = $addr->id;
                        $rua->save();
                    }
                }

                $_fName = (isset($formData['firstName']) ? strval($formData['firstName']) : null);
                if ( (strlen($_fName) > 0) && ($this->_user->first_name !== $_fName) ) {
                    $this->_user->first_name = $_fName;
                }

                $_lName = (isset($formData['lastName']) ? strval($formData['lastName']) : null);
                if ( (strlen($_lName) > 0) && ($this->_user->last_name !== $_lName) ) {
                    $this->_user->last_name = $_lName;
                }

                $_ccInfo = (isset($formData['creditCardInfo']) ? strval($formData['creditCardInfo']) : null);
                if ( $this->_user->ccard_info !== $_ccInfo ) {
                    $this->_user->ccard_info = $_ccInfo;
                }

                if ( User::$_TYPE_DRIVER === $this->_user->user_type ) {
                    $this->_car = Car::findOne(['driver_id' => $this->_user->id]);
                    if ( !$this->_car ) {
                        $this->_car = new Car();
                        $this->_car->driver_id = $this->_user->id;

                        $this->_car->save();
                    }

                    $this->processFileField('car_photo', $this->_car);
                    $this->processFileField('license_photo', $this->_user);
                    $this->processFileField('insurance_photo', $this->_user);

                    $_priceMile = (isset($formData['priceMile']) ? strval($formData['priceMile']) : null);
                    if ( (strlen($_priceMile) > 0) && ($this->_car->price_mile !== $_priceMile) ) {
                        $this->_car->price_mile = $_priceMile;
                    }
                    $_carMake = (isset($formData['carMake']) ? strval($formData['carMake']) : null);
                    if ( (strlen($_carMake) > 0) && ($this->_car->make !== $_carMake) ) {
                        $this->_car->make = $_carMake;
                    }
                    $_carModel = (isset($formData['carModel']) ? strval($formData['carModel']) : null);
                    if ( (strlen($_carModel) > 0) && ($this->_car->model !== $_carModel) ) {
                        $this->_car->model = $_carModel;
                    }
                    $_carYear = (isset($formData['carYear']) ? strval($formData['carYear']) : null);
                    if ( (strlen($_carYear) > 0) && ($this->_car->year !== $_carYear) ) {
                        $this->_car->year = $_carYear;
                    }
                    $_licensePlateNumber = (isset($formData['licensePlateNumber']) ? strval($formData['licensePlateNumber']) : null);
                    $_existing_car = Car::findOne(['license_plate' => $_licensePlateNumber]);
                    
                    if ( $_existing_car ) {
                        Yii::$app->session->addFlash('license_plate', 'License plate number already registered');
                    } elseif ( (strlen($_licensePlateNumber) > 0) && ($this->_car->license_plate !== $_licensePlateNumber) ) {
                        $this->_car->license_plate = $_licensePlateNumber;
                    }

                    $this->_car->save();
                }
            }

            $this->_user->save();
        }
        $this->loadData();
        return true;
    }
}
