<?php
namespace mdm\admin\models\form;

use Yii;
use mdm\admin\models\User;
use yii\base\Model;

/**
 * Signup form
 */
class Update extends Model
{
    public $username;
    public $email;
    public $phone;
    public $password;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            //['username', 'unique', 'targetClass' => 'mdm\admin\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],
            [['email','phone'],'safe'],
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function updateUser($id)
    {
        if ($this->validate()) {
            $user = User::findIdentityAny($id);
            $user->username = $this->username;
            $user->email = $this->email;
            $user->phone = $this->phone;
            if ($this->password){
                $user->setPassword($this->password);
                $user->generateAuthKey();
            }
            if ($user->save()) {
                return $user;
            }
        }

        return null;
    }

    public function getUser($id)
    {
        $user =  User::findIdentityAny($id);
        $update = new Update();
        $update->username = $user->username;
        $update->email = $user->email;
        $update->phone = $user->phone;
        return $update;
    }

    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'email' => '邮箱',
            'phone' => '电话',
            'password'=>'密码'
        ];
    }
}
