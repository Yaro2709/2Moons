{block name="title" prepend}Jackpot{/block}
{block name="content"}
	<table style="width:590px;">
		<tbody>
			<tr>
		        <td colspan="2">
		           Jack Pot !
		       </td>
		    </tr>
		    <tr>
		        <td>
			        This mod is a little game where you could win up to {$jackpot|number} DM<br>
			     	Will you be one of the lucky winners? <br>
			     	Jack Pot Prize increase by 10.000 each 12h<br><br>
			     	You receive 5 free attemps each 12h.
		        </td>
		    </tr>
		</tbody>
	</table>

	<table style="width:590px;">
		<tbody>
			<form action="game.php?page=jackpot&act=buyext" method="POST">
			<tr>
			    <td>
			       	You still have {$amount|number} free attemps left today<br> (next reset: {if !empty($next_reset)} <span style="color:green;" class="countdown2"  secs="{$next_reset}"></span>{/if})
			    </td>
			    <td>
			    	Buy extra attemps <br>
					<select name="premium">
						{foreach from=$prices item=i key=k}
							<option value="{$k}">{$k} Attemp(s) - {$i|number} DM</option>
						{/foreach}
					</select>
				</td>
			</tr>
			<tr>
			    <td colspan="2">
				    <input type="submit" value="Buy" name="Buy">
			    </td>
			</tr>
			</form>
			<tr>
			    <td colspan="2">
					ack Pot Prize : {$jackpot|number} DM
			    </td>
			</tr>
		</tbody>
	</table>

	<table style="width:590px;">
		<tbody>
			<tr>
			    <td>
			        Are you a safe cracker? Show it us and try to crack this amaizing box !. Enter here the code that you think will<br> match with the safe. You will receive in info about the code if ur choosen code is in <br>range of the safe code (+/-30)
			    </td>
			</tr>
	    </tbody>
	</table>

	<table style="width:590px;">
		<tbody>
			<tr>
				<th><center>NUMBER #1</center></th>
				<th><center>NUMBER #2</center></th>
				<th><center>NUMBER #3</center></th>
			</tr>
			<form action="game.php?page=jackpot&act=try" method="POST" id="harv" name="harv">
			<tr>
				<td>
			        <select size="3" name="code1" id="code1">
				    	<option value="0">0</option> 
				        <option value="1">1</option>
				        <option value="2">2</option>
				        <option value="3">3</option>
				    	<option value="4">4</option>
				        <option value="5">5</option>
				        <option value="6">6</option>
				    	<option value="7">7</option>
				        <option value="8">8</option>
				        <option value="9">9</option>
			    	</select>
			    </td>
			    <td>
			    	<select size="3" name="code2" id="code2">
				    	<option value="0">0</option> 
				        <option value="1">1</option>
				        <option value="2">2</option>
				        <option value="3">3</option>
				    	<option value="4">4</option>
				        <option value="5">5</option>
				        <option value="6">6</option>
				    	<option value="7">7</option>
				        <option value="8">8</option>
				        <option value="9">9</option>
			    	</select>
			    </td>
			    <td>
			    	<select size="3" name="code3" id="code3" >
				    	<option value="0">0</option> 
				        <option value="1">1</option>
				        <option value="2">2</option>
				        <option value="3">3</option>
				    	<option value="4">4</option>
				        <option value="5">5</option>
				        <option value="6">6</option>
				    	<option value="7">7</option>
				        <option value="8">8</option>
				        <option value="9">9</option>
			    	</select>
			    </td>
			</tr>
			<tr>
			    <td colspan="3">
			    	<input type="submit" value="Try">
			    </td>
			</tr>
			</form>
		</tbody>
	</table>

	<table style="width:590px;">
		<tbody>
			<tr>
				<th>Username</th>
				<th>Time Success</th>
				<th>Price</th>
			</tr>
			{foreach $resultLog as $log}
			<tr>
				<td>{$log.username}</td>
				<td>{$log.timeSuccess}</td>
				<td>{$log.price}</td>
			</tr>
			{/foreach}
		</tbody>
	</table>
{/block}