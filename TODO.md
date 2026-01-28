# TODO: Modify LoginResponse to redirect to welcome.blade

- [x] Add a new route '/welcome' in routes/web.php that returns view('welcome')
- [x] Update app/Http/Responses/LoginResponse.php to change the default redirect to '/welcome' for users without specific roles

# TODO: Change default page to welcome.blade

- [ ] Change the root redirect in routes/web.php from '/admin' to '/welcome'
