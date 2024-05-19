import pandas as pd
import os


csv_files = [file for file in os.listdir() if file.endswith('.csv')]
print(csv_files)

dfs = []

for file in csv_files:

    year = file[-8:-4]
    print(year)
    if int(year) <= 2017:
        df = pd.read_csv(os.path.join("", file), delimiter=',')
    else:
        df = pd.read_csv(os.path.join("", file), delimiter=';')

    df['ANUL_STATISTICII'] = year


    dfs.append(df)

# Concatenate all DataFrames into a single DataFrame
merged_df = pd.concat(dfs, ignore_index=True)


merged_df.to_csv('result/merged_file.csv', index=False)