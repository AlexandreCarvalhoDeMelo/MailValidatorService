## Email Validator Service
![Validator service](https://i.imgur.com/hvKOco8.gif)

To run:
> docker run -t -p 8080:8080 -e PORT=8080 alemelo/mailservice

Test request with CURL:
> curl -XPOST -d '{"email":"alexandre.carvalho.melo@gmail.com"}' http://localhost:8080/email/validate

### Installing locally

Clone the project
> git clone git@github.com:AlexandreCarvalhoDeMelo/MailValidatorService.git

Then composers and the tests
> composer install

> vendor/bin/phpunit

If the tests are ok, you're good to go! : )
