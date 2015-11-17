# -*- coding: utf-8 -*-

# Form implementation generated from reading ui file 'login_form.ui'
#
# Created by: PyQt4 UI code generator 4.11.4
#
# WARNING! All changes made in this file will be lost!

from PyQt4 import QtCore, QtGui
from classes import *
import sys

try:
    _fromUtf8 = QtCore.QString.fromUtf8
except AttributeError:
    def _fromUtf8(s):
        return s

try:
    _encoding = QtGui.QApplication.UnicodeUTF8
    def _translate(context, text, disambig):
        return QtGui.QApplication.translate(context, text, disambig, _encoding)
except AttributeError:
    def _translate(context, text, disambig):
        return QtGui.QApplication.translate(context, text, disambig)

class Ui_LoginForm(QtGui.QWidget):
    def __init__(self):
        QtGui.QWidget.__init__(self)
        self.setupUi(self)

    def setupUi(self, LoginForm):
        LoginForm.setObjectName(_fromUtf8("LoginForm"))
        LoginForm.resize(289, 310)
        self.centralwidget = QtGui.QWidget(LoginForm)
        self.centralwidget.setObjectName(_fromUtf8("centralwidget"))
        self.label = QtGui.QLabel(self.centralwidget)
        self.label.setGeometry(QtCore.QRect(30, 60, 51, 16))
        self.label.setObjectName(_fromUtf8("label"))
        self.label_2 = QtGui.QLabel(self.centralwidget)
        self.label_2.setGeometry(QtCore.QRect(30, 90, 51, 16))
        self.label_2.setObjectName(_fromUtf8("label_2"))
        self.txt_username = QtGui.QLineEdit(self.centralwidget)
        self.txt_username.setGeometry(QtCore.QRect(90, 60, 141, 20))
        self.txt_username.setObjectName(_fromUtf8("txt_username"))
        self.txt_password = QtGui.QLineEdit(self.centralwidget)
        self.txt_password.setGeometry(QtCore.QRect(90, 90, 141, 20))
        self.txt_password.setObjectName(_fromUtf8("txt_password"))
        self.label_3 = QtGui.QLabel(self.centralwidget)
        self.label_3.setGeometry(QtCore.QRect(110, 10, 51, 31))
        font = QtGui.QFont()
        font.setPointSize(16)
        self.label_3.setFont(font)
        self.label_3.setObjectName(_fromUtf8("label_3"))
        self.console_out = QtGui.QTextEdit(self.centralwidget)
        self.console_out.setGeometry(QtCore.QRect(30, 130, 211, 81))
        self.console_out.setObjectName(_fromUtf8("console_out"))
        self.btn_login = QtGui.QPushButton(self.centralwidget)
        self.btn_login.setGeometry(QtCore.QRect(110, 240, 75, 23))
        self.btn_login.setObjectName(_fromUtf8("btn_login"))
        self.btn_login.clicked.connect(self.handle_login)

        self.retranslateUi(LoginForm)
        QtCore.QMetaObject.connectSlotsByName(LoginForm)

    def retranslateUi(self, LoginForm):
        LoginForm.setWindowTitle(_translate("LoginForm", "MainWindow", None))
        self.label.setText(_translate("LoginForm", "Username:", None))
        self.label_2.setText(_translate("LoginForm", "Password:", None))
        self.label_3.setText(_translate("LoginForm", "Login", None))
        self.btn_login.setText(_translate("LoginForm", "Login", None))

    def handle_login(self):
        user = str(self.txt_username.text())
        pw = str(self.txt_password.text())
        currentUser = User()
        currentUser.login(user, pw)
        if(currentUser.error_code == 1):
            self.console_out.setText("Account suspended! Contact admin...")
            self.txt_username.setText("")
            self.txt_password.setText("")
        elif(currentUser.error_code == 2):
            self.console_out.setText("ERROR: invalid passowrd")
            self.txt_username.setText("")
            self.txt_password.setText("")
        elif(currentUser.error_code == 3):
            self.console_out.setText("ERROR: username does not exist")
            self.txt_username.setText("")
            self.txt_password.setText("")
        elif(currentUser.error_code == 0):
            self.console_out.setText("Login Successful!")

if(__name__ == "__main__"):
    app = QtGui.QApplication(sys.argv)
    ex = Ui_LoginForm()
    ex.show()
    sys.exit(app.exec_())
