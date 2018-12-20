<div>
    <p>We have sent a reset password email to <a href="mailto:{{ $email }}">{{ $email }}</a>.</p>
    <p>Please, click the reset password link to set your new password.</p>
    <br>
    <p>Didn't receive the email yet?</p>
    <p class="second-hide">Please, check your spam folder or <a id = "resendPassword" href="/resend">RESEND</a> the email.</p>
    <p class="second-show">Re-sending will be available in <span class="seconds">10</span> second(s)</p>

    <p class="success-reset">Success send. Check your email.</p>
    <p class="error-reset">Send error. Try again later.</p>
</div>