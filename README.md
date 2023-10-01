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

cd regression-python-service-docker &&  docker-compose -f docker-compose.yml up -d --build

## 2. Classification

cd classify-python-service-docker &&  docker-compose -f docker-compose.yml up -d --build

## 3. Prognose

cd prognose && php prognose-train.php
cd prognose && php prognose-predict.php

