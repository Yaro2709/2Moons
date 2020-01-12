{block name="title" prepend}Fleeter package{/block}
{block name="content"}
<table>
	<tr>
		<th colspan="2">
			Fleet Package
		</th>
	</tr>
	<tr>
		<td  style="text-align:left;">
			With this pack you can purchase some fleet to help in your journey<br> 
			<ul>
				<li> {$ship1} Lune Noir </li>
		        <li> {$ship2} Star Crasher </li>
		        <li> {$ship3} Death Star </li>
			</ul>
			<b><font color="red">{$LNG.cost} {$cost} {$LNG.dm}</font></b>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			{if $status === true}
				<form method="POST">
					<input type="submit" name="Buy" value="Buy">
				</form>
			{else}
				<b><font color="lime">Next bonus</font></b><br>
				<big><b><font color="yellow"><span class="countdown2" secs="{$status}"></span></font></b></big>
			{/if}
		</td>
	</tr>
</table>
{/block}