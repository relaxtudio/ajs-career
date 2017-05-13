angular.module('adm', ['adm-config', 'adm-controller', 'adm-service', 'ui.router'])
	.run(function($rootScope, $state, $ws) {
		$rootScope.$on("$stateChangeStart", function (event, toState) {
			if (toState.authenticate && !$ws.isLogin()) {
				$state.transitionTo("login");
				event.preventDefault();
			}

			if (!toState.authenticate && $ws.isLogin()) {
				$state.transitionTo("app.main");
				event.preventDefault();
			}
		});
	})

	.config(function($stateProvider, $urlRouterProvider) {
		$stateProvider
			.state('app', {
				url: '/app',
				templateUrl: 'template/app.html',
				controller: 'AppCtrl',
				authenticate: true
			})
			.state('login', {
				url: '/login',
				templateUrl: 'template/login.html',
				controller: 'LoginCtrl',
				authenticate: false
			})
			.state('app.admin', {
				url: '/admin',
				views: {
					'content': {
						templateUrl: 'template/admin.html',
						controller: 'AdminCtrl'
					}
				},
				authenticate: true
			})
			.state('app.main', {
				url: '/main',
				views: {
					'content': {
						templateUrl: 'template/main.html',
						controller: 'MainCtrl'
					}
				},
				authenticate: true
			})
		$urlRouterProvider.otherwise('/app/admin');
	})

.filter('startFrom', function() {
    return function(input, start) {
        start = +start; //parse to int
        return input.slice(start);
    }
})