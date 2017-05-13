angular.module('adm-controller', ['textAngular'])
	.controller('AppCtrl', function($scope, $ws, $state, CONFIG) {
		$scope.logout = function () {
			$ws.logout();
			$state.transitionTo('login');
		}
		$scope.goTo = function (val) {
			$state.transitionTo('app.'+val);
		}
		$scope.formatDate = function (date) {
		    var d = new Date(date),
		        month = '' + (d.getMonth() + 1),
		        day = '' + d.getDate(),
		        year = d.getFullYear();

		    if (month.length < 2) month = '0' + month;
		    if (day.length < 2) day = '0' + day;

		    return [year, month, day].join('-');
		}
	})

	.controller('LoginCtrl', function($scope, $ws, $state) {
		$scope.init = function () {
			$scope.user = {
				name: '',
				pass: ''
			};
		};
		$scope.login = function (form) {
			var error = function (respon) {
				console.log('error');
			};
			var user = $scope.user;
			if (form.$valid) {
				$ws.login(user, function (respon) {
					$state.transitionTo('app.admin');
				}, error);
			}
		};
		$scope.init();
	})

	.controller('AdminCtrl', function($scope, $ws) {
		$scope.init = function () {
			$scope.config = {};
			$scope.initWs();
		};
		$scope.initWs = function () {
			$scope.getConfig();
		};
		$scope.getConfig = function () {
			$ws.getConfig(function (respon) {
				$scope.config = respon.data[0].getConfig[0];
				$scope.config.min_date = new Date($scope.config.min_date);
				$scope.config.max_date = new Date($scope.config.max_date);
			}, $scope.$parent.errorWs);
		};
		$scope.save = function () {
			var data = {
				text: $scope.config.text,
				min_date: $scope.$parent.formatDate($scope.config.min_date),
				max_date: $scope.$parent.formatDate($scope.config.max_date),
				toggle: $scope.config.toggle
			};
			$ws.updateConfig(data, function (respon) {
				alert("Content berhasil disimpan");
			}, $scope.$parent.errorWs);
		};
		$scope.test = function () {
			console.log($scope.config);
		}
		$scope.init();
	})

	.controller('MainCtrl', function($scope, $ws) {
		$scope.init = function () {
			$scope.registree = [];
			$scope.job = {
				old: [],
				new: []
			};
			$scope.showJob = true;
			$scope.newJob = {};
			$scope.initWs();
		};
		$scope.initWs = function () {
			$scope.getJob();
			$scope.getRegistree();
		};
		$scope.getJob = function () {
			$ws.getJob(function (respon) {
				$scope.job.old = respon.data[0].getJob;
				$scope.job.new = respon.data[0].getJob;
			}, $scope.$parent.errorWs);
		};
		$scope.addJob = function () {
			$ws.addJob($scope.newJob, function (respon) {
				console.log(respon);
				$scope.initWs();
				$scope.showJob = true;
			}, $scope.$parent.errorWs);
		};
		$scope.cancelJob = function () {
			$scope.showJob = true;
		};
		$scope.updateJob = function (val) {
			for (i in $scope.job.new) {
				if ($scope.job.new[i].job_id == val) {
					var data = $scope.job.new[i];
					$ws.updateJob(data, function (respon) {
						alert("Data berhasil diupdate");
					}, $scope.$parent.errorWs);
				}
			}
		};
		$scope.deleteJob = function (data) {
			$ws.deleteJob(data, function (respon) {
				$scope.initWs();
			}, $scope.$parent.errorWs);
		};
		$scope.getRegistree = function () {
			$ws.getRegistree(function (respon) {
				$scope.registree = respon.data[0].getRegistree;
			}, $scope.$parent.errorWs);
		};
		$scope.init();
	})