<!DOCTYPE html>
<html lang="en" ng-app="of">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Oufei </title>
        <!-- Bootstrap -->
        <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
        <style type="text/css">
            .datepicker{padding-top: 10px;}
            .nav-tabs.nav-justified{width: 200px;cursor: pointer;}
        </style>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <nav class="navbar navbar-default" role="navigation">
                        <div class="container-fluid">
                            <!-- Brand and toggle get grouped for better mobile display -->
                            <div class="navbar-header">
                                <div class="col-md-2">
                                     <a class="navbar-brand" href="#">欧飞平台</a>
                                </div>
                    <div class="col-md-2">
                        <div ng-controller="DatepickerDemoCtrl"  class="datepicker">
                            <p class="input-group">
                                <input type="text" class="form-control" datepicker-popup="{{format}}" ng-model="dt" is-open="opened" min="2013-12-12" max="'2015-06-22'" datepicker-options="dateOptions" date-disabled="disabled(date, mode)" ng-required="true" close-text="Close" />
                                <span class="input-group-btn">
                                    <button class="btn btn-default" ng-click="open($event)"><i class="glyphicon glyphicon-calendar"></i></button>
                                </span>
                            </p>
                        </div>
                    </div>
                                
                    <div class="col-md-2">
                        <div ng-controller="DatepickerDemoCtrl" class="datepicker">
                            <p class="input-group">
                                <input type="text" class="form-control" datepicker-popup="{{format}}" ng-model="dt" is-open="opened" min="2013-12-12" max="'2015-06-22'" datepicker-options="dateOptions" date-disabled="disabled(date, mode)" ng-required="true" close-text="Close" />
                                <span class="input-group-btn">
                                    <button class="btn btn-default" ng-click="open($event)"><i class="glyphicon glyphicon-calendar"></i></button>
                                </span>
                            </p>
                            {{dt | date:'yyyy-MM-dd' }}  
                        </div>
                        
                    </div>
                                <div class="col-md-3 datepicker" class="">
                                    <input type="text" class="form-control" placeholder="请输入邮箱或手机号">
                                </div>
                                <div class="col-md-1 datepicker" class="">
                                    <button type="button" class="btn btn-success">搜索</button>
                                </div>
                            </div>
                            <div class="collapse navbar-collapse" >
                            </div><!-- /.navbar-collapse -->
                        </div><!-- /.container-fluid -->
                    </nav>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
             </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div ng-controller="TabsDemoCtrl">
                        <tabset justified="true">
                            <tab heading="成功订单">

                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>用户名</th>
                                            <th>充值状态</th>
                                            <th>充值时间</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>Mark</td>
                                            <td>Otto</td>
                                            <td>@mdo</td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>Jacob</td>
                                            <td>Thornton</td>
                                            <td>@fat</td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td colspan="2">Larry the Bird</td>
                                            <td>@twitter</td>
                                        </tr>
                                    </tbody>
                                </table>

                            </tab>
                            <tab heading="失败订单">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>用户名</th>
                                            <th>充值状态</th>
                                            <th>充值时间</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>Mark</td>
                                            <td>失败</td>
                                            <td>@mdo</td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>Jacob</td>
                                            <td>Thornton</td>
                                            <td>@fat</td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td colspan="2">Larry the Bird</td>
                                            <td>@twitter</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </tab>
                        </tabset>
                    </div>

                </div>
            </div>
        </div>

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="../bootstrap/jquery.min.js"></script>
        <script src="../bootstrap/angular.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="../bootstrap/ui-bootstrap-tpls.min.js"></script>
        <script type="text/javascript">
                        angular.module('of', ['ui.bootstrap']);
                        var DatepickerDemoCtrl = function($scope) {
                            $scope.today = function() {
                                $scope.dt = new Date();
                            };
                            $scope.today();
                            $scope.clear = function() {
                                $scope.dt = null;
                            };
                            $scope.toggleMin = function() {
                                $scope.minDate = ($scope.minDate) ? null : new Date();
                            };
                            $scope.toggleMin();

                            $scope.open = function($event) {
                                $event.preventDefault();
                                $event.stopPropagation();
                                $scope.opened = true;
                            };

                            $scope.dateOptions = {
                                'year-format': "'yy'",
                                'starting-day': 1
                            };

                            $scope.formats = ['yyyy-MM-dd', 'yyyy/MM/dd', 'shortDate'];
                            $scope.format = $scope.formats[0];
                        };

                        var TabsDemoCtrl = function($scope) {
                            $scope.tabs = [
                                {title: "Dynamic Title 1", content: "Dynamic content 1"},
                                {title: "Dynamic Title 2", content: "Dynamic content 2", disabled: true}
                            ];

                            $scope.alertMe = function() {
                                setTimeout(function() {
                                    alert("You've selected the alert tab!");
                                });
                            };

                            $scope.navType = 'pills';
                        };
        </script>
    </body>
</html>