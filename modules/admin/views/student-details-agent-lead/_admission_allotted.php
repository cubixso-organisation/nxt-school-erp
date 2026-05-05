

  <?php
  use yii\helpers\Html;
  use kartik\export\ExportMenu;
  use app\models\User;
  use app\modules\admin\models\base\StudentDetailsAgentLead;


  use kartik\grid\GridView;

  $gridColumn_admission_ok = [
      ['class' => 'yii\grid\SerialColumn'],

      ['attribute' => 'id', 'visible' => false],


      [
              'attribute' => 'agent_id',
              'label' => Yii::t('app', 'Agent'),
              'value' => function ($model) {
                  return $model->agent->first_name;
              },
              'filterType' => GridView::FILTER_SELECT2,
              'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\User::find()->asArray()->all(), 'id', 'first_name'),
              'filterWidgetOptions' => [
                  'pluginOptions' => ['allowClear' => true],
              ],
              'filterInputOptions' => ['placeholder' => 'Agent Name', 'id' => 'grid-student-details-agent-lead-search-agent_id']
          ],

 

      'student_name',

      'gender',

      'date_of_birth',

      'name_of_the_parent',

      'phone_number',






      [
          'attribute' => 'verified_phone',
          'format' => 'raw',
          'value' => function ($model) {
              return $model->getStatePhoneNumberVerified();
          },


      ],


      'previous_school_name:ntext',

      'previous_school_address',

      [
              'attribute' => 'student_class_id',
              'label' => Yii::t('app', 'Student Class'),
              'value' => function ($model) {
                  return isset($model->studentClass->title)? $model->studentClass->title:'';
              },
              'filterType' => GridView::FILTER_SELECT2,
              'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\StudentClass::find()->asArray()->all(), 'id', 'title'),
              'filterWidgetOptions' => [
                  'pluginOptions' => ['allowClear' => true],
              ],
              'filterInputOptions' => ['placeholder' => 'Student class', 'id' => 'grid-student-details-agent-lead-search-student_class_id']
          ],




          [
              'attribute' => 'amount',
              'label' => Yii::t('app', 'amount'),
              'value' => function ($model) {
                  if (!empty($model->agentStudentJoins[0]->amount)) {
                      return $model->agentStudentJoins[0]->amount;
                  }
              },
              'filterType' => GridView::FILTER_SELECT2,
              'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\AgentStudentJoin::find()->asArray()->all(), 'id', 'amount'),
              'filterWidgetOptions' => [
                  'pluginOptions' => ['allowClear' => true],
              ],
              'filterInputOptions' => ['placeholder' => 'paid amount', 'id' => 'grid-student-details-agent-lead-search-amount']
          ],





      [
          'attribute' => 'special_courses_id',
          'label' => Yii::t('app', 'Student Special Course'),
          'value' => function ($model) {
              return !empty($model->specialCourses->course_name)??$model->specialCourses->course_name;
          },
          'filterType' => GridView::FILTER_SELECT2,
          'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\SpecialCourses::find()->asArray()->all(), 'id', 'course_name'),
          'filterWidgetOptions' => [
              'pluginOptions' => ['allowClear' => true],
          ],
          'filterInputOptions' => ['placeholder' => 'Student Special Courses', 'id' => 'grid-student-details-agent-lead-search-special_courses_id']
      ],


      [
          'attribute' => 'hostal_is_required',
          'format' => 'raw',
          'value' => function ($model) {
              return $model->getStateHostel();
          },


      ],





      [
          'attribute' => 'bus_transport_required',
          'format' => 'raw',
          'value' => function ($model) {
              return $model->getStateTransport();
          },


      ],




  ];
  ?>
    <?= GridView::widget([
      'dataProvider' => $dataProvider_status_status_admission_ok,
      'filterModel' => $searchModel,
      'columns' => $gridColumn_admission_ok,
      'pjax' => true,
      'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-student-details-agent-lead']],
      'panel' => [
          'type' => GridView::TYPE_PRIMARY,
          'heading' => '<span class="glyphicon glyphicon-book"></span>  ' . Html::encode($this->title),
      ],
      'export' => false,
      // your toolbar can include the additional full export menu
      'toolbar' => [
          '{export}',
          ExportMenu::widget([
              'dataProvider' => $dataProvider_status_status_admission_ok,
              'columns' => $gridColumn_admission_ok,
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




