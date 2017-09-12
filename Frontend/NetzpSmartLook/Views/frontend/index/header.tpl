{extends file='parent:frontend/index/header.tpl'}

{block name="frontend_index_header_javascript_tracking" append}

{if "{config name="netzp_smartlook_key"}" != "" && "{config name="netzp_smartlook_active"}" == 1} 
<script type="text/javascript">
    window.smartlook||(function(d) {
    var o=smartlook=function(){ o.api.push(arguments)},h=d.getElementsByTagName('head')[0];
    var c=d.createElement('script');o.api=new Array();c.async=true;c.type='text/javascript';
    c.charset='utf-8';c.src='//rec.smartlook.com/recorder.js';h.appendChild(c);
    })(document);

    smartlook('init', '{config name="netzp_smartlook_key"}');
    smartlook('tag', 'websiteName', '{$netzp_shopname}');
    
    {if "{config name="netzp_smartlook_visitordata"}" == "1"}
        {if $netzp_user_email != ""}
        smartlook('tag', 'email', '{$netzp_user_email}');
    	{/if}
        {if $netzp_user_name != ""}
        smartlook('tag', 'name', '{$netzp_user_name}');
        {/if}
    {/if}
</script>
{/if}

{if "{config name="netzp_smartsupp_key"}" != "" && "{config name="netzp_smartsupp_active"}" == 1} 
<script type="text/javascript">
    var _smartsupp = _smartsupp || {};
    _smartsupp.key = '{config name="netzp_smartsupp_key"}';
    _smartsupp.sendEmailTanscript = {config name="netzp_smartsupp_sendtranscript"};
    _smartsupp.ratingEnabled = {config name="netzp_smartsupp_ratingenabled"}; 
    _smartsupp.ratingComment = {config name="netzp_smartsupp_ratingcomment"}; 
    _smartsupp.hideMobileWidget = {config name="netzp_smartsupp_hidemobilewidget"};

    window.smartsupp||(function(d) {
        var s,c,o=smartsupp=function(){ o._.push(arguments)};o._=[];
        s=d.getElementsByTagName('script')[0];c=d.createElement('script');
        c.type='text/javascript';c.charset='utf-8';c.async=true;
        c.src='//www.smartsuppchat.com/loader.js?';s.parentNode.insertBefore(c,s);
    })(document);
    {if $netzp_locale != ""}
    smartsupp('language', '{$netzp_locale}');
    {/if}
    {if $netzp_user_email != ""}
    smartsupp('email', '{$netzp_user_email}');
    {/if}
    {if $netzp_user_name != ""}
    smartsupp('name', '{$netzp_user_name}');
    {/if}

    {if "{config name="netzp_smartsupp_visitordata"}" == 1}
    smartsupp('variables', {
        {if $netzp_user_name != ""}
            userName: { label: 'Name', value: '{$netzp_user_name}' },
        {/if}
        {if $netzp_user_title != ""}
            userTitle: { label: 'Anrede / Titel', value: '{$netzp_user_title}' },
        {/if}
        {if $netzp_user_number != ""}
            userCustomerNumber: { label: 'Kundennr.', value: '{$netzp_user_number}' },
        {/if}
        {if $netzp_user_turnover != ""}
            userTurnover: { label: 'Umsatz (gesamt)', value: '{$netzp_user_turnover} {$netzp_shopcurrency}' },
        {/if}
        {if $netzp_user_comment != ""}
            userComment: { label: 'Interner Kommentar', value: '{$netzp_user_comment}' },
        {/if}
    });
    {/if}
</script>
{/if}

{/block}
