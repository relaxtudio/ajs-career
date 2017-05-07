angular.module('adm-service', [])
	.factory('$ls', function($window) {
		return {
			set: function (key, value) {
                $window.localStorage.setItem(key, value);
            },
            get: function (key, defaultValue) {
                return $window.localStorage.getItem(key) || defaultValue;
            },
            remove: function (key) {
                $window.localStorage.removeItem(key);
            },
            setObject: function (key, value) {
                $window.localStorage.setItem(key, JSON.stringify(value));
            },
            getObject: function (key, defaultValue) {
                return JSON.parse($window.localStorage.getItem(key) || defaultValue);
            }
		};
	})

	.factory('$ws', function($ls, $http, CONFIG) {
		var C_SESSION = CONFIG.APP_ID + '.session';
        var C_CACHE = CONFIG.APP_ID + '.cache';
        var C_SERVER = CONFIG.APP_ID + '.server';
        var C_SERVERNAME = CONFIG.APP_ID + '.servername';
        var C_WS;
        var getServer = function (path) {
            path = path || '';
            return $ls.get(C_SERVER, CONFIG.SERVER) + path;
        };
        var initServer = function () {
            C_WS = getServer(CONFIG.API_PHP);
        };
        var setServer = function (url) {
            $ls.set(C_SERVER, url);
            initServer();
        };
        var setServerName = function (name) {
            $ls.set(C_SERVERNAME, name);
        };
        var getServerName = function () {
            return $ls.get(C_SERVERNAME);
        };

        initServer();

        return {
        	login: function(user, success, error) {
        		var session = {
        			name: user.name,
        			token: ''
        		};
        		return $http.post(C_WS + 'login;' + user.name + ";" + user.pass).then(function (respon) {
        			if (respon.data[0].login) {
        				session.token = respon.data[0].login;
        				$ls.setObject(C_SESSION, session);
        			}
        			success(session);
        		}, error);
        	},
        	isLogin: function() {
        		return ($ls.getObject(C_SESSION, null) !== null);
        	},
        	logout: function() {
        		$ls.remove(C_SESSION);
        	},
            getConfig: function(success, error) {
                return $http.post(C_WS + 'getConfig').then(success, error);
            },
            updateConfig: function(data, success, error) {
                return $http.post(C_WS + 'updateConfig', data).then(success, error);
            },
            getJob: function(success, error) {
                return $http.post(C_WS + 'getJob').then(success, error);
            },
            updateJob: function(data, success, error) {
                return $http.post(C_WS + 'updateJob', data).then(success, error);
            },
            getRegistree: function(success, error) {
                return $http.post(C_WS + 'getRegistree').then(success, error);
            }
        }
	})