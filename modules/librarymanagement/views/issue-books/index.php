<?php

/* @var $this yii\web\View */
/* @var $searchModel app\modules\librarymanagement\models\search\IssueBooksSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use kartik\export\ExportMenu;
use app\models\User;
use app\modules\admin\models\base\Banner;
use app\modules\librarymanagement\models\base\IssueBooks;
use kartik\grid\GridView;
use yii\helpers\Url;

$this->title = Yii::t('app', 'Issue Books');
$this->params['breadcrumbs'][] = $this->title;
$search = "$('.search-button').click(function(){
	$('.search-form').toggle(1000);
	return false;
});";
$this->registerJs($search);


?>
<div class="issue-books-index">
    <div class="card">
        <div class="card-body">

            <?php // echo $this->render('_search', ['model' => $searchModel]); 
            ?>

            <p>
                <?php if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_LIBRARIAN) { ?>
                    <?= Html::a(Yii::t('app', 'Issue Books'), ['create'], ['class' => 'btn btn-success']) ?>
                <?php  } ?>
                <!-- <?= Html::a(Yii::t('app', 'Advance Search'), '#', ['class' => 'btn btn-info search-button']) ?> -->
            </p>
            <div class="search-form" style="display:none">
                <?= $this->render('_search', ['model' => $searchModel]); ?>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <?php
            $gridColumn = [
                ['class' => 'yii\grid\SerialColumn'],

                ['attribute' => 'id', 'visible' => false],

                [
                    'attribute' => 'library_member_id',
                    'label' => Yii::t('app', 'Issued To'),
                    'value' => function ($model) {
                        if ($model->libraryMember) {
                            return $model->libraryMember->name;
                        } else {
                            return null;
                        }
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => \yii\helpers\ArrayHelper::map(\app\modules\librarymanagement\models\LibraryMembers::find()->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])->asArray()->all(), 'id', 'name'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Issued to', 'id' => 'grid-issue-books-search-library_member_id'],
                ],

                [
                    'attribute' => 'book_id',
                    'label' => Yii::t('app', 'Book'),
                    'value' => function ($model) {
                        return $model->book->book_title;
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => \yii\helpers\ArrayHelper::map(\app\modules\librarymanagement\models\LibraryBooks::find()->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])->asArray()->all(), 'id', 'book_title'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Library books', 'id' => 'grid-issue-books-search-book_id']
                ],

                [
                    'attribute' => 'author',
                    'label' => Yii::t('app', 'Author'),
                    'value' => function ($model) {
                        return $model->author;
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => \yii\helpers\ArrayHelper::map(\app\modules\librarymanagement\models\LibraryBooks::find()->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])->asArray()->all(), 'id', 'author'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Library books', 'id' => 'grid-issue-books-search-author']
                ],

                [
                    'attribute' => 'subject_code',
                    'label' => Yii::t('app', 'Subject Code'),
                    'value' => function ($model) {
                        return $model->subject_code;
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => \yii\helpers\ArrayHelper::map(\app\modules\librarymanagement\models\LibraryBooks::find()->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])->asArray()->all(), 'id', 'subject'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Library books', 'id' => 'grid-issue-books-search-subject_code']
                ],

                [
                    'attribute' => 'serial_no',
                    'label' => Yii::t('app', 'Serial No'),
                    'value' => function ($model) {
                        return $model->serial_no;
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => \yii\helpers\ArrayHelper::map(\app\modules\librarymanagement\models\LibraryBooks::find()->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])->asArray()->all(), 'id', 'book_number'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Library books', 'id' => 'grid-issue-books-search-serial_no']
                ],
                'library_id',
                

                'issued_date',
                'due_date',
                [
                    'attribute' => 'updated_on',
                    'label' => Yii::t('app', 'Returned On'),
                    'format' => 'raw',
                    'value' => function ($model) {
                        return $model->getReturnDate();
                    },


                ],

                'note',
                // [
                //     'attribute' => 'status',
                //     'format' => 'raw',
                //     'value' => function($model){                   
                //         return $model->getStateOptionsBadges();                   
                //     },


                // ],
                [
                    'attribute' => 'status',
                    'filter'  => (new IssueBooks)->getStateOptions(),
                    "format" => 'raw',
                    'value' => function ($data) {
                        $html = '';


                        $html .= '<select id="status_list_' . $data->id . '" data-id="' . $data->id . '" ' . ($data->status == 2 ? 'disabled' : '') . '>';

                        $lists = $data->getStateOptions();

                        foreach ($lists as $key => $list) {

                            if ($key == $data->status) {
                                $html .= '<option value="' . $key . '" selected>' . $list . '</option>';
                            } else {
                                $html .= '<option value="' . $key . '">' . $list . '</option>';
                            }
                        }
                        $html .= '</select>';

                        return $html;
                    }
                ],
                [
                    'class' => 'kartik\grid\ActionColumn',
                    'template' => '{view}',
                    'buttons' => [
                        'view' => function ($url, $model) {
                            if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_MANAGER || \Yii::$app->user->identity->user_role == User::ROLE_LIBRARIAN) {
                                return Html::a('<span class="fas fa-eye" aria-hidden="true"></span>', $url);
                            }
                        },
                        'update' => function ($url, $model) {
                            if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_MANAGER || \Yii::$app->user->identity->user_role == User::ROLE_LIBRARIAN) {
                                return Html::a('<span class="fas fa-pencil-alt" aria-hidden="true"></span>', $url);
                            }
                        },
                        'delete' => function ($url, $model) {
                            if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_LIBRARIAN) {
                                return Html::a('<span class="fas fa-trash-alt" aria-hidden="true"></span>', $url, [
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
                'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-issue-books']],
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
                    ]),
                ],
            ]); ?>
        </div>
    </div>
</div>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    $(document).on('change', 'select[id^=status_list_]', function() {
        var id = $(this).attr('data-id');
        var val = $(this).val();

        $.ajax({
            type: "POST",
            url: "<?= Url::toRoute(['issue-books/status-change']) ?>",
            data: {
                id: id,
                val: val
            },
            success: function(data) {
                swal("Good job!", "Status Successfully Changed!", "success");


                if (val === '2') {

                    $('#status_list_' + id).prop('disabled', true);
                } else {

                    $('#status_list_' + id).prop('disabled', false);
                }
            }
        });
    });
</script>