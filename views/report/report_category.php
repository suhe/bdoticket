<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use app\models\TicketCategory;
use app\models\Ticket;
$this->params['breadcrumbs'] = [
    ['label' => Yii::t('app','report'),'url' => ['report']],
    ['label' => Yii::t('app','graphic report'),'url' => ['report/chart']]
];
$this->params['addUrl'] = 'ticket/order';
use app\miloschuman\highcharts\Highcharts;
use yii\web\JsExpression;
?>
<div class="row">
    <div class="col-lg-12">						
	<!-- START YOUR CONTENT HERE -->
            <div class="portlet">
		<div class="portlet-heading">
		    <div class="portlet-title">
			<h4 class="danger"><?=Yii::t('app','graphic report')?></h4>
		    </div>
		    
		    <div class="portlet-widgets">
			<a data-toggle="collapse" data-parent="#accordion" href="#ft-3"><i class="fa fa-chevron-down"></i></a>
		    </div>
		    <div class="clearfix"></div>
		</div>
		
                <div id="ft-3" class="panel-collapse collapse in">
		    <div class="portlet-body">
			<?php $form = ActiveForm::begin([
                        'id' => 'menu-add-form',
                        'method' => 'get',
                        'options' => ['class' => 'form-inline pull-right'],
                        'fieldConfig' => [
                            'template' => "{label}\n<div class=\"col-sm-9 search\">{input} {error}</div>\n",
                            'labelOptions' => ['class' => 'col-sm-3 control-label'],
                        ],
                        ]);?>
			
			<?=$form->field($model,'ticket_from_date',['template' => '<div class="input-group date">{input}<div class="input-group-addon"><span class="glyphicon glyphicon-th"></span> </div></div>'])->textInput(['placeholder' => Yii::t('app','date from')]);?>
			<?=$form->field($model,'ticket_to_date',['template' => '<div class="input-group date">{input}<div class="input-group-addon"><span class="glyphicon glyphicon-th"></span> </div></div>'])->textInput(['placeholder' => Yii::t('app','date to')]);?>
		    	
			<div class="form-group " >
                            <div class="col-md-offset-1 col-md-11">
                                <?=Html::submitButton(Yii::t('app','search'), ['class' => 'btn btn-primary','name' => 'search'])?>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <?php ActiveForm::end() ?>
						<div class="clearfix" style="margin-bottom:30px"></div>
			
                        <?=Highcharts::widget([
                        'scripts' => [
                            'modules/exporting',
                            'themes/grid-light',
                        ],
                        'options' => [
                            'title' => [
                                'text' => Yii::t('app','ticket category report').' '.Yii::t('app','periode').' '.(isset($params)?$model->ticket_from_date.' '.Yii::t('app','to').' '.$model->ticket_to_date:Yii::t('app','today')),
                            ],
                            'xAxis' => [
                                'categories' => TicketCategory::getLists(['ticket_from_date' => $model->ticket_from_date,'ticket_to_date' => $model->ticket_to_date]),
                            ],
                            
                            'series' => [
                                [
								    'type' => 'column',
								    'name' => Yii::t('app','total'),
								    'data' => $model->getTotalTicketByCategory($params),
								],
								
								[
								    'type' => 'spline',
								    'name' => Yii::t('app','ticket category report'),
								    'data' => $model->getTotalTicketByCategory($params),
								    'marker' => [
									'lineWidth' => 2,
									'lineColor' => new JsExpression('Highcharts.getOptions().colors[3]'),
									'fillColor' => 'green',
								    ],
								],
                                
                            ],
                        ]
                ]);?>
                         
		    </div>
		</div>
            </div>
    </div>
</div>
<style>
    .help-block{line-height:10px;}
</style>

<script>
	//for tables checkbox dem
    jQuery(function($) {
    	$('.input-group.date').datepicker({
            autoclose : true,
         	format: "dd/mm/yyyy"
       	});
       	    
    });
</script>