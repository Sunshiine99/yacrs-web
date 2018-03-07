#!/usr/bin/python3

# OMP_Analysis.py
# Adapted from the tutorial "Document Clustering With Python" by Brandon Rose
# Written by Chase Condon

# Don't use xwindows for plots
import matplotlib
matplotlib.use('Agg')

import pymysql
import os
import sys
import pandas as pd
import re
import nltk
from nltk.stem.snowball import SnowballStemmer
from sklearn.pipeline import Pipeline
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.cluster import KMeans
from sklearn.metrics.pairwise import cosine_similarity
from sklearn.manifold import MDS
import matplotlib.pyplot as plt, mpld3

# Get MySQL database info from environment
db_host = os.environ['MYSQL_HOST']
db_user = os.environ['MYSQL_DATABASE']
db_password = os.environ['MYSQL_PASSWORD']
db_name = os.environ['MYSQL_DATABASE']

# Open database connection
db = pymysql.connect(db_host, db_user, db_password, db_name)

# If database is not connected, exit script
if not db.open:
    sys.exit()

# Run query to get ALL text questions
cur_q = db.cursor()
sql = "SELECT " + \
      "    sq.`ID` as sessionQuestionID, " + \
      "    q.`question` as question, " + \
      "    qt.`name` as type " + \
      "FROM " + \
      "	   `yacrs_sessionQuestions` as sq, " + \
      "    `yacrs_questions` as q, " + \
      "    `yacrs_questionTypes` as qt " + \
      "WHERE sq.`questionID` = q.`questionID` " + \
      "  AND q.`type` = qt.`ID` " + \
      "  AND " + \
      "  ( " + \
      "      qt.`name` = 'text' " + \
      "      OR " + \
      "      qt.`name` = 'textlong' " + \
      "  )"
cur_q.execute(sql)

nltk.download('stopwords')
nltk.download('punkt')

# Loop for every question
for row_q in cur_q:

    # Put row into more sensible variables
    sessionQuestionID = row_q[0]
    question = row_q[1]
    type = row_q[2]

    print(question)

    # Delete previous analysis items
    cur_r = db.cursor()
    sql = "DELETE a " + \
          "FROM `yacrs_analysis` AS a " + \
          "  JOIN `yacrs_response` AS r " + \
          "    ON a.`responseID` = r.`ID` " + \
          "WHERE r.`sessionQuestionID` = %s"
    cur_r.execute(sql, sessionQuestionID)
    db.commit()

    # Run query to get all responses for this this question
    cur_r = db.cursor()
    sql = "SELECT ID, userID, response " + \
          "FROM `yacrs_response` AS r " + \
          "WHERE r.`sessionQuestionID` = %s"
    cur_r.execute(sql, sessionQuestionID)

    # Data structure used to store responses
    responses = {'responseID': [], 'userID': [], 'response': []}

    # Load responses
    for row_r in cur_r:

        # Put row into more sensible variables
        responseID = row_r[0]
        userID = row_r[1]
        response = row_r[2]

        # Add response to data structure
        responses['responseID'].append(responseID)
        responses['userID'].append(userID)
        responses['response'].append(response)

        print("    ", userID, response)

    response_frame = pd.DataFrame(responses, columns=['responseID', 'userID', 'response'])
    stopwords = nltk.corpus.stopwords.words('english')
    stemmer = SnowballStemmer("english")

    def tokenize(text):
        tokens = [word for sent in nltk.sent_tokenize(text) for word in nltk.word_tokenize(sent)]
        filtered_tokens = []
        for token in tokens:
            if re.search('[a-zA-Z]', token):
                filtered_tokens.append(token)
        stems = [stemmer.stem(t) for t in filtered_tokens]
        return stems

    pipeline = Pipeline([('vect', TfidfVectorizer(max_df=0.8, max_features=20000,
                                                  min_df=0.2, stop_words='english',
                                                  use_idf=True, tokenizer=tokenize,
                                                  ngram_range=(1, 3))),
                         ('clust', KMeans(n_clusters=3))])

    try:
        pipeline.fit(response_frame['response'])
    except ValueError:
        print("    ERROR: Not enough clusters")
        continue

    matrix = pipeline.named_steps['vect'].fit_transform(response_frame['response'])
    dist = 1 - cosine_similarity(matrix)

    clusters = pipeline.named_steps['clust'].labels_.tolist()

    mds = MDS(n_components=2, dissimilarity='precomputed', random_state=1)
    pos = mds.fit_transform(dist)
    x, y, = pos[:, 0], pos[:, 1]

    clustered_responses = {'responseID': response_frame['responseID'], 'userID': response_frame['userID'], 'response': response_frame['response'], 'cluster': clusters,
                           'x': x, 'y': y}
    cluster_frame = pd.DataFrame(clustered_responses, columns=['responseID', 'userID', 'response', 'cluster', 'x', 'y']).sort_values(
        'cluster')

    cluster_colors = {0: '#1b9e77', 1: '#d95f02', 2: '#7570b3'}
    cluster_names = {0: 'Cluster 1', 1: 'Cluster 2', 2: 'Cluster 3'}

    df = pd.DataFrame(dict(x=x, y=y, label=clusters, title=cluster_frame['userID']))
    groups = df.groupby('label')

    fig, ax = plt.subplots(figsize=(17, 9))
    ax.margins(0.05)

    for name, group in groups:
        ax.plot(group.x, group.y, marker='o', linestyle='', ms=12,
                label=cluster_names[name], color=cluster_colors[name],
                mec='none')
        ax.set_aspect('auto')
        ax.tick_params( \
            axis='x',
            which='both',
            bottom=False,
            top=False,
            labelbottom=False)
        ax.tick_params( \
            axis='y',
            which='both',
            left=False,
            top=False,
            labelleft=False)

    ax.legend(numpoints=1)

    # Loop through clusters
    for c in cluster_frame.values:

        # Use more sensible variable names for cluster variables
        responseID = c[0]
        cluster = c[3]
        x = c[4]
        y = c[5]

        # Insert cluster into database
        cur_r = db.cursor()
        sql = "INSERT INTO `yacrs_analysis` (`responseID`, `cluster`, `x`, `y`)" + \
              "VALUES ('%s', '%s', '%s', '%s')"
        cur_r.execute(sql, (responseID, cluster, x, y))
        db.commit()

    # Close DB cursor
    cur_q.close()

    print()

# Close DB cursor
cur_q.close()

# Disconnect from database
db.close()