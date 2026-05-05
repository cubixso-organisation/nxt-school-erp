<?php

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\search\AdmissionEnquirieSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use yii\helpers\Html;
use kartik\export\ExportMenu;
use app\models\User;
use app\modules\admin\models\base\Banner;
use kartik\grid\GridView;

$this->title = Yii::t('app', 'Admission Enquiries');
$this->params['breadcrumbs'][] = $this->title;
$search = "$('.search-button').click(function(){
	$('.search-form').toggle(1000);
	return false;
});";
$this->registerJs($search);
 

?>
<div class="admission-enquirie-index">
<div class="card">
    <div class="card-body text-center">
        <?php if (isset($data['formLink']) && !empty($data['formLink'])): ?>
            <div class="d-flex align-items-center justify-content-center gap-3">
                <!-- Display QR Code -->
                

                <!-- Buttons -->
                <div>
                    <a href="<?= htmlspecialchars($data['formLink']) ?>" class="btn btn-primary" target="_blank">
                        Generate Admission Form
                    </a>
                </div>
                <div>
                    <button class="btn btn-secondary" onclick="copyLink()">Copy Link</button>
                </div>
                <div>
                    <button class="btn btn-success" id="download-qr">Download QR as Image</button>
                </div>
                <div>
                    <canvas id="qr-code-display" style="display: block; margin: auto; width: 150px; height: 150px;"></canvas>
                </div>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
            <script>
                const formLink = "<?= htmlspecialchars($data['formLink']) ?>";
                const displayCanvas = document.getElementById('qr-code-display');
                const downloadCanvas = document.createElement('canvas'); // Hidden canvas for download

                // Set size for download canvas (300px)
                downloadCanvas.width = 300;
                downloadCanvas.height = 300;

                // Generate the QR code for display (150px)
                QRCode.toCanvas(displayCanvas, formLink, { width: 150 }, function (error) {
                    if (error) console.error(error);
                });

                // Generate the QR code for download (300px)
                QRCode.toCanvas(downloadCanvas, formLink, { width: 300 }, function (error) {
                    if (error) console.error(error);
                });

                // Add event listener to download the QR code as an image
                document.getElementById('download-qr').addEventListener('click', function () {
                    const imageData = downloadCanvas.toDataURL('image/png'); // Get image data from the hidden canvas
                    const link = document.createElement('a'); // Create a temporary link element
                    link.href = imageData; // Set the image data as the link's href
                    link.download = 'qr-code.png'; // Set the filename for the downloaded image
                    link.click(); // Trigger the download
                });

                // Copy Link to Clipboard
                function copyLink() {
                    navigator.clipboard.writeText(formLink).then(() => {
                        alert("Link copied to clipboard!");
                    }).catch((error) => {
                        console.error("Failed to copy link:", error);
                    });
                }
            </script>
        <?php else: ?>
            <p>No form link available.</p>
        <?php endif; ?>
    </div>
</div>








    
    <div class="card">
       <div class="card-body">
<?php 
    $gridColumn = [
        ['class' => 'yii\grid\SerialColumn'],
   
        ['attribute' => 'id', 'visible' => false],
   
        // [
        //         'attribute' => 'campus_id',
        //         'label' => Yii::t('app', 'Campus'),
        //         'value' => function($model){                   
        //             return $model->campus->id;                   
        //         },
        //         'filterType' => GridView::FILTER_SELECT2,
        //         'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\Campus::find()->asArray()->all(), 'id', 'id'),
        //         'filterWidgetOptions' => [
        //             'pluginOptions' => ['allowClear' => true],
        //         ],
        //         'filterInputOptions' => ['placeholder' => 'Campus', 'id' => 'grid-admission-enquirie-search-campus_id']
        //     ],
   
        'student_name',
   
        'parent_name',
   
        'contact_no',
   
        'next_class',
   
        'previous_class',
   
        'dob',
   
        'address:ntext',
   
        [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function($model){                   
                    return $model->getStateOptionsBadges();                   
                },
               
               
            ],
        [
            'class' => 'kartik\grid\ActionColumn',
             'template' => '{view} {update} {delete}',
             'buttons' => [
            'view'=> function($url,$model) {
            if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_MANAGER) {
                    return Html::a( '<span class="fas fa-eye" aria-hidden="true"></span>', $url);
                } 
                },
            'update'=> function($url,$model) {
            if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_MANAGER) {
                    return Html::a( '<span class="fas fa-pencil-alt" aria-hidden="true"></span>', $url);

                } 
                },
            'delete'=> function($url,$model) {
            if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
                    return Html::a( '<span class="fas fa-trash-alt" aria-hidden="true"></span>', $url,[
                        'data' => [
                                    'method' => 'post',
                                     // use it if you want to confirm the action
                                     'confirm' => 'Are you sure?',
                                 ],
                                ]);
                } 
                },


        ]
            
           

        ],
    ];   
    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $gridColumn,
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-admission-enquirie']],
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<span class="glyphicon glyphicon-book"></span>  ' . Html::encode($this->title),
        ],
        'export' => false,
        // your toolbar can include the additional full export menu
        'toolbar' => [
            '{export}',
            ExportMenu::widget([
                'dataProvider' => $dataProvider,
                'columns' => $gridColumn,
                'target' => ExportMenu::TARGET_BLANK,
                'fontAwesome' => true,
                'dropdownOptions' => [
                    'label' => 'Full',
                    'class' => 'btn btn-default',
                    'itemsBefore' => [
                        '<li class="dropdown-header">Export All Data</li>',
                    ],
                ],
                'exportConfig' => [
                    ExportMenu::FORMAT_PDF => false
                ]
            ]) ,
        ],
    ]); ?>
</div>
</div>
</div>

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
$(document).on('change','select[id^=status_list_]',function(){
var id=$(this).attr('data-id');
var val=$(this).val();

$.ajax({
	  type: "POST",
	 
      url: "/estudent_backend/gii/default/status-change",
     
 
      data: {id:id,val:val},
	  success: function(data){
		  swal("Good job!", "Status Successfully Changed!", "success");
	  }
	});
});


</script>