
var ThemeSetupTool = (function($){

    //callbacks from button clicks
    var callbacks = {
        activateLicense: function(btn){
            activateLicenseHandler(btn);
        },
        installPlugins: function(btn){
            var plugins = new PluginManager();
            plugins.init(btn);
        },
        installContent: function(btn){
            var content = new ContentManager();
            content.init(btn);
        }
    };

    var loadingBtn;
    var attempt;

    function activateLicenseHandler(btn){
        var license_key = $('#' + htwiki_theme_setup.theme_slug + '_license_key').val();
        var licenseStatus = $('.htwiki-license-status');
        loadingContent();
        var ajax = $.ajax({
            url: htwiki_theme_setup.ajaxurl, 

            type: 'post',
            data: {
                    action: 'htwiki_activate_theme_key',
                    wpnonce: htwiki_theme_setup.wpnonce,
                    htwiki_license_activate: true,
                    htwiki_nonce : $(btn).data('nonce'),
                    key: license_key,
                    attempt: attempt || 0
                },
            beforeSend: function(xhr){
                    licenseStatus.addClass('active');
                    var licenseMessage = licenseStatus.find('span').data('submit-message');
                    licenseStatus.find( 'span' ).text(licenseMessage);
                },
            complete: function(response){
                    response = response.responseJSON;
                    if( 'undefined' != response.done && 1 == response.done && 'undefined' != response.valid && 1 == response.valid ){
                        //do something - license good
                        console.log('license good');
                        $('.htwiki-licenseinput').addClass( 'htwiki-licenseinput--valid' );
                        licenseStatus.find( 'span' ).text(response.message);
                        window.location.href=btn.href;
                    } else {
                        //do something - license not good
                        console.log('license not good');
                        licenseStatus.find( 'span' ).text(response.message);
                        if(true===response.retry){
                            console.log('retrying license activation');
                            attempt = response.attempt;
                            //recusive call
                            activateLicenseHandler(btn);
                        } else {
                            console.log('license activation failed');
                            $('.htwiki-licenseinput').addClass( 'htwiki-licenseinput--invalid' );
                        }
                        //reset the window, something went wrong
                        resetLicenseWindow();
                    }                    
                }
        });
    }

    function resetLicenseWindow(){
       loadingBtn.done();
       loadingBtn = null;
    }

    function clearLicenseMessage(){
        var licenseStatus = $('.htwiki-license-status');
        licenseStatus.find('span').text('');
        licenseStatus.removeClass('active');

        var licenseEntry = $('.htwiki-licenseinput');
        licenseEntry.removeClass('htwiki-licenseinput--valid');
        licenseEntry.removeClass('htwiki-licenseinput--invalid');
    }



    function windowLoaded(){
        //init button clicks
        $('.button-next').each(function( index, element ) {
            $(element).on( 'click', function(e) {
                console.log('button-next click');
                loadingBtn = loadingButton(this);
                if(!loadingBtn){
                    return false;
                }
                if($(this).data('callback') && typeof callbacks[$(this).data('callback')] != 'undefined'){
                    //process callback before form submission
                    loadingContent();
                    callbacks[$(this).data('callback')](this);
                    return false;
                }else{
                    loadingContent();
                    return true;
                }
            });
        });
        $('.button-upload').on( 'click', function(e) {
            e.preventDefault();
            renderMediaUploader();
        });
        $('.theme-presets a').on( 'click', function(e) {
            e.preventDefault();
            var ul = $(this).parents('ul').first();
            ul.find('.current').removeClass('current');
            var li = $(this).parents('li').first();
            li.addClass('current');
            var newcolor = $(this).data('style');
            $('#new_style').val(newcolor);
            return false;
        });

        $('input.htwiki-licenseinput__input').change(function() {
            clearLicenseMessage();
        });
    }

    function loadingContent(){
        $('.htwiki-setupwizard__content').block({
            message: null,
            overlayCSS: {
                background: '#fff',
                opacity: 0.6
            }
        });
    }

    function PluginManager(){

        var complete;
        var itemsCompleted = 0;
        var currentItem = '';
        var currentNode;
        var currentItemHash = '';

        function ajaxCallback(response){
            if(typeof response == 'object' && typeof response.responseJSON != 'undefined'){
                //get the json response
                response = response.responseJSON;
                currentNode.find('span').text(response.message);
                if(typeof response.url != 'undefined'){
                    //ajax url action to perform.

                    if(response.hash == currentItemHash){
                        currentNode.find('span').text("failed");
                        findNext();
                    }else {
                        currentItemHash = response.hash;
                        //recursive call

                        $.ajax({
                            url: response.url, 
                            type: 'post',
                            data: response,
                            complete: function(response2) {
                                        processCurrent();
                                        currentNode.find('span').text(response.message + htwiki_theme_setup.verify_text);
                                    },
                            fail: ajaxCallback
                        });

                    }

                }else if(typeof response.done != 'undefined'){
                    // finished processing this plugin, move onto next
                    findNext();
                }else{
                    // error processing this plugin
                    findNext();
                }
            }else{
                // error - try again with next plugin
                currentNode.find('span').text("ajax error");
                findNext();
            }
        }
        function processCurrent(){
            if(currentItem){
                loadingContent();
                currentNode.find('.spinner').addClass('is-active');
                //tgm call
                //no reply - safe to continue
                $.ajax({    url: htwiki_theme_setup.ajaxurl, 
                            type: 'post', 
                            data: {
                                action: 'htwiki_setup_plugins',
                                wpnonce: htwiki_theme_setup.wpnonce,
                                slug: currentItem
                            },
                            complete: ajaxCallback 
                        });
            }
        }

        function findNext(){
            var doNext = false;
            if(currentNode){
                if(!currentNode.data('done_item')){
                    itemsCompleted++;
                    //nb - check this method appears not to work
                    currentNode.data('done_item',1);
                }
                currentNode.find('.spinner').removeClass('is-active');
            }
            var li = $('.theme-install-setup-plugins li');
            li.each(function(){
                if(currentItem === '' || doNext){
                    currentItem = $(this).data('slug');
                    currentNode = $(this);
                    processCurrent();
                    doNext = false;
                }else if($(this).data('slug') == currentItem){
                    doNext = true;
                }
            });
            if(itemsCompleted >= li.length){
                //finished loading all plugins
                complete();
            }
        }
        
        return {
            init: function(btn){
                $('.theme-install-setup-plugins').addClass('installing');
                complete = function(){
                    loadingContent();
                    //load next page
                    window.location.href=btn.href;
                };
                findNext();
            }
        };
    }

    function ContentManager(){

        var complete;
        var itemsCompleted = 0;
        var currentItem = '';
        var currentNode;
        var currentItemHash = '';

        function ajaxCallback(response) {
            if(typeof response == 'object' && typeof response.responseJSON != 'undefined'){
                //get the json response
                response = response.responseJSON;
                currentNode.find('span').text(response.message);
                if(typeof response.url != 'undefined'){
                    // we have an ajax url action to perform.
                    if(response.hash == currentItemHash){
                        currentNode.find('span').text("failed");
                        findNext();
                    }else {
                        currentItemHash = response.hash;
                        //recursive call
                        $.ajax({
                            url: response.url, 
                            type: 'post',
                            data: response,
                            complete: ajaxCallback
                        });
                    }
                }else if(typeof response.done != 'undefined'){
                    //finished processing this node, move onto next
                    findNext();
                }else{
                    //error processing this node
                    findNext();
                }
            }else{
                //error - try again with next node
                currentNode.find('span').text("ajax error");
                findNext();
            }
        }

        function processCurrent(){
            if(currentItem){
                var required = currentNode.find('input:checkbox');
                if(required.is(':checked')) {
                    console.log('processing-> '+currentItem);
                    currentNode.find('.spinner').addClass('is-active');
                    //process
                    $.ajax({
                        url: htwiki_theme_setup.ajaxurl, 
                        type: 'post',
                        data: {
                            action: 'htwiki_setup_content',
                            wpnonce: htwiki_theme_setup.wpnonce,
                            add: currentItem
                        },
                        complete: ajaxCallback
                    });
                }else{
                    currentNode.find('span').text('Skipping');
                    setTimeout(findNext,300);
                }
            }
        }
        function findNext(){
            var doNext = false;
            if(currentNode){
                if(!currentNode.data('done_item')){
                    itemsCompleted++;
                    currentNode.data('done_item',1);
                }
                currentNode.find('.spinner').removeClass('is-active');
            }
            var items = $('li.htwiki-content-item');
            items.each(function(){
                if (currentItem === '' || doNext) {
                    currentItem = $(this).find('input:checkbox').data('content');
                    currentNode = $(this);
                    processCurrent();
                    doNext = false;
                } else if ($(this).find('input:checkbox').data('content') == currentItem) {
                    doNext = true;
                }
            });
            if(itemsCompleted >= items.length){
                //finishedAll
                complete();
            }
        }

        return {
            init: function(btn){
                $('.theme-install-setup-content').addClass('installing');
                $('.theme-install-setup-content').find('input').prop("disabled", true);
                complete = function(){
                    loadingContent();
                    //load next page
                    window.location.href=btn.href;
                };
                findNext();
            }
        };
    }

    function loadingButton(btn){

        var button = jQuery(btn);
        //if(button.data('done-loading') == 'yes')return false;
        var existingText = button.text();
        var existingWidth = button.outerWidth();
        var loading_text = '⡀⡀⡀⡀⡀⡀⡀⡀⡀⡀⠄⠂⠁⠁⠂⠄';
        var completed = false;
        button.css('width',existingWidth);
        button.addClass('loading_btn_current');
        var modifier = button.is('input') || button.is('button') ? 'val' : 'text';
        button[modifier](loading_text);
        button.attr('disabled',true);
        button.data('done-loading','yes');

        var anim_index = [0,1,2];


        // animate the text indent
        function textIndentAnimate() {
            if (completed)return;
            var current_text = '';
            // increase each index up to the loading length
            for(var i = 0; i < anim_index.length; i++){
                anim_index[i] = anim_index[i]+1;
                if(anim_index[i] >= loading_text.length)anim_index[i] = 0;
                current_text += loading_text.charAt(anim_index[i]);
            }
            button[modifier](current_text);
            setTimeout(function(){ textIndentAnimate();},60);
        }

        textIndentAnimate();

        return {
            done: function(){
                completed = true;
                button[modifier](existingText);
                button.removeClass('loading_btn_current');
                button.attr('disabled',false);
            }
        };

    }

    return {
        init: function(){
            t = this;
            $(windowLoaded);
        },
        callback: function(func){
            console.log(func);
            console.log(this);
        }
    };

})(jQuery);


ThemeSetupTool.init();