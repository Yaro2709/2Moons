{block name="title" prepend}Resources Bonus{/block}
{block name="content"}
<table>
	<tr>
		<th colspan="2">
			Daily Resources Bonus
		</th>
	</tr>
	<tr>
    	<td  style="text-align:left;">
			This pack = highest mine income, Times 24 hours Times 31 planets, for 10.000 DM you will receive:
	        <br>
		    <ul>
				<li>- {$price_metal} Metal </li>
				<li>- {$price_crystal} Crystal </li> 
				<li>- {$price_deuterium} Deuterium </li> 
			</ul>
	       <br>
			<b><font color="lime">You can purchase this pack once every 12h.</font></b><br />
			<b><font color="red">Cost {$cost} DM</font></b>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			{if $status === true}
			<form method="POST">
				<input type="submit" name="Buy" value="Buy">
			</form>
			{else}
				<b><font color="lime">Next Daily Resources Bonus in:</font></b><br>
				<big><b><font color="yellow"><span class="countdown2" secs="{$status}"></span></font></b></big>
			{/if}
		</td>
	</tr>
</table>
{/block}