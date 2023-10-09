from flask import Flask, jsonify, request
import pickle
import json

app = Flask(__name__)
app.debug = True

@app.route("/")
def classify():
    data = request.args.get('data')
    with open('project/tf_idf_vect.pickle', 'rb') as handle:
        tfidf_vect = pickle.load(handle)
    with open('project/model.pickle', 'rb') as handle:
        model = pickle.load(handle)
    vect = tfidf_vect.transform([data])
    result = model.predict(vect)
    return {'results': result.tolist()}
