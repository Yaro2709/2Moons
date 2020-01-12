{block name="title" prepend}Extra Planet{/block}
{block name="content"}
{if $requiredDarkMatter}
<table style="width:590px;">
    <tbody>
        <tr>
            <th>{$LNG.fcm_info}</th>
        </tr>
        <tr>
            <td><span style="color:red;">{$requiredDarkMatter}</span></td>
        </tr>
    </tbody>
</table>
<br>
<br>
{/if}
<table style="width:590px;">
    <tbody>
        <tr>
            <td colspan="2">
                Buy one more planet slot 
            </td>
        </tr>
        <tr>
           <td>
                {$extraone}
                <br>
                {$LNG.extratwo} {$extra_planet} / {$extratree}.<br>
                {$LNG.extrafour}
            </td>
        </tr>
        {if !$requiredDarkMatter}
        <tr>
            <form method="POST">
                <td colspan="2">
                    <button type="submit" name="Buy" class="button" style="height:25px;">{$extrafive}</button>   
                </td>  
            </form>
        </tr>
        {/if}
    </tbody>
</table>
{/block}
