#import des modules necessaires 
import pandas as pd
import json
import argparse
import os
import logging

# Configuration du système de logs
def setup_logging():
    log_dir = "log"
    os.makedirs(log_dir, exist_ok=True)  # Crée le dossier `log` s'il n'existe pas
    log_file = os.path.join(log_dir, "etl.log")
    
    logging.basicConfig(
        filename=log_file,
        level=logging.INFO,
        format="%(asctime)s - %(levelname)s - %(message)s"
    )

# Extraction du contenu d'une extension JSON
def extract_json(file_path):
    logging.info(f"Extraction des données du fichier JSON : {file_path}")
    with open(file_path, 'r') as f:
        data = json.load(f)
    return pd.DataFrame(data)

# Extraction du contenu d'une extension CSV
def extract_csv(file_path):
    logging.info(f"Extraction des données du fichier CSV : {file_path}")
    return pd.read_csv(file_path)

# Transformation des données
def transform_data(df):
    logging.info("Transformation des données : suppression des doublons, normalisation et agrégation.")
    # Suppression des doublons
    df = df.drop_duplicates()

    # Normalisation (standardisation des noms de colonnes en minuscules)
    df.columns = [col.lower().strip() for col in df.columns]

    # Agrégation si la colonne 'value' est présente
    if 'value' in df.columns:
        df = df.groupby('category', as_index=False).sum()

    return df

# Chargement des données dans un fichier de sortie
def load_data(df, output_file):
    logging.info(f"Chargement des données transformées dans le fichier : {output_file}")
    df.to_csv(output_file, index=False)

# Pipeline ETL principal
def etl_pipeline(input_file, file_type, output_file):
    # Extraction
    if file_type.lower() == "json":
        data = extract_json(input_file)
    elif file_type.lower() == "csv":
        data = extract_csv(input_file)
    else:
        logging.error(f"Type de fichier non pris en charge : {file_type}")
        raise ValueError("Le type de fichier doit être 'json' ou 'csv'.")

    # Transformation
    clean_data = transform_data(data)

    # Chargement
    load_data(clean_data, output_file)
    logging.info("Pipeline ETL terminé avec succès.")

# Point d'entrée principal
if __name__ == "__main__":
    # Configuration des logs
    setup_logging()

    # Analyse des arguments de la ligne de commande
    parser = argparse.ArgumentParser(description="Exécuter un pipeline ETL.")
    parser.add_argument(
        "--input_file", type=str, required=True, help="Chemin du fichier d'entrée (JSON ou CSV)."
    )
    parser.add_argument(
        "--file_type", type=str, required=True, choices=["json", "csv"], help="Type de fichier (json ou csv)."
    )
    parser.add_argument(
        "--output_file", type=str, default="cleaned_data.csv", help="Chemin du fichier de sortie."
    )

    args = parser.parse_args()

    try:
        logging.info("Début du pipeline ETL.")
        etl_pipeline(args.input_file, args.file_type, args.output_file)
    except Exception as e:
        logging.error(f"Erreur lors de l'exécution du pipeline ETL : {str(e)}")
