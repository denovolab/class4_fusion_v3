#!/usr/bin/env python3
import psycopg2
import psycopg2.extras

def get_clients():
	conn = psycopg2.connect(host='10.10.0.254', port=5432, 
							database='class4_vm_11',
						 	user='class4_vm_11', password='u73gd673gd7s8')
	conn.autocommit = True
	cursor = conn.cursor(cursor_factory=psycopg2.extras.DictCursor)
	cursor.execute("select client_id, name from client")
	clients = cursor.fetchall()
	cursor.close()
	conn.close()
	return clients

def balance_worker(client_id):
	balance_total = {
		'payment_received' : 0,
		'payment_sent'	   : 0,
		'incoming_traffic' : 0,
		'outgoing_traffic' : 0,
	}
	conn = psycopg2.connect(host='10.10.0.117', port=5432, 
							database='class4_vm_11',
						 	user='class4_vm_11', password='u73gd673gd7s8')
	conn.autocommit = True
	cursor = conn.cursor(cursor_factory=psycopg2.extras.DictCursor)
	# payment received
	cursor.execute("""SELECT COALESCE(sum(amount), 0) as amount FROM client_payment 
WHERE receiving_time between '2011-01-01 00:00:00+00' and '2013-11-08 23:59:59+00' 
and payment_type in (4,5) AND client_id = %s""", (client_id, ))
	balance_total['payment_received'] += cursor.fetchone()['amount']
	# payment sent
	cursor.execute("""SELECT COALESCE(sum(amount), 0) as amount FROM client_payment 
WHERE receiving_time between '2011-01-01 00:00:00+00' and '2013-11-08 23:59:59+00' 
and payment_type in (3,6) AND client_id = %s""", (client_id, ))
	balance_total['payment_sent'] += cursor.fetchone()['amount']
	# incomming_traffic
	cursor.execute("""SELECT COALESCE(sum(ingress_call_cost), 0) as call_cost from cdr_report 
where report_time between '2011-01-01 00:00:00+00' and '2013-11-02 23:59:59+00' 
and ingress_client_id = %s""", (client_id, ))
	balance_total['incoming_traffic'] += cursor.fetchone()['call_cost']
	

	# outgoing_traffic
	cursor.execute("""SELECT COALESCE(sum(egress_call_cost), 0) as call_cost 
from cdr_report where report_time between  '2011-01-01 00:00:00+00' 
and '2013-11-02 23:59:59+00'  and egress_client_id = %s""", (client_id, ))
	balance_total['outgoing_traffic'] += cursor.fetchone()['call_cost']
	

	cursor.close()
	conn.close()



	conn = psycopg2.connect(host='10.10.0.254', port=5432, 
							database='class4_vm_11',
						 	user='class4_vm_11', password='u73gd673gd7s8')
	conn.autocommit = True
	cursor = conn.cursor(cursor_factory=psycopg2.extras.DictCursor)
	# payment received
	cursor.execute("""SELECT COALESCE(sum(amount), 0) as amount FROM client_payment 
WHERE receiving_time between '2013-11-08 00:00:00+00' and '2013-11-11 23:59:59+00' 
and payment_type in (4,5) AND client_id = %s""", (client_id, ))
	balance_total['payment_received'] += cursor.fetchone()['amount']
	# payment sent
	cursor.execute("""SELECT COALESCE(sum(amount), 0) as amount FROM client_payment 
WHERE receiving_time between '2013-11-08 00:00:00+00' and '2013-11-11 23:59:59+00' 
and payment_type in (3,6) AND client_id = %s""", (client_id, ))
	balance_total['payment_sent'] += cursor.fetchone()['amount']

	# incomming_traffic
	cursor.execute("""select COALESCE(amount, 0) as amount
FROM ingress_cost(%s, '2013-11-03 00:00:00+00', '2013-11-11 23:59:59' ) as (amount numeric)""", (client_id, ))
	balance_total['incoming_traffic'] += cursor.fetchone()['amount']

	# outgoing_traffic
	cursor.execute("""select COALESCE(amount, 0) as amount
FROM egress_cost(%s, '2013-11-03 00:00:00+00', '2013-11-11 23:59:59' ) as (amount numeric)""", (client_id, ))
	balance_total['outgoing_traffic'] += cursor.fetchone()['amount']


	cursor.execute("""DELETE FROM balance_history_actual WHERE client_id = %s""", (client_id, ))


	actual_balance = balance_total['payment_received'] - balance_total['incoming_traffic'] - balance_total['payment_sent'] + balance_total['outgoing_traffic']


	cursor.execute("""INSERT INTO balance_history_actual(payment_received,payment_sent,
unbilled_incoming_traffic,unbilled_outgoing_traffic, actual_balance, client_id, "date") values (%s, %s, %s, %s, %s, %s, %s)""", 
				(balance_total['payment_received'], balance_total['payment_sent'], balance_total['incoming_traffic'], 
				balance_total['outgoing_traffic'], actual_balance, client_id, '2013-11-11 00:00:00+00'))

	cursor.execute("""INSERT INTO client_payment (result, receiving_time, amount, client_id, description, payment_type, payment_time)
            VALUES(true, CURRENT_TIMESTAMP, %s, %s, 'Synchronize', 14, %s)""", (actual_balance, client_id, '2013-11-12 00:00:00+00'))


	
	cursor.close()
	conn.close()

	return balance_total

def main():
	

	
	for client in get_clients():
		print(client['client_id'])
		balance_total = balance_worker(client['client_id'])
		print(client['client_id'], "{payment_received} {payment_sent} {incoming_traffic} {outgoing_traffic}".format(**balance_total))

main()

