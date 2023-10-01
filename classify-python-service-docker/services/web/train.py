# import bibliotek
from sklearn.model_selection import train_test_split
from sklearn import  metrics, ensemble
import pickle
import pandas as pd

# import danych
passengers = pd.read_csv('train_and_test2.csv')
passengers.head()

# usunięcie zbędnych kolumn
passengers.drop(['Passengerid', 'zero', 'zero.1', 'zero.2', 'zero.3', 'zero.4', 'zero.5', 'zero.6', 'zero.7',
                 'zero.8', 'zero.9', 'zero.10', 'zero.11', 'zero.12', 'zero.13', 'zero.14', 'zero.15', 'zero.16',
                 'zero.17', 'zero.18'], axis=1, inplace = True)

# uzupełnij brakujące wartości
passengers.fillna(passengers.mean(), inplace=True)
passengersCopy = passengers.copy()

# utwórz zestaw treningowy
X = passengers.drop('2urvived', axis=1).to_numpy()

# utwórz etykiety zestawu treningowego
y = passengers.loc[:, '2urvived'].to_numpy()

# podziel zbiór na dane treningowe i testowe
X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=12345)


# uniwersalna metoda do trenowania i ewaluacji modelu
def train_model(classifier, feature_vector_train, label, feature_vector_valid):
    # trenuj model
    classifier.fit(feature_vector_train, label)

    # generuj etykiety dla zbioru walidacyjnego
    predictions = classifier.predict(feature_vector_valid)

    # wyznacz metryki oceny modelu
    scores = list(metrics.precision_recall_fscore_support(predictions, y_test))
    score_vals = [
        scores[0][0],
        scores[1][0],
        scores[2][0]
    ]
    score_vals.append(metrics.accuracy_score(predictions, y_test))
    return classifier, score_vals

# MODEL - Lasy losowe
classifier, accuracy = train_model(ensemble.RandomForestClassifier(), X_train, y_train, X_test)
print ("RF: ", accuracy)

#export model
with open('project/model.pickle', 'wb') as handle:
    pickle.dump(classifier, handle)