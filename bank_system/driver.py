#############################
# B A N K I N G S Y S T E M #
#############################
from classes import *
import locale
def login_menu():
	print "\n\n\n"
	print "   Welcome to Sweg Banking"
	print "~~~~~~~~~~~~~~~~~~~~~~~~~~~~~"
	print " Please login to continue...(CTRL+C to quit)"
	print "\n"

def customer_menu():
	print ""
	print "Please select an option:"
	print "~~~~~~~~~~~~~~~~~~~~~~~~~~~~~"
	print "1) Deposit Money"
	print "2) Withdraw Money"
	print "3) Transfer Money"
	print "4) Check Balance"
	print "5) View transaction history"
	print "6) End Sweg Banking Transaction"
	print
	return input ("Choose your option: ")

def admin_menu():
	print ""
	print "Please select an option:"
	print "~~~~~~~~~~~~~~~~~~~~~~~~~~~~~"
	print "1) Create Customer" #assign username and password
	print "2) Create Account"
	print "3) Assign Account to customer"
	print "4) Create a User for TradeNet"
	print "5) View system Log"
	print "6) List all account numbers, customers and balances"
	print "7) Suspend/Reactivate Account"
	print "8) End Sweg Banking Transaction"
	print
	return input ("Choose your option: ")


locale.setlocale(locale.LC_ALL, '')
#Database connection error catching
try:
    conn = sqlite3.connect('bank_system.db')
    cur = conn.cursor()    
    cur.execute('SELECT SQLITE_VERSION()')
    data = cur.fetchone()
    print "SQLite version: %s" % data                
except lite.Error, e:
    print "Error %s:" % e.args[0]
    sys.exit(1)

