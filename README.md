# MailValidatorService
CMD ["php", "-S", "0.0.0.0:80", "-t", "/tmp"]

 docker build --tag alemelo/mailservice:latest . &&  docker push alemelo/mailservice:latest && cleandock