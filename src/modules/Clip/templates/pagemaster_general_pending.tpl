
<h1>{gt text=$pubtype.title}</h1>

{include file='pagemaster_generic_navbar.tpl' section='pending'}

<p class="z-statusmsg">{gt text='Publication accepted, pending moderation.'}</p>

<p>
    {gt text='Thanks for your submission!'}
    <br />
    <a href="{modurl modname='Clip' tid=$pubtype.tid}">
        {gt text='Go back to the list'}
    </a>
</p>
