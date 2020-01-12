{block name="title" prepend}Turtle package{/block}
{block name="content"}
<table>
	<tr>
		<th colspan="2">
			Turtle Packages
		</th>
	</tr>
	<tr>
		<td  style="text-align:left;">
			The Turle Package is a bonus for players who are serious about building defence. This feature rewards you with certain ammount of defence. How this feature works you will get certain amount of ships on planet where you are currently and amount is same for all players.<br> 
            <center><font color="red">This will be increased in the future</font></center>
	        <ul>
				<li> {$ship3} Missile Launcher </li>
				<li>{$ship2} Gravitation Cannon </li>
		        <li>{$ship1} Light Laser </li>
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
				<b><font color="lime">Next Weekly Fleet Bonus in:</font></b><br>
				<big><b><font color="yellow"><span class="countdown2" secs="{$status}"></span></font></b></big>
			{/if}
		</td>
	</tr>
</table>
{/block}