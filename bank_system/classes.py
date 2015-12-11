import locale
import sqlite3

locale.setlocale(locale.LC_ALL, '')

class User:
	userID = 0
	username = ""
	password = ""
	name = ""
	is_active = 0
	error_code = 0
	def __init__(self):
		pass

	def login(self, username, password):
		conn = sqlite3.connect('bank_system.db')
		cur = conn.cursor()
		cur.execute("SELECT username FROM user WHERE username=?", [username])
		if(cur.fetchone() != None):
			cur.execute("SELECT password FROM user WHERE password=?", [password])
			if(cur.fetchone() != None):
					cur.execute("SELECT is_active FROM user WHERE username=?", [username])
					active = cur.fetchone()[0]
					if active == 0:
						self.error_code = 1
						return False
					else:
						return True
				
			else:
				self.error_code = 2
				return False
		else:
			print cur.fetchone()
			self.error_code = 3
			return False

class Admin(User):
	def __init__(self, userID, username, password, name, is_active):
		self.userID = userID
		self.username = username
		self.password = password
		self.name = name
		self.is_active = is_active
	
	def __str__(self):
		return "userID: " + str(self.userID) + ", username:" + str(self.username) + ", password:" + str(self.password) + ", name:" + str(self.name) + ", is_active:" + str(self.is_active)

class Customer(User):
	def __init__(self, userID, username, password, name, is_active):
		self.userID = userID
		self.username = username
		self.password = password
		self.name = name
		self.is_active = is_active


	def __str__(self):
		return "userID: " + str(self.userID) + ", username:" + str(self.username) + ", password:" + str(self.password) + ", name:" + str(self.name) + ", is_active:" + str(self.is_active)

	def deposit(self, thisDeposit, object):
		thisDeposit = abs(thisDeposit)
		object.accountBalance += thisDeposit
		return object.accountBalance
		
	def withdraw(self, thisWithdrawal, object):
		thisWithdrawal = abs(thisWithdrawal)
		if(object.accountBalance >= thisWithdrawal):
			object.accountBalance -= thisWithdrawal
		return object.accountBalance

	def transfer(self, amount, object):
		amount = abs(amount)
		object.account_bal += amount
		#remove amount from 2nd account_bal
		return object.account_bal

class Account:
	accountNum = 0
	accountBalance = 0.00
	accountType = ""
	def __init__(self, accountNum, accountType, accountBalance):
		self.accountNum = accountNum
		self.accountType = accountType
		self.accountBalance = accountBalance
	def __str__(self):
		return "accountNum: " + str(self.accountNum) + ", account_bal: " + str(self.account_bal) + ", accountType: " + str(self.accountType)

	def checkBalance(self):
		return self.account_bal

class Savings(Account):
	def __init__(self, accountNum):
		self.accountNum = accountNum
		self.accountType = "savings"

class Checking(Account):
	def __init__(self, accountNum):
		self.accountNum = accountNum
		self.accountType = "checking"

class Broker(Account):
	def __init__(self, accountNum):
		self.accountNum = accountNum
		self.accountType = "brokerage"

