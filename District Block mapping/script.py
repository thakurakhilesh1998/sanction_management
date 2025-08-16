import pandas as pd
import json

# Load data from Excel file
excel_file = 'district_block.xlsx'  # Update with your file name
df = pd.read_excel(excel_file)

# Group blocks by district
districts = {}
for index, row in df.iterrows():
    district_name = row['District Name']
    block_name = row['Block Name']
    
    if district_name not in districts:
        districts[district_name] = []
    
    districts[district_name].append(block_name)

# Convert to JSON format
json_data = json.dumps(districts, indent=4)

# Write JSON data to a file
with open('districts_and_blocks.json', 'w') as json_file:
    json_file.write(json_data)

print("JSON file created successfully.")
