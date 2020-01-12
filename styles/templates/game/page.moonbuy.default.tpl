{block name="title" prepend}Buy Moon{/block}
{block name="content"}
<tr><td><div style="border:1px solid white; background-color:#1C1F23; width:530px; margin-left:200px; border-radius: 5px;"></td></tr>
<table>
	<tbody>
		<tr>
	        <td colspan="2">
	           Moon Request
	       </td>
	    </tr>
		<tr>
			<td rowspan="1" style="width:120px;">
				<img src="styles/theme/gow/planeten/moon_req_pic.png" width="120" height="120">
			</td>
			<td>
				 A moon will be placed to your planet for a price of {$cost} Dark matter.
			</td>
		</tr>
		<tr>
			<td colspan="2">
				{if $moonExist == 0}
					<form method="POST">
						<center><button type="submit" name="Buy" class="button" style="height:25px;">Purchase!</button></center>
					</form>
				{else}
					<span style="color: red; font-weight: bold;">You already have a moon on this planet</span>
				{/if}
			</td>
		</tr>
	</tbody>
</table>
{/block}