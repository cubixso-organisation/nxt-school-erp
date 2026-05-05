<?php

namespace app\modules\librarymanagement\models;

use Yii;
use \app\modules\librarymanagement\models\base\LibraryMembers as BaseLibraryMembers;

/**
 * This is the model class for table "library_members".
 */
class LibraryMembers extends BaseLibraryMembers
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['campus_id', 'status', 'created_user_id', 'updated_user_id'], 'integer'],
            [['created_on', 'updated_on'], 'safe'],
            [['member_id', 'library_card_no', 'admission_no', 'phone'], 'string', 'max' => 20],
            [['name'], 'string', 'max' => 255],
            [['member_type'], 'string', 'max' => 50]
        ]);
    }
	

}
