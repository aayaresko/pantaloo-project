<div>
    <p>We have sent a verification email to <a href="mailto:{{ $email }}">{{ $email }}</a>.</p>
    <p>Please, click the link from email to finish your registration.</p>
    <br>
    <p>Didn't receive the email yet?</p>
    <p class="second-hide">Please, check your spam folder or <a id = "reconfirmPassword" href="/reconfirm">RESEND</a> the email.</p>
    <p class="second-show" style="display: none;">Re-sending will be available in <span class="seconds">10</span> second(s)</p>

    <p class="success-reset" style="display: none;">Success send. Check your email.</p>
    <p class="error-reset" style="display: none;">Send error. Try again later.</p>
</div>