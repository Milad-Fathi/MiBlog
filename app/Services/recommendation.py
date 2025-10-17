 
import json
import sys
import numpy as np
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.metrics.pairwise import cosine_similarity

def get_recommendations(data):
    # Extract user text and blog texts from the JSON data
    user_text = data.get('user')
    blog_texts = {key: value for key, value in data.items() if key != 'user'}

    # Create a list of blog texts
    blog_ids = list(blog_texts.keys())
    blog_contents = list(blog_texts.values())

    # Combine user text with blog texts for vectorization
    texts = [user_text] + blog_contents

    # Vectorize the texts using TF-IDF
    vectorizer = TfidfVectorizer()
    tfidf_matrix = vectorizer.fit_transform(texts)

    # Calculate cosine similarity
    user_vector = tfidf_matrix[0]  # The first vector corresponds to the user's text
    blog_vectors = tfidf_matrix[1:]  # The rest correspond to the blog texts

    # Compute cosine similarity scores
    similarity_scores = cosine_similarity(user_vector, blog_vectors).flatten()

    # Get the top 5 blog IDs with the highest similarity scores
    top_indices = np.argsort(similarity_scores)[-5:][::-1]  # Get the indices of the top 5 scores
    top_blog_ids = [blog_ids[i] for i in top_indices]

    return top_blog_ids

if __name__ == "__main__":
    try:
        # Read the path from the command line argument
        input_file = sys.argv[1]
        
        # Read JSON input from the file
        with open(input_file, 'r', encoding='utf-8') as f:
            data = json.load(f)

        # Process the data to get recommendations
        recommendations = get_recommendations(data)

        # Output the recommended blog IDs as a JSON array
        with open(input_file + '.result', 'w', encoding='utf-8') as f:
            json.dump(recommendations, f)
        
        print(recommendations)

    except Exception as e:
        print(f"Error: {str(e)}", file=sys.stderr)
