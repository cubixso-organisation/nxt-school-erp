<?php

namespace app\modules\librarymanagement\models;

use Yii;
use \app\modules\librarymanagement\models\base\LibraryRacks as BaseLibraryRacks;

/**
 * This is the model class for table "library_racks".
 */
class LibraryRacks extends BaseLibraryRacks
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['rack_number', 'rack_location'], 'required'],
            [['created_on', 'updated_on'], 'safe'],
            [['created_user_id', 'updated_user_id'], 'integer'],
            [['rack_number'], 'string', 'max' => 20],
            [['rack_location'], 'string', 'max' => 255]
        ]);
    }
	

}
