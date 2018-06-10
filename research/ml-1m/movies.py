import MySQLdb as mdb
import numpy as np
import random

con = mdb.connect('localhost', 'root', '', 'recommender');

data = []

with open('movies.dat' , 'r') as f:
	for line in f:
		_data = line.split("::")
		_data = [d.strip() for d in _data]
		data.append(_data)

data = np.array(data)
films = data[:,1]
names = []
genres = []
years = []
genre_relations = []

for f in data:
	if f[2] not in genres:
		gs = f[2].split("|")
		for g in gs:
			if g not in genres:
				genres.append(g)

			genre_relations.append([int(f[0]), genres.index(g)+1])
	else:
		genre_relations.append([int(f[0]), genres.index(f[2])+1])

for n in films:
	years.append(n[-5:-1])
	names.append(n[:-6].strip())

# ratings = []

# with open('ratings.dat' , 'r') as f:
# 	for line in f:
# 		_data = line.split("::")
# 		_data = [d.strip() for d in _data]
# 		ratings.append(_data)

# ratings = np.array(ratings)

# print(ratings)

try:
	with con:
		cur = con.cursor()

		# for genre in genres:
		# 	query = "INSERT INTO `movie_genres` VALUES (NULL, %s);"
		# 	cur.execute(query, (genre))

		for i in range(len(data)):
			query = "INSERT INTO `films` VALUES (%s, %s, %s, %s);"
			cur.execute(query, (data[:,0][i], names[i], years[i], random.uniform(1.5, 4.5)))

		for i in range(len(genre_relations)):
			query = "INSERT INTO `movie_genre_relations` VALUES (NULL, %s, %s)"
			cur.execute(query, (genre_relations[i][0],genre_relations[i][1]))

		# try not using the code below this because it adds over 1 million entries
		# for i in range(len(ratings)):
		# 	query = "INSERT INTO `movie_ratings` VALUES (NULL, %s, %s, %s)"
		# 	cur.execute(query, (ratings[:,0][i], ratings[:,1][i], ratings[:,2][i]))
except Exception as e:
	print("Error connecting to database: " + str(e))

if con:    
	con.close()