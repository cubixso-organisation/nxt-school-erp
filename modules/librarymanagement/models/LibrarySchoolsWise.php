<?php

namespace app\modules\librarymanagement\models;

use Yii;
use \app\modules\librarymanagement\models\base\LibrarySchoolsWise as BaseLibrarySchoolsWise;

/**
 * This is the model class for table "library_schools_wise".
 */
class LibrarySchoolsWise extends BaseLibrarySchoolsWise
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['name', 'email', 'phone', 'school_name', 'status', 'address', 'domain'], 'required'],
            [['created_on', 'updated_on'], 'safe'],
            [['created_user_id', 'updated_user_id'], 'integer'],
            [['name', 'email', 'school_name', 'address'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 20],
            [['status', 'domain'], 'string', 'max' => 50]
        ]);
    }
	

}
