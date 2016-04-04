<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use app\models\Ticket;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app','ticket list'), 'url' => ['#']];
$this->params['addUrl'] = 'ticket/new';
?>
<div class="row">
    <div class="col-lg-12">						
	<!-- START YOUR CONTENT HERE -->
	<div class="portlet"><!-- /Portlet -->
	    <div class="portlet-heading dark">
		<div class="portlet-title">
		     <h4><i class="fa fa-newspaper-o"></i> <?=Yii::t("app","my ticket") ?></h4>
		</div>
		<div class="portlet-widgets">
		    <a data-toggle="collapse" data-parent="#accordion" href="#basic"><i class="fa fa-chevron-down"></i></a>
                        <span class="divider"></span>
			<a href="#" class="box-close"><i class="fa fa-times"></i></a>
		</div>
		<div class="clearfix"></div>
	    </div>
	    
            <div id="basic" class="panel-collapse collapse in">
		<div class="portlet-body">
		    <div class="row">
		    <div class="log-lg-12">
		    	<?=Yii::$app->session->getFlash("message")?>
		    </div>
			
			
		   
			<div class="col-lg-12">
			<?=GridView::widget( [
			    'dataProvider' => $dataProvider,
			    //'filterModel'  => $model,
			    'tableOptions' => ['class'=>'table table-bordered table-hover tc-table table-responsive'],
			    'layout' => '<div class="hidden-sm hidden-xs hidden-md">{summary}</div>{errors}{items}<div class="pagination pull-right">{pager}</div> <div class="clearfix"></div>',
			    'columns'=>[
				['class' => 'yii\grid\SerialColumn'],
				'ticket_id' => [
				    'attribute' => 'ticket_id',
				    'footer' => Yii::t('app','id'),
				    'headerOptions' => ['class'=>'hidden-xs hidden-sm'],
				    'contentOptions'=> ['class'=>'hidden-xs hidden-sm'],
				    'footerOptions' => ['class'=>'hidden-xs hidden-sm'],
				],
				'ticket_date' => [
				    'attribute' => 'ticket_date',
				    'footer' => Yii::t('app','date'),
				],	
				'ticket_subject' => [
				    'attribute' => 'ticket_subject',
				    'footer' => Yii::t('app','subject'),
				],
				'ticket_status_string' => [
				    'attribute' => 'ticket_status_string',
				    'footer' => Yii::t('app','status'),
				    'headerOptions' => ['class'=>'hidden-xs hidden-sm'],
				    'contentOptions'=> ['class'=>'hidden-xs hidden-sm'],
				    'footerOptions' => ['class'=>'hidden-xs hidden-sm'],
				],
				
				'helpdesk_name' => [
				    'attribute' => 'helpdesk_name',
				    'footer' => Yii::t('app','support'),
				],	
				
				
			    ],
			    'showFooter' => true ,
			] );?>
			</div>
		    
		     </div>
		    
		</div>
	    </div>
	</div><!--/Portlet -->
    </div>  <!-- Enf of col lg-->                                      
</div> <!-- ENd of row -->

<script>
	//for tables checkbox dem
    jQuery(function($) {
    	$('.input-group.date').datepicker({
            autoclose : true,
         	format: "dd/mm/yyyy"
       	});
       	    
		$('table th input:checkbox').on('click' , function(){
		    var that = this;
		    $(this).closest('table').find('tr > td:first-child input:checkbox')
		    .each(function(){
			this.checked = that.checked;
			$(this).closest('tr').toggleClass('selected');
		    });
							
		});
	
		$('.btn-pwd').click(function (e) {
		    if (!confirm('<?=Yii::t('app/message','msg btn password')?>')) return false;
		    return true;
		});
	
		$('.btn-delete').click(function (e) {
		    if (!confirm('<?=Yii::t('app/message','msg btn delete')?>')) return false;
		    return true;
		});
    });
</script>