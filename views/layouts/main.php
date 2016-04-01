<?php
use app\assets\AppAsset;
use app\assets\AppAssetIE8;
use app\assets\AppAssetIE9;
use yii\helpers\Url;
use yii\helpers\Html;
//use backend\components\Auth;
use yii\widgets\Breadcrumbs;
AppAsset::register($this);
AppAssetIE8::register($this);
AppAssetIE9::register($this);
$totalLog = count(\app\models\TicketLog::getNewTicketLogData());
$totalLog2 =count(\app\models\Ticket::getHelpdeskTicketData(3));
$totalLog3 =count(\app\models\Ticket::getHelpdeskNewTicketData(4));
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
  <head>
    <meta charset="utf-8">
    <title><?=Yii::t('app','page title').' '.$this->title?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
 <?php $this->head() ?>	
</head>
<body>
<?php $this->beginBody() ?>   
  <div id="wrapper">
    <div id="main-container">		
      <!-- BEGIN TOP NAVIGATION -->
      <nav class="navbar-top" role="navigation">
	<!-- BEGIN BRAND HEADING -->
	<div class="navbar-header">
	  <button type="button" class="navbar-toggle pull-right" data-toggle="collapse" data-target=".top-collapse">
	    <i class="fa fa-bars"></i>
	  </button>
	  <div class="navbar-brand">
	    <a href="<?=Url::to(['/'])?>"><img src="assets/images/logo.png" alt="logo" class="img-responsive"></a>
	  </div>
	</div>
	<!-- END BRAND HEADING -->
	
	<!-- BEGIN NAV TOP NAVIGATION -->				
	<div class="nav-top">
	  <!-- BEGIN RIGHT SIDE DROPDOWN BUTTONS -->
	  <ul class="nav navbar-right">
	    <li class="dropdown">
		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
		  <i class="fa fa-bars"></i>
		</button>
	    </li>
	    <?php if(!Yii::$app->user->isGuest){ ?>
	    <li class="dropdown">
		<a title="<?=Yii::t('app','replies')?>"  href="#" class="dropdown-toggle" data-toggle="dropdown">
		  <i class="fa fa-envelope"></i> <?php if($totalLog>0){ ?><span class="badge up badge-primary"><?=$totalLog?></span><?php } ?></a>
		    <ul class="dropdown-menu dropdown-scroll dropdown-messages">
			<li class="dropdown-header"><i class="fa fa-envelope"></i> <?=$totalLog?> <?=Yii::t('app','replies')?></li>
			<li id="messageScroll">
			  <ul class="list-unstyled">
			   <?php foreach(\app\models\TicketLog::getNewTicketLogData() as $msg){ ?>
			    <li>
			     <a href="<?=Url::to(['ticket/open','id'=>$msg->ticket_id])?>">
				<div class="row">
				  <div class="col-xs-12">
				    <p>
				      <strong><?=$msg->EmployeeFirstName?></strong>: <?=$msg->log_desc?>
				    </p>
				    <p class="small">
				      <i class="fa fa-clock-o"></i> <?=\app\components\Common::timeAgo(strtotime($msg->log_date))?>
				    </p>
				  </div>
				</div>
			      </a>
			    </li>
			    <?php } ?>
			  </ul>
			</li>
			<li class="dropdown-footer">
			  <a href="<?=Url::to(['ticket/myticket'])?>"><?=Yii::t('app','read all')?></a>
			</li>
		      </ul>
	      </li>
	      <?php if(Yii::$app->session->get('helpdesk')){ ?>
	      <li class="dropdown">
		<a title="<?=Yii::t('app','open ticket')?>"  href="#" class="dropdown-toggle" data-toggle="dropdown">
		  <i class="fa fa-bell"></i> <?php if($totalLog2>0){?><span class="badge up badge-primary"><?=$totalLog2?></span><?php } ?></a>
		    <ul class="dropdown-menu dropdown-scroll dropdown-messages">
			<li class="dropdown-header"><i class="fa fa-envelope"></i> <?=$totalLog2?> <?=Yii::t('app','new list ticket')?></li>
			<li id="messageScroll">
			  <ul class="list-unstyled">
			   <?php foreach(\app\models\Ticket::getHelpdeskTicketData(3) as $row){ ?>
			    <li>
			      <a href="<?=Url::to(['ticket/open','id'=>$row->ticket_id])?>">
				<div class="row">
				  <div class="col-xs-12">
				    <p>
				      <strong><?=$row->EmployeeFirstName?></strong>: <?=$row->ticket_subject?>
				    </p>
				    <p class="small">
				      <i class="fa fa-clock-o"></i> <?=\app\components\Common::timeAgo(strtotime($row->ticket_cdate))?>
				    </p>
				  </div>
				</div>
			      </a>
			    </li>
			    <?php } ?>
			  </ul>
			</li>
			<li class="dropdown-footer">
			  <a href="<?=Url::to(['ticket/management'])?>"><?=Yii::t('app','read all')?></a>
			</li>
		      </ul>
	      </li>
	      <li class="dropdown">
		<a title="<?=Yii::t('app','new list ticket')?>"  href="#" class="dropdown-toggle" data-toggle="dropdown">
		  <i class="fa fa-exchange"></i> <?php if($totalLog3>0){ ?><span class="badge up badge-primary"><?=$totalLog3?></span><?php } ?></a>
		    <ul class="dropdown-menu dropdown-scroll dropdown-messages">
			<li class="dropdown-header"><i class="fa fa-envelope"></i> <?=$totalLog3?> <?=Yii::t('app','new list ticket')?></li>
			<li id="messageScroll">
			  <ul class="list-unstyled">
			   <?php foreach(\app\models\Ticket::getHelpdeskNewTicketData(4) as $row){ ?>
			    <li>
			      <a href="<?=Url::to(['ticket/set','id'=>$row->ticket_id])?>">
				<div class="row">
				  <div class="col-xs-12">
				    <p>
				      <strong><?=$row->EmployeeFirstName?></strong>: <?=$row->ticket_subject?>
				    </p>
				    <p class="small">
				      <i class="fa fa-clock-o"></i> <?=\app\components\Common::timeAgo(strtotime($row->ticket_cdate))?>
				    </p>
				  </div>
				</div>
			      </a>
			    </li>
			    <?php } ?>
			  </ul>
			</li>
			<li class="dropdown-footer">
			  <a href="<?=Url::to(['ticket/management'])?>"><?=Yii::t('app','read all')?></a>
			</li>
		      </ul>
	      </li>
	      <?php } ?>
	      <!--Speech Icon-->
	      <li class="dropdown">
		<a href="#" class="speech-button">
		  <i class="fa fa-microphone"></i>
		</a>
	      </li>
	      <!--Speech Icon-->
	      <li class="dropdown user-box">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown">
		  <img class="img-circle" src="assets/images/user.jpg" alt=""> <span class="user-info"><?=Yii::$app->user->identity->EmployeeFirstName?></span> <b class="caret"></b>
		</a>
		<ul class="dropdown-menu dropdown-user">
		  <li><?=Html::a('<i class="fa fa-wrench"></i> '.Yii::t('app','general setting'),['administration/general'])?></li>
		  <li><?=Html::a('<i class="fa fa-key"></i> '.Yii::t('app','change password'),['administration/password'])?></li>
		  <li><?=Html::a('<i class="fa fa-exchange"></i> '.Yii::t('app','my ticket'),['ticket/index'])?></li>
		  <li><?=Html::a('<i class="fa fa-power-off"></i> '.Yii::t('app','logout'),['site/logout'])?></li>
		</ul>
	      </li>						
	    </ul>
	    <!-- END RIGHT SIDE DROPDOWN BUTTONS -->
	    <?php } ?>					
	    <!-- BEGIN TOP MENU -->
	    <div class="collapse navbar-collapse top-collapse">
	    <!-- .nav -->
	    <?php if(!Yii::$app->user->isGuest){ ?>
	    <ul class="nav navbar-left navbar-nav">
		<li><a href="<?=Url::to(['ticket/new'])?>"><i class="fa fa-plus"></i> <?=Yii::t('app','new ticket')?></a></li>
		<li class="dropdown">
		  <a href="#" class="dropdown-toggle" data-toggle="dropdown">
		    <?=Yii::t('app','navigation')?> <b class="caret"></b>
		  </a>
		  <ul class="dropdown-menu">
		    <li><a href="<?=Url::to(['ticket/new'])?>"><i class="fa fa-plus"></i><?=Yii::t('app','new ticket')?></a></li>
		    <li><a href="<?=Url::to(['ticket/index'])?>"> <i class="fa fa-list"></i> <?=Yii::t('app','my ticket')?></a></li>
		    <li><a href="<?=Url::to(['administration/general'])?>"> <i class="fa fa-wrench"></i> <?=Yii::t('app','general setting')?></a></li>
		    <li><a href="<?=Url::to(['administration/password'])?>"> <i class="fa fa-key"></i> <?=Yii::t('app','change password')?></a></li>
		  </ul>
	      </li>
	      <li><a href="http://www.bdo.co.id" target="_blank">Visit Bdo.co.id </a></li>
	    </ul><!-- /.nav -->
	    <?php } ?>
	  </div>
	  <!-- END TOP MENU -->
	</div><!-- /.nav-top -->
      </nav><!-- /.navbar-top -->
    <!-- END TOP NAVIGATION -->

				
				<!-- BEGIN SIDE NAVIGATION -->				
				<nav class="navbar-side" role="navigation">							
					<div class="navbar-collapse sidebar-collapse collapse">
					
						<!-- BEGIN SHORTCUT BUTTONS -->
						<div class="media">							
							<?php if(!Yii::$app->user->isGuest){ ?>
							<ul class="sidebar-shortcuts">
								<li><a title="<?=Yii::t('app','new ticket')?>" href="<?=Url::to(['ticket/new'])?>"  class="btn"><i class="fa fa-plus icon-only"></i></a></li>
								<li><a title="<?=Yii::t('app','my ticket')?>"  href="<?=Url::to(['ticket/index'])?>"class="btn"><i class="fa fa-list icon-only"></i></a></li>
								<li><a title="<?=Yii::t('app','general setting')?>"  href="<?=Url::to(['administration/general'])?>"class="btn"><i class="fa fa-wrench icon-only"></i></a></li>
								<li><a title="<?=Yii::t('app','change password')?>"  href="<?=Url::to(['administration/password'])?>"class="btn"><i class="fa fa-key icon-only"></i></a></li>
							</ul>
							<?php } ?>	
						</div>
						<!-- END SHORTCUT BUTTONS -->	
						
						<?php if(Yii::$app->session->get('helpdesk') && Yii::$app->user->getId() ){?>
						<?=\app\components\NavMenuWidget::widget([
						    'menu'=>[
							[
							  'label' => Yii::t('app','ticket'),
							  'url'   => 'ticket',
							  'icon'  => 'fa fa-file',
							  'sub'   => [
							    [
							      'label'=>Yii::t('app','new ticket'),
							      'url'  => 'ticket/order',
							      'icon' => 'fa fa-plus'
							    ],
							    [
							      'label'=>Yii::t('app','list new ticket'),
							      'url'  => 'ticket/management',
							      'icon' => 'fa fa-list'
							    ],
							    [
							      'label'=>Yii::t('app','my ticket'),
							      'url'  => 'ticket/myjob',
							      'icon' => 'fa fa-exchange'
							    ],
							    [
							      'label'=>Yii::t('app','all ticket'),
							      'url'  => 'ticket/list',
							      'icon'  => 'fa fa-file',
							    ],
								
							  ]
							],
							[
							  'label' => Yii::t('app','graphic report'),
							  'url'   => 'ticket',
							  'icon'  => 'fa fa-file',
							  'sub'   => [
							    [
							      	'label'=>Yii::t('app','performance report'),
							      	'url'  => 'report/performance',
							      	'icon' => 'fa fa-area-chart'
							    ],
							  	[
							  		'label'=>Yii::t('app','ticket category report'),
							  		'url'  => 'report/category',
							  		'icon' => 'fa fa-area-chart'
							  	],
							    	
							  ]
							],
							[
							  'url' => '#',
							  'label'=> Yii::t('app','preference'),
							  'icon' => 'fa fa-wrench',
							  'sub'   => [
							    [
							      'label'=> Yii::t('app','helpdesk'),
							      'url'  => 'administration/helpdesk',
							      'icon' => 'fa fa-user'
							    ],
							    [
							      'label'=> Yii::t('app','add helpdesk'),
							      'url'  => 'administration/helpdesk_add',
							      'icon' => 'fa fa-plus'
							    ],
								
							  ]
							],
						    ]
						])?>
						<?php } elseif(Yii::$app->user->getId()) { ?>
						<?=\app\components\NavMenuWidget::widget([
						    'menu'=>[
							[
							  'label' => Yii::t('app','ticket'),
							  'url'   => 'ticket',
							  'icon'  => 'fa fa-file',
							  'sub'   => [
							    [
							      'label'=>Yii::t('app','new ticket'),
							      'url'  => 'ticket/new',
							      'icon' => 'fa fa-plus'
							    ],
							  	[
							  		'label'=>Yii::t('app','ticket list'),
							  		'url'  => 'ticket/request',
							  		'icon' => 'fa fa-list'
							  	],
							    [
							      'label'=>Yii::t('app','my ticket'),
							      'url'  => 'ticket/index',
							      'icon' => 'fa fa-list'
							    ],
								
							  ]
							],
							
						    ]
						])?>
						<?php } ?>
						
							
							
					</div><!-- /.navbar-collapse -->
				</nav><!-- /.navbar-side -->
			<!-- END SIDE NAVIGATION -->
				

			<!-- BEGIN MAIN PAGE CONTENT -->
				<div id="page-wrapper">
					<!-- BEGIN PAGE HEADING ROW -->
						<div class="row" style="margin-bottom:10px">
							<div class="col-lg-12">
								<!-- BEGIN BREADCRUMB -->
								<div class="breadcrumbs">
								  <?= Breadcrumbs::widget([
								      'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
								  ]) ?>
									
									<?php if(!Yii::$app->user->isGuest) {?>
									<div class="b-right hidden-xs">
										<ul>
											<li><a href="#" title=""><i class="fa fa-signal"></i></a></li>
											<li><a href="#" title=""><i class="fa fa-comments"></i></a></li>
											<li class="dropdown"><a href="#" title="" data-toggle="dropdown"><i class="fa fa-exchange"></i><span> Tasks</span></a>
												<ul class="dropdown-menu dropdown-primary dropdown-menu-right">
													<li><a href="<?=Url::to([$this->params['addUrl']])?>"><i class="fa fa-plus"></i>  <?=Yii::t('app','add new')?></a></li>
													<li><a href="<?=Url::to(['ticket/myticket'])?>"><i class="fa fa-exchange"></i>  <?=Yii::t('app','my ticket')?></a></li>
													
												</ul>
											</li>
										</ul>
									</div>
									<?php } ?>
								</div>
								<!-- END BREADCRUMB -->	
								
								
								
							</div><!-- /.col-lg-12 -->
						</div><!-- /.row -->
						
					<!-- END PAGE HEADING ROW -->					
						<div class="row">
						   <?=$content;?>	
						</div>
						
					<!-- BEGIN FOOTER CONTENT -->		
						<div class="footer">
							<div class="footer-inner">
								<!-- basics/footer -->
								<div class="footer-content">
									<?=Yii::$app->params['copyright']?>
								</div>
								<!-- /basics/footer -->
							</div>
						</div>
						<button type="button" id="back-to-top" class="btn btn-primary btn-sm back-to-top">
							<i class="fa fa-angle-double-up icon-only bigger-110"></i>
						</button>
					<!-- END FOOTER CONTENT -->
						
				</div><!-- /#page-wrapper -->	  
			<!-- END MAIN PAGE CONTENT -->
		</div>  
	</div>
<?php $this->endBody() ?>      
  </body>
</html>
<?php $this->endPage() ?>

