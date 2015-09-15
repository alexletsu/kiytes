<?php

namespace app\models;

class UserIdent extends \yii\base\Object implements \yii\web\IdentityInterface
{
    public $id;
    public $email;
    public $password;
    public $authKey;
    public $accessToken;

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        $dbUser = User::findOne(['id' => $id]);
        
        if ( $dbUser ) {
            return UserIdent::initFromDbInstance($dbUser);
        }
        return null;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    /**
     * Finds user by username
     *
     * @param  string      $username
     * @return static|null
     */
    /*
    public static function findByUsername($username)
    {
        return null;
    }*/

    /**
     * @param string $email
     * @return app\models\User|null User
     */
    public static function findByEmail($email) {
        $dbUser = User::findOne(['email' => $email]);
        
        if ( $dbUser ) {
            return UserIdent::initFromDbInstance($dbUser);
        }
        return null;
    }
    
    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * @param string $password Unencrypted password
     * @return boolean whether password is valid
     */
    public function validatePassword($password)
    {
        return ( crypt($password, $this->password) == $this->password );
        
        //retuires php5.5
        //return password_verify($password, $this->password);
    }
    
    public static function initFromDbInstance(User $user) {
        $userIdent = new UserIdent;
        
        $userIdent->id = $user->id;
        $userIdent->email = $user->email;
        $userIdent->password = $user->password;
        
        return $userIdent;
    }
}
