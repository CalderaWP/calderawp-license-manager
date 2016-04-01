/** globals jQuery, CWP_SUPPORT */
jQuery( document ).ready( function ( $ ) {
    var support_form = 'form.' + CWP_SUPPORT.forms.support;
    var login_form =  'form.' + CWP_SUPPORT.forms.login;
    var api = CWP_SUPPORT.api_url;




    ( function() {
        var token;
        var checkToken;
        var user;
        var cookieTokenName = 'wp-api-jwt-token';
        var cookieUserName = 'wp-api-user-name';

        /**
         * Get token and user ID from cookies or show login form
         */
        (checkToken = function(){
            var _token, _user;
            _token = Cookies.get( cookieTokenName );
            _user = Cookies.get( cookieUserName );
            if( null == _token || 'undefined' == _token || null == _user || 'undefined' == _user) {
                show( login_form );
                hide( support_form);
            }else{
                hide( login_form);
                show( support_form );
                token = _token;
                user = _user;
                setUserData();
            }
        })();

        /**
         * Handle login form submission
         */
        $( login_form ).on( 'submit', function(e) {
            e.preventDefault();
            username = $( '#username' ).val();
            var password = $( '#password' ).val();
            getToken( username, password );
        });



        /**
         * Get token using username and password
         *
         * @param username
         * @param password
         */
        function getToken( username, password ) {
            $.ajax({
                url:api + '/jwt-auth/v1/token',
                method:'POST',
                data:{
                    username: username,
                    password: password
                },
                complete: function( response ) {
                    if( 200 == response.status ) {
                        var data = response.responseJSON;
                        token = data.token;
                        user = data.ID;
                        Cookies.set( cookieTokenName, token, { expires: 1 } );
                        Cookies.set( cookieUserName, user, {expires: 1 } );
                        hide( login );
                        show( support );
                    }
                },
                error: function( response, status, xhr ) {
                    alert(response.responseJSON.message);

                }
            });

        }

        $( support_form ).on( 'submit', function(e){
            e.preventDefault();
            var data = $(this ).serialize();
            $.ajax({
                method: "POST",
                url: "http://local.wordpress-trunk.dev/wp-json/calderawp_api/v2/support/",
                data: data
            } ).error( function( a,b,c){
                var pants;
            } ).success( function( response ){
                var pants;
            });
        });


        /**
         * Hide a div
         *
         * @param el
         */
        function hide( el ){
            $( el ).hide().css( 'visibility', 'hidden' ).attr( 'aria-hidden', 'true' );
        }

        /**
         * Show a div
         *
         * @param el
         */
        function show( el ){
            $( el ).show().css( 'visibility', 'visible' ).attr( 'aria-hidden', 'false' );
        }


    } )();

} );