#Main loop
while True:
	success = False
	while success != True:
		login_menu()
		user = raw_input("Please enter your username: ")
		pw = raw_input("Please enter your password: " )
		currentUser = User()
		success = currentUser.login(user, pw)

	#get users access level
	cur.execute("SELECT access_level FROM user WHERE username=?", [user])
	access_level = cur.fetchone()[0]

	#USER MENU
	if(access_level == 0):
		#Get the user data
		cur.execute("SELECT * FROM user WHERE username=?", [user])
		userdata = cur.fetchone()
		currentUser = Customer(userdata[0],
							   userdata[1],
							   userdata[2],
							   userdata[3],
							   userdata[4])	
		#Log
		cur.execute("INSERT INTO system_log (user_acct, event) VALUES(?,?)",[userdata[0], "login"])
		conn.commit()

		print("")
		print("Welcome " + currentUser.name + "!")
		cur.execute("SELECT account_number FROM user_account WHERE uid=?", [userdata[0]])
		availableAccs = cur.fetchall()
		print("\nAvailable accounts: ")
		for accs in availableAccs:
			print("Account # ") + str(accs[0])
		actnum = input("Please enter the account number you wish to modify: ")
		print("")
		#retrieve users account data
		cur.execute("SELECT * FROM account WHERE account_number=?", [actnum])
		actdata = cur.fetchone()
		print("*** Account #" + str(actdata[0]) + " --- " + str(actdata[1]) + " account selected! ***")
		dummy = raw_input("\nPress ENTER to continue...")
		print("\n\n\n")

		acc = Account(actdata[0],
					  actdata[1],
					  actdata[2])

		cur.execute("SELECT * FROM user_account WHERE uid=? AND account_number=?", [userdata[0], actnum])
		verify = cur.fetchone()
		if(verify == None):
			print("ERROR you do not own this account")
			exit()
		else:
			#Customer Menu Loop
			while 1: 
				choice = customer_menu()
				cur.execute("SELECT * FROM account WHERE account_number=?", [actnum])
				actdata = cur.fetchone()
				acc = Account(actdata[0],
					  actdata[1],
					  actdata[2])
				#Deposit
				if choice == 1: 
					amt = input("\nAmount to deposit: $")
					new_bal = currentUser.deposit(amt, acc)
					cur.execute("UPDATE account SET account_bal =? WHERE account_number=?", [new_bal, actdata[0]])
					#Log
					cur.execute("INSERT INTO transaction_log (transaction_type, transaction_amount, uid, source_acct) " + 
						"VALUES(?,?,?,?)", ["Deposit", amt, userdata[0], actdata[0]])
					conn.commit()
					print("Deposited " + str(locale.currency(amt)) + " to account " + str(acc.accountNum))
					dummy = raw_input("\nPress ENTER to continue...")
					print("\n\n\n")
				#Withdraw
				elif choice == 2:
					amt = input("\nAmount to withdraw: $")
					new_bal = currentUser.withdraw(amt,acc)
					#Ensure the customer has sufficient funds
					if(amt <= acc.accountBalance):
						currentUser.withdraw(amt, acc)
					else:
						print("Not enough funds! Only " + str(locale.currency(acc.account_bal)) + " available.")
					cur.execute("UPDATE account SET account_bal =? WHERE account_number=?", [new_bal, actdata[0]])
					#Log
					cur.execute("INSERT INTO transaction_log (transaction_type, transaction_amount, uid, source_acct) " + 
						"VALUES(?,?,?,?)", ["Withdraw", amt, userdata[0], actdata[0]])
					conn.commit()
					print("Withdrew " + str(locale.currency(amt)) + " from account " + str(acc.accountNum))
					dummy = raw_input("\nPress ENTER to continue...")
					print("\n\n\n")
				#Transfer
				elif choice == 3:
					cur.execute("SELECT account_number FROM user_account WHERE uid=?", [userdata[0]])
					availableAccs = cur.fetchall()
					print("\nAvailable accounts: ")
					for accs in availableAccs:
						if(accs[0] != actnum):
							print("Account # ") + str(accs[0])
					accTo = input("\nAccount number to transfer to: ")

					cur.execute("SELECT * FROM account WHERE account_number=?", [accTo])
					destActdata = cur.fetchone()
					#Verify destination account exists
					if(destActdata == None):
						print("Destination account does not exist! Sorry...\n Returning to the main menu...")
					else:
						destActdata = Account(destActdata[0],
								  		   destActdata[1],
								 		   destActdata[2])
						#Get amount of money to transfer
						transferAmt = input("Enter amount of money to transfer: ")
						#Verify sufficient funds in account
						if(transferAmt <= acc.accountBalance):
							#Compute new balance of account
							new_bal = currentUser.withdraw(transferAmt, acc)
						else:
							print("Not enough funds! Only " + str(locale.currency(acc.accountBalance)) + " available.")

						cur.execute("SELECT account_bal FROM account WHERE account_number =?", [accTo])
						newTransferAmt = cur.fetchone()[0] + transferAmt
						cur.execute("UPDATE account SET account_bal =? WHERE account_number=?", [newTransferAmt, accTo])
						cur.execute("UPDATE account SET account_bal =? WHERE account_number=?", [new_bal, actdata[0]])
						#Log
						cur.execute("INSERT INTO transaction_log (transaction_type, transaction_amount, "+
							"uid, source_acct, destination_acct) VALUES(?,?,?,?,?)", 
							["Transfer", transferAmt, userdata[0], actdata[0], accTo])
						conn.commit()
						print("Success! $" + str(transferAmt) + " was added to account " + str(accTo))
				#Return current balance of account
				elif choice == 4:
					print("\nCurrent balance: " + str(locale.currency((acc.accountBalance))))
					dummy = raw_input("\nPress ENTER to continue...")
					print("\n\n\n")
				#View Transaction log
				elif choice == 5:
					cur.execute("SELECT * FROM transaction_log WHERE uid = ?", [userdata[0]])
					rows = cur.fetchall()
					print("Transaction ID, Transaction Type, Amount, UID, Source Acct, Destination Acct")
	  				for row in rows:
	        				print(str(row[0]) + ", 	" + str(row[1]) + ", 	" + str(locale.currency(row[2])) + ", 	" + str(row[3])+ ", 	" + str(row[4])+ ", 	" + str(row[5]))
					dummy = raw_input("\nPress ENTER to continue...")
					print("\n\n\n")
				#EXIT
				elif choice == 6:
					print("\nThanks for using Sweg Banking! Goodbye :-)\n")
					#Log
					cur.execute("INSERT INTO system_log (user_acct, event) VALUES(?,?)",[userdata[0], "logout"])
					conn.commit()
					break
	#ADMIN menu loop
	elif(access_level == 1):
		cur.execute("SELECT * FROM user WHERE username=?", [user])
		admindata = cur.fetchone()
		currentAdmin = Admin(admindata[0],
						   admindata[1],
						   admindata[2],
						   admindata[3],
						   admindata[4])
		#Log	
		cur.execute("INSERT INTO system_log (admin_acct, event) VALUES(?,?)",[admindata[0], "login"])
		conn.commit()

		print("")
		print("Welcome " + currentAdmin.name + "!")
		print("")

		while 1:
			choice = admin_menu()
			#Add user
			if choice == 1:
				#input
				newUserName = raw_input("\nEnter the new customers username: ")
				newUserPassword = raw_input("Enter the new customers password: ")
				newFullName = raw_input("Enter the customers full name: ")
				isActive = 1
				accessLevel = input("Enter the users access level(0 for customer, 1 for admin: ")
				cur.execute("INSERT INTO user (username, password, name, is_active, access_level) VALUES (?,?,?,?,?)",
							 [newUserName, newUserPassword, newFullName, isActive, access_level])
				cur.execute("SELECT uid FROM user WHERE username=?", [newUserName])
				logUid = cur.fetchone()[0]
				#Log
				cur.execute("INSERT INTO system_log (admin_acct, user_acct, event) VALUES (?,?,?)", [admindata[0], logUid,"create user"])
				conn.commit()
				print("\nUser added successfully! \n")
				dummy = raw_input("\nPress ENTER to continue...")
			#Add Account
			elif choice == 2:
				#input
				newAccounType = raw_input("\nEnter the new account type: ")
				newAccountBal = input("Enter the new account balance: ")
				cur.execute("INSERT INTO account (account_type, account_bal) VALUES (?,?)",
							[newAccounType, newAccountBal])
				#Log
				cur.execute("INSERT INTO system_log (admin_acct, event) VALUES (?,?)", [admindata[0],"create account"])
				conn.commit()
				print("\nAccount created successfully! \n")
				dummy = raw_input("\nPress ENTER to continue...")
			#Add user to account
			elif choice == 3:
				newUid = input("\nEnter the customers userID: ")
				newAccountNumber = input("Enter the account number for the user: ")
				cur.execute("INSERT INTO user_account VALUES(?,?)",[newUid,newAccountNumber])
				event = "assigned user to account "+str(newAccountNumber)
				#Log
				cur.execute("INSERT INTO system_log (admin_acct, user_acct, event) VALUES (?,?,?)", [admindata[0], newUid ,event])
				conn.commit()
				print("\nUser assigned to account successfully \n")
				dummy = raw_input("\nPress ENTER to continue...")

			#Add a brokerage user
			elif choice == 4:
				uid = input("\nEnter the customers userID:")
				cur.execute("SELECT uid FROM user WHERE uid=?",[uid])
				if(cur.fetchone() == None):
					print "ERROR user does not exist, please add customer first \n"
					break

				username = raw_input("\nEnter the users name: ")
				pw = raw_input("\nEnter the users password: ")
				act_num = input("\nEnter the users brokerage account number: ")
				cur.execute("SELECT * FROM account WHERE account_number=?", [act_num])
				act_info = cur.fetchone()
				if(act_info[0] == None):
					print "ERROR account does not exist \n"
					break
		
				if(act_info[1] != "brokerage"):
					print "ERROR account is not a brokerage account"
					break

				cur.execute("SELECT * FROM user_account WHERE uid=? AND account_number=?", [uid, act_num])
				if(cur.fetchone() == None):
					print "ERROR this user does not own this account"
					break
	
				balance = act_info[2]

				cur.execute("INSERT INTO brokerage_user (uid, username, password, account_number, balance) VALUES (?,?,?,?,?)",
							[uid, username, pw, act_num, balance])
				conn.commit()
				print("\nUser created a TradeNet account successfully\n")
				dummy = raw_input("\nPress ENTER to continue...")


			#View system log
			elif choice == 5:
				cur.execute("SELECT * FROM system_log")
				data = cur.fetchall()
				print("System log ID, Administrator account, User account, Action")
				#Display System Log
				for erow in data:
					print(str(erow[0]) + "\t" + str(erow[1]) + "\t" + str(erow[2]) + "\t" + str(erow[3]))
				dummy = raw_input("\nPress ENTER to continue...")
				print("\n\n\n")
			#List all account numbers, balances, and account owners
			elif choice == 6:
				cur.execute("SELECT * from user")
				users = cur.fetchall()
				for user in users:
					print("\nUser: ") + str(user[3])
					cur.execute("SELECT account_number FROM user_account WHERE uid = ?", [user[0]])
					accounts = cur.fetchall()
					for acc in accounts:
						print("		Account #: ") + str(acc[0])
						cur.execute("SELECT account_bal, account_type FROM account WHERE account_number = ?", [acc[0]])
						balances = cur.fetchall()
						for bal in balances:
							print("		Account type: ") + bal[1]
							print("		Current balance: ") + str(locale.currency(bal[0]))
			#Activate or deactive a user account
			elif choice == 7:
				cur.execute("SELECT * FROM user")
				users = cur.fetchall()
				for user in users:
					print("\n ID: ") + str(user[0]) + "    Name: " + str(user[3])
				selection = input("\nEnter the corresponding user ID to activate/deactivate: ")
				cur.execute("SELECT is_active FROM user WHERE uid =?", [selection])
				opt = cur.fetchone()[0]

				if opt == 0:
					#Active account
					cur.execute("UPDATE user SET is_active =1 WHERE uid =?", [selection])
					print("\nAccount successfully activated!")
					#Log
					cur.execute("INSERT INTO system_log (admin_acct, user_acct, event) VALUES (?,?,?)", [admindata[0],user[0], "Customer account reactivated"])
					conn.commit()
				elif opt == 1:
					#Deactivate Account
					cur.execute("UPDATE user SET is_active =0 WHERE uid =?", [selection])
					print("\nAccount successfully suspended!")
					#Log
					cur.execute("INSERT INTO system_log (admin_acct, user_acct, event) VALUES (?,?,?)", [admindata[0],user[0], "Customer account suspended"])
					conn.commit()
			if choice == 8:
				print("\nThanks for using Sweg Banking! Goodbye :-)\n")
				#Log
				cur.execute("INSERT INTO system_log (admin_acct, event) VALUES(?,?)",[admindata[0], "logout"])
				conn.commit()
				break
