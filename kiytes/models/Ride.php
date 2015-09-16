<?php

namespace app\models;
class Ride extends \yii\db\ActiveRecord
{
    public static $_STATUS_INVITATION   = 0;
    public static $_STATUS_PENDING      = 1;
    public static $_STATUS_DECLINED     = 2;
    public static $_STATUS_ACCEPTED     = 3;
    public static $_STATUS_COMPLETE     = 4;
    
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
        return 'rides';
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
            'id' => "ID",
            'make' => "Car Make",
            'model' => "Car Model",
            'year' => "Car Year",
            'license_plate' => "Car License Plate",
            'car_photo' => "Car Photo",
            'price_mile' => "Proce/Mile",
        );
    }
    
    public function generateRideToken() {
        $this->ride_token = uniqid();
    }
}
