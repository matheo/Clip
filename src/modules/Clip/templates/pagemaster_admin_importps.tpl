
{include file='pagemaster_admin_header.tpl'}

<div class="z-admincontainer">
    <div class="z-adminpageicon">{img modname='core' src='run.gif' set='icons/large' alt=''}</div>

    <h2>{gt text='Import pagesetter publications'}</h2>

    {if $alreadyexists}
    <p class="z-warningmsg">{gt text='Be sure to know what you are doing on this process.'}</p>
    {/if}

    <p>{gt text='On this page you can import publications from Pagesetter.<br />You can only import publications when no Clip publication exist.<br />Please configure the Upload Path before you import something.<br />No Pagesetter data will be changed due the import.'}</p>

    <ul>
        <li>
            <a href="{modurl modname='Clip' type='admin' func='importps' step='1'}">{gt text='Import lists,'}</a>
        </li>
        <li>
            {if $alreadyexists eq 0}
            <a href="{modurl modname='Clip' type='admin' func='importps' step='2'}">{gt text="Import publication types,"}</a>
            {else}
            {gt text='PageMaste already has publication types. It must be empty before perform an import.'}
            {/if}
        </li>
        <li>
            <a href="{modurl modname='Clip' type='admin' func='importps' step='3'}">{gt text="Create the database tables,"}</a>
        </li>
        <li>
            <a href="{modurl modname='Clip' type='admin' func='importps' step='4'}">{gt text="Import data."}</a>
        </li>
    </ul>
</div>
