import sys

usrid = sys.argv[1]

import numpy as np
import MySQLdb as mdb
from scipy import sparse
from scipy.sparse.linalg import svds
from collections import defaultdict
from operator import itemgetter

def sparse_mean(mat, row = -1, column = -1):
	if row != -1:
		mat = mat[row]
	elif column != -1:
		mat = mat.transpose()[column]

	if mat.count_nonzero() != 0:
		return mat.sum()/mat.count_nonzero()
	else:
		return 0

def recommend(id):
	con = mdb.connect('sql12.freesqldatabase.com', 'sql12247654', 'Ptw6aicQFD', 'sql12247654');
	# con = mdb.connect("localhost", "root", "", "recommender")
	data = []
	try:
		with con:
			cur = con.cursor()
			query = "SELECT * FROM user_ratings"
			cur.execute(query)
			result = cur.fetchall()

			for r in result:
				data.append(r)

			cur = con.cursor()
			query = "SELECT COUNT(*) FROM users"
			cur.execute(query)

			n_users = cur.fetchone()[0]

			cur = con.cursor()
			query = "SELECT COUNT(*) FROM films"
			cur.execute(query)

			n_films = cur.fetchone()[0]
	except Exception:
		print(Exception)

	data = np.array(data, dtype=np.float32)

	res = defaultdict(list)
	for v,u,k in data: res[v].append(k)

	first_usr = int(data[:,0].min())
	first_mov = int(data[:,1].min())

	user_ratings_mean = []
	for i in res:
		user_ratings_mean.append(sum(res[i])/len(res[i]))

	urm_array = []
	original_matrix = np.zeros(shape=(n_users, n_films), dtype=np.float32)

	for cur_rating in user_ratings_mean:
		urm_array.append([cur_rating]*n_films)

	for d in data:
		urm_array[int(d[0])-first_usr][int(d[1])-first_mov] = original_matrix[int(d[0])-first_usr, int(d[1])-first_mov] = d[2]

	urm = np.array(urm_array)

	cur.close()
	con.close()

	def unrated(userid, movieid):
		if not original_matrix[userid,movieid]:
			return 1

	original_matrix_sparse = sparse.csr_matrix(original_matrix)
	ratings_mean = sparse_mean(original_matrix_sparse) # mean of all ratings in original_matrix

	film_ratings_mean = []

	for i in range(n_films):
		film_ratings_mean.append(sparse_mean(original_matrix_sparse,-1, i))

	predictions = []

	U, S, V = sparse.linalg.svds(sparse.csr_matrix(urm))
	P = S * V.T

	for i in range(first_usr-1, n_users):
		for j in range(first_mov-1, n_films):
			if unrated(i,j):
				p = [i,j,((ratings_mean+(film_ratings_mean[j]-ratings_mean)+(user_ratings_mean[i]-ratings_mean))+(U[i]*P[j]).sum())/2]
				predictions.append(p)

	for prediction in predictions:
		urm[prediction[0], prediction[1]] = prediction[2]

	preds = defaultdict(list)
	for a,b,c in predictions: preds[a].append([b+1,c])

	user_preds = []
	for i in preds[int(id)-1]:
		user_preds.append(i)

	user_preds = sorted(user_preds, key=itemgetter(1), reverse=True)
	print(user_preds[:5])

recommend(usrid)