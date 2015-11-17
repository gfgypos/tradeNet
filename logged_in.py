# -*- coding: utf-8 -*-

# Form implementation generated from reading ui file 'logged_in.ui'
#
# Created by: PyQt4 UI code generator 4.11.4
#
# WARNING! All changes made in this file will be lost!

from PyQt4 import QtCore, QtGui

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

class Ui_frm_logged_in(QtGui.QWidget):
    def __init__(self):
        QtGui.QWidget.__init__(self)
        self.setupUi()

    def setupUi(self, frm_logged_in):
        frm_logged_in.setObjectName(_fromUtf8("frm_logged_in"))
        frm_logged_in.resize(621, 438)
        self.btn_submit = QtGui.QPushButton(frm_logged_in)
        self.btn_submit.setGeometry(QtCore.QRect(280, 390, 75, 23))
        self.btn_submit.setObjectName(_fromUtf8("btn_submit"))
        self.txt_input = QtGui.QTextEdit(frm_logged_in)
        self.txt_input.setGeometry(QtCore.QRect(50, 80, 191, 271))
        self.txt_input.setObjectName(_fromUtf8("txt_input"))
        self.txt_output = QtGui.QTextEdit(frm_logged_in)
        self.txt_output.setGeometry(QtCore.QRect(380, 80, 191, 271))
        self.txt_output.setObjectName(_fromUtf8("txt_output"))
        self.label = QtGui.QLabel(frm_logged_in)
        self.label.setGeometry(QtCore.QRect(110, 50, 81, 16))
        self.label.setObjectName(_fromUtf8("label"))
        self.label_2 = QtGui.QLabel(frm_logged_in)
        self.label_2.setGeometry(QtCore.QRect(430, 50, 81, 16))
        self.label_2.setObjectName(_fromUtf8("label_2"))

        self.retranslateUi(frm_logged_in)
        QtCore.QMetaObject.connectSlotsByName(frm_logged_in)

    def retranslateUi(self, frm_logged_in):
        frm_logged_in.setWindowTitle(_translate("frm_logged_in", "Form", None))
        self.btn_submit.setText(_translate("frm_logged_in", "Submit", None))
        self.label.setText(_translate("frm_logged_in", "Input Window", None))
        self.label_2.setText(_translate("frm_logged_in", "Output Window", None))

