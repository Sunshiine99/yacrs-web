#!/usr/bin/python3

# OMP_Analysis.py
# Adapted from the tutorial "Document Clustering With Python" by Brandon Rose
# Written by Chase Condon

import MySQLdb

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


class Analyser(object):

    def __init__(self, server, user, passwd, db):
        self.db = MySQLdb.connect(host=server,
                                  user=user,
                                  passwd=passwd,
                                  db=db)
        self.cur = db.cursor()

    def get_analysis(self, question_number):
        query = self.cur.execute("SELECT userID, response",
                            "FROM yacrs_response, yacrs_sessionQuestions",
                            "WHERE sessionQuestionId=", question_number,
                            "AND yacrs_response.sessionQuestionID=yacrs_sessionQuestions.ID")

        responses = {'guid': [], 'response': []}
        result = query.fetchall()
        for res in result:
            guid, response = res
            responses['guid'].append(guid)
            responses['response'].append(response)

        response_frame = pd.DataFrame(responses, columns=['guid', 'response'])

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
                                                     ngram_range=(1,3))),
                             ('clust', KMeans(n_clusters=3))])

        pipeline.fit(response_frame['response'])
        matrix = pipeline.named_steps['vect'].fit_transform(response_frame['response'])
        dist = 1 - cosine_similarity(matrix)

        clusters = pipeline.named_steps['clust'].labels_.tolist()

        mds = MDS(n_components=2, dissimilarity='precomputed', random_state=1)
        pos = mds.fit_transform(dist)
        x, y, = pos[:, 0], pos[:, 1]

        clustered_responses = {'guid': response_frame['guid'], 'response': response_frame['response'], 'cluster': clusters, 'x': x, 'y': y}
        cluster_frame = pd.DataFrame(clustered_responses, columns = ['guid', 'response', 'cluster', 'x', 'y']).sort_values('cluster')

        cluster_colors = {0: '#1b9e77', 1: '#d95f02', 2: '#7570b3'}
        cluster_names =  {0: 'Cluster 1', 1: 'Cluster 2', 2: 'Cluster 3'}

        df = pd.DataFrame(dict(x=xs, y=ys, label=clusters, title=cluster_frame['guid']))
        groups = df.groupby('label')

        fig, ax = plt.subplots(figsize=(17, 9))
        ax.margins(0.05)

        for name, group in groups:
            ax.plot(group.x, group.y, marker='o', linestyle='', ms=12,
                label=cluster_names[name], color=cluster_colors[name],
                mec='none')
            ax.set_aspect('auto')
            ax.tick_params(\
            axis= 'x',
            which='both',
            bottom='off',
            top='off',
            labelbottom='off')
            ax.tick_params(\
            axis= 'y',
            which='both',
            left='off',
            top='off',
            labelleft='off')

        ax.legend(numpoints=1)

        mpld3.save_html(fig, 'fig.html')

        return cluster_frame.values.toList()
