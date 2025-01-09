from flask import Flask, jsonify, request
import mysql.connector
import numpy as np
from sklearn.linear_model import LinearRegression
import pandas as pd

app = Flask(__name__)

# Connect to MySQL
def get_mysql_connection():
    try:
        print("Attempting to connect to MySQL...")
        conn = mysql.connector.connect(
            host="127.0.0.1",
            user="root",
            database="tms"
        )
        print("Connected to MySQL successfully.")
        return conn
    except mysql.connector.Error as err:
        print(f"Error connecting to MySQL: {err}")
        return None

# Retrieve historical quarterly data
def get_quarterly_data(query, organization_id=None):
    if organization_id is not None:
        query = query.replace("WHERE", f"WHERE organization_id = {organization_id} AND")  # Filter by organization

    print(f"Executing query: {query}")
    conn = get_mysql_connection()
    if conn is None:
        print("Failed to connect to the database. Returning empty DataFrame.")
        return pd.DataFrame()  # Return an empty DataFrame on connection failure
    try:
        cursor = conn.cursor(dictionary=True)
        cursor.execute(query)
        data = cursor.fetchall()
        print(f"Data retrieved: {data}")
        return pd.DataFrame(data)
    finally:
        conn.close()
        print("Database connection closed.")

# Function to predict the next 4 quarters
def predict_next_4_quarters(X, y):
    print(f"Training model with X: {X}, y: {y}")
    model = LinearRegression().fit(X, y)
    future_quarters = np.array([[len(X) + i] for i in range(1, 5)])  # next 4 quarters
    predictions = model.predict(future_quarters).tolist()
    print(f"Predictions for next 4 quarters: {predictions}")
    return predictions

# Main endpoint to get all predictions
@app.route('/predict-all', methods=['POST'])
def predict_all():
    data = request.get_json()
    organization_id = data.get('organization_id')  # Get the organization_id from the request
    print(f"Received organization_id: {organization_id}")
    predictions = {}
    print("Starting prediction process...")

    # Predict Quarterly Sales Revenue
    sales_data = get_quarterly_data(""" 
        SELECT YEAR(date) AS year, QUARTER(date) AS quarter, SUM(total_amount) AS revenue 
        FROM transactions 
        WHERE transaction_type = 'Sales' 
        GROUP BY year, quarter 
        ORDER BY year, quarter 
    """, organization_id)
    if not sales_data.empty:
        X_sales = np.arange(len(sales_data)).reshape(-1, 1)
        y_sales = sales_data['revenue'].values
        print(f"Quarterly Sales Revenue Data - X: {X_sales}, y: {y_sales}")
        predictions["projected_quarterly_sales_revenue"] = predict_next_4_quarters(X_sales, y_sales)
    else:
        print("No sales data available for prediction. Setting projected_quarterly_sales_revenue to 0.")
        predictions["projected_quarterly_sales_revenue"] = [0, 0, 0, 0]  # Return zero predictions for next 4 quarters

    # Predict Quarterly Cost of Purchases
    purchase_data = get_quarterly_data(""" 
        SELECT YEAR(date) AS year, QUARTER(date) AS quarter, SUM(total_amount) AS purchase_cost 
        FROM transactions 
        WHERE transaction_type = 'Purchase' 
        GROUP BY year, quarter 
        ORDER BY year, quarter 
    """, organization_id)
    if not purchase_data.empty:
        X_purchases = np.arange(len(purchase_data)).reshape(-1, 1)
        y_purchases = purchase_data['purchase_cost'].values
        print(f"Quarterly Purchase Cost Data - X: {X_purchases}, y: {y_purchases}")
        predictions["projected_quarterly_purchase_cost"] = predict_next_4_quarters(X_purchases, y_purchases)
    else:
        print("No purchase data available for prediction. Setting projected_quarterly_purchase_cost to 0.")
        predictions["projected_quarterly_purchase_cost"] = [0, 0, 0, 0]  # Return zero predictions for next 4 quarters

    # Predict Quarterly Purchase Count
    purchase_count_data = get_quarterly_data(""" 
        SELECT YEAR(date) AS year, QUARTER(date) AS quarter, COUNT(*) AS purchase_count 
        FROM transactions 
        WHERE transaction_type = 'Purchase' 
        GROUP BY year, quarter 
        ORDER BY year, quarter 
    """, organization_id)
    if not purchase_count_data.empty:
        X_purchase_counts = np.arange(len(purchase_count_data)).reshape(-1, 1)
        y_purchase_counts = purchase_count_data['purchase_count'].values
        predictions["projected_quarterly_purchase_count"] = predict_next_4_quarters(X_purchase_counts, y_purchase_counts)
    else:
        print("No purchase count data available for prediction. Setting projected_quarterly_purchase_count to 0.")
        predictions["projected_quarterly_purchase_count"] = [0, 0, 0, 0]  # Return zero predictions for next 4 quarters

    # Predict Quarterly Tax Estimates
    tax_data = get_quarterly_data(""" 
        SELECT YEAR(date) AS year, QUARTER(date) AS quarter, SUM(vat_amount) AS tax 
        FROM transactions 
        WHERE Paidstatus = 'Paid' 
        GROUP BY year, quarter 
        ORDER BY year, quarter 
    """, organization_id)
    if not tax_data.empty:
        X_tax = np.arange(len(tax_data)).reshape(-1, 1)
        y_tax = tax_data['tax'].values
        predictions["projected_quarterly_tax_estimate"] = predict_next_4_quarters(X_tax, y_tax)
    else:
        print("No tax data available for prediction. Setting projected_quarterly_tax_estimate to 0.")
        predictions["projected_quarterly_tax_estimate"] = [0, 0, 0, 0]  # Return zero predictions for next 4 quarters

    # Predict End-of-Year Tax Liability (projected total at year-end)
    end_of_year_tax_data = get_quarterly_data(""" 
        SELECT YEAR(date) AS year, SUM(vat_amount) AS tax 
        FROM transactions 
        WHERE Paidstatus = 'Paid' 
        GROUP BY year 
        ORDER BY year 
    """, organization_id)
    if not end_of_year_tax_data.empty:
        X_yearly_tax = np.arange(len(end_of_year_tax_data)).reshape(-1, 1)
        y_yearly_tax = end_of_year_tax_data['tax'].values
        model_yearly_tax = LinearRegression().fit(X_yearly_tax, y_yearly_tax)
        next_year = np.array([[len(end_of_year_tax_data) + 1]])
        predictions["projected_end_of_year_tax"] = model_yearly_tax.predict(next_year)[0]
        print(f"Predicted end-of-year tax: {predictions['projected_end_of_year_tax']}")
    else:
        print("No end-of-year tax data available for prediction. Setting projected_end_of_year_tax to 0.")
        predictions["projected_end_of_year_tax"] = 0  # Return zero for the end-of-year tax prediction

    print("Prediction process completed.")
    return jsonify(predictions)

if __name__ == '__main__':
   app.run(debug=True, host="0.0.0.0", port=5000)
