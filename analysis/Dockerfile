FROM python:3

WORKDIR /usr/src/app

#RUN apt-get update && \
#    apt-get install python3-mysqldb

COPY requirements.txt ./
RUN pip3 install --no-cache-dir -r requirements.txt

COPY . .

# Run analysis script
CMD [ "python", "./analysis.py" ]