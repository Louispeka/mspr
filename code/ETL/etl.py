import pandas as pd
import json
import argparse
import os
import logging

def setup_logging():
    log_dir = "log"
    os.makedirs(log_dir, exist_ok=True)
    log_file = os.path.join(log_dir, "etl.log")
    
    logging.basicConfig(
        filename=log_file,
        level=logging.INFO,
        format="%(asctime)s - %(levelname)s - %(message)s"
    )
    
    # Affiche aussi les logs dans la console
    console_handler = logging.StreamHandler()
    console_handler.setFormatter(logging.Formatter("%(levelname)s - %(message)s"))
    logging.getLogger().addHandler(console_handler)

def extract_json(file_path):
    logging.info(f"Extraction JSON : {file_path}")
    with open(file_path, 'r') as f:
        data = json.load(f)
    return pd.DataFrame(data)

def extract_csv(file_path):
    logging.info(f"Extraction CSV : {file_path}")
    return pd.read_csv(file_path)

def transform_data(df):
    logging.info("Début de la transformation des données...")
    
    df = df.drop_duplicates()
    df.columns = [col.lower().strip().replace(" ", "_") for col in df.columns]

    num_cols = df.select_dtypes(include=["number"]).columns
    for col in num_cols:
        if df[col].isnull().any():
            median = df[col].median()
            df[col].fillna(median, inplace=True)
            logging.info(f"Valeurs nulles de '{col}' remplacées par la médiane : {median}")
    
    if 'value' in df.columns and 'category' in df.columns:
        df = df.groupby('category', as_index=False).sum()

    return df

def load_data(df, output_file):
    logging.info(f"Chargement des données dans : {output_file}")
    df.to_csv(output_file, index=False, sep=';')

def etl_pipeline(input_files, file_type, output_file):
    combined_df = pd.DataFrame()

    for file_path in input_files:
        if not os.path.exists(file_path):
            logging.warning(f"Fichier non trouvé : {file_path}, ignoré.")
            continue

        if file_type == "json":
            temp_df = extract_json(file_path)
        elif file_type == "csv":
            temp_df = extract_csv(file_path)
        else:
            logging.error(f"Type de fichier non pris en charge : {file_type}")
            raise ValueError("Le type de fichier doit être 'json' ou 'csv'.")

        combined_df = pd.concat([combined_df, temp_df], ignore_index=True, sort=False)

    if combined_df.empty:
        logging.warning("Aucune donnée à transformer.")
        return

    transformed_df = transform_data(combined_df)
    load_data(transformed_df, output_file)
    logging.info("Pipeline ETL terminé avec succès.")

if __name__ == "__main__":
    setup_logging()

    parser = argparse.ArgumentParser(description="Exécuter un pipeline ETL avec plusieurs fichiers.")
    parser.add_argument(
        "--input_files", type=str, nargs='+', required=True,
        help="Chemins des fichiers d'entrée (JSON ou CSV)."
    )
    parser.add_argument(
        "--file_type", type=str, required=True, choices=["json", "csv"],
        help="Type de fichier (json ou csv)."
    )
    parser.add_argument(
        "--output_file", type=str, default="cleaned_data.csv",
        help="Nom du fichier de sortie (dans le dossier output/)."
    )

    args = parser.parse_args()

    output_dir = "output"
    os.makedirs(output_dir, exist_ok=True)
    output_file_path = os.path.join(output_dir, args.output_file)

    try:
        logging.info("Lancement du pipeline ETL multi-fichiers...")
        etl_pipeline(args.input_files, args.file_type, output_file_path)
    except Exception as e:
        logging.error(f"Erreur durant l'exécution : {str(e)}")
