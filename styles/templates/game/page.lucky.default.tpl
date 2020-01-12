{block name="title" prepend}Lucky 7{/block}
{block name="content"}
<table>
	<tr>
		<th colspan="2">
			Lucky 7
		</th>
	</tr>
	<tr>
		<td  style="text-align:left;">
			The weekly Resource Enhancement is a bonus for players who are serious about building there empires. This feature rewards you for building mines which is the lifeline of every empire. How this feature works is we scan your empire for your top level metal,crystal and deut mines. We look at your daily production including officers. At this point we multiply your daily production by 7 days for a weekly number. Then multiply by 7 again for your lucky seven bonus. Your weekly bonus can increase every week with your mining efforts. The more you build the more you make with lucky 7!
			<ul>
				<li>- {$price_metal} Metal </li>
				<li>- {$price_crystal} Crystal </li> 
				<li>- {$price_deuterium} Deuterium </li> 
			</ul>
			<b><font color="lime">You can purchase this pack once every {$time}.</font></b><br />
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
				<b><font color="lime">Next Lucky 7 in:</font></b><br>
				<big><b><font color="yellow"><span class="countdown2" secs="{$status}"></span></font></b></big>
			{/if}
		</td>
	</tr>
</table>
{/block}