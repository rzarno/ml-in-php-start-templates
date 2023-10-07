# import bibliotek
from sklearn.model_selection import train_test_split
from sklearn import metrics
from sklearn.ensemble import RandomForestRegressor
import pandas as pd
import pickle


# import danych z pliku csv
cars = pd.read_csv('Car_Prices_Poland_Kaggle.csv')
cars = cars.sample(frac = 1)

#kodowanie danych kategorycznych
marks = pd.get_dummies(cars['mark'])
cars = pd.merge(left=cars, right=marks, left_index=True, right_index=True)

fuels = pd.get_dummies(cars['fuel'])
cars = pd.merge(left=cars, right=fuels, left_index=True, right_index=True)

# usuwanie nadmiarowych kolumn
del cars['city']
del cars['generation_name']
del cars['province']
del cars['fuel']
del cars['model']
del cars['mark']

#usunięcie wartości odstających
cars = cars.drop(cars[cars.price > 1300000].index)
cars = cars.drop(cars[cars.mileage > 1200000].index)
cars = cars.drop(cars[cars.vol_engine > 7000].index)

# przygotuj zbiór treningowy (bez etykiet)
X = cars.drop('price', axis=1).to_numpy()

# przygotuj etykiety (cena)
y = cars.loc[:, 'price'].to_numpy()

# Podziel na zbiór testowy i treningowy
X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=12345)

# funkcja do trenowania modelu
def train_model(classifier, feature_vector_train, label, feature_vector_valid):
    # trenuj model
    classifier.fit(feature_vector_train, label)

    # wygeneruj przewidywania
    predictions = classifier.predict(feature_vector_valid)

    # oceń model
    score_vals = [
        metrics.mean_squared_error(predictions, y_test, squared=False),
        metrics.mean_absolute_error(predictions, y_test)
    ]
    return classifier, score_vals

regressor = RandomForestRegressor(n_estimators = 50, random_state = 0)
classifier, accuracy = train_model(regressor, X_train, y_train, X_test)
print ('random forrest tree' , accuracy)

#export model
with open('project/model.pickle', 'wb') as handle:
    pickle.dump(classifier, handle)