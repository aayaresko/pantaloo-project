Click here to reset your password: <a href="{{ $link = $currentLang . url('password/reset', $token).'?email='.urlencode($user->getEmailForPasswordReset()) }}"> {{ $link }} </a>
