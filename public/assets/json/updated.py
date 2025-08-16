import pandas as pd
import json

# Read Excel file into a DataFrame
df = pd.read_excel('gp name constituency wise.xlsx')

# Create a dictionary to store the hierarchy
json_data = {}

# Create a mapping dictionary for Gram Panchayats to Assembly Constituencies
gp_to_constituency_mapping = {}

# Iterate through rows of the DataFrame
for index, row in df.iterrows():
    district = row['District_Name']
    block = row['Block_Name']
    panchayat = row['Gram_Panchayat']
    assembly_constituency = row['Assembly_Constituency']  # New column

    # Check if district exists in the dictionary
    if district not in json_data:
        json_data[district] = {}

    # Check if block exists in the district
    if block not in json_data[district]:
        json_data[district][block] = {}

    # Check if Gram Panchayat exists in the block
    if panchayat not in json_data[district][block]:
        json_data[district][block][panchayat] = []

    # Append the data to the Gram Panchayat
    json_data[district][block][panchayat].append(assembly_constituency)

    # Map Gram Panchayat to Assembly Constituency
    gp_to_constituency_mapping[panchayat] = assembly_constituency

# Create a dictionary for the final JSON structure
final_json = {
    "data": json_data,
    "gp_to_constituency_mapping": gp_to_constituency_mapping  # Include the mapping
}

# Convert the dictionary to JSON
json_result = json.dumps(final_json, indent=2)

# Save the JSON to a file
with open('output.json', 'w') as json_file:
    json.dump(final_json, json_file, indent=2)
