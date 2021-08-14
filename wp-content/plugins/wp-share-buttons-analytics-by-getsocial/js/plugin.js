function resizeIframe(obj) {
    // obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
}

function forceUpdate(event){
    var data = {
        'action': 'gs_update'
    };

    // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
    jQuery.post(ajaxurl, data, function(response) { console.log('getsocial.io updated');});
}

function forceUpdateWithValues(){
    jQuery.get(jQuery('main').attr('data-href'), {}, function(response){
        var data = {
            'action': 'gs_update_with_values',
            'response': response
        };

        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
        jQuery.post(ajaxurl, data , function(response) {
            console.log('getsocial.io updated');
        });
    });


}

var getsocial_window;

// function checkForUpdate(){
//     getsocial_window.postMessage("needs update?", "*");
// }

jQuery('.getsocial-tab').on('click', function(e){
    e.preventDefault();
    $this = jQuery(this);

    if(!$this.hasClass('only-activate')){
        getsocial_window = window.open($this.attr('href'));
    }

    // setInterval('checkForUpdate()', 10000);
    setInterval('forceUpdateWithValues()', 5000);
})

function handleMessage(event){
    currEvent = event.data;

    switch (currEvent){
        case 'publish':
            forceUpdate(currEvent);
            break;
        default:
            break;
    }
}

jQuery(function($){
    jQuery('#api-key-form').submit(function(){
        var data = jQuery(this).serialize();
        jQuery('.notification-bar').hide();

        if(jQuery(this).find('#gs-api-key').val() != 0){
            jQuery(this).find('input').prop('disabled', true);
            jQuery(this).addClass('gs-disabled');

            jQuery.post( 'options.php', data).success( function(response){
                jQuery('.loading-create').removeClass('active');
                jQuery('.notification-bar.success').show();

                setTimeout('window.location.reload();', 3000);
            });
        } else {
            jQuery('.notification-bar.error').show().find('p').html('API KEY cannot be blank.');
        }



        return false;
    });

    jQuery('.create-gs-account').on('click', function(e){
        e.preventDefault();

        jQuery('.create-gs-account').hide();
        jQuery('.loading-create').addClass('active');

        jQuery.post(jQuery(this).attr('href'), { source: 'wordpress' }, function(data){
            if(data.errors != undefined){
                jQuery('.loading-create').removeClass('active');
                jQuery('.account-info').hide();
                jQuery('.notification-bar.error').show().find('p').html(data.errors[0]);
                jQuery('.api-key').show();
            } else {
                jQuery('#gs-api-key').attr('value', data.api_key);
                jQuery('#api-key-form').trigger('submit');
            }
        });
    });

    if(jQuery('.graphs').length > 0){
        var $graphs = jQuery('.graphs'),
            graph_api = $graphs.attr('data-graph-api');

        $.get(graph_api, function(data){
            $.each(['total_visits', 'total_shares', 'total_leads'], function(i,stat){
                jQuery('.'+stat).html(data[stat]);
            });
        })
    }

    jQuery(document).on('click', '.deactivate', function(e){
        e.preventDefault();

        $this = jQuery(this);

        if(confirm('Are you sure you want to deactivate the application?') == true){
            $.post($this.attr('data-disable-app'), function(data){
                if(window.location.href.match(/delete/)) {
                    window.location.reload();
                } else {
                    window.location = window.location.href+'&delete=1'
                }
            });
        }
    });

    jQuery(document).on('click', '.only-activate', function(e){
        e.preventDefault();

        $.post($(this).attr('href'), function(data){
            window.location = window.location.href+'&update=1'
        });

        return false;
    });

    if (!window.addEventListener){
        window.attachEvent('onmessage', handleMessage);
    } else {
        window.addEventListener('message', handleMessage);
    }

    /** ==================================== *\
     *    HEADER SUBMENU ACTION
     * ===================================== */
    $('body').on('click', function(e) {
        if($('div.nav-submenu-wrapper').is(':visible')) {
            $('.nav-submenu.active').removeClass('active');
            $('div.nav-submenu-wrapper').stop().delay(200).hide(0);
        }
    });

    $('.nav-submenu-link > a').on('click', function(e){
        e.stopPropagation();

        var $menu = $(this).next('.nav-submenu-wrapper'),
            $menu_list = $menu.find('.nav-submenu');

        if( $menu.is(':visible') ){
            $menu_list.removeClass('active');
            $menu.stop().delay(200).hide(0);
        } else {
            var $menu_active = $('.nav-submenu.active');
            $menu_active.removeClass('active');
            $menu_active.parent().stop().delay(200).hide(0);

            $menu.stop().show();
            var $menu_height = $menu.find('.nav-submenu').outerHeight();
            $menu.css({ height: $menu_height });
            $menu_list.addClass('active');
        }
    });

    /** ==================================== *\
     *    SCROLL TO TOP
     * ===================================== */
    $('#backToTop').on('click', function(){
        $('html, body').animate({ scrollTop: 0 }, 400);
    });

    /** ==================================== *\
     *    MODAL ACTION
     * ===================================== */
    function modal(trigger){
        $(trigger).on('click', function(){
            var modal_link = $(this).attr('id'),
                modal_id = '#' + modal_link + '-modal';

            if( !$(modal_id).hasClass('active') ){
                $(modal_id).removeClass('hide').addClass('active');
                $('#gs-master-wrapper').addClass('no-scroll');
            }
        });
        $('.modal-close').on('click', function(){
            $('.modal-wrapper.active').stop().removeClass('active').addClass('rewind').delay(700).queue(function(){
                $(this).removeClass('rewind').addClass('hide');
                $('#gs-master-wrapper').removeClass('no-scroll');
                $.dequeue(this);
            });
        });
    }
    modal('#settings');

    function closeModal() {
        $('.modal-wrapper.active').stop().removeClass('active').addClass('rewind').delay(700).queue(function(){
            $(this).removeClass('rewind').addClass('hide');
            $.dequeue(this);
        });
    }

    forceUpdateWithValues();
});
