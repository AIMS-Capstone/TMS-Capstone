from flask import Flask, request, jsonify
import pandas as pd
from sklearn.linear_model import LinearRegression
from sklearn.model_selection import train_test_split

app = Flask(__name__)

# Example training data for demonstration purposes
data = {
    'feature1': [1, 2, 3, 4, 5],
    'feature2': [10, 20, 30, 40, 50],
    'target': [100, 200, 300, 400, 500]
}

df = pd.DataFrame(data)

# Split data into features and target
X = df[['feature1', 'feature2']]
y = df['target']

# Create and train the model
model = LinearRegression()
model.fit(X, y)

@app.route('/predict', methods=['POST'])
def predict():
    # Get the JSON data from the request
    request_data = request.get_json()
    features = pd.DataFrame(request_data['features'])

    # Make predictions
    predictions = model.predict(features)
    return jsonify({'predictions': predictions.tolist()})

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)  # Listen on all interfaces on port 5000
