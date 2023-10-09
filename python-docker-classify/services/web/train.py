#import bibliotek
import pandas as pd
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn import model_selection, preprocessing, linear_model, naive_bayes, metrics, svm
from sklearn import ensemble
import pickle

trainDF = pd.read_csv('indexing_bots.csv', sep='\t')

# podziel dane na zbiór treningowy i testowy
train_x, valid_x, train_y, valid_y = model_selection.train_test_split(trainDF['user_agent'], trainDF['is_indexing_bot'])

# zakoduj tekst na wektory numeryczne TF-IDF
tfidf_vect = TfidfVectorizer(analyzer='word', token_pattern=r'\w{1,}', max_features=5000)
tfidf_vect.fit(trainDF['user_agent'])
with open('project/tf_idf_vect.pickle', 'wb') as handle:
    pickle.dump(tfidf_vect, handle)
xtrain_tfidf =  tfidf_vect.transform(train_x)
xvalid_tfidf =  tfidf_vect.transform(valid_x)

# uniwersalna metoda do trenowania i ewaluacji modelu
def train_model(classifier, feature_vector_train, label, feature_vector_valid):
    # trenuj model
    classifier.fit(feature_vector_train, label)

    # generuj etykiety dla zbioru walidacyjnego
    predictions = classifier.predict(feature_vector_valid)

    # wyznacz metryki oceny modelu
    scores = list(metrics.precision_recall_fscore_support(predictions, valid_y))
    score_vals = [
        scores[0][0],
        scores[1][0],
        scores[2][0]
    ]
    score_vals.append(metrics.accuracy_score(predictions, valid_y))
    return classifier, score_vals

# MODEL - Lasy losowe
classifier, accuracy = train_model(ensemble.RandomForestClassifier(), xtrain_tfidf, train_y, xvalid_tfidf)
print ("RF: ", accuracy)

#export model
with open('project/model.pickle', 'wb') as handle:
    pickle.dump(classifier, handle)