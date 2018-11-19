<html>
<body>
<b>development s2s:</b>
<form id = 'mainForm' action="https://stage.game-program.com/api/seamless/provider" method="POST">
    u:<input type="text" name="api_login" value="jure_api">
    p:<input type="text" name="api_password" value="jure">
    show systems: <input type="checkbox" name="show_systems" value="1" />
    <select name="method">
        <option value="giveMoney">giveMoney</option>
        <option value="takeMoney">takeMoney</option>
        <option value="getPlayerBalance">getPlayerBalance</option>
        <option value="getDailyReport">getDailyReport</option>
        <option value="getDailyBalances">getDailyBalances</option>
        <option value="playerExists">playerExists</option>
        <option value="getGame">getGame</option>
        <option value="getGameDirect">getGameDirect</option>
        <option value="getGameList" >getGameList</option>
        <option value="createPlayer">createPlayer</option>
        <option value="getPaymentTransactions">getPaymentTransactions</option>
        <option value="addFreeRounds">addFreeRounds</option>
        <option value="getSystemUsername">getSystemUsername</option>
        <option value="setSystemUsername">setSystemUsername</option>
        <option value="setSystemPassword">setSystemPassword</option>
    </select>
    amount:<input type="text" name="amount" value="1">
    transid:<input type="text" name="transactionid" value="1397549799">
    pu:<input type="text" name="user_username" value="juretest">
    pp:<input type="text" name="user_password" value="jure">
    gameid:<input type="text" name="gameid" value="898">
    playforfun:<input type="checkbox" name="play_for_fun" value="1" title="play for fun?"/>
    date:<input type="text" name="date" value="2012-01-26">
    associateid:<input type="text" name="associateid" value="">
    render:<select name="render">
        <option value="text">text</option>
        <option value="json">json</option>
    </select>
    date_start
    <input type="text" value="2014-07-29 06:57:36" name="date_start">
    date_end
    <input type="text" value="2014-07-29 06:57:36" name="date_end">
    status:
    <select name="status">
        <option value="PENDING">PENDING</option>
        <option value="PROCESSED">PROCESSED</option>
        <option value="FAILED">FAILED</option>
        <option value="CANCELLED">CANCELLED</option>
        <option value="ERROR">ERROR</option>
        <option value="DENIED">DENIED</option>
        <option value="IN PROGRESS">IN PROGRESS</option>
    </select>
    <br /><br />
    <table>
        <tr>
            <td valign='top'>
                <table>
                    <caption><i>addFreeRounds params</i></caption>
                    <tr>
                        <td>Tittle</td>
                        <td><input type="text" value="freeround 1" name="tittle"></td>
                    </tr>
                    <tr>
                        <td>Player ids</td>
                        <td><input type="text" value="220650,21352" name="playerids"></td>
                    </tr>
                    <tr>
                        <td>Game ids</td>
                        <td><input type="text" value="787,789" name="gameids"></td>
                    </tr>
                    <tr>
                        <td>Avaliable</td>
                        <td><input type="text" value="2" name="available"></td>
                    </tr>
                    <tr>
                        <td>Valid from</td>
                        <td><input type="text" value="" name="validFrom"></td>
                    </tr>
                    <tr>
                        <td>Valid to</td>
                        <td><input type="text" value="2016-05-11" name="validTo"></td>
                    </tr>
                </table>
            </td>
            <td valign='top'>
                <table>
                    <caption><i>System player operations</i>(getSystemUsername, setSystemUsername, setSystemPassword)</caption>
                    <tr>
                        <td>System</td>
                        <td><input type="text" value="hi" name="system"></td>
                    </tr>
                    <tr>
                        <td>System_Player_Username</td>
                        <td><input type="text" value="thebadmother" name="splayer_username"></td>
                    </tr>
                    <tr>
                        <td>System_Player_Password</td>
                        <td><input type="password" value="testpass" name="splayer_password"></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <input type="submit">
</form>
</body>
</html>