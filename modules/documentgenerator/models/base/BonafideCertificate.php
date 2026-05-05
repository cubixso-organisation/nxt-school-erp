<?php


namespace app\modules\documentgenerator\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use mootensai\behaviors\UUIDBehavior;
/**
 * This is the base model class for table "bonafide_certificate".
 *
 * @property integer $id
 * @property integer $campus_id
 * @property string $certificate_name
 * @property string $header_left_text
 * @property string $header_center_text
 * @property string $header_right_text
 * @property string $body_text
 * @property string $footer_right_text
 * @property string $right_sig
 * @property string $certificate_design
 * @property integer $header_height
 * @property integer $footer_height
 * @property integer $body_height
 * @property integer $body_width
 * @property string $background_image
 * @property integer $template_type
 * @property string $status
 * @property string $created_on
 * @property string $updated_on
 * @property integer $created_user_id
 * @property integer $updated_user_id
 *
 * @property \app\modules\admin\models\Campus $campus
 */
class BonafideCertificate extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;


    /**
    * This function helps \mootensai\relation\RelationTrait runs faster
    * @return array relation names of this model
    */
    public function relationNames()
    {
        return [
            'campus'
        ];
    }

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETE = 2;

    const IS_FEATURED = 1;
    const IS_NOT_FEATURED = 0;
    const TEMPLATE_TYPE_LANDSCAPE = 1;
    const TEMPLATE_TYPE_NOTICE = 2;
    /**
     * @inheritdoc
     */    public $section_name;
    public function rules()
    {
        return [
            [['campus_id', 'certificate_name', 'template_type'], 'required'],
            [['campus_id', 'header_height', 'footer_height', 'body_height', 'body_width', 'template_type', 'created_user_id', 'updated_user_id'], 'integer'],
            [['header_left_text', 'header_center_text', 'header_right_text', 'body_text', 'footer_right_text', 'right_sig', 'certificate_design'], 'string'],
            [['updated_on'], 'safe'],
            [['certificate_name', 'background_image'], 'string', 'max' => 255],
            [['status', 'created_on'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bonafide_certificate';
    }
    public function getTemplateType()
    {
        return [

            self::TEMPLATE_TYPE_LANDSCAPE => 'Landscape',
            self::TEMPLATE_TYPE_NOTICE => 'A4 template',
            // self::STATUS_DELETE => 'Deleted',

        ];
    }
    public function getTemplateTypeBadges()
    {
        if ($this->template_type == self::TEMPLATE_TYPE_LANDSCAPE) {
            return '<span class="badge badge-primary">Landscape</span>';
        } elseif ($this->template_type == self::TEMPLATE_TYPE_NOTICE) {
            return '<span class="badge badge-primary">Portrait</span>';
        }
    }
    public function getStateOptions()
    {
        return [

            self::STATUS_INACTIVE => 'In Active',
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_DELETE => 'Deleted',

        ];
    }
    public function getStateOptionsBadges()
    {

        if ($this->status == self::STATUS_ACTIVE) {
            return '<span class="badge badge-success">Active</span>';
        } elseif ($this->status == self::STATUS_INACTIVE) {
            return '<span class="badge badge-default">In Active</span>';
        }elseif ($this->status == self::STATUS_DELETE) {
            return '<span class="badge badge-danger">Deleted</span>';
        }

    }

    public function getFeatureOptions()
    {
        return [

            self::IS_FEATURED => 'Is Featured',
            self::IS_NOT_FEATURED => 'Not Featured',
           
        ];
    }

    public function getFeatureOptionsBadges()
    {
        if ($this->is_featured == self::IS_FEATURED) {
            return '<span class="badge badge-success">Featured</span>';
        } elseif ($this->is_featured == self::IS_NOT_FEATURED) {
            return '<span class="badge badge-danger">Not Featured</span>';
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'campus_id' => Yii::t('app', 'Campus ID'),
            'certificate_name' => Yii::t('app', 'Certificate Name'),
            'header_left_text' => Yii::t('app', 'Header Left Text'),
            'header_center_text' => Yii::t('app', 'Header Center Text'),
            'header_right_text' => Yii::t('app', 'Header Right Text'),
            'body_text' => Yii::t('app', 'Body Text'),
            'footer_right_text' => Yii::t('app', 'Footer Right Text'),
            'right_sig' => Yii::t('app', 'Right Sig'),
            'certificate_design' => Yii::t('app', 'Certificate Design'),
            'header_height' => Yii::t('app', 'Header Height'),
            'footer_height' => Yii::t('app', 'Footer Height'),
            'body_height' => Yii::t('app', 'Body Height'),
            'body_width' => Yii::t('app', 'Body Width'),
            'background_image' => Yii::t('app', 'Background Image'),
            'template_type' => Yii::t('app', 'Template Type'),
            'status' => Yii::t('app', 'Status'),
            'created_on' => Yii::t('app', 'Created On'),
            'updated_on' => Yii::t('app', 'Updated On'),
            'created_user_id' => Yii::t('app', 'Created User ID'),
            'updated_user_id' => Yii::t('app', 'Updated User ID'),
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCampus()
    {
        return $this->hasOne(\app\modules\admin\models\Campus::className(), ['id' => 'campus_id']);
    }
    
    /**
     * @inheritdoc
     * @return array mixed
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_on',
                'updatedAtAttribute' => 'updated_on',
                'value' => new \yii\db\Expression('NOW()'),
            ],
            'blameable' => [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'created_user_id',
                'updatedByAttribute' => 'updated_user_id',
            ],
            'uuid' => [
                'class' => UUIDBehavior::className(),
                'column' => 'id',
            ],
        ];
    }



    /**
     * @inheritdoc
     * @return \app\modules\documentgenerator\models\BonafideCertificateQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\documentgenerator\models\BonafideCertificateQuery(get_called_class());
    }
public function asJson(){
    $data = [] ; 
            $data['id'] =  $this->id;
        
                $data['campus_id'] =  $this->campus_id;
        
                $data['certificate_name'] =  $this->certificate_name;
        
                $data['header_left_text'] =  $this->header_left_text;
        
                $data['header_center_text'] =  $this->header_center_text;
        
                $data['header_right_text'] =  $this->header_right_text;
        
                $data['body_text'] =  $this->body_text;
        
                $data['footer_right_text'] =  $this->footer_right_text;
        
                $data['right_sig'] =  $this->right_sig;
        
                $data['certificate_design'] =  $this->certificate_design;
        
                $data['header_height'] =  $this->header_height;
        
                $data['footer_height'] =  $this->footer_height;
        
                $data['body_height'] =  $this->body_height;
        
                $data['body_width'] =  $this->body_width;
        
                $data['background_image'] =  $this->background_image;
        
                $data['template_type'] =  $this->template_type;
        
                $data['status'] =  $this->status;
        
                $data['created_on'] =  $this->created_on;
        
                $data['updated_on'] =  $this->updated_on;
        
                $data['created_user_id'] =  $this->created_user_id;
        
                $data['updated_user_id'] =  $this->updated_user_id;
        
            return $data;
}


}


