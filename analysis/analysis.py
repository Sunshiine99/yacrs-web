#!/usr/bin/python3
import pymysql
import os
import sys

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

# Loop for every question
for row_q in cur_q:

    # Put row into more sensible variables
    sessionQuestionID = row_q[0]
    question = row_q[1]
    type = row_q[2]

    print(question)

    # Run query to get all responses for this this question
    cur_r = db.cursor()
    sql = "SELECT userID, response " + \
          "FROM `yacrs_response` AS r " + \
          "WHERE r.`sessionQuestionID` = %s"
    cur_r.execute(sql, sessionQuestionID)

    # Loop for every response
    for row_r in cur_r:

        # Put row into more sensible variables
        userID = row_r[0]
        response = row_r[1]

        print("    %s %s", userID, response)

    # Close DB cursor
    cur_q.close()
    print()

# Close DB cursor
cur_q.close()

# Disconnect from database
db.close()