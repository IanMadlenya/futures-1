<?php
use Cake\ORM\TableRegistry;
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = 'CakePHP: the rapid development php framework';
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        Back To The Futures:
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>

    <?= $this->Html->css('bootstrap.min'); ?>
    <?= $this->Html->css('datepicker3'); ?>
    <?= $this->Html->css('styles.css'); ?>
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.css">
    
    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body>
	<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#sidebar-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="<?= $this->Url->build(['controller' => 'pages', 'action' => 'dashboard']); ?>"><span class="glyphicon glyphicon-fire"></span> Back<span>To</span>The<span>Futures</span></a>
				<ul class="user-menu">
					<li class="dropdown pull-right">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span> User <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="<?= $this->Url->build(['controller' => 'pages', 'action' => 'profile']); ?>"><span class="glyphicon glyphicon-user"></span> Profile</a></li>
							<li><a href="<?= $this->Url->build(['controller' => 'pages', 'action' => 'settings']); ?>"><span class="glyphicon glyphicon-cog"></span> Settings</a></li>
							<li><a href="#"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
						</ul>
					</li>
				</ul>
			</div>
		</div><!-- /.container-fluid -->
	</nav>
		
    <?php
        $numPositions = 0;
        $hedgePositions = TableRegistry::get('HedgePositions')->find('all');    
        foreach($hedgePositions as $pos) {
            if($pos->status == 1) {
                $numPositions++;
            }
        }
        
        $numThisWeek = 3;
        $numNextWeek = 1;
        $numThisQuarter = 2;
        
        $numContracts = $numThisWeek + $numNextWeek + $numThisQuarter;
    ?>
    
	<div id="sidebar-collapse" class="col-sm-3 col-lg-2 sidebar">
		<ul class="nav menu">
			<li><a href="<?= $this->Url->build(['controller' => 'pages', 'action' => 'dashboard']); ?>"><span class="glyphicon glyphicon-dashboard"></span> Dashboard</a></li>
            
            <li role="presentation" class="divider"></li>
            <li>
                <a href="<?= $this->Url->build(['controller' => 'HedgePositions', 'action' => 'index']); ?>">
                    <span class="glyphicon glyphicon-stats"></span> Hedge Positions 
                    <span style="background-color: #30a5ff" class="pull-right badge"><?= $numPositions ?></span> 
                </a>
            </li>
            <li class="parent">
				<a href="<?= $this->Url->build(['controller' => 'pages', 'action' => 'contracts']); ?>">
					<span class="glyphicon glyphicon-list"></span> Active Futures Positions <span data-toggle="collapse" href="#sub-item-1" class="icon pull-right"><em class="glyphicon glyphicon-s glyphicon-plus glyphicon-minus"></em></span>  
                </a>
				<ul class="children collapse in" id="sub-item-1">
					<li>
                        <a style="padding: 10px 15px" href="<?= $this->Url->build(['controller' => 'pages', 'action' => 'contracts_this_week']); ?>">
                            <span class="glyphicon glyphicon-share-alt"></span> Contracts This Week
                            <span style="background-color: #30a5ff;" class="pull-right badge"><?= $numThisWeek ?></span>                             
                        </a>
                        
					</li>
					<li>
						<a style="padding: 10px 15px" href="<?= $this->Url->build(['controller' => 'pages', 'action' => 'contracts_next_week']); ?>">
							<span class="glyphicon glyphicon-share-alt"></span> Contracts New Week
                            <span style="background-color: #30a5ff; " class="pull-right badge"><?= $numNextWeek ?></span> 
						</a>
					</li>
                    <li>
						<a style="padding: 10px 15px" href="<?= $this->Url->build(['controller' => 'pages', 'action' => 'contracts_this_quarter']); ?>">
							<span class="glyphicon glyphicon-share-alt"></span> Contracts This Quarter 
                            <span style="background-color: #30a5ff" class="pull-right badge"><?= $numThisQuarter ?></span> 
						</a>
					</li>
                </ul>
            </li>

            <li role="presentation" class="divider"></li>
            <li><a href="<?= $this->Url->build(['controller' => 'spot_ticker_prices', 'action' => 'graph']); ?>"><span class="glyphicon glyphicon-file"></span> Spot Tickers </a> </li>
            <li><a href="<?= $this->Url->build(['controller' => 'spot_depth_prices', 'action' => 'graph']); ?>"><span class="glyphicon glyphicon-usd"></span> Spot Market Depth  </a> </li>
            <li><a href="<?= $this->Url->build(['controller' => 'spot_trade_prices', 'action' => 'graph']); ?>"><span class="glyphicon glyphicon-sort-by-order"></span> Spot Trades </a> </li>
            <li><a href="<?= $this->Url->build(['controller' => 'spot_candlestick_prices', 'action' => 'graph']); ?>"><span class="glyphicon glyphicon-transfer"></span> Spot Candlestick Data </a> </li>

            <li role="presentation" class="divider"></li>
            <li><a href="<?= $this->Url->build(['controller' => 'future_ticker_prices', 'action' => 'graph']); ?>"><span class="glyphicon glyphicon-file"></span> Futures Tickers </a> </li>
            <li><a href="<?= $this->Url->build(['controller' => 'future_depth_prices', 'action' => 'graph']); ?>"><span class="glyphicon glyphicon-usd"></span> Futures Market Depth </a> </li>
            <li><a href="<?= $this->Url->build(['controller' => 'future_trade_prices', 'action' => 'graph']); ?>"><span class="glyphicon glyphicon-sort-by-order"></span> Futures Trades </a> </li>
            <li><a href="<?= $this->Url->build(['controller' => 'future_candlestick_prices', 'action' => 'graph']); ?>"><span class="glyphicon glyphicon-transfer"></span> Futures Candlestick Data </a> </li>

            
			<li role="presentation" class="divider"></li>
			<li><a href="<?= $this->Url->build(['controller' => 'exchanges', 'action' => 'index']); ?>"><span class="glyphicon glyphicon-cog"></span> Exchanges </a></li>
            <li><a href="<?= $this->Url->build(['controller' => 'pages', 'action' => 'sandbox']); ?>"><span class="glyphicon glyphicon-screenshot"></span> Testing Sandbox </a></li>
		</ul>
	</div><!--/.sidebar-->
		
	<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">			
        <div class="row">   <!-- Breadcrum Row -->
			<ol class="breadcrumb">
				<li><a href="<?= $this->Url->build(['controller' => 'pages', 'action' => 'dashboard']); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
				<li class="active"><?= isset($this->request->params['pass'][0]) ? ucwords($this->request->params['pass'][0]) : $this->fetch('title') ?></li>
			</ol>
		</div><!--/.row-->        
        
        <div class="row2">
            <?= $this->Flash->render() ?>
        </div>
        
		<div class="row2">
            <?= $this->fetch('content'); ?>    
        </div>
	</div>	<!--/.main-->

    <?= $this->Html->script('jquery-1.11.1.min'); ?>
    <?= $this->Html->script('bootstrap.min'); ?>
    <?= $this->Html->script('chart.min'); ?>
    <?= $this->Html->script('easypiechart'); ?>
    <?= $this->Html->script('bootstrap-datepicker'); ?>
    <!-- <script type="text/javascript" src="//cdn.datatables.net/plug-ins/1.10.7/api/fnReloadAjax.js"></script> -->
    <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.js"></script>
</body>
</html>

<script> 	 	
$(document).ready(function() { 	 	
    !function ($) {
		    $(document).on("click","ul.nav li.parent > a > span.icon", function(){          
		        $(this).find('em:first').toggleClass("glyphicon-minus");      
		    }); 
		    $(".sidebar span.icon").find('em:first').addClass("glyphicon-plus");
    }(window.jQuery);

    $('#datatable').dataTable({
        "pagingType": "full_numbers",
    });  
    
    $("#datepicker").datepicker();
}); 	 	
</script>

