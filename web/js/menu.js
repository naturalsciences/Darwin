jQuery(function(){
    o = {"dropShadows":false, "autoArrows":true, "firstOnClick":true, "delay":400};
    jQuery('ul.main_menu').supersubs().superfish(o).find('ul').bgIframe({"opacity":false});
    $('ul.main_menu > li:not(.house):not(.exit)').append('<img class="highlight" src="/images/menu_expand.png" alt="" />');
});