<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TranslationsKey extends Seeder
{
    /**
     * @throws Exception
     *
     */
    public function run()
    {
        $currentDate = new DateTime();

        $data = [
            [
                'locale' => 'en',
                'namespace' => '*',
                'group' => 'casino',
                'item' => 'support_most_popular_content',
                'text' => '<h2>How do I create my CasinoBit.io account?</h2>
                    <p>To get started, click the “Registration” button in the upper right corner and fill in all the required fields. We will send you an email with a confirmation link to verify your email address. Once all this is done, your account will become fully active.</p>
                    <h2>What currencies does CasinoBit.io accept?</h2>
                    <p>We accept bitcoins only.</p>
                    <h2>Where can I purchase bitcoins?</h2>
                    <p>There are plenty of places that allow you to buy and sell bitcoins. Online exchanges include Coinbase, Bitstamp, and Bitpanda.</p>
                    <h2>How long will my withdrawal take?</h2>
                    <p>Within one hour. CasinoBit.io endeavours to pay out funds immediately, however some withdrawals may take up to 24 hours.</p>',
                'unstable' => '0',
                'locked' => '0',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'locale' => 'en',
                'namespace' => '*',
                'group' => 'casino',
                'item' => 'support_registration',
                'text' => 'Registration',
                'unstable' => '0',
                'locked' => '0',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'locale' => 'en',
                'namespace' => '*',
                'group' => 'casino',
                'item' => 'support_registration_content',
                'text' => '<h2>How do I create my CasinoBit.io account?</h2>
                    <p>To get started, click the “Registration” button in the upper right corner and fill in all the required fields. We will send you an email with a confirmation link to verify your email address. Once all this is done, your account will become fully active.</p>
                    <h2>I haven’t received a confirmation email. Why?</h2>
                        <p> 1. Check if you have entered your email address correctly.</p> 
                        <p> 2. Make sure that our email has not been mistakenly sent to your Spam folder. Sometimes, automatic emails can be found there because of your email box filtering settings. If this happens, find the email and mark it as “Not spam”. This will stop any future messages from CasinoBit.io going to the Spam bin as well!</p>
                        <p> 3. If you still cannot find our email in any of your email box folders, please contact us via the Live Chat.</p>
                    <h2>I don’t remember my password. What should I do?</h2>
                    <p>The quickest way to regain access to your CasinoBit.io account is to create a new password.</p>
                        <p> 1. Click on the “Forgot Password?” button.</p>
                        <p> 2. A recovery email with a link will be automatically sent to your email address.</p>
                        <p> 3. Follow the link and create a new password for you CasinoBit.io account.</p>
                    <p>Alternatively, you can contact our customer support team via the Live Chat. We will be more than happy to assist you further.</p>
                    <h2>Can I change my registration email?</h2>
                    <p>No. Unfortunately, it is not possible to change your email address.</p>
                    <h2>Can I have more than one account?</h2>
                    <p>No, you are allowed to have and use only one account. According to the rules, multiple accounts are not permitted at CasinoBit.io.</p>
                    <h2>Can I delete my account?</h2>
                    <p>Yes, you may permanently delete your account by contacting our customer support team.</p>',
                'unstable' => '0',
                'locked' => '0',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'locale' => 'en',
                'namespace' => '*',
                'group' => 'casino',
                'item' => 'support_deposit_and_withdrawals',
                'text' => 'Deposit & Withdrawals',
                'unstable' => '0',
                'locked' => '0',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'locale' => 'en',
                'namespace' => '*',
                'group' => 'casino',
                'item' => 'support_deposit_and_withdrawals_content',
                'text' => '<h2>How to make a deposit?</h2>
                    <p>You need to click on the “Deposit” button at the top of your screen. After that you will see a bitcoin wallet address (and QR code) to send your funds to.
Also, you can top up your balance through your personal account by opening the “Deposit” tab.
</p>
                    <h2>What is the minimum deposit?</h2>
                    <p>The minimum deposit is 3 mBTC (0,003 BTC).</p>
                    <h2>When will my deposit appear in my account?</h2>
                    <p>As soon as your transaction data gets recorded to the blockchain, your deposit will be seen in your account.
You can always check the blockchain to see if your transfer is visible there, or ask our customer support team if you need a hand with this.
</p>
                    <h2>Where can I view my balance and transaction history?</h2>
                    <p>Your balance is displayed in the upper right corner and is available on all pages of the website. When you open the balance list, you can see how much funds is currently on your Real Balance and Bonus Balance.
Also, you can view the full transaction history (both deposits and withdrawals) in your Profile by opening the “Deposit” tab.
</p>
                    <h2>What are the rules to withdraw my funds?</h2>
                    <p>Before being able to withdraw your funds, you have to wager your initial deposit in full.</p>
                    <h2>Can I withdraw my bonus?</h2>
                    <p>Yes. As soon as you fulfill all the bonus wagering requirements, the bonus will be converted to your real balance. After that, it can be withdrawn to your wallet.</p>
                    <h2>Are there any withdrawal limits?</h2>
                    <p>You are allowed to withdraw no more than 1 BTC per day, and no more than 10 BTC per month. Meanwhile, the maximum withdrawal amount that can be processed to you within a 7-day period is 3 BTC. If the amount of funds requested for withdrawal is greater than 10 BTC per month, the remaining sum will be placed back to your account. You will be allowed to withdraw the remaining sum the following month.</p>
                    <h2>What are fees on deposits and withdrawals?</h2>
                    <p>CasinoBit.io does not have any fee on deposit or withdrawal.</p>
                    <h2>How long will my withdrawal take?</h2>
                    <p>Within one hour.
CasinoBit.io endeavours to pay out funds immediately, however some withdrawals may take up to 24 hours.
</p>',
                'unstable' => '0',
                'locked' => '0',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'locale' => 'en',
                'namespace' => '*',
                'group' => 'casino',
                'item' => 'support_the_casino_content',
                'text' => '<h2>What is the minimum age allowed in the casino?</h2>
                    <p>You may participate in any of the games if and only if you are over 18 years of age or such higher minimum legal age of majority as stipulated in the jurisdiction of your residence.</p>
                    <h2>What currencies does CasinoBit.io accept?</h2>
                    <p>We accept bitcoins only.</p>
                    <h2>Where can I purchase bitcoins?</h2>
                    <p>There are plenty of places that allow you to buy and sell bitcoins. Online exchanges include Coinbase, Bitstamp, and Bitpanda.</p>
                    <h2>What is a wager?</h2>
                    <p>Wager is an amount of bets a player must make to be able to withdraw their bonus money. As a rule, a withdrawal is possible only after fulfilling all wagering requirements.</p>
                    <h2>What are wagering requirements?</h2>
                    <p>Wagering requirements are conditions linked to the use of casino bonuses. The conditions imply a minimum number of bets that a player has to make to receive their bonus money.
Let\'s say you make the first deposit of 100 mBTC and receive a 110% bonus (which is 110 mBTC) with a wagering requirement of 50x. This means that you have to wager your bonus 50 times before being able to withdraw money from the account.
The full list of CasiboBit.io wagering requirements is in the Bonus Terms and Conditions section.
</p>
                    <h2>What is mBTC?</h2>
                    <p>A millibitcoin refers to one-thousandth of a bitcoin, or 0.001 BTC.</p>',
                'unstable' => '0',
                'locked' => '0',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'locale' => 'en',
                'namespace' => '*',
                'group' => 'casino',
                'item' => 'support_bonuses',
                'text' => 'Bonuses',
                'unstable' => '0',
                'locked' => '0',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'locale' => 'en',
                'namespace' => '*',
                'group' => 'casino',
                'item' => 'support_bonuses_content',
                'text' => '<h2>What type of bonuses can I get here?</h2>
                    <p>Here are the bonuses all new players can receive:</p>
                        <ul>
                            <li>110% 1st Deposit Bonus,</li>
                            <li>80% 2nd Deposit Bonus,</li>
                            <li>55% 3rd Deposit Bonus.</li>
                        </ul>
                    <p>Remember: a player with a disposable or unconfirmed email address is not eligible for bonuses.</p>    
                    <h2>How many bonuses can I use at once?</h2>
                    <p>You can use only one bonus at once.</p>
                    <h2>How do I activate a bonus?</h2>
                    <p> 1. Go to the “Bonuses” page.</p>
                    <p> 2. Click on the “Activate” button.</p>
                    <p> 3. Make a minimum deposit of 3 mBTC.</p>
                    <p>Make sure to activate your bonus within the period specified in the Bonus Terms and Conditions, otherwise it will expire.</p>
                    <h2>What is a bonus balance?</h2>
                    <p>A bonus balance is virtual funds that can be used to bet in some casino games. As a rule, in order to withdraw funds from the bonus balance a player must fulfill a set of conditions specified in the Bonus Terms and Conditions.</p>
                    <h2>How to wager my bonus balance?</h2>
                    <p>Each bonus type has its own wagering requirements, which are detailed in the Bonus Terms and Conditions.</p>',
                'unstable' => '0',
                'locked' => '0',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'locale' => 'en',
                'namespace' => '*',
                'group' => 'casino',
                'item' => 'support_technical_issues',
                'text' => 'Technical Issues',
                'unstable' => '0',
                'locked' => '0',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'locale' => 'en',
                'namespace' => '*',
                'group' => 'casino',
                'item' => 'support_technical_issues_content',
                'text' => '<h2>I can’t get the game started. What should I do?</h2>
                    <p> 1. Make sure that you have Java installed on your computer. You can download Java here: https://www.java.com/en/.</p>
                    <p> 2. Check if you have installed the latest version of FlashPlayer. If you need to download FlashPlayer, you can do it here: https://get.adobe.com/flashplayer/.</p>
                    <p> 3. Perhaps, you have lost contact with your server. Try logging out and logging back in. Occasionally, you may need to shut down the browser and reopen it to load the game.</p>
                    <p>If you have tried all these but the problem continues to persist, please contact our customer support team.</p>
                    <h2>What should I do if the game freezes?</h2>
                    <p> 1. Most of the time, if your game or computer freezes or you lose the Internet connection you can pick up exactly where you left off next time you open the game. If there is no option for you to do this, then the round will continue to play out on the server. All your winnings will be paid into your account as normal.</p>
                    <p> 2. In order to get back into the game a simple refresh of the site usually works.</p>
                    <p> 3. If this does not let you back into the game, you need to clear your cookies and cache in your browser.</p>
                    <p> 4. If the game still does not work, contact us via our Live Chat.</p>
                    <h2>An error or technical issue occurred. What should I do?</h2>
                    <p>Please contact our support team.
Try to note down the game name, the amount of time you have played, the stake you were playing, and details on what happened. Also, provide a screenshot of the problem. Remember that the more information you can give us, the easier it will be for us to resolve the issue.
</p>
                    <h2>How can I take a screenshot?</h2>
                    <p>A screenshot is an image taken of whatever is on your screen. In order to take a screenshot in Windows, just follow the steps outlined below:</p>
                    <p> 1. Press the “Print Screen” button (“Pr Sc” or “PRTSC”) located at the top right of the keyboard. On laptops that use a compact keyboard, the “Print Screen” key is often combined with other keys (for example, with “Fn” or upper, lower case, etc.)</p>
                    <p> 2. Open any image editor (for instance, Paint) and press “Ctrl + V” to paste an image. Save the file to your computer.</p>
                    <p> 3. If the “Print Screen” key is missing or you cannot find it, use Snipping Tool built into the Windows system. To open it, go to the Start menu and search for “Snipping Tool”. In the opened window, click on the “Create” button, and select an area for your screenshot. Save the image file to your computer.</p>
                    <p> 4. Attach the saved file to your message to our customer support team, including all additional information about an encountered error.</p>
                    <p>In order to take a screenshot on a Mac, just follow the steps outlined below:</p>
                    <p> 1. Press the “Cmd (⌘) + Shift + 3” key combination. The image file will be saved to your desktop automatically.</p>
                    <p> 2. Attach the saved file to your message to our customer support team, including all additional information about an encountered error.</p>
                    <p>In order to take a screenshot on a smartphone or tablet, you need to:</p>
                    <p> 1. Simultaneously press the power and volume down buttons. Hold them for a few seconds.</p>
                    <p> 2. The screen capture will be saved on the device.</p>
                    <p> 3. Attach the saved file to your message to our customer support team, including all additional information about an encountered error.</p>',
                'unstable' => '0',
                'locked' => '0',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'locale' => 'en',
                'namespace' => '*',
                'group' => 'casino',
                'item' => 'support_security_content',
                'text' => '<h2>Am I required to upload documents? Why?</h2>
                    <p>In most cases, you are not.
However, we do reserve the right to carry out the KYC compliance procedure if our support team specialists have doubts about the identity of the user who intends to withdraw their funds. If there are no doubts, the documents will not be required.
Our top priority is to make sure that we keep all our players safe, and we take this very seriously.
</p>
                    <h2>How does your KYC process work?</h2>
                    <p>Know Your Customer (KYC) refers to the process of verifying customer identities before conducting financial transactions.
If the CasinoBit.io support team has doubts about the identity of a user requesting a withdrawal, we will send them an email with the list of required documents and an instruction on how to undergo the KYC procedure. The documents will be considered within 12 hours. In most cases, this process takes 1 hour.
</p>
                    <h2>Can my documents be refused?</h2>
                    <p>This may happen.
In cases where it is needed, we will reach out to you to ask for more or alternative document copies, in order to stay within the law.
</p>
                    <h2>Do you support GDPR compliance?</h2>
                    <p>Yes, we do. You can find more details about it in the “Privacy Policy” section.</p>
                    <h2>Is all my information secure on CasinoBit.io?</h2>
                    <p>Always.
As soon as you log in, any communication you make between your web browser and the CasinoBit.io website will be protected by industry-standard encryption technology. This means that all your personal data and any activity you make on the site will be kept completely private.
</p>
                    <h2>Are my bitcoins secure on CasinoBit.io?</h2>
                    <p>Absolutely.
Our experts have taken various sophisticated measures to prevent the theft of funds or sensitive information. We store all bitcoins in encrypted cold wallets, which are completely isolated from any online systems. Being in cold wallets, they are protected with air-gap isolation.
</p>
                    <h2>How can I make sure my account is fully protected?</h2>
                    <p>There are several steps you can take to make sure that your account is as safe as it can be.</p>
                    <p> 1. Make sure you are using a different password for CasinoBit.io than your email account connected to your CasinoBit.io account or any other site you are using.</p>
                    <p> 2. Do regular virus scans on your computer to ensure no malicious programs such as keyloggers that can gather your secure information are hiding on your computer.</p>',
                'unstable' => '0',
                'locked' => '0',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'locale' => 'en',
                'namespace' => '*',
                'group' => 'casino',
                'item' => 'try_another_password',
                'text' => 'Try another password',
                'unstable' => '0',
                'locked' => '0',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ]
        ];

        foreach ($data as $item) {
            DB::table('translator_translations')->where('item', $item['item'])->delete();
        }

        DB::table('translator_translations')->insert($data);
    }
}