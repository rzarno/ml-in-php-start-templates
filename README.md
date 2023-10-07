# ml-in-php-starting-templates
This project is supposed to be a starting template for replacing business logic in PHP projects 
It covers 4 use cases:

1. Regression model train and use as micorservice

    Python + Scikit-Learn + Flask + Gunicorn + Nginx

1. Classification model train and use as micorservice

    Python + Scikit-Learn + Flask + Gunicorn + Nginx

1. Prognose sales from time series

    PHP + Rindow Neural Networks

1. Recognize captcha images

   PHP + Rindow Neural Networks + Nvidia DAVE-2 CNN model architecture



## 1. Regression

setup containers:
cd python-docker-regression &&  docker-compose -f docker-compose.yml up -d --build

request API GET:
http://127.0.0.1:1337/?data=[[22.0, 7.2500, 0, 1, 0, 3, 2.0]]

## 2. Classification

setup containers:
cd python-docker-classify &&  docker-compose -f docker-compose.yml up -d --build

request API GET:
http://127.0.0.1:1338/?data=[[90004,2011,124000,1198,0,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,0,0]]

## 3. Prognose

To train and test model run:

`bin/cli prognose-sales-nn-pipeline`

or using docker:

`docker run --rm rzarno/ml-in-php-start-templates prognose-sales-nn-pipeline`

## 4. Image recognition

To train and test model run:

`bin/cli captcha-image-classification-cnn-pipeline`

or using docker:

`docker run --rm rzarno/ml-in-php-start-templates captcha-image-classification-cnn-pipeline`