# ml-in-php-starting-templates
This project is supposed to be a starting template for replacing business logic in PHP projects 
It covers 4 use cases:

1. Regression model train and use as micorservice

    Python + Scikit-Learn + Flask + Gunicorn + Nginx

2. Classification model train and use as micorservice

    Python + Scikit-Learn + Flask + Gunicorn + Nginx

2. Prognose sales from time series

    PHP + PHP-ML


## 1. Regression

setup containers:
cd regression-python-service-docker &&  docker-compose -f docker-compose.yml up -d --build --force-recreate

request API GET:
http://127.0.0.1:1337/?data=[[22.0, 7.2500, 0, 1, 0, 3, 2.0]]

## 2. Classification

setup containers
cd classify-python-service-docker &&  docker-compose -f docker-compose.yml up -d --build --force-recreate

request API GET:
http://127.0.0.1:1337/?data=[[22.0,%207.2500,%200,%201,%200,%203,%202.0]]

## 3. Prognose

cd prognose && php prognose-train.php
cd prognose && php prognose-predict.php

