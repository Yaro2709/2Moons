{block name="title" prepend}Relocate{/block}
{block name="content"}
	<table id="ranks" class="userHighscore">
		<tbody>
			<tr>
				<th>Relocate</th>
			</tr>
			<tr>
				<td>
					You need {$cout|number} Dark Matter to move your planet / moon!
				</td>
			</tr>
			<tr>
				<td>
					<form method="POST" action="game.php?page=relocate&mode=send">
						<input name="galaxy" size="20" value="{$galaxy}" type="text" placeholder="Galaxy">
						<input name="system" size="20" value="{$system}" type="text" placeholder="System">
						<input name="planet" size="20" value="{$planet}" type="text" placeholder="Planet">
						{if $PM != $planetId}
							<input class="btn_blue" value="Change" type="submit">
						{else}
							<br>
							<span style="color: red;">You can not move your mother planet</span>
						{/if}
					</form>
				</td>
			</tr>
	</tbody>
</table>
{/block}