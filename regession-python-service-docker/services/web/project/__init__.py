from flask import Flask, jsonify, request
import pickle
import json

app = Flask(__name__)
app.debug = True

@app.route("/")
def regression():
    data = request.args.get('data')
    with open('project/model.pickle', 'rb') as handle:
        model = pickle.load(handle)
    result = model.predict(json.loads(data))
    return {'results': result.tolist()}
